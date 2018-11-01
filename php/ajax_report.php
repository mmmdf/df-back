<?php

/*
 * ajax_report.php
 *   - 
 *
 * Author  : Andrei Gavrila (andrei.gavrila@gmail.com)
 * Date    : 2018.10.03
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

$report = $db->query("SELECT r.*, s.name AS _type_name, s.acronym AS _type_acronym, a.name AS _airport_name, a.acronym AS _airport_acronym, c.name AS _consolidator_name
                      FROM reports r
                      INNER JOIN service s ON s.id = r.typeID
                      INNER JOIN airport a ON a.id = r.airportID
                      INNER JOIN consolidator c ON c.id = r.consolidatorID
                      WHERE r.id = '" . $db->escape($_POST['report']) . "'");
if (!is_array($report) || !count($report)) {
  die();
}

$report[0]['_created_relative'] = relativeTime(strtotime($report[0]['created']));
$report[0]['_created_formatted'] = date('j M h:i', strtotime($report[0]['created']));
$report[0]['_lastUpdate_formatted'] = date('j M h:i', strtotime($report[0]['lastUpdate']));
$report[0]['_name'] = $report[0]['firstname'] . ' ' . $report[0]['surname'];
$report[0]['_leavingDate_formatted'] = date('d/m/Y', strtotime($report[0]['leavingDate']));
$report[0]['_leavingDate_additional'] = date('h:i A', strtotime($report[0]['leavingDate']));
$report[0]['_returnDate_formatted'] = date('d/m/Y', strtotime($report[0]['returnDate']));
$report[0]['_returnDate_additional'] = date('h:i A', strtotime($report[0]['returnDate']));

$report[0]['_auditTrail'] = $db->query("SELECT * FROM audit_trail WHERE report = '" . $db->escape($_POST['report']) . "' ORDER BY `date` DESC");
if (is_array($report[0]['_auditTrail']) && count($report[0]['_auditTrail'])) {
  foreach ($report[0]['_auditTrail'] as $a => $b) {
    $report[0]['_auditTrail'][$a]['_record'] = json_decode($report[0]['_auditTrail'][$a]['record']);
  }
}

$report[0]['_payment'] = $db->query("SELECT * FROM extra_payment WHERE report = '" . $db->escape($_POST['report']) . "' ORDER BY `date` DESC");

echo json_encode($report[0], JSON_PRETTY_PRINT);

/**
 * Return a relative representation of $time
 *
 * Adapted from https://stackoverflow.com/a/7487809
 */
function relativeTime($time)
{
  $intervals = array(
//    array(1,'a second', 'seconds', true),
    array(60,'a minute', 'minutes', true),
    array(3600,'an hour', 'hours', true),
    array(86400,'a day', 'days', true),
    array(604800,'a week', 'weeks', true),
    array(2592000,'a month', 'months', true),
    array(31104000,'a year', 'years', true)
  );

  $relativeTime = '';

  $secondsLeft = time() - $time;
  for ($i = count($intervals) - 1; $i > -1; $i--) {
    $tmp0 = intval($secondsLeft/$intervals[$i][0]);

    if ($tmp0 != 0) {
      $relativeTime .= (abs($tmp0) > 1 ? (abs($tmp0) . ' ' . $intervals[$i][2]) : $intervals[$i][1]) . ' ';

      if ($intervals[$i][3]) {
        break;
      }
    }

    $secondsLeft -= $tmp0*$intervals[$i][0];
  }

  if ($relativeTime == '') {
    return 'just now';
  }

  return $relativeTime . (time() - $time > 0 ? 'ago' : 'left');
}
