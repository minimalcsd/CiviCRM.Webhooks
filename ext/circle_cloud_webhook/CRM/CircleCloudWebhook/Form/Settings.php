<?php

class CRM_CircleCloudWebhook_Form_Settings extends CRM_Admin_Form_Setting {
  /**
   * Variables
   * @var string
   */
  const PREFERENCE = 'CircleCloudWebhook Preferences';
  protected $_settings = [
    'circle_cloud_webhook_settings' => CRM_CircleCloudWebhook_Form_Settings::PREFERENCE,
  ];

  /**
   * Set variables up before form is built.
   */
  public function preProcess() {
    $this->setPageTitle(ts('Circle Cloud Webhook Settings'));
  }

  /**
   * Get the metadata relating to the settings on the form, ordered by the keys in $this->_settings.
   *
   * @return array
   */
  protected function getSettingsMetaData(): array {
    $allSettingMetaData = civicrm_api3('setting', 'getfields', []);
    $settingMetaData = array_intersect_key($allSettingMetaData['values'], $this->_settings);
    // This array_merge re-orders to the key order of $this->_settings.
    $settingMetaData = array_merge($this->_settings, $settingMetaData);
    return $settingMetaData;
  }

  /**
   * Function to actually build the form
   *
   * @access public
   */
  public function buildQuickForm() {

    // Retrieve elements from settings
    $settingMetaData = $this->getSettingsMetaData();
    $elementNames = [
      'webhookurl',
      'debugOn',
    ];
    $this->assign('elementNames', $elementNames);$attributes = [
      'size' => 64,
      'maxlength' => 255,
    ];
    $this->add('url', 'circle_cloud_webhook_settings[webhookurl]', ts('Webhook URL'), $attributes, TRUE);
    $this->addYesNo('circle_cloud_webhook_settings[debugOn]', ts('Debug On?'), [], TRUE);

    // Add submit/save button
    $this->addButtons([
      [
        'type' => 'submit',
        'name' => ts('Save'),
      ],
      [
        'type' => 'cancel',
        'name' => ts('Cancel'),
      ],
    ]);
  }

  /**
   * Default values
   *
   * @access public
   * @return void
   */
  public function setDefaultValues() {
    $defaults = parent::setDefaultValues();
    if (!isset($defaults['circle_cloud_webhook_settings'])
      || !isset($defaults['circle_cloud_webhook_settings']['debugOn'])
    ) {
      $defaults['circle_cloud_webhook_settings']['debugOn'] = 0;
    }
    return $defaults;
  }

  /**
   * Function to process the form
   *
   * @access public
   */
  public function postProcess() {
    $params = $this->controller->exportValues($this->_name);
    parent::commonProcess($params);
    CRM_Core_Session::setStatus(ts('Settings saved successfully.'), ts('Changes Saved'), "success");
    CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/admin', 'reset=1'));
  }

}
