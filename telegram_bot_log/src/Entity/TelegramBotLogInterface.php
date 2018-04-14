<?php

namespace Drupal\telegram_bot_log\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Telegram Bot Log entities.
 *
 * @ingroup telegram_bot_log
 */
interface TelegramBotLogInterface extends ContentEntityInterface, EntityOwnerInterface {

  /**
   * Gets the Telegram Bot Log creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Telegram Bot Log.
   */
  public function getCreatedTime();

  /**
   * Sets the Telegram Bot Log creation timestamp.
   *
   * @param int $timestamp
   *   The Telegram Bot Log creation timestamp.
   *
   * @return \Drupal\telegram_bot_log\Entity\TelegramBotLogInterface
   *   The called Telegram Bot Log entity.
   */
  public function setCreatedTime($timestamp);


  /**
   * Sets the Telegram Bot Log request.
   *
   * @param string $request
   *   The Telegram Bot Log creation timestamp.
   *
   * @return \Drupal\telegram_bot_log\Entity\TelegramBotLogInterface
   *   The called Telegram Bot Log entity.
   */
  public function setRequest($request);

  /**
   * Sets the Telegram Bot Log response.
   *
   * @param string $response
   *   The Telegram Bot Log creation timestamp.
   *
   * @return \Drupal\telegram_bot_log\Entity\TelegramBotLogInterface
   *   The called Telegram Bot Log entity.
   */
  public function setResponse($response);
}
