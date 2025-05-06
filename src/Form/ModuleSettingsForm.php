<?php

namespace Drupal\access_amie\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 *
 */
class ModuleSettingsForm extends ConfigFormBase {

  /**
   * Constructor
   */
  public function __construct() {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static();
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'access-amie-settings-form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('access_amie.settings');

    $form['api_key_id'] = [
      '#type' => 'key_select',
      '#title' => 'API key',
      '#description' => 'Only Authentication keys are accepted',
      '#required' => true,
      '#default_value' => $config->get('api_key_id'),
      '#key_filters' => ['type_group' => 'authentication']
    ];
    $form['site_name'] = [
      '#type' => 'textfield',
      '#title' => 'Site name',
      '#description' => 'The name of the local site',
      '#required' => true,
      '#default_value' => $config->get('site_name')
    ];
    $form['rest_url'] = [
      '#type' => 'textfield',
      '#title' => 'REST URL',
      '#description' => 'The URL of the remote AMIE REST service',
      '#required' => true,
      '#default_value' => $config->get('rest_url')
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $config = $this->config('access_amie.settings');

    $config->set('api_key_id', $form_state->getValue('api_key_id'));
    $config->set('site_name', $form_state->getValue('site_name'));
    $config->set('rest_url', $form_state->getValue('rest_url'));
    $config->save();
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['access_amie.settings'];
  }
}
