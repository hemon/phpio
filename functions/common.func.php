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
    global $flow_uris;
    $html = '<ul class="tree">';
    foreach ( $tree as $name => $node ) {
        $fullpath = (!empty($parent) ? "$parent.$name" : $name);
        $class = ( (strpos($current, $name) !== false) ? 'label' : 'label label-empty');
        $class = ( ($fullpath === $current) ? 'label important' : $class);
        $html .= '<li><a href="?profile_id='.$fullpath.'" class="'.$class.'">'.$flow_uris[$fullpath].'</a>';
        if ( !empty($node) ) {
            $html .= phpio_ul($node, $current, $fullpath);
        }
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}

function phpio_argnames($function) {
    static $arg_name;
    $key = $function;
    if ( is_array($function) ) {
        list($class, $method) = $function;
        $key = "$class::$method";
    }

    if ( isset($arg_name[$key]) ) {
        return $arg_name[$key];
    }

    try {
        if ( isset($class) ) {
            if ( class_exists("PHPIO_Reflection_$class", false) ) {
                $class = "PHPIO_Reflection_$class";
            }
            $rf = new ReflectionMethod($class, $method);  
        } else {
            $rf = new ReflectionFunction($function);
        }

        $arg_name[$key] = array();
        foreach ($rf->getParameters() as $param) {
            $arg_name[$key][] = $param->getName();
        }
        return $arg_name[$key];
    } catch ( Exception $e ) {
        echo $e;
    }
}

function phpio_rmdir($dir) {
    if ( is_dir($dir) ) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") { 
                if (filetype($dir."/".$object) == "dir") {
                    phpio_rmdir($dir."/".$object);
                } else {
                    unlink($dir."/".$object); 
                }
            }
        }
        reset($objects);
        rmdir($dir);
    }
}

function phpio_unserialize_fail($classname) {
    eval("class $classname{}");
}
