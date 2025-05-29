<?php

require_once 'civiwebhook.civix.php';

use GuzzleHttp\Client;
use CRM_Civiwebhook_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 */
function civiwebhook_civicrm_config(&$config): void {
  _civiwebhook_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 */
function civiwebhook_civicrm_install(): void {
  _civiwebhook_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 */
function civiwebhook_civicrm_enable(): void {
  _civiwebhook_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_post().
 */
function civiwebhook_civicrm_post($op, $objectName, $objectId, &$objectRef): void {
  file_put_contents('/tmp/civiwebhook.log', "POST HOOK: $op on $objectName (ID: $objectId)\n", FILE_APPEND);
  ContactHook::handlePost($op, $objectName, $objectId, $objectRef);
}

/**
 * Implements hook_civicrm_pre().
 */
function civiwebhook_civicrm_pre($op, $objectName, $objectId, &$params): void {
  file_put_contents('/tmp/civiwebhook.log', "PRE HOOK: $op on $objectName (ID: $objectId)\n", FILE_APPEND);
  ContactHook::handlePre($op, $objectName, $objectId, $params);
}



class ContactHook {
  public static function handlePost($op, $objectName, $objectId, &$objectRef) {
    if (!in_array($objectName, ['Individual', 'Aircraft', 'Organization'])) {
      return;
    }

    $data = [
      'operation' => $op,
      'id' => $objectId,
      'contact' => $objectRef ?? [],
    ];

    self::sendToWebhook($data);
  }

  public static function handlePre($op, $objectName, $objectId, &$params) {
    if (!in_array($objectName, ['Individual', 'Aircraft', 'Organization']) || $op !== 'delete') {
      return;
    }

    $data = [
      'operation' => 'delete',
      'id' => $objectId
    ];

    self::sendToWebhook($data);
  }

  protected static function sendToWebhook($payload) {
    $webhookUrl = \Civi::settings()->get('civiwebhook_url');
    if (!$webhookUrl) {
      file_put_contents('/tmp/civiwebhook.log', "No webhook URL set\n", FILE_APPEND);
      return;
    }
  
    file_put_contents('/tmp/civiwebhook.log', json_encode([
      'event' => 'sending_webhook',
      'url' => $webhookUrl,
      'payload' => $payload,
      'timestamp' => date('c'),
    ]) . "\n", FILE_APPEND);
  
    try {
      $client = new Client();
      $response = $client->post($webhookUrl, [
        'headers' => [
          'Content-Type' => 'application/json',
        ],
        'body' => json_encode($payload),
      ]);
      file_put_contents('/tmp/civiwebhook.log', "Webhook sent: " . $response->getStatusCode() . "\n", FILE_APPEND);
    } catch (\Throwable $e) {
      file_put_contents('/tmp/civiwebhook.log', "Webhook send failed: " . $e->getMessage() . "\n", FILE_APPEND);
    }
  }
}