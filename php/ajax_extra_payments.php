<?php

/*
 * ajax_extra_payments.php
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

$tmp0 = "WHERE 1 ";
if (isset($_POST['service']) && intval($_POST['service']) > 0) {
  $tmp0 .= "AND r.typeID = '" . $db->escape(intval($_POST['service'])) . "' ";
}
if (isset($_POST['airport']) && intval($_POST['airport']) > 0) {
  $tmp0 .= "AND r.airportID = '" . $db->escape(intval($_POST['airport'])) . "' ";
}

$payments = $db->query("SELECT ep.*, r.refNum AS _refNum FROM extra_payment AS ep
                        LEFT JOIN reports AS r ON r.id = ep.report
                        " . $tmp0 . "
                        ORDER BY date DESC
                        LIMIT 0, 50");

echo json_encode($payments, JSON_PRETTY_PRINT);
