<?php

class Application
{

    public static function Init()
    {
        if (substr_count($_SERVER["REQUEST_URI"], '/') == 3) {
            $values = explode('/', $_SERVER["REQUEST_URI"], 4);
            if ($values) {
                $className =  ucfirst($values[2]);
                $classPath = "endpoint/" . $className . ".php";
                if (file_exists($classPath)) {

                    include_once("endpoint/" . $className . ".php");
                    if (class_exists($className)) {
                        $class = new $className;
                        $method = $values[3];
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
