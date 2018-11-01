<?php

/*
 * ajax_levels.php
 *   - 
 *
 * Author  : Andrei Gavrila (andrei.gavrila@gmail.com)
 * Date    : 2018.09.28
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
if (isset($_POST['consolidator']) && intval($_POST['consolidator']) > 0) {
  $tmp0 .= "AND r.consolidatorID = '" . $db->escape(intval($_POST['consolidator'])) . "' ";
}

$selectedDate = date('Y-m-d');
if (isset($_POST['date']) && date('Y-m-d', strtotime($_POST['date'])) ==  $_POST['date']) {
  $selectedDate = $_POST['date'];
}

$start = -999;
$data = array();

for ($i = 29; $i >= 0; $i--) {
  $date = date('Y-m-d', strtotime('-' . $i . ' days', strtotime($selectedDate)));

  if ($start == -999) {
    $tmp1 = $db->query("SELECT 
                          COUNT(r.id) AS _start
                          FROM reports r
                        " . $tmp0 . "AND '" . $db->escape($date . ' 00:00:00') . "' BETWEEN r.leavingDate AND r.returnDate");
    $start = $tmp1[0]['_start'];
  }

  $tmp1 = $db->query("SELECT 
                        '" . $db->escape($date) . "' AS _date,
                        SUM(IF(DATE(r.leavingDate) = '" . $db->escape($date) . "', 1, 0)) AS _out,
                        SUM(IF(DATE(r.returnDate) = '" . $db->escape($date) . "', 1, 0)) AS _in
                        FROM reports r
                      " . $tmp0 . "AND '" . $db->escape($date) . "' BETWEEN DATE(r.leavingDate) AND DATE(r.returnDate)");
  $tmp1[0]['_date_formatted'] = date('jS M', strtotime($date));
  $tmp1[0]['_start'] = $start;

  if (!$tmp1[0]['_out']) {
    $tmp1[0]['_out'] = "0";
  }
  if (!$tmp1[0]['_in']) {
    $tmp1[0]['_in'] = "0";
  }

  $data[] = $tmp1[0];

  $start = $start - $tmp1[0]['_out'] + $tmp1[0]['_in'];
}

echo json_encode($data, JSON_PRETTY_PRINT);
