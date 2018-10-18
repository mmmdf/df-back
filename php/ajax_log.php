<?php

/*
 * ajax_log.php
 *   - 
 *
 * Author  : Andrei Gavrila (andrei.gavrila@gmail.com)
 * Date    : 2018.10.15
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

$log = $db->query("SELECT at.*, r.refNum AS _refNum FROM audit_trail AS at
                   LEFT JOIN reports AS r ON r.id = at.report
                   ORDER BY `date` DESC LIMIT 0, 50");
if (is_array($log) && count($log)) {
  foreach ($log as $a => $b) {
    $log[$a]['_date_formatted'] = date('j M h:i', strtotime($log[$a]['date']));
    $log[$a]['_record'] = json_decode($log[$a]['record']);
  }
}

echo json_encode($log, JSON_PRETTY_PRINT);
