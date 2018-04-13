<?php

namespace Drupal\telegram_bot;

use Drupal\Core\Config\ConfigFactoryInterface;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

/**
 * Class TelegramBotManager.
 */
class TelegramBotManager implements TelegramBotManagerInterface {

  /**
   * Telegram bot token.
   *
   * @var string
   */
  protected $botToken;

  /**
   * Telegram bot name.
   *
   * @var string
   */
  protected $botName;

  /**
   * The logger channel factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $logger;

  /**
   * Constructs a new TelegramBotManager object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger
   *   Logger object.
   */
  public function __construct(ConfigFactoryInterface $config_factory, LoggerChannelFactoryInterface $logger) {
    $this->logger = $logger;
    // Get telegram token and name.
    $config = $config_factory->get('telegram_bot.settings');
    $this->botName = $config->get('name');
    $this->botToken = $config->get('token');
  }

  /**
   * {@inheritdoc}
   */
  public function connect(string $bot_token = NULL) {
    $telegram = NULL;
    try {
      // Create Telegram API object.
      $telegram = new Telegram($bot_token ?: $this->botToken, $this->botName);
    }
    catch (TelegramException $e) {
      $this->logger->get('telegram bot')->error($e->getMessage());
    }

    return $telegram;
  }

  /**
   * {@inheritdoc}
   */
  public function sendMessage(string $message, string $chat_id) {
    $this->connect();
    $result = Request::sendMessage(['chat_id' => $chat_id, 'text' => $message]);
    return $result->isOk();
  }

}
