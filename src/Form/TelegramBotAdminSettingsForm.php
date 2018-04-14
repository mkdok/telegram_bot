<?php

namespace Drupal\telegram_bot\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\DatabaseStorage;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Site\Settings;
use Drupal\Core\Url;
use Drupal\telegram_bot\TelegramBotManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure Telegram Bot settings for this site.
 */
class TelegramBotAdminSettingsForm extends ConfigFormBase {

  /**
   * The telegram bot manager.
   *
   * @var \Drupal\telegram_bot\TelegramBotManagerInterface
   */
  protected $telegramBotManager;

  /**
   * Constructs a new TelegramBotAdminSettingsForm.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\telegram_bot\TelegramBotManagerInterface $telegram_bot_manager
   *   The telegram bot manager service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, TelegramBotManagerInterface $telegram_bot_manager) {
    parent::__construct($config_factory);
    $this->telegramBotManager = $telegram_bot_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('telegram_bot.manager')
    );
  }

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
      '#description' => $this->t('The Telegram bot username that should be used for sending notifications. Note: it must end in <em>bot</em>.'),
      '#required' => TRUE,
      '#title' => $this->t('Telegram Bot name'),
      '#type' => 'textfield',
    ];

    $form['general']['telegram_bot_token'] = [
      '#default_value' => $config->get('token'),
      '#description' => $this->t('The Telegram bot token that has been received during bot creation. Note: token could be generated also through <em>/token</em> command'),
      '#required' => TRUE,
      '#title' => $this->t('Telegram Bot token'),
      '#type' => 'textfield',
    ];

    // Get help info.
    $this->getHelpInfo($form);

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    if (!$this->telegramBotManager->connect($form_state->getValue('telegram_bot_token'))) {
      $form_state->setErrorByName('telegram_bot_token', $this->t('The telegram bot token is invalid.'));
    }
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

  /**
   * Get help info about bot creation.
   *
   * @param array $form
   *   Form data array.
   */
  protected function getHelpInfo(array &$form) {
    $links = [
      Link::fromTextAndUrl('Telegram Bot API', Url::fromUri('https://core.telegram.org/bots', ['fragment' => '6-botfather', 'attributes' => ['target' => '_blank']])),
      Link::fromTextAndUrl('Github', Url::fromUri('https://github.com/php-telegram-bot/core', ['fragment' => 'create-your-first-bot', 'attributes' => ['target' => '_blank']])),
    ];
    $help_info = $output = '<p>' . $this->t('If you don\'t have bot, first of all you need to create it. It can be done with using @BotFather specific bot. You can check next FAQs how to create bots:') . '</p>';
    $form['general']['help_info'] = [
      '#markup' => $help_info,
    ];
    $form['general']['help_links'] = [
      '#theme' => 'item_list',
      '#items' => $links,
    ];
  }

}
