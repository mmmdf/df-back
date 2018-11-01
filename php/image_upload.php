<?php

/*
 * image_upload.php
 *   - 
 *
 * Author  : Radu Dumitrescu (radu.dvr@gmail.com)
 * Date    : 2018.10.11
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

require_once('ImageResize.php');
require_once('ImageResizeException.php');

if ($_FILES) {
  if (!isset($_POST['report']) || intval($_POST['report']) == 0 || intval($_POST['report']) != $_POST['report']) {
    die();
  }

  if ($_FILES['file']['error'] == 0) {
    $image_name = md5(uniqid(rand(), true));

    $imageResizer = new \Gumlet\ImageResize($_FILES['file']['tmp_name']);
    $imageResizer->resizeToBestFit(100, 100);
    $imageResizer->save('data/images/image_reports_' . $image_name .'.jpg', IMAGETYPE_JPEG, 100);

    $imageResizer = new \Gumlet\ImageResize($_FILES['file']['tmp_name']);
    $imageResizer->save('data/images/image_reports_' . $image_name .'-original.jpg', IMAGETYPE_JPEG, 100);

    $db->query("INSERT INTO image (report, name, size) VALUES (
                  '" . $db->escape($_POST['report']) . "',
                  '" . $db->escape($image_name) . "',
                  '" . $db->escape(filesize('data/images/image_reports_' . $image_name . '.jpg')) . "'
                )");

    echo json_encode(array(), JSON_PRETTY_PRINT);
  }
} else {
  if (!isset($_GET['report']) || intval($_GET['report']) == 0 || intval($_GET['report']) != $_GET['report']) {
    die();
  }

  $images = $db->query("SELECT id, CONCAT('image_reports_', name, '.jpg') AS name, report AS reportId, size FROM image WHERE report = '" . $db->escape($_GET['report']) . "'");

  echo json_encode($images, JSON_PRETTY_PRINT);
}
