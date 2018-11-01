<?php

/*
 * ajax_payment_setaspaid.php
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

$db->query("UPDATE extra_payment
            SET
              status = 'Paid'
            WHERE id = '" . $db->escape($_POST['id']) . "'");

echo json_encode(array('result' => 'OK', 'status' => 'Paid'), JSON_PRETTY_PRINT);
