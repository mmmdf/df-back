<?php

/*
 * ajax_report_save.php
 *   - 
 *
 * Author  : Andrei Gavrila (andrei.gavrila@gmail.com)
 * Date    : 2018.10.08
 * Licence : GPL 2.0
 *
 * Changes :
 *   - []
 */

include('../_include/Include.inc.php');

/*
 *
 */

header('Content-Type: application/json');

if (!isset($_POST['id']) || intval($_POST['id']) == 0 || intval($_POST['id']) != $_POST['id']) {
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

$report = $db->query("SELECT * FROM reports r WHERE r.id = '" . mysql_escape_string($_POST['id']) . "'");
if (!is_array($report) || !count($report)) {
  die();
}

$auditTrailFields = array(
  'leavingDate',
  'returnDate',
  'carModel',
  'carColour',
  'carReg',
  'returnFlightNum',
  'terminal_in',
  'terminal_out',
  'notes'
);

$auditTrailResult = array();

foreach ($auditTrailFields as $auditTrailFieldIndex => $auditTrailFieldValue) {
  if ($_POST[$auditTrailFieldValue] != $report[0][$auditTrailFieldValue]) {
    $auditTrailResult[] = array(
      'field' => $auditTrailFieldValue,
      'before' => $report[0][$auditTrailFieldValue],
      'after' => $_POST[$auditTrailFieldValue]
    );
  }
}

if (count($auditTrailResult)) {
  $db->query("INSERT INTO audit_trail (report, record) VALUES ('" . mysql_escape_string($_POST['id']) . "', '" . mysql_escape_string(json_encode($auditTrailResult, JSON_PRETTY_PRINT)) . "')");
}

$db->query("UPDATE reports
            SET
              leavingDate = '" . mysql_escape_string($_POST['leavingDate']) . "',
              returnDate = '" . mysql_escape_string($_POST['returnDate']) . "',
              carModel = '" . mysql_escape_string($_POST['carModel']) . "',
              carColour = '" . mysql_escape_string($_POST['carColour']) . "',
              carReg = '" . mysql_escape_string($_POST['carReg']) . "',
              returnFlightNum = '" . mysql_escape_string($_POST['returnFlightNum']) . "',
              terminal_in = '" . mysql_escape_string($_POST['terminal_in']) . "',
              terminal_out = '" . mysql_escape_string($_POST['terminal_out']) . "',
              notes = '" . mysql_escape_string($_POST['notes']) . "'
            WHERE id = '" . mysql_escape_string($_POST['id']) . "'");

echo json_encode(array('result' => 'OK'), JSON_PRETTY_PRINT);
