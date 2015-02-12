<?php

namespace Drupal\financials_saas\Help;

class HelpPageBase {
  protected $parser;
  protected $path;
  protected $fileName;

  static function title() {
    return t('Base help page');
  }

  public function __construct() {
    libraries_load('parsedown');
    $this->parser = new \Parsedown();
    $this->path = drupal_get_path('profile', 'financials_saas') . '/config/help';
  }

  public function parse() {
    $this->config();
    return $this->parser->text($this->load());
  }

  protected function load() {
    $filePath = $this->path . '/' . $this->fileName;
    if (is_file($filePath)) {
      return file_get_contents($filePath);
    }
    return null;
  }

  protected function config() {
    $this->parser->setBreaksEnabled(true);
  }
}
