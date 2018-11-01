<?php

/*
 * ajax_stats.php
 *   - 
 *
 * Author  : Andrei Gavrila (andrei.gavrila@gmail.com)
 * Date    : 2018.09.29
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

$dateFrom = date('Y-m-d');
if (isset($_POST['date_from']) && date('Y-m-d', strtotime($_POST['date_from'])) ==  $_POST['date_from']) {
  $dateFrom = $_POST['date_from'];
}

$dateTo = date('Y-m-d');
if (isset($_POST['date_to']) && date('Y-m-d', strtotime($_POST['date_to'])) ==  $_POST['date_to']) {
  $dateTo = $_POST['date_to'];
}

$data = array();

$consolidators = $db->query("SELECT * FROM consolidator ORDER BY id ASC", "id");
foreach ($consolidators as $consolidator) {
  $data[$consolidator['id']] = array(
    'acronym' => $consolidator['acronym'],
    'data' => array()
  );
}

$date = $dateFrom;

$count = 0;

while ($date <= $dateTo && $count < 14) {
  $tmp1 = $db->query("SELECT 
                        r.consolidatorID, COUNT(r.id) AS _total
                        FROM reports r
                      " . $tmp0 . "AND DATE(r.leavingDate) = '" . $db->escape($date) . "'
                      GROUP BY r.consolidatorID");

  foreach ($data as $dataKey => $dataItem) {
    $data[$dataKey]['data'][date('jS M', strtotime($date))] = 0;
  }

  foreach ($tmp1 as $resultKey => $resultItem) {
    $data[$resultItem['consolidatorID']]['data'][date('jS M', strtotime($date))] = $resultItem['_total'];
  }

  $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));

  $count++;
}

echo json_encode($data, JSON_PRETTY_PRINT);
