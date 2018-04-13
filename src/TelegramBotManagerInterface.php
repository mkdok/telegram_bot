<?php

namespace Drupal\telegram_bot;

/**
 * Interface TelegramBotManagerInterface.
 */
interface TelegramBotManagerInterface {

  /**
   * Connect to Telegram bot.
   *
   * @param string $bot_token
   *   Telegram bot token.
   */
  public function connect(string $bot_token = NULL);

  /**
   * Send message by telegram bot.
   *
   * @param string $message
   *   The message.
   * @param string $chat_id
   *   Telegram chat id.
   *
   * @return bool
   *   Status of sending message.
   *
   * @throws \Longman\TelegramBot\Exception\TelegramException
   */
  public function sendMessage(string $message, string $chat_id);

}
