<?php

namespace Drupal\telegram_bot_log;

use Drupal\Component\Utility\Unicode;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a class to build a listing of Telegram Bot Log entities.
 *
 * @ingroup telegram_bot_log
 */
class TelegramBotLogListBuilder extends EntityListBuilder {

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * Constructs a new TelegramBotLogListBuilder object.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, DateFormatterInterface $date_formatter) {
    parent::__construct($entity_type, $storage);
    $this->dateFormatter = $date_formatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity.manager')->getStorage($entity_type->id()),
      $container->get('date.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Log ID');
    $header['request'] = $this->t('Request');
    $header['response'] = $this->t('Response');
    $header['uid'] = $this->t('User');
    $header['created'] = $this->t('Timestamp');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $url = $entity->toUrl();

    $row['id'] = $entity->id();
    $row['request']['data'] = [
      '#type' => 'link',
      '#title' => Unicode::truncate($entity->get('request')->value, 64, FALSE, TRUE),
      '#url' => $url,
    ];
    $row['response']['data'] = [
      '#type' => 'link',
      '#title' => Unicode::truncate($entity->get('response')->value, 64, FALSE, TRUE),
      '#url' => $url,
    ];
    $row['uid']['data'] = [
      '#theme' => 'username',
      '#account' => $entity->getOwner(),
    ];
    $row['created'] = $this->dateFormatter->format($entity->getCreatedTime(), 'short');

    return $row + parent::buildRow($entity);
  }

}
