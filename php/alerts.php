<?php

/*
 * alerts.php
 *   - 
 *
 * Author  : Andrei Gavrila (andrei.gavrila@gmail.com)
 * Date    : 2018.10.16
 * Licence : GPL 2.0
 *
 * Changes :
 *   - []
 */

include('../_include/Include.inc.php');

/*
 *
 */

header('Content-Type: text/html');

$html = new CTemplate();

if (isset($_POST)) {
  if (isset($_POST['id']) && intval($_POST['id']) > 0) {
    $db->query("DELETE FROM alerts WHERE id = '" . mysql_escape_string($_POST['id']) . "'");
  } else if (isset($_POST['carReg']) && isset($_POST['email'])) {
    if (!preg_match('/^[A-Za-z0-9 ]+$/', $_POST['carReg'])) {
      die();
    }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      die();
    }

    $db->query("INSERT INTO alerts (carReg, email) VALUES(
                '" . mysql_escape_string($_POST['carReg']) . "',
                '" . mysql_escape_string($_POST['email']) . "')");
  }
}

$html->assign('alerts', $db->query("SELECT * FROM alerts"));

$html->tDisplay('alerts.tmpl', $session['language']);
