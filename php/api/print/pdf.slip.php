<?php

/*
 * print.slip.php
 *   - 
 *
 * Author  : Andrei Gavrila (andrei.gavrila@gmail.com)
 * Date    : 2018.10.31
 * Licence : GPL 2.0
 *
 * Changes :
 *   - []
 */

include('../../../_include/Include.inc.php');
include('../../../_include/fpdf181/fpdf.php');

/*
 *
 */

header('Content-Type: application/pdf');

$tmp0 = "WHERE 1 ";
if (isset($_POST['service']) && intval($_POST['service']) > 0) {
  $tmp0 .= "AND r.typeID = '" . mysql_escape_string(intval($_POST['service'])) . "' ";
}
if (isset($_POST['airport']) && intval($_POST['airport']) > 0) {
  $tmp0 .= "AND r.airportID = '" . mysql_escape_string(intval($_POST['airport'])) . "' ";
}
if (isset($_POST['consolidator']) && intval($_POST['consolidator']) > 0) {
  $tmp0 .= "AND r.consolidatorID = '" . mysql_escape_string(intval($_POST['consolidator'])) . "' ";
}
if (isset($_POST['date']) && date('Y-m-d', strtotime($_POST['date'])) ==  $_POST['date']) {
  $tmp0 .= "AND (r.leavingDate LIKE '" . mysql_escape_string($_POST['date']) . "%' OR r.returnDate LIKE '" . mysql_escape_string($_POST['date']) . "%')";
}
if (isset($_POST['terminal']) && intval($_POST['terminal']) > 0) {
  $tmp0 .= "AND (r.terminal_in IS NULL OR r.terminal_out IS NULL)";
}

