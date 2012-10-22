<?php

require_once 'optionsimporter.civix.php';

/**
 * Implementation of hook_civicrm_config
 */
function optionsimporter_civicrm_config(&$config) {
  _optionsimporter_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 */
function optionsimporter_civicrm_xmlMenu(&$files) {
  _optionsimporter_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 */
function optionsimporter_civicrm_install() {
  return _optionsimporter_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function optionsimporter_civicrm_uninstall() {
  return _optionsimporter_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 */
function optionsimporter_civicrm_enable() {
  return _optionsimporter_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function optionsimporter_civicrm_disable() {
  return _optionsimporter_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 */
function optionsimporter_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _optionsimporter_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function optionsimporter_civicrm_managed(&$entities) {
  return _optionsimporter_civix_civicrm_managed($entities);
}
