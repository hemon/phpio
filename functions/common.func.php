<?php

function phpio_load() {
    $files = phpio_dir(PHPIO_LIB);
    sort($files);
	foreach ($files as $classfile) {
		require $classfile;
	}
}

function phpio_dir($directory, &$files = array()) {
    $handle = opendir($directory);
    while ( false !== ($resource = readdir($handle)) ) {
        if ( !in_array($resource, array('.','..')) ) {
            $fullpath = $directory.$resource.'/';
            if ( is_dir($fullpath) )
                array_merge($files, phpio_dir($fullpath, $files));
            else
                $files[] = $directory.$resource;
        }
    }
    closedir($handle); 
    return $files;
}

function phpio_class_set_properties($class, $properties) {
	if ( is_object($class) ) {
		foreach ( $properties as $property => $value ) {
			$class->$property = $value;
		}
	}
}
