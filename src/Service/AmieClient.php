<?php

namespace Drupal\access_amie\Service;

use Drupal\access_amie\Exception\AmieException;
use Drupal\access_amie\Packets\AccountCreateData;
use Drupal\access_amie\Packets\CreateAccount;
use Drupal\access_amie\Packets\CreateProjectExtension;
use Drupal\access_amie\Packets\CreateProjectNew;
use Drupal\access_amie\Packets\CreateProjectRenewal;
use Drupal\access_amie\Packets\CreateProjectSupplement;
use Drupal\access_amie\Packets\CreateProjectTransfer;
use Drupal\access_amie\Packets\DeleteUser;
use Drupal\access_amie\Packets\InactivateAccount;
use Drupal\access_amie\Packets\InactivateProject;
use Drupal\access_amie\Packets\IncomingTransactionComplete;
use Drupal\access_amie\Packets\OutgoingPacket;
use Drupal\access_amie\Packets\MergePerson;
use Drupal\access_amie\Packets\ModifyUser;
use Drupal\access_amie\Packets\Packet;
use Drupal\access_amie\Packets\ProjectCreateData;
use Drupal\access_amie\Packets\ReactivateAccount;
use Drupal\access_amie\Packets\ReactivateProject;
use Drupal\access_amie\Packets\RemotePacket;
use Drupal\access_amie\Transactions\Transaction;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\key\KeyRepositoryInterface;
use GuzzleHttp\Client;


/**
 *
 */
class AmieClient {

  // properties


  /**
   * A container for this module's configuration settings
   *
   * @var ImmutableConfig
   */
  private ImmutableConfig $config;

  /**
   *
   * @var LoggerChannelInterface
   */
  private LoggerChannelInterface $logger;

  /**
   *
   * @var KeyRepositoryInterface
   */
  private KeyRepositoryInterface $repository;

  /**
   * An HTTP client
   *
   * @var Client
   */
  private Client $client;


  // constructor


  /**
   * Constructor
   *
   * @param ConfigFactoryInterface $config_factory
   *   A factory for this module's configuration settings
   * @param Client $client
   *   An HTTP client
   * @param LoggerChannelFactoryInterface $logger_factory
   *
   * @param KeyRepositoryInterface $key_repo
   *
   */
  public function __construct(ConfigFactoryInterface $config_factory, Client $client, LoggerChannelFactoryInterface $logger_factory, KeyRepositoryInterface $key_repo) {
    $this->config = $config_factory->get('access_amie.settings');
    $this->logger = $logger_factory->get('access_amie');
    $this->repository = $key_repo;
    $this->client = $client;
  }


  // public methods


  /**
   *
   * @return array
   *
   * @throws GuzzleException
   * @throws AmieException
   */
  public function getPackets(): array {
    $site_name = $this->config->get('site_name');
    $path = 'packets/' . $site_name;
    $packets = $this->callApi('GET', $path);
    $result = [];

    foreach ($packets['result'] as $packet) {
      $packet_type = $packet['type'];

      if ($packet_type == 'request_project_create') {
        $allocation_type = $packet['body']['AllocationType'];

        if ($allocation_type == 'new') {
          $result[] = new CreateProjectNew($packet);
        }
        else if ($allocation_type == 'renewal') {
          $result[] = new CreateProjectRenewal($packet);
        }
        else if ($allocation_type == 'extension') {
          $result[] = new CreateProjectExtension($packet);
        }
        else if ($allocation_type == 'supplement') {
          $result[] = new CreateProjectSupplement($packet);
        }
        else if ($allocation_type == 'transfer' || $allocation_type == 'adjustment') {
          $result[] = new CreateProjectTransfer($packet);
        }
        else {
          throw new AmieException('Unrecognized allocation type "' . $allocation_type . '"');
        }
      }
      else if ($packet_type == 'data_project_create') {
        $result[] = new ProjectCreateData($packet);
      }
      else if ($packet_type == 'request_project_inactivate') {
        $result[] = new InactivateProject($packet);
      }
      else if ($packet_type == 'request_project_reactivate') {
        $result[] = new ReactivateProject($packet);
      }
      else if ($packet_type == 'request_account_create') {
        $result[] = new CreateAccount($packet);
      }
      else if ($packet_type == 'data_account_create') {
        $result[] = new AccountCreateData($packet);
      }
      else if ($packet_type == 'request_account_inactivate') {
        $result[] = new InactivateAccount($packet);
      }
      else if ($packet_type == 'request_account_reactivate') {
        $result[] = new ReactivateAccount($packet);
      }
      else if ($packet_type == 'request_user_modify') {
        $action_type = $packet['body']['ActionType'];

        if ($action_type == 'replace') {
          $result[] = new ModifyUser($packet);
        }
        else if ($action_type == 'delete') {
          $result[] = new DeleteUser($packet);
        }
        else {
          throw new AmieException('Unrecognized action type "' . $action_type . '"');
        }
      }
      else if ($packet_type == 'request_person_merge') {
        $result = new MergePerson($packet);
      }
      else if ($packet_type == 'inform_transaction_complete') {
        $result[] = new IncomingTransactionComplete($packet);
      }
      else {
        throw new AmieException('Unrecognized packet type "' . $packet_type . '"');
      }
    }

    return $result;
  }

