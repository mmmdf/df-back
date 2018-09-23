<?php

/*
 * Config-1.inc.php
 *   - configuration file
 *
 * Author  : Andrei Gavrila (andrei.gavrila@gmail.com)
 * Date    : 2005.06.02
 * Licence : GPL 2.0
 *
 * Changes :
 *   - [2008.09.16] - Andrei Gavrila - Language support
 *   - [2011.02.13] - Andrei Gavrila - Smarty production use configuration
 *   - []
 */

/*
 * Error reporting
 */

error_reporting(E_ALL);

/*
 * Project variables
 *
 * Do not use . in the project name as it is used as a variable name for the session cookie (the . is not accepted as part of a cookie name). You can use _ instead.
 */

define('PROJECT_TEMP', '/tmp/php_');
define('PROJECT_NAME', 'drivefly');
define('PROJECT_URL', 'http://drivefly.creativefish.ro/');

define('PROJECT_THEME', 'design-01');

define('PROJECT_SESSION_TIMEOUT', 60*60);

putenv('FREETDSCONF=/usr/local/etc/freetds.conf');

define('PROJECT_LANGUAGE_DEFAULT', 'EN');
define('PROJECT_LANGUAGES', serialize(array('EN', 'RO')));

// Development
define('SMARTY_CACHING', false);
define('SMARTY_FORCE_COMPILE', true);

// Production
//define('SMARTY_CACHING', true);
//define('SMARTY_FORCE_COMPILE', false);


// Replace sitename.net and your email addresses with your site/configured email accounts.
define('REGISTRATION_USERNAME_REGEX', '/^[a-z\d_\.]{5,20}$/i');
define('REGISTRATION_EMAIL_REGEX', '/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_\-]+([.][a-zA-Z0-9_\-]+)*[.][a-zA-Z]{2,4}$/');

define('REGISTRATION_FROM', 'sitename.net administrator <admin@sitename.net>');
define('REGISTRATION_BCC', 'Administrator <admin@sitename.net>');
define('REGISTRATION_SUBJECT', 'Registration');

define('FORGOTPASSWORD_FROM', 'sitename.net administrator <admin@sitename.net>');
define('FORGOTPASSWORD_BCC', 'Administrator <admin@sitename.net>');
define('FORGOTPASSWORD_SUBJECT', 'Forgot Password');

define('RESET_FROM', 'sitename.net administrator <admin@sitename.net>');
define('RESET_BCC', 'Administrator <admin@sitename.net>');
define('RESET_SUBJECT', 'Reset Password');
