<?php

class Router {
    public static $Routes = array();

    public static function dispatch($controller ,$method,$id){
        try{
            if (file_exists( $controller . '.php'))
            {
                $controller = strtolower($controller); 
                include ($controller . '.php');
                $controllerObj = new $controller();
                $controllerObj->$action($id);
            } 
        }catch(Exception $ex){
            echo "Controller Exception " . $ex->getMessage(); 
        }
    }
    public static function addRoute($httpMethod, $path, $controller, $action)
	{
		static::$Routes[] = array(
			'httpMethod'		=> $httpMethod,
			'path'			=> $path,
			'controller'		=> $controller,
			'action'		=> $action
		);
	}

	public static function doRoute($httpMethod, $path)
	{
        foreach(static::$Routes as  $route)
        {
            if (preg_match($route['path'], $path, $groups) && $httpMethod == $route['httpMethod'])
            {
                static::dispatch($route['controller'],$route['action'], isset($groups[1]) ? $groups[1] : null);
                break;
            }
        }
	}
}