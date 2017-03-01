<?php
namespace Pug;
$srcPath = dirname(__FILE__) . '/pug/src/';
spl_autoload_register(function($class) use($srcPath) {
	if (strstr($class, 'Pug') /* new name */ || strstr($class, 'Jade') /* old name */ ) {
		$c = str_replace("\\", DIRECTORY_SEPARATOR, $class);
	  include $srcPath . $c . '.php';
  }
});