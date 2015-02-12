<?php

namespace Drupal\financials_saas\Help;

class HelpHome extends HelpPageBase {
  protected $fileName = 'home.md';

  static function title() {
    return t('Help! How to do the things.');
  }
}
