<?php

/*
 * ajax_payment_add.php
 *   - 
 *
 * Author  : Andrei Gavrila (andrei.gavrila@gmail.com)
 * Date    : 2018.10.12
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

if (!isset($_POST['report']) || intval($_POST['report']) == 0 || intval($_POST['report']) != $_POST['report']) {
  die();
}

if (!isset($_POST['for']) || strlen($_POST['for']) > 64) {
  die();
}

if (!isset($_POST['amount']) || intval($_POST['amount']) <= 0 || intval($_POST['amount']) != $_POST['amount']) {
  die();
}

$report = $db->query("SELECT * FROM reports r WHERE r.id = '" . mysql_escape_string($_POST['report']) . "'");
if (!is_array($report) || !count($report)) {
  die();
}

$db->query("INSERT INTO extra_payment (report, `for`, amount, status)
            VALUES(
              '" . mysql_escape_string($_POST['report']) . "',
              '" . mysql_escape_string($_POST['for']) . "',
              '" . mysql_escape_string($_POST['amount']) . "',
              'OK'
            )");

$payment = $db->query("SELECT * FROM extra_payment WHERE id = '" . mysql_escape_string(mysql_insert_id()) . "'");

echo json_encode(array('result' => 'OK', 'payment' => $payment[0]), JSON_PRETTY_PRINT);
