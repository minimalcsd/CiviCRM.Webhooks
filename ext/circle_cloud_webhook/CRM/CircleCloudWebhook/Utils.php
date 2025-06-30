<?php

class CRM_CircleCloudWebhook_Utils {

  /**
   * Contact Types.
   * @var array
   */
  private static $contactTypes = [
    'Individual', 'Organization', 'Household',
  ];

  /**
   * Handle hook call.
   *
   * @param string $hookName
   * @param string $op
   * @param string $objectName
   * @param int $objectId
   * @param object $objectRef
   *
   */
  public static function handleHook(string $hookName, $op, $objectName, $objectId, $objectRef = NULL) {
    self::logMessage('HookParams', [
      'hook' => $hookName,
      'op' => $op,
      'objectName' => $objectName,
      'objectId' => $objectId,
    ]);

    if (!in_array($objectName, self::$contactTypes)
      || ($hookName === 'pre' && $op !== 'delete')
    ) {
      return;
    }

    $data = [
      'operation' => $op,
      'id' => $objectId,
    ];

    if ($hookName !== 'pre') {
      $data['contact'] = $objectRef ?? [];
    }
    self::sendToWebhook($data);
  }

  /**
   * Get settings.
   *
   * @return array
   */
  private static function getSettings(string $name) {
    static $settings;
    if (empty($settings)) {
      $settings = \Civi\Api4\Setting::get(FALSE)
        ->addSelect('circle_cloud_webhook_settings')
        ->execute()
        ->first()['value'];
    }

    return $settings[$name] ?? NULL;
  }

  /**
   * Log message.
   *
   * @param string $label
   * @param string|array $message
   *
   */
  private static function logMessage($label, $message) {
    if (!self::getSettings('debugOn')) {
      return;
    }

    CRM_Core_Error::debug_log_message(
      "{$label}: " . print_r($message, 1),
      FALSE, 'CircleCloudWebhook', PEAR_LOG_INFO
    );
  }

  /**
   * Send data to webhook.
   *
   * @param array $payload
   *
   */
  private static function sendToWebhook($payload) {
    $webhookUrl = self::getSettings('webhookurl');
    if (!$webhookUrl) {
      self::logMessage('WebhookUrl', 'No webhook URL set');
      return;
    }

    self::logMessage('payloadParam', [
      'event' => 'sending_webhook',
      'url' => $webhookUrl,
      'payload' => $payload,
    ]);

    try {
      $client = new \GuzzleHttp\Client();
      $response = $client->post($webhookUrl, [
        'headers' => [
          'Content-Type' => 'application/json',
          'User-Agent' => 'CiviCRM Webhook',
        ],
        'body' => json_encode($payload),
      ]);
      self::logMessage('Webhook', 'Webhook sent: ' . $response->getStatusCode());
    }
    catch (Exception $e) {
      self::logMessage('Webhook', 'Webhook send failed: ' . $e->getMessage());
    }
  }

}
