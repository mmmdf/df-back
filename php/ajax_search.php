<?php

/*
 * ajax_search.php
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

$reports = $db->query("SELECT r.*, s.name AS _type_name, s.acronym AS _type_acronym, a.name AS _airport_name, a.acronym AS _airport_acronym, c.name AS _consolidator_name
                       FROM reports r
					   INNER JOIN service s ON s.id = r.typeID
					   INNER JOIN airport a ON a.id = r.airportID
					   INNER JOIN consolidator c ON c.id = r.consolidatorID
					   WHERE r.id LIKE '%" . $db->escape($_POST['search']). "%' OR 
					       r.refNum LIKE '%" . $db->escape($_POST['search']). "%' OR
					       r.firstname LIKE '%" . $db->escape($_POST['search']). "%' OR
					       r.surname LIKE '%" . $db->escape($_POST['search']). "%' OR
					       r.mobile LIKE '%" . $db->escape($_POST['search']). "%' OR
					       r.carReg LIKE '%" . $db->escape($_POST['search']). "%'
					   ORDER BY r.created DESC LIMIT 0, 50", "id");
foreach ($reports as $reportIndex => $reportItem) {
  $reports[$reportIndex]['_created_relative'] = relativeTime(strtotime($reportItem['created']));
  $reports[$reportIndex]['_created_formatted'] = date('j M h:i', strtotime($reportItem['created']));
  $reports[$reportIndex]['_name'] = $reportItem['firstname'] . ' ' . $reportItem['surname'];
  $reports[$reportIndex]['_leavingDate_formatted'] = date('j M Y', strtotime($reportItem['leavingDate']));
  $reports[$reportIndex]['_leavingDate_additional'] = date('h:i', strtotime($reportItem['leavingDate']));
  $reports[$reportIndex]['_returnDate_formatted'] = date('j M Y', strtotime($reportItem['returnDate']));
  $reports[$reportIndex]['_returnDate_additional'] = date('h:i', strtotime($reportItem['returnDate']));
}

echo json_encode($reports, JSON_PRETTY_PRINT);

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
