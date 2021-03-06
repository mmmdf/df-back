<?php

/*
 * extra_payments.php
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

$html->tDisplay('extra_payments.tmpl', $session['language']);

function smarty_function_md5_rand($params, &$smarty)
{
  return md5(rand());
}