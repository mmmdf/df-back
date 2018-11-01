<?php

/*
 * ajax_monthly.php
 *   - 
 *
 * Author  : Andrei Gavrila (andrei.gavrila@gmail.com)
 * Date    : 2018.09.30
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

$date = date('Y-m');
if (isset($_POST['date_year']) && isset($_POST['date_month']) && date('Y-m', strtotime($_POST['date_year'] . '-' . $_POST['date_month'] . '-01')) ==  $_POST['date_year'] . '-' . $_POST['date_month']) {
  $date = $_POST['date_year'] . '-' . $_POST['date_month'];
}

$tmp1 = $db->query("SELECT 
                      SUM(IF(SUBSTRING(r.leavingDate, 1, 7) = '" . $db->escape($date) . "', 1, 0)) AS _out,
                      SUM(IF(SUBSTRING(r.returnDate, 1, 7) = '" . $db->escape($date) . "', 1, 0)) AS _in
                      FROM reports r
                    " . $tmp0 . "AND (SUBSTRING(r.leavingDate, 1, 7) = '" . $db->escape($date) . "' OR SUBSTRING(r.returnDate, 1, 7) = '" . $db->escape($date) . "')");

if (!$tmp1[0]['_out']) {
  $tmp1[0]['_out'] = "0";
}
if (!$tmp1[0]['_in']) {
  $tmp1[0]['_in'] = "0";
}

$data = array(
  'total' => array(
    'out' => $tmp1[0]['_out'],
    'in' => $tmp1[0]['_in']
  ),
  'reports' => array(),
  'totals' => array(
    'bookings' => 0,
    'per_booking' => 0,
    'estimate_net' => 0
  )
);

$tmp1 = $db->query("SELECT 
                      r.consolidatorID,
                      c.name AS _agent,
                      CONCAT(a.acronym, ' ', r.product) AS _product,
                      COUNT(r.id) AS _bookings,
                      TRUNCATE(SUM(r.net)/COUNT(r.id), 2) AS _per_booking,
                      'N/A' AS _accuracy,
                      TRUNCATE(SUM(r.net), 2) AS _estimate_net
                      FROM reports r
                    LEFT JOIN airport AS a ON a.id = r.airportID
                    LEFT JOIN consolidator AS c ON c.id = r.consolidatorID
                    " . $tmp0 . "AND SUBSTRING(r.leavingDate, 1, 7) = '" . $db->escape($date) . "'
                    GROUP BY r.consolidatorID, _product");

foreach ($tmp1 as $report) {
  $data['totals']['bookings'] += intval($report['_bookings']);
  $data['totals']['per_booking'] = (floatval($data['totals']['estimate_net']) + floatval($report['_estimate_net']))/intval($data['totals']['bookings']);
  $data['totals']['estimate_net'] = floatval($data['totals']['estimate_net']) + floatval($report['_estimate_net']);

  if (!isset($data['reports'][$report['consolidatorID']])) {
    $data['reports'][$report['consolidatorID']] = array(
      'head' => array(
        '_agent' => $report['_agent'],
        '_product' => '-',
        '_bookings' => intval($report['_bookings']),
        '_per_booking' => floatval($report['_per_booking']),
        '_accuracy' => 'N/A',
        '_estimate_net' => floatval($report['_estimate_net'])
      ),
      'rows' => array(
        $report
      )
    );
  } else {
    $data['reports'][$report['consolidatorID']]['head']['_bookings'] += intval($report['_bookings']);
    $data['reports'][$report['consolidatorID']]['head']['_per_booking'] = (floatval($data['reports'][$report['consolidatorID']]['head']['_estimate_net']) + floatval($report['_estimate_net']))/intval($data['reports'][$report['consolidatorID']]['head']['_bookings']);
    $data['reports'][$report['consolidatorID']]['head']['_accuracy'] = 'N/A';
    $data['reports'][$report['consolidatorID']]['head']['_estimate_net'] = floatval($data['reports'][$report['consolidatorID']]['head']['_estimate_net']) + floatval($report['_estimate_net']);

    $data['reports'][$report['consolidatorID']]['rows'][] = $report;
  }
}

echo json_encode($data, JSON_PRETTY_PRINT);
