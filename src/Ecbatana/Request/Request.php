<?php
namespace Ecbatana\Request;

class Request
{
    /**
     * Variable to contain superglobal Server Request URI
     * 
     * @var string
     */
    static public $request;

    /**
     * Method to set the request
     * 
     * @return TODO
     */
    static private function setRequest()
    {
        self::$request = $_SERVER['REQUEST_URI'];
    }

    /**
     * Method to set if front directory separator is used or not
     * 
     * @return TODO
     */
    static private function setSeparator()
    {
        if (strlen(self::$request) > 1) {
            self::$request = substr_replace(self::$request, '', 0, 1);
        }
    }

    /**
     * Method to sanitize variable request
     * 
     * @return TODO
     */
    static private function sanitize()
    {
        self::$request = trim(strip_tags(self::$request));
    }

    /**
     * Method to run the initiator
     * 
     * @return TODO
     */
    static public function run()
    {
        self::setRequest();
        self::setSeparator();
        self::sanitize();
    }
}