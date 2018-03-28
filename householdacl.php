<?php

require_once 'householdacl.civix.php';
use CRM_Householdacl_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function householdacl_civicrm_config(&$config) {
  _householdacl_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function householdacl_civicrm_xmlMenu(&$files) {
  _householdacl_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function householdacl_civicrm_install() {
  _householdacl_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function householdacl_civicrm_postInstall() {
  _householdacl_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function householdacl_civicrm_uninstall() {
  _householdacl_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function householdacl_civicrm_enable() {
  _householdacl_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function householdacl_civicrm_disable() {
  _householdacl_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function householdacl_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _householdacl_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function householdacl_civicrm_managed(&$entities) {
  _householdacl_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function householdacl_civicrm_caseTypes(&$caseTypes) {
  _householdacl_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function householdacl_civicrm_angularModules(&$angularModules) {
  _householdacl_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function householdacl_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _householdacl_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_aclWhereClause().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_aclWhereClause
 *
 */
function householdacl_civicrm_aclWhereClause($type, &$tables, &$whereTables, &$contactID, &$where) {
  if (!$contactID) {
    return NULL;
  }
  $headOfHouseHold = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_RelationshipType', 'Head of Household for', 'id', 'name_a_b');
  $memberOfHousehold = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_RelationshipType', 'Household Member of', 'id', 'name_a_b');
  $houseHoldId = CRM_Core_DAO::singleValueQuery("SELECT contact_id_b FROM `civicrm_relationship` cr1
    WHERE cr1.contact_id_a = {$contactID} AND cr1.relationship_type_id = {$headOfHouseHold}"
  );
  if (!$houseHoldId) {
    return NULL;
  }
  if (!empty($where)) {
    $where .= ' AND ';
  }
  $where .= " contact_a.id IN (SELECT contact_id_a FROM `civicrm_relationship` cr1
    WHERE cr1.contact_id_b = {$houseHoldId} AND cr1.relationship_type_id IN ({$headOfHouseHold}, {$memberOfHousehold})
    UNION SELECT {$houseHoldId})";
}
