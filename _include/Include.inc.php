<?php

/*
 * Include.inc.php
 *   - default included file
 *
 * Author  : Andrei Gavrila (andrei.gavrila@gmail.com)
 * Date    : 2005.06.02
 * Licence : GPL 2.0
 *
 * Changes :
 *   - [2005.10.06] - Andrei Gavrila - Added Template class
 *   - [2005.10.06] - Andrei Gavrila - Added Miscellaneous functions file
 *   - []
 */

define('PROJECT_PATH', substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), '_include')));

include(PROJECT_PATH . '_include/Config-1.inc.php');

include(PROJECT_PATH . '_include/Miscellaneous.inc.php');

include(PROJECT_PATH . '_include/mySQL/mySQL.class.php');

include(PROJECT_PATH . '_include/Smarty/Smarty.class.php');
include(PROJECT_PATH . '_include/Template/Template.class.php');

include(PROJECT_PATH . '_include/Config-2.inc.php');

include(PROJECT_PATH . '_include/Session.inc.php');

include(PROJECT_PATH . '_include/Project.inc.php');
