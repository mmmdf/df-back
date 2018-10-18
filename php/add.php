<?php

/*
 * add.php
 *   - 
 *
 * Author  : Andrei Gavrila (andrei.gavrila@gmail.com)
 * Date    : 2018.10.06
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

$services = $db->query("SELECT * FROM service", "id");
$html->assign('services', $services);

$airports = $db->query("SELECT * FROM airport", "id");
$html->assign('airports', $airports);

$consolidators = $db->query("SELECT * FROM consolidator", "id");
$html->assign('consolidators', $consolidators);

if ($_POST) {
  if (!isset($_POST['firstname']) || !preg_match('/^[A-Za-z]{2,}$/', $_POST['firstname'])) {
    die();
  }

  if (!isset($_POST['surname']) || !preg_match('/^[A-Za-z]{2,}$/', $_POST['surname'])) {
    die();
  }

  if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    die();
  }

  if (!isset($_POST['mobile']) || !preg_match('/^[0-9 .+\/]{10,}$/', $_POST['mobile'])) {
    die();
  }

  if (!isset($_POST['leavingDate']) || date('Y-m-d H:i:s', strtotime($_POST['leavingDate'])) != $_POST['leavingDate']) {
    die();
  }

  if (!isset($_POST['returnDate']) || date('Y-m-d H:i:s', strtotime($_POST['returnDate'])) != $_POST['returnDate']) {
    die();
  }

  if (!isset($_POST['carModel']) || !preg_match('/^[A-Za-z0-9 ]+$/', $_POST['carModel'])) {
    die();
  }

  if (!isset($_POST['carColour']) || !preg_match('/^[A-Za-z ]+$/', $_POST['carColour'])) {
    die();
  }

  if (!isset($_POST['carReg']) || !preg_match('/^[A-Za-z0-9 ]+$/', $_POST['carReg'])) {
    die();
  }

  if (!isset($_POST['returnFlightNum']) || !preg_match('/^[A-Z]{2}[0-9]{1,}$/', $_POST['returnFlightNum'])) {
    die();
  }

  if (!isset($_POST['terminal_in']) || !preg_match('/^[A-Za-z0-9]{1,}$/', $_POST['terminal_in'])) {
    die();
  }

  if (!isset($_POST['terminal_out']) || !preg_match('/^[A-Za-z0-9]{1,}$/', $_POST['terminal_out'])) {
    die();
  }

  if (!isset($_POST['price']) || !preg_match('/^[0-9]{1,}$/', $_POST['price'])) {
    die();
  }

  if (!isset($_POST['product']) || !preg_match('/^[A-Za-z ]{1,}$/', $_POST['product'])) {
    die();
  }

  if (!isset($_POST['typeID']) || intval($_POST['typeID']) == 0) {
    die();
  }

  if (!isset($_POST['consolidatorID']) || intval($_POST['consolidatorID']) == 0) {
    die();
  }

  if (!isset($_POST['airportID']) || intval($_POST['airportID']) == 0) {
    die();
  }

  $db->query("INSERT INTO reports (firstname, surname, email, mobile, typeID, consolidatorID, airportID, leavingDate, returnDate, amountPaid, created, carReg, carModel, carColour, returnFlightNum, terminal_out, terminal_in, refNum, product, notes) VALUES(
                    '" . mysql_escape_string($_POST['firstname']) . "',
                    '" . mysql_escape_string($_POST['surname']) . "',
                    '" . mysql_escape_string($_POST['email']) . "',
                    '" . mysql_escape_string($_POST['mobile']) . "',
                    '" . mysql_escape_string($_POST['typeID']) . "',
                    '" . mysql_escape_string($_POST['consolidatorID']) . "',
                    '" . mysql_escape_string($_POST['airportID']) . "',
                    '" . mysql_escape_string($_POST['leavingDate']) . "',
                    '" . mysql_escape_string($_POST['returnDate']) . "',
                    '" . mysql_escape_string($_POST['price']) . "',
                    '" . mysql_escape_string($_POST['price']) . "',
                    '" . mysql_escape_string($_POST['carReg']) . "',
                    '" . mysql_escape_string($_POST['carModel']) . "',
                    '" . mysql_escape_string($_POST['carColour']) . "',
                    '" . mysql_escape_string($_POST['returnFlightNum']) . "',
                    '" . mysql_escape_string($_POST['terminal_out']) . "',
                    '" . mysql_escape_string($_POST['terminal_in']) . "',
                    '" . mysql_escape_string($_POST['refNum']) . "',
                    '" . mysql_escape_string($_POST['product']) . "',
                    '" . mysql_escape_string($_POST['notes']) . "')");

  header('Location: /');

  die();
}

if (isset($_GET['report']) && intval($_GET['report']) > 0) {
  $report = $db->query("SELECT * FROM reports WHERE id = '" . mysql_escape_string(intval($_GET['report'])) . "'");
  if (is_array($report) && count($report)) {
    $html->assign('report', $report[0]);
  }
}

$html->tDisplay('add.tmpl', $session['language']);
