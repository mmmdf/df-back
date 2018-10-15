<?php

/*
 * image_delete.php
 *   - 
 *
 * Author  : Radu Dumitrescu (radu.dvr@gmail.com)
 * Date    : 2018.10.12
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

header('Content-Type: application/json');

if (!isset($_POST['id']) || intval($_POST['id']) == 0 || intval($_POST['id']) != $_POST['id']) {
  die();
}

$image = $db->query("SELECT * FROM image WHERE id = '" . mysql_escape_string($_POST['id']) . "'");
if (is_array($image) && count($image)) {
  unlink('data/images/image_reports_' . $image[0]['name'] .'.jpg');
  unlink('data/images/image_reports_' . $image[0]['name'] .'-original.jpg');

  $db->query("DELETE FROM image WHERE id = '" . mysql_escape_string($_POST['id']) . "'");
}

echo json_encode(array(), JSON_PRETTY_PRINT);
