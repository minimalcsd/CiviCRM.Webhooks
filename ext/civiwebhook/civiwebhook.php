<?php

require_once 'civiwebhook.civix.php';

use CRM_Civiwebhook_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function civiwebhook_civicrm_config(&$config): void {
  _civiwebhook_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function civiwebhook_civicrm_install(): void {
  _civiwebhook_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function civiwebhook_civicrm_enable(): void {
  _civiwebhook_civix_civicrm_enable();
}
