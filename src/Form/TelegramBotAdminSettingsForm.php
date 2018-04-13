<?php

namespace Drupal\telegram_bot\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Telegram Bot settings for this site.
 */
class TelegramBotAdminSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'telegram_bot_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['telegram_bot.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('telegram_bot.settings');

    $form['general'] = [
      '#type' => 'details',
      '#title' => $this->t('General settings'),
      '#open' => TRUE,
    ];

    $form['general']['telegram_bot_name'] = [
      '#default_value' => $config->get('name'),
      '#description' => '',
      '#required' => TRUE,
      '#title' => $this->t('Telegram Bot name'),
      '#type' => 'textfield',
    ];

    $form['general']['telegram_bot_token'] = [
      '#default_value' => $config->get('token'),
      '#description' => '',
      '#required' => TRUE,
      '#title' => $this->t('Telegram Bot token'),
      '#type' => 'textfield',
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
    $this->config('telegram_bot.settings')
      ->set('name', $form_state->getValue('telegram_bot_name'))
      ->set('token', $form_state->getValue('telegram_bot_token'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
