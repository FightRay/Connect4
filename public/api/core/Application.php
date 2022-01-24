<?php

class Application
{

    public static function Init()
    {
        if (substr_count($_SERVER["REQUEST_URI"], '/') == 2) {
            $values = explode('/', $_SERVER["REQUEST_URI"], 3);
            if ($values) {
                $className =  ucfirst($values[1]);
                $classPath = "endpoint/" . $className . ".php";
                if (file_exists($classPath)) {

                    include_once("endpoint/" . $className . ".php");
                    if (class_exists($className)) {
                        $class = new $className;
                        $method = $values[2];
                        if (method_exists($class, $method)) {
                            $class::$method();
                            return;
                        }
                    }
                }
            }
        }

        echo "Request invalid!";
        die();
    }
}
