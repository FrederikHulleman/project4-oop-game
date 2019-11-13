<?php

//make sure the right class php file is required when an object is initialized 
function autoloader($class_name) {
  foreach(glob(__DIR__.'/*',GLOB_ONLYDIR) as $dir) {
    if(file_exists("$dir/" . $class_name . '.php')) {
      require_once "$dir/" . $class_name . '.php';
      break;
    }
  }
}

spl_autoload_register('autoloader');

?>
