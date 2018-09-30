<?php

/*
 * monthly.php
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

header('Content-Type: text/html');

$html = new CTemplate();
$html->registerPlugin('function', 'md5_rand', 'smarty_function_md5_rand', false, null);

$services = $db->query("SELECT * FROM service", "id");
$html->assign('services', $services);

$airports = $db->query("SELECT * FROM airport", "id");
$html->assign('airports', $airports);

$consolidators = $db->query("SELECT * FROM consolidator", "id");
$html->assign('consolidators', $consolidators);

$years = array(
  array('value' => date('Y', strtotime('-2 years')), 'label' => date('Y', strtotime('-2 years')), 'selected' => false),
  array('value' => date('Y', strtotime('-1 year')), 'label' => date('Y', strtotime('-1 year')), 'selected' => false),
  array('value' => date('Y'), 'label' => date('Y'), 'selected' => true)
);
$html->assign('years', $years);

$months = array();
$date = strtotime(date('Y') . '-01-01');
for ($i = 0; $i < 12; $i++) {
  $months[] = array('value' => date('m', strtotime('+' . $i . ' months', $date)), 'label' => date('M', strtotime('+' . $i . ' months', $date)), 'selected' => date('Y-m-d', strtotime('+' . $i . ' months', $date)) == date('Y-m-') . '01');
}
$html->assign('months', $months);

$html->tDisplay('monthly.tmpl', $session['language']);

function smarty_function_md5_rand($params, &$smarty)
{
  return md5(rand());
}
