<?php

/**
 * 
 * Ecbatana PHP Framework
 * <Still on development>
 * 
 * 
 * @author armenthiz <github.com/armenthiz>
 * 
 */


if (!ini_get('display_errors')) {
    ini_set('display_errors', '1');
}

if (file_exists('../src/autoload.php')) {
	require('../src/autoload.php');
}

$autoload = new Autoload();
$autoload->run();

if (file_exists('../app/config/config.php')) {
	$ecbtnConfig = require('../app/config/config.php');
}

$ecbatana = new Ecbatana\App;
$ecbatana->run($ecbtnConfig);