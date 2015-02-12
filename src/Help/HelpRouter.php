<?php

namespace Drupal\financials_saas\Help;

class HelpRouter {
  static function load() {
    $args = func_get_args();
    $className = array_shift($args);

    if (class_exists($className)) {
      $class = new $className();
      return call_user_func(array($class, 'parse'));
    }
    throw new \Exception(t('Invalid page class: @class', array('@class' => $className)));
  }

  static function pages() {
    $items = array();
    $items['help'] = array(
      'title' => HelpHome::title(),
      'className' => 'HelpHome',
    );
    return $items;
  }
}
