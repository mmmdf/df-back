<?php

/*
 * Template.class.php
 *   - template wrapper for Smarty
 *
 * Author  : Andrei Gavrila (andrei.gavrila@gmail.com)
 * Date    : 2005.08.22
 * Licence : GPL 2.0
 *
 * Changes :
 *   - [2005.10.06] - Andrei Gavrila - Changed location for configuration (language) files
 *   - [2006.02.28] - Andrei Gavrila - Added tDisplay() function and _base.tmpl support
 *   - [2006.02.28] - Andrei Gavrila - Added language support
 *   - [2011.02.13] - Andrei Gavrila - Upgrade to Smarty 3
 *   - [2011.02.13] - Andrei Gavrila - Upgrade to PHP5 class style (constructor)
 *   - [2018.09.20] - Andrei Gavrila - Upgrade to Smarty 3.1.33 (template_dir, config_dir are now arrays)
 *   - []
 */

class CTemplate extends Smarty {
  function __construct() {
    parent::__construct();

    $tmp = PROJECT_TEMP . PROJECT_NAME;

    if (!is_dir($tmp)) {
      mkdir($tmp);
      mkdir($tmp . '/cache');
      mkdir($tmp . '/compile');
    }

    $this->template_dir[0] = PROJECT_PATH . '/_templates/' . PROJECT_THEME . '/';
    $this->config_dir[0]   = PROJECT_PATH . '/_languages/';
    $this->cache_dir       = $tmp . '/cache/';
    $this->compile_dir     = $tmp . '/compile/';

    $this->config_overwrite = false;

    /*
     * Change this in a production environement ! 
     */

    $this->caching = SMARTY_CACHING;
    $this->force_compile = SMARTY_FORCE_COMPILE;
  }

  function tDisplay($szTemplate, $language = 'EN') {
    include(PROJECT_PATH . "_include/Assign.inc.php");

    /*
     * Load the language file
     */

    if (is_file($this->config_dir[0] . '/' . substr($szTemplate, 0, -5) . '.lg')) {
      $this->configLoad(substr($szTemplate, 0, -5) . '.lg', $language);
    }

    $__main__ = $this->fetch($this->template_dir[0] . $szTemplate);
    $this->assign('__main__', $__main__);

    /*
     * Embed the content of the template into _base.tmpl
     *   and load the _base.lg config file
     */

    if (strrpos($szTemplate, '/') !== FALSE) {
      if (is_file($this->template_dir[0] . '/' . substr($szTemplate, 0, strrpos($szTemplate, '/')) . '/_base.tmpl')) {
        if (is_file($this->config_dir[0] . '/' . substr($szTemplate, 0, strrpos($szTemplate, '/')) . '/_base.lg')) {
          $this->configLoad(substr($szTemplate, 0, strrpos($szTemplate, '/')) . '/_base.lg', $language);
        }

        $this->display(substr($szTemplate, 0, strrpos($szTemplate, '/')) . '/_base.tmpl');
      } else {
        print($__main__);
      }
    } else {
      if (is_file($this->template_dir[0] . '/_base.tmpl')) {
        if (is_file($this->config_dir[0] . '/_base.lg')) {
          $this->configLoad('_base.lg', $language);
        }

        $this->display('_base.tmpl');
      } else {
        print($__main__);
      }
    }
  }
}
