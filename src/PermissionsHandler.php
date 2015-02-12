<?php

namespace Drupal\financials_saas;

class PermissionsHandler {
  /**
   * Array of roles and their permissions
   *
   * Structure of roleName => array(..Permissions..)
   *
   * @var array;
   */
  protected $acl = array();

  /**
   * Track if the provisioner has ensured the roles.
   * @var bool
   */
  protected $provisioned = false;

  /**
   * Path of files.
   */
  protected $path;

  /**
   * Initiates the ACL Provisioner.
   *
   * Loads ACL configuration files to define platform roles and permissions.
   */
  public function __construct() {
    //libraries_load('spyc');
    $this->path = drupal_get_path('profile', 'financials_saas') . '/config';

    $this->loadConfig('anonymous');
    $this->loadConfig('authenticated');
  }

  public function getACL() {
    return $this->acl;
  }

  /**
   * Ensures all roles exist that have been provided to provisioner.
   */
  public function ensureRoles() {
    // Loop through the ACL and ensure roles.
    foreach ($this->getACL() as $roleName => $roleData) {
      $role = user_role_load_by_name($roleName);
      if ($role == false) {
        $role = new stdClass();
        $role->name = $roleName;
        $role->weight = $roleData['weight'];
        user_role_save($role);
      }
      $this->acl[$roleName]['rid'] = (int) $role->rid;
    }
  }

  public function ensurePermissions() {
    $this->ensureRoles();
    foreach($this->getACL() as $roleName => $roleData) {
      user_role_change_permissions($roleData['rid'], $roleData['permissions']);
    }
  }

  public function loadConfig($uri) {
    $uri = $this->path . '/user.role.' . $uri . '.yml';
    if (is_file($uri)) {
      $config = \Spyc::YAMLLoad($uri);

      $configName = $config['name'];

      // Normalize the permissions value, if empty.
      if (!is_array($config['permissions'])) {
        $config['permissions'] = array();
      }

      $this->acl[$configName] = $config;
    }
  }

  /**
   * Exports the YML for a role
   *
   * @param int $rid The role ID to export.
   *
   * @return string
   *  Formatted YML output.
   */
  public function exportYML($rid) {
    // Load the role.
    $role = user_role_load($rid);

    // Load the role's permissions.
    $permissions = user_role_permissions(array($role->rid => $role->name));
    $permissions = reset($permissions);

    // Load all system permissions
    $modules = user_permission_get_modules();

    // Mark the permissions as false which aren't allowed, so we can utilize
    // user_role_change_permissions() like submitting permissions form.
    $revokedPermissions = array_diff_key($modules, $permissions);
    foreach ($revokedPermissions as $permission => $module) {
      $revokedPermissions[$permission] = false;
    }
    $permissions += $revokedPermissions;


    $output = array(
      'name' => $role->name,
      'weight' => $role->weight,
      'permissions' => $permissions,
    );

    return \Spyc::YAMLDump($output);
  }
}
