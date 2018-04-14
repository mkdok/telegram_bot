<?php

namespace Drupal\telegram_bot_log\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Telegram Bot Log entity.
 *
 * @ContentEntityType(
 *   id = "telegram_bot_log",
 *   label = @Translation("Telegram Bot Log"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\telegram_bot_log\TelegramBotLogListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\telegram_bot_log\TelegramBotLogAccessControlHandler",
 *     "form" = {
 *       "delete" = "Drupal\telegram_bot_log\Form\TelegramBotLogDeleteForm",
 *     },
 *   },
 *   base_table = "telegram_bot_log",
 *   fieldable = FALSE,
 *   entity_keys = {
 *     "id" = "id",
 *     "uid" = "uid",
 *   },
 *   links = {
 *     "canonical" = "/admin/reports/telegram-bot-log/{telegram_bot_log}",
 *     "delete-form" = "/admin/reports/telegram-bot-log/{telegram_bot_log}/delete",
 *     "collection" = "/admin/reports/telegram-bot-log",
 *   },
 * )
 */
class TelegramBotLog extends ContentEntityBase implements TelegramBotLogInterface {

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'uid' => \Drupal::currentUser()->id(),
      'created' => \Drupal::time()->getRequestTime(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('uid')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('uid', $account->id());
    return $this;
  }

  /**
   * Sets request data.
   */
  public function setRequest($request) {
    $this->set('request', $request);
    return $this;
  }

  /**
   * Sets response data.
   */
  public function setResponse($response) {
    $this->set('response', $response);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = [];

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the log.'))
      ->setReadOnly(TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User'))
      ->setDescription(t('The user ID of author.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', ['label' => 'above', 'type' => 'entity_reference_label', 'weight' => 0]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time when the log was created.'))
      ->setDisplayOptions('view', ['label' => 'above', 'type' => 'timestamp', 'weight' => 1]);

    $fields['request'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Request'))
      ->setDescription(t('Request.'))
      ->setDisplayOptions('view', ['label' => 'above', 'type' => 'text_default', 'weight' => 2]);

    $fields['response'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Response'))
      ->setDescription(t('Response.'))
      ->setDisplayOptions('view', ['label' => 'above', 'type' => 'text_default', 'weight' => 3]);

    return $fields;
  }

}
