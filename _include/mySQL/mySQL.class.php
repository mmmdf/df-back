<?php

/*
 * mySQL.class.php
 *   - mySQL wrapper
 *
 * Author  : Andrei Gavrila (andrei.gavrila@gmail.com)
 * Date    : 2004.08.11
 * Licence : GPL 2.0
 *
 * Example :
 *
 *   $db = new CmySQL('mysql://[user[:pass]]@[server:[port]]/database', [bFatalError = true]);
 *   print_r($db->query('SELECT * FROM table'[, 'id']));
 *
 * Changes :
 *   - [2005.06.02] - Andrei Gavrila - Coding style changes
 *   - [2008.10.06] - Andrei Gavrila - Small optimizations
 *   - [2011.02.13] - Andrei Gavrila - Removed warning in port detection
 *   - [2011.02.13] - Andrei Gavrila - Upgrade to PHP5 class style (constructor)
 *   - [2018.10.01] - Andrei Gavrila - Updated to mysqli
 *   - []
 */

class CmySQL {
  var $dbHost = ''; // Hostname
  var $dbUser = ''; // Username
  var $dbPass = ''; // Password
  var $dbName = ''; // Database
  var $dbPort = ''; // Server port or socket

  var $dbLink      = false;   // Link to server
  var $dbResult    = false;   // Result
  var $dbArray     = array(); // Array with selected rows
  var $bFatalError = true;    // die() on errors

  var $szmySQLMessages = array(
        'wrongParams'  => 'Wrong format.',
        'noSupport'    => 'mySQL lib not present.',
        'errorConnect' => 'Cannot connect to mySQL server.',
        'errorSelect'  => 'Cannot select database.',
        'errorQuery'   => 'Cannot perform query.');

  var $szErrorFormat = '<div style="font-family: verdana; font-size: 12px; font-weight: bold; color: red;">__MESSAGE__<br />__MYSQLERRNO__ : __MYSQLERROR__</div>';

  function __construct($dbData, $bFatalError = true) {
    /*
     * Load values in member variables
     */

    if (eregi('mysql://([a-z0-9_:\ ]+)@([a-z0-9\.\:\-]+)/([a-z0-9_]+)', $dbData, $dbParams)) {
      if (strpos($dbParams[1], ':') !== FALSE) {
        list ($this->dbUser, $this->dbPass) = split('\:', $dbParams[1]);
      } else {
        $this->dbUser = $dbParams[1];
      }

      if (strpos($dbParams[2], ':') !== FALSE) {
        list ($this->dbHost, $this->dbPort) = split('\:', $dbParams[2]);
      } else {
        $this->dbHost = $dbParams[2];
      }

      $this->dbName = $dbParams[3];
    }
    else {
      $this->error($this->szmySQLMessages['wrongParams']);

      return false;
    }

    $this->bFatalError = $bFatalError;

    /*
     * Check the presence of the mySQL library
     */

    if (!$this->checkmySQL()) {
      $this->error($this->szmySQLMessages['noSupport']);

      return false;
    }

    /*
     * Connect to mySQL server
     */

    if (!$this->connect()) {
      $this->error($this->szmySQLMessages['errorConnect']);

      return false;
    }

    /*
     * Select the database
     */

    if (!$this->select()) {
      $this->error($this->szmySQLMessages['errorSelect']);

      return false;
    }
  }

  /*
   * Check the presence of the mySQL library
   */

  function checkmySQL() {
    return function_exists('mysqli_connect');
  }

  /*
   * Connect to mySQL server
   */

  function connect() {
    /*
     * Close the connection
     */

    if ($this->dbLink) {
      @mysqli_close($this->dbLink);
    }

    /*
     * Connect
     */

    if (empty($this->dbPort)) {
      $this->dbLink = @mysqli_connect($this->dbHost, $this->dbUser, $this->dbPass);
    } else {
      $this->dbLink = @mysqli_connect($this->dbHost . ':' . $this->dbPort, $this->dbUser, $this->dbPass);
    }

    return $this->dbLink;
  }

  /*
   * Select the database
   */

  function select() {
    if ($this->dbLink) {
      if (@mysqli_select_db($this->dbLink, $this->dbName)) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  /*
   * Perform a mySQL query (index the result on szIndex)
   */

  function query($szQuery, $szIndex = '') {
    if ($this->dbLink) {
      /*
       * Free the result
       */

      if ($this->dbResult) {
        @mysqli_free_result($this->dbResult);
      }

      $this->dbResult = @mysqli_query($this->dbLink, $szQuery);

      /*
       * Test to see if the link broke down
       */

      if (mysqli_errno($this->dbLink) == 1001) {
        /*
         * Connect to mySQL server
         */

        if (!$this->connect()) {
          $this->error($this->szmySQLMessages['errorConnect']);

          return false;
        }

        /*
         * Select the database
         */

        if (!$this->select()) {
          $this->error($this->szmySQLMessages['errorSelect']);

          return false;
        }

        $this->dbResult = @mysqli_query($this->dbLink, $szQuery);
      }

      if (!$this->dbResult) {
        $this->error($this->szmySQLMessages['errorQuery']);

        return false;
      }

      /*
       * Put the result in dbArray
       */

      $this->dbArray = array();
      if (@mysqli_num_rows($this->dbResult) > 0) {
        while ($entry = @mysqli_fetch_array($this->dbResult, MYSQL_ASSOC)) {
          if (!empty($szIndex)) {
            $this->dbArray[$entry[$szIndex]] = $entry;
          } else {
            array_push($this->dbArray, $entry);
          }
        }
      }

      return $this->dbArray;
    } else {
      $this->error($this->szmySQLMessages['errorQuery']);

      return false;
    }
  }

  /*
   * Display an error and die() if bFatalError is true
   */

  function error($szMessage) {
    $szMsg = $this->szErrorFormat;
    $szMsg = str_replace('__MESSAGE__', $szMessage, $szMsg);
    $szMsg = str_replace('__MYSQLERROR__', mysqli_error($this->dbLink), $szMsg);
    $szMsg = str_replace('__MYSQLERRNO__', mysqli_errno($this->dbLink), $szMsg);

    if ($this->bFatalError) {
      die($szMsg);
    } else {
      echo $szMsg;
    }
  }

  /*
   *
   */

  function escape($string) {
    return mysqli_escape_string($this->dbLink, $string);
  }
}
