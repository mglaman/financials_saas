<?php

/**
 * @file
 * Doc file for profile
 */

 /**
 * Implements hook_form_FORM_ID_alter() for install_configure_form().
 *
 * Allows the profile to alter the site configuration form.
 */
function financials_saas_form_install_configure_form_alter(&$form, $form_state) {
  // Pre-populate the site name with the server name.
  $form['site_information']['site_name']['#default_value'] = 'Who Needs a Budget?';
}

/**
 * Implements hook_libraries_info().
 */
function financials_saas_libraries_info() {
  $libraries['spyc'] = array(
    'name' => 'Spyc',
    'version' => '0.5.1',
    'vendor url' => 'https://github.com/mustangostang/spyc',
    'download url' => 'https://raw2.github.com/mustangostang/spyc/master/Spyc.php',
    'version arguments' => array(
      'file' => 'Spyc.php',
      'pattern' => '@version\s+([0-9a-zA-Z\.-]+)@',
    ),
    'files' => array(
      'php' => array('Spyc.php'),
    ),
    'xautoload' => function($adapter) {$adapter->composerJson('composer.json');}
  );

  return $libraries;
}
