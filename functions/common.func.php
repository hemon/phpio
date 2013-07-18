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
            $fullpath = $directory.'/'.$resource;
            if ( is_dir($fullpath) )
                array_merge($files, phpio_dir($fullpath, $files));
            else
                $files[] = $directory.'/'.$resource;
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

function phpio_tree($array) {
    $tree = array();
    foreach($array as $item) {
        $items = explode('.',$item);
        $var = '$tree["'.implode('"]["', $items).'"]';
        eval(' if ( !isset('.$var.') ) '.$var.' = array(); ');
    }
    return $tree;
}

function phpio_ul($tree, $current='', $parent='') {
    $html = '<ul class="tree">';
    foreach ( $tree as $name => $node ) {
        $fullpath = (!empty($parent) ? "$parent.$name" : $name);
        $class = ( (strpos($current, $name) !== false) ? 'label' : 'label label-empty');
        $class = ( ($fullpath === $current) ? 'label notice' : $class);
        $html .= '<li><a href="?profile_id='.$fullpath.'" class="'.$class.'">'.$name.'</a>';
        if ( !empty($node) ) {
            $html .= phpio_ul($node, $current, $fullpath);
        }
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}