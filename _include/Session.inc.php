<?php

/*
 * Session.inc.php
 *   - session handling
 *
 * Author  : Andrei Gavrila (andrei.gavrila@gmail.com)
 * Date    : 2005.08.22
 * Licence : GPL 2.0
 *
 * Changes :
 *   - [2008.08.02] - Andrei Gavrila - Language support
 *   - [2008.09.16] - Andrei Gavrila - Changed language support to retrieve languages from the config file
 *   - [2011.02.13] - Andrei Gavrila - Added user language support
 *   - [2018.09.20] - Andrei Gavrila - Fixed notice on first cookie access
 *   - []
 */

/*
 * Clean up expired sessions
 */

$szQuery = "DELETE FROM " . DBPREFIX . "admin_sessions
            WHERE UNIX_TIMESTAMP(lastdate) + " . PROJECT_SESSION_TIMEOUT . " < UNIX_TIMESTAMP(NOW())";
$db->query($szQuery);

/*
 * Handle the cookie
 */

$session = isset($_COOKIE[PROJECT_NAME . '_session']) ? $_COOKIE[PROJECT_NAME . '_session'] : '';

if (empty($session)) {
  /*
   * First access, no session id
   */

  $session = md5(uniqid(rand(), true));

  $szQuery = "INSERT INTO " . DBPREFIX . "admin_sessions
              (session, user, startdate, lastdate)
              VALUES (
                       '" . mysql_escape_string($session) . "',
                       '0',
                       NOW(),
                       NOW()
                     )";
  $db->query($szQuery);
}
else {
  /*
   * Got a session
   */

  $szQuery = "SELECT *
              FROM " . DBPREFIX . "admin_sessions
              WHERE session = '" . mysql_escape_string($session) . "'";
  $tmp0 = $db->query($szQuery);

  if (!count($tmp0)) {
    /*
     * Session expired or bad session
     */

    $session = md5(uniqid(rand(), true));

    $szQuery = "INSERT INTO " . DBPREFIX . "admin_sessions
                (session, user, startdate, lastdate)
                VALUES (
                         '" . mysql_escape_string($session) . "',
                         '0',
                         NOW(),
                         NOW()
                       )";
    $db->query($szQuery);
  }
}

/*
 * Update the session
 */

$szQuery = "UPDATE " . DBPREFIX . "admin_sessions
            SET lastdate = NOW()
            WHERE session = '" . mysql_escape_string($session) . "'";
$db->query($szQuery);

/*
 * Reset the cookie
 *   (time based or until the user will close the browser)
 */

//setcookie(PROJECT_NAME . '_session', $session, time() + PROJECT_SESSION_TIMEOUT, '/');
setcookie(PROJECT_NAME . '_session', $session, 0, '/');

/*
 * Load the session (if not already loaded)
 */

$szQuery = "SELECT *
            FROM " . DBPREFIX . "admin_sessions
            WHERE session = '" . mysql_escape_string($session) . "'";
$tmp0 = $db->query($szQuery);

$session = $tmp0[0];

/*
 * Load user data if the user is logged in
 */

if ($session['user']) {
  $szQuery = "SELECT *
              FROM " . DBPREFIX . "admin_users
              WHERE id = '" . mysql_escape_string($session['user']) . "'";
  $tmp0 = $db->query($szQuery);

  $userData = $tmp0[0];
}

/*
 * Loggoff other sessions for this user
 */

/*
if ($userData['id']) {
  $szQuery = "DELETE FROM " . DBPREFIX . "admin_sessions
              WHERE user = '" . $userData['id'] . "' AND session != '" . $session['session'] . "'";
  $db->query($szQuery);
}
*/

/*
 * Language support
 */

if (isset($userData)) {
  if ($session['language'] != $userData['language']) {
    $szQuery = "UPDATE " . DBPREFIX . "admin_sessions
                SET language = '" . mysql_escape_string($userData['language']) . "'
                WHERE session = '" . mysql_escape_string($session['session']) . "'";
    $db->query($szQuery);

    $session['language'] = $userData['language'];
  }
}

if (!empty($_GET['project_language'])) {
  if (!in_array($_GET['project_language'], unserialize(PROJECT_LANGUAGES))) {
    $_GET['project_language'] = PROJECT_LANGUAGE_DEFAULT;
  }

  $szQuery = "UPDATE " . DBPREFIX . "admin_sessions
              SET language = '" . mysql_escape_string($_GET['project_language']) . "'
              WHERE session = '" . mysql_escape_string($session['session']) . "'";
  $db->query($szQuery);

  $session['language'] = $_GET['project_language'];

  if (isset($userData)) {
    $szQuery = "UPDATE " . DBPREFIX . "admin_users
                SET language = '" . mysql_escape_string($_GET['project_language']) . "'
                WHERE id = '" . mysql_escape_string($userData['id']) . "'";
    $db->query($szQuery);

    $userData['language'] = $_GET['project_language'];
  }
}
