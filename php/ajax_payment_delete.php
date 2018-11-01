<?php

/*
 * ajax_payment_delete.php
 *   - 
 *
 * Author  : Andrei Gavrila (andrei.gavrila@gmail.com)
 * Date    : 2018.10.14
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

$db->query("DELETE FROM extra_payment
            WHERE id = '" . $db->escape($_POST['id']) . "'");

echo json_encode(array('result' => 'OK'), JSON_PRETTY_PRINT);
