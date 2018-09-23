<?php

/*
 * Assign.inc.php
 *   - smarty variable assignment
 *
 * Author  : Andrei Gavrila (andrei.gavrila@gmail.com)
 * Date    : 2006.02.28
 * Licence : GPL 2.0
 *
 * Changes :
 *   - []
 */

/*
 * This file is included from a function inside the CTemplate class
 *   - The Smarty object is referenced by "this"
 *   - Global variables must be accessed using "global"
 */

global $session;
global $userData;

$this->assign('session', $session);
$this->assign('userData', $userData);
