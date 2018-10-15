<?php

/*
 * daily_without_terminal.php
 *   - 
 *
 * Author  : Andrei Gavrila (andrei.gavrila@gmail.com)
 * Date    : 2009.03.01
 * Licence : GPL 2.0
 *
 * Changes :
 *   - [2011.02.13] - Andrei Gavrila - Upgrade to Smarty 3
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

$html->tDisplay('daily_without_terminal.tmpl', $session['language']);

function smarty_function_md5_rand($params, &$smarty)
{
  return md5(rand());
}