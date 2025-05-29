<?php

return [
  'civiwebhook_url' => [
    'name' => 'civiwebhook_url',
    'type' => 'String',
    'default' => '',
    'html_type' => 'text',
    'title' => ts('Webhook URL'),
    'description' => ts('The endpoint to send contact events to.'),
    'is_domain' => 1,
    'settings_pages' => ['civiwebhook' => ['weight' => 1]],
  ],
];
