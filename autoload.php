<?php

function getClassPath($namespace)
{
    $class_path = explode('\\', $namespace);
    
    if($class_path[0] === "App") {
        array_shift($class_path);
        return mb_strtolower(implode(DIRECTORY_SEPARATOR, $class_path) . ".php");
    }
    
    return $namespace . ".php";
}

spl_autoload_register(function($class) 
{
    $class_path = getClassPath($class);
    if (file_exists($class_path)) {
        require_once(realpath($class_path));
    }
});