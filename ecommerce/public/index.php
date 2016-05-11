<?php

error_reporting(E_ALL);
ini_set('display_errors', false);
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));
//define('BASE_PROJECT', '/var/www/sis/trunk');
define('BASE_URL', '');
//define('HTTP_URL', 'http://crs.unochapeco.edu.br');
define('ItensPerPage', 10);
// Setup autoloading
include 'init_autoloader.php';


// Run the application!
Zend\Mvc\Application::init(include 'config/application.config.php')->run();