$reports = $db->query("SELECT r.*, s.name AS _type_name, s.acronym AS _type_acronym, a.name AS _airport_name, a.acronym AS _airport_acronym
                       FROM reports r
					   INNER JOIN service s ON s.id = r.typeID
					   INNER JOIN airport a ON a.id = r.airportID
					   " . $tmp0 . "
					   ORDER BY r.created DESC LIMIT 0, 50", "id");
foreach ($reports as $reportIndex => $reportItem) {
  $reports[$reportIndex]['_created_relative'] = relativeTime(strtotime($reportItem['created']));
  $reports[$reportIndex]['_created_formatted'] = date('j M h:i', strtotime($reportItem['created']));

  $reports[$reportIndex]['_name'] = $reportItem['firstname'] . ' ' . $reportItem['surname'];



  $reports[$reportIndex]['_leavingDate_formatted'] = date('j M', strtotime($reportItem['leavingDate']));
  $reports[$reportIndex]['_leavingDate_formatted_DMY'] = date('d-m-Y', strtotime($reportItem['leavingDate']));
  $reports[$reportIndex]['_leavingDate_additional'] = date('h:i', strtotime($reportItem['leavingDate']));

  $reports[$reportIndex]['_returnDate_formatted'] = date('d/m', strtotime($reportItem['returnDate']));
  $reports[$reportIndex]['_returnDate_formatted_DMY'] = date('d-m-Y', strtotime($reportItem['returnDate']));
  
  $reports[$reportIndex]['_returnDate_additional'] = date('h:i', strtotime($reportItem['returnDate']));
}

//echo "<pre>";
//var_dump($reports);

$fpdf = new FPDF();

foreach ($reports as $report) {
  $fpdf->AddPage('L', 'A4');

  /* LEFT SIDE */
  $fpdf->SetFont('Arial', '', 14);
  $fpdf->SetY(40);
  $fpdf->SetX(10);
  $fpdf->MultiCell(100, 10, $report['_name'] . "\n" . $report['mobile'] . "\n" . $report['refNum']);

  $fpdf->SetFont('Arial', '', 48);
  $fpdf->SetY(20);
  $fpdf->SetX(180);
  $fpdf->MultiCell(100, 48, $report['_returnDate_formatted']);

  $fpdf->SetFont('Arial', '', 14);
  $fpdf->SetY(80);
  $fpdf->SetX(10);
  $fpdf->MultiCell(100, 12, $report['carReg']);

  $fpdf->SetFont('Arial', '', 12);
  $fpdf->SetY(92);
  $fpdf->SetX(10);
  $fpdf->MultiCell(100, 12, $report['carModel'] . " " . $report['carColour']);

  $fpdf->SetFont('Arial', '', 12);
  $fpdf->SetY(125);
  $fpdf->SetX(10);
  $fpdf->MultiCell(100, 12, $report['_leavingDate_formatted_DMY']);

  $fpdf->SetFont('Arial', '', 12);
  $fpdf->SetY(125);
  $fpdf->SetX(45);
  $fpdf->MultiCell(100, 12, $report['_leavingDate_additional']);

  $fpdf->SetFont('Arial', '', 12);
  $fpdf->SetY(125);
  $fpdf->SetX(75);
  $fpdf->MultiCell(100, 12, $report['ppl']);

  $fpdf->SetFont('Arial', '', 12);
  $fpdf->SetY(135);
  $fpdf->SetX(10);
  $fpdf->MultiCell(100, 12, $report['_returnDate_formatted_DMY']);

  $fpdf->SetFont('Arial', '', 12);
  $fpdf->SetY(135);
  $fpdf->SetX(45);
  $fpdf->MultiCell(100, 12, $report['_returnDate_additional']);

  $fpdf->SetFont('Arial', '', 12);
  $fpdf->SetY(135);
  $fpdf->SetX(75);
  $fpdf->MultiCell(100, 12, $report['ppl']);

  /* CENTER */
  $fpdf->SetFont('Arial', '', 48);
  $fpdf->SetY(70);
  $fpdf->SetX(130);
  $fpdf->MultiCell(100, 48, $report['_returnDate_additional']);

  $fpdf->SetFont('Arial', '', 48);
  $fpdf->SetY(115);
  $fpdf->SetX(145);
  $fpdf->MultiCell(100, 48, $report['ppl']);
  
  /* RIGHT SIDE */

  $fpdf->SetFont('Arial', '', 16);
  $fpdf->SetY(100);
  $fpdf->SetX(210);
  $fpdf->MultiCell(80, 16, $report['refNum']);

  $fpdf->SetFont('Arial', '', 16);
  $fpdf->SetY(100);
  $fpdf->SetX(245);
  $fpdf->MultiCell(80, 16, $report['carReg']);

  $fpdf->SetFont('Arial', '', 12);
  $fpdf->SetY(125);
  $fpdf->SetX(210);
  $fpdf->MultiCell(100, 12, $report['carModel'] . "  " . $report['carColour']);

  $fpdf->SetFont('Arial', '', 12);
  $fpdf->SetY(135);
  $fpdf->SetX(210);
  $fpdf->MultiCell(100, 12, $report['_returnDate_formatted_DMY']);

  $fpdf->SetFont('Arial', '', 12);
  $fpdf->SetY(135);
  $fpdf->SetX(245);
  $fpdf->MultiCell(100, 12, $report['_returnDate_additional']);

  $fpdf->SetFont('Arial', '', 11);
  $fpdf->SetY(135);
  $fpdf->SetX(275);
  $fpdf->MultiCell(100, 12, $report['ppl']);

  $fpdf->SetFont('Arial', '', 12);
  $fpdf->SetY(145);
  $fpdf->SetX(250);
  $fpdf->MultiCell(100, 12, $report['_type_name']);
  
  /* BOTTOM */
  
  $fpdf->SetFont('Arial', '', 12);
  $fpdf->SetY(160);
  $fpdf->SetX(0);
  $fpdf->MultiCell($fpdf->GetPageWidth(), 14, $report['refNum'] . "   " . $report['carReg'], 0, 'C');

  $fpdf->SetFont('Arial', '', 12);
  $fpdf->SetY(170);
  $fpdf->SetX(0);
  $fpdf->MultiCell($fpdf->GetPageWidth(), 14, $report['carModel'] . "  " . $report['carColour'], 0, 'C');
}

$fpdf->Output();

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
