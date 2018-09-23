<?php

/*
 * Miscellaneous.inc.php
 *   - miscellaneous functions
 *
 * Author  : Andrei Gavrila (andrei.gavrila@gmail.com)
 * Date    : 2005.08.22
 * Licence : GPL 2.0
 *
 * Changes :
 *   - []
 */

function printr($mixedArgument)
{
  echo '<pre>';

  if (is_array($mixedArgument)) {
    print_r($mixedArgument);
  } else {
    echo $mixedArgument;
  }

  echo '</pre>';
}

function printrlog($mixedArgument)
{
  if (is_array($mixedArgument)) {
    $content = print_r($mixedArgument, true);
  } else {
    $content = $mixedArgument;
  }

  $f = fopen(PROJECT_TEMP . PROJECT_NAME . '.log', "a");
  fwrite($f, date("D M j G:i:s T Y") . "\n" . $content);
  fclose($f);
}
