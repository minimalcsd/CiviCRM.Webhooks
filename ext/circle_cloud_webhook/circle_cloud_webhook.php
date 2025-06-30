<?php

require_once 'circle_cloud_webhook.civix.php';

use CRM_CircleCloudWebhook_Utils as Utils;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function circle_cloud_webhook_civicrm_config(&$config): void {
  _circle_cloud_webhook_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function circle_cloud_webhook_civicrm_install(): void {
  _circle_cloud_webhook_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function circle_cloud_webhook_civicrm_enable(): void {
  _circle_cloud_webhook_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_post().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_post
 */
function circle_cloud_webhook_civicrm_post($op, $objectName, $objectId, &$objectRef): void {
  Utils::handleHook('post', $op, $objectName, $objectId, $objectRef);
}

/**
 * Implements hook_civicrm_pre().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_pre
 */
function circle_cloud_webhook_civicrm_pre($op, $objectName, $objectId, &$params): void {
  Utils::handleHook('pre', $op, $objectName, $objectId);
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
 */
function circle_cloud_webhook_civicrm_navigationMenu(&$menu) {
  _circle_cloud_webhook_civix_insert_navigation_menu($menu, 'Administer/System Settings', [
    'label' => ts('Circle Cloud Webhook Settings', ['domain' => 'circle_cloud_webhook']),
    'name' => 'circle_cloud_webhook_settings',
    'url' => CRM_Utils_System::url(
      'civicrm/admin/setting/circle_cloud_webhook',
      'reset=1', TRUE
    ),
    'active' => 1,
    'permission_operator' => 'AND',
    'permission' => 'administer CiviCRM',
  ]);
}
