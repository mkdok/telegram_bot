<?php

namespace Drupal\telegram_bot_log;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Telegram Bot Log entity.
 *
 * @see \Drupal\telegram_bot_log\Entity\TelegramBotLog.
 */
class TelegramBotLogAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view telegram bot log entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete telegram bot log entities');
    }
    return AccessResult::neutral();
  }

}
