<?php

class Sanitizer
{
    private function __construct() {}

    /*
     * Sanitize a given URL
     */
    public static function sanitizeURL($url)
    {
        return htmlspecialchars(strip_tags($url));
    }

}
class Request
{
    // contains the URL of the request
    private static $url = null;

    // contains the request type of the request : GET | POST
    private static $type = null;

    // contains the segments of the request
    private static $segments = null;

    // contains the name of the controller of the request
    private static $controller = null;
    // contains the name of the method for the controller
    private static $method = null;
    // contains additional parameters
    private static $data = null;

    /*
     * Prevent this class from being called 'non-statically'
     */
    private function __construct() {}

    /*
     * Returns the Request object, so it can be used as a dependency
     */
    public static function getRequest()
    {
        return new self;
    }

    /*
     * Stores all Request info into the class
     */
    public static function init()
    {
        self::$url = Sanitizer::sanitizeURL(rtrim(substr($_SERVER["REQUEST_URI"], 1), '/'));
        self::$type = $_SERVER['REQUEST_METHOD'];
        self::$data = (object) array();

        self::parseURL();

        $postParameters = filter_input_array(INPUT_POST);
        $cookieParameters = filter_input_array(INPUT_COOKIE);

        if(!is_null($postParameters))
        {
            foreach($postParameters as $parameter => $value)
            {
                if(!isset(self::$data->postParameters))
                {
                    self::$data->postParameters = (object) array();
                }

                self::$data->postParameters->{$parameter} = $value;
            }
        }

        if(!is_null($cookieParameters))
        {
            foreach($cookieParameters as $parameter => $value)
            {
                if(!isset(self::$data->cookieParameters))
                {
                    self::$data->cookieParameters = (object) array();
                }

                self::$data->cookieParameters->{$parameter} = $value;
            }
        }
    }

    /*
     * Grabs all the info from the requested URL
     */
    private static function parseURL()
    {
        $url = self::$url;
        self::$segments = explode('/', $url);
        self::$data->getParameters = array_values(array_diff(array_slice(self::$segments, 2), array('')));
        self::$data->controller = isset(self::$segments[0]) && !empty(self::$segments[0]) ? self::$segments[0] : 'index';
        self::$data->method = isset(self::$segments[1]) && !empty(self::$segments[1]) ? self::$segments[1] : 'index';
    }

    /*
     * Returns the requested URL
     */
    public static function getURL()
    {
        return self::$url;
    }

    /*
     * Returns the request type
     */
    public static function getType()
    {
        return self::$type;
    }

    /*
     * Returns the request data
     */
    public static function getData()
    {
        return self::$data;
    }

    /*
    * Returns the name of the controller
    */
    public static function getController()
    {
        return self::$data->controller;
    }

    /*
    * Returns the name of the method for the controller
    */
    public static function getMethod()
    {
        return self::$data->method;
    }
}