  /**
   *
   * @param OutgoingPacket $packet
   *
   * @return array
   *
   * @throws GuzzleException
   */
  public function sendPacket(OutgoingPacket $packet): array {
    $site_name = $this->config->get('site_name');
    $path = 'packets/' . $site_name;
    $params = [
      'json' => $packet->getData()
    ];

    return $this->callApi('POST', $path, $params);
  }

  /**
   *
   * @param int $record_id
   *
   * @return Packet
   *
   * @throws GuzzleException
   * @throws AmieException
   */
  public function getPacket(int $record_id): Packet {
    $site_name = $this->config->get('site_name');
    $path = 'packets/' . $site_name . '/' . $record_id;
    $packet = $this->callApi('GET', $path);

    return new RemotePacket($packet['result']);
  }

  /**
   *
   * @param int $record_id
   *
   * @return Transaction
   *
   * @throws GuzzleException
   */
  public function getTransaction(int $record_id): Transaction {
    $site_name = $this->config->get('site_name');
    $path = 'transactions/' . $site_name . '/' . $record_id . '/packets';
    $transaction = $this->callApi('GET', $path);

    return new Transaction($transaction['result']);
  }

  /**
   *
   * @param int $record_id
   * @param string $state
   *
   * @return array
   *
   * @throws GuzzleException
   */
  public function setClientState(int $record_id, string $state): array {
    $site_name = $this->config->get('site_name');
    $path = 'packets/' . $site_name . '/' . $record_id . '/client_state/' . $state;

    return $this->callApi('PUT', $path);
  }

  /**
   *
   * @param int $record_id
   *
   * @return array
   *
   * @throws GuzzleException
   */
  public function deleteClientState(int $record_id): array {
    $site_name = $this->config->get('site_name');
    $path = 'packets/' . $site_name . '/' . $record_id . '/client_state';

    return $this->callApi('DELETE', $path);
  }

  /**
   *
   * @param int $record_id
   * @param string|array $json
   *
   * @return array
   *
   * @throws GuzzleException
   */
  public function setClientJson(int $record_id, string|array $json): array {
    $site_name = $this->config->get('site_name');
    $path = 'packets/' . $site_name . '/' . $record_id . '/client_json';
    $params = [
      'json' => is_array($json) ? $json : json_decode($json, true)
    ];

    return $this->callApi('PUT', $path, $params);
  }

  /**
   *
   * @param int $record_id
   *
   * @return array
   *
   * @throws GuzzleException
   */
  public function deleteClientJson(int $record_id): array {
    $site_name = $this->config->get('site_name');
    $path = 'packets/' . $site_name . '/' . $record_id . '/client_json';

    return $this->callApi('DELETE', $path);
  }


  // private methods


  /**
   * Makes a call to the AMIE API using values provided in the module settings
   *
   * @param string $method
   *   An HTTP method
   * @param string $path
   *   The URL path
   * @param array $params
   *   An array of parameters for the HTTP client
   *
   * @return array
   *   The JSON response from AMIE converted to an associative array
   *
   * @throws GuzzleException
   */
  private function callApi(string $method, string $path, ?array $params = null): array {
    $base_url = $this->config->get('rest_url');
    $site_name = $this->config->get('site_name');
    $key = $this->config->get('api_key_id');
    $api_key = $this->repository->getKey($key)->getKeyValue();
    $url = $base_url . '/' . $path;
    $options = [
      'headers' => [
        'XA-API-KEY' => $api_key,
        'XA-SITE' => $site_name
      ]
    ];

    if (!empty($params)) {
      foreach ($params as $key => $value) {
        $options[$key] = $value;
      }
    }

    $this->logger->debug('Calling ' . $url . ' using method ' . $method . ' and options ' . json_encode($params));

    $request = $this->client->request($method, $url, $options);
    $response = $request->getBody();

    $this->logger->debug('Response: ' . $response);

    return json_decode($response, true);
  }
}
