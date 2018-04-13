<?php

namespace Drupal\telegram_bot\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure Telegram Bot settings for this site.
 */
class TelegramBotNotificationSettingsForm extends ConfigFormBase {

  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Class constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The plugin manager for entity storage.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($config_factory);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'telegram_bot_notification_settings';
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

    $form = [
      '#type' => 'details',
      '#open' => TRUE,
    ];
    $form['settings'] = [
      '#type' => 'container',
      '#title' => $this->t('Settings for content types'),
      '#tree' => TRUE,
    ];

    $content_types = $this
      ->entityTypeManager
      ->getStorage('node_type')
      ->loadMultiple();

    $default_value = $config
      ->get('notification.entities');

    // Collect form to output.
    foreach ($content_types as $content_type) {
      $form['settings']['node'][$content_type->id()]['enable'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Notification for ' . $content_type->id()),
        '#default_value' => $default_value['node'][$content_type->id()]['enable'],
      ];
      $form['settings']['node'][$content_type->id()]['text'] = [
        '#type' => 'textarea',
        '#title' => $this->t('Settings'),
        '#default_value' => $default_value['node'][$content_type->id()]['text'],
        '#states' => [
          'invisible' => [
            ':input[name="settings[node][' . $content_type->id() . '][enable]"]' => ['checked' => FALSE],
          ],
        ],
      ];
    }

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
    foreach ($form_state->getValue('settings') as $entity_type => $bundles) {
      foreach ($bundles as $name => $data) {
        $configs[$entity_type][$name] = [
          'enable' => $data['enable'],
          'text' => $data['text'],
        ];
      }
    }
    $this->config('telegram_bot.settings')
      ->set('notification.entities', $configs)
      ->save();

    parent::submitForm($form, $form_state);
  }

}
