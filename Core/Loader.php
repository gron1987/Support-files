<?php
/**
 * File: Loader.php.
 * User: gron
 * Date: 3/25/12
 * Time: 1:41 PM
 */
namespace Core;

/**
 * Load class and method.
 * Factory-like class.
 * Use it in sequense:
 * - init(string)
 * - loadClass() - class will be returned
 * - loadMethod() - throw exception if no method will be fined.
 */
class Loader
{
    const DEFAULT_METHOD = "index";

    /**
     * Addon directories
     * @var array
     */
    private $addon_dirs = array('extension');
    /**
     * Server uri string, which delimited by /
     * @var array
     */
    private $server = array();
    /**
     * Object which created by loadClass method
     * @var object
     */
    private $class;

    /**
     * Initialise Loader class, set $this->server property
     * @param string $request_uri request uri like $_SERVER['REQUEST_URI']
     */
    public function init($request_uri)
    {
        $request_uri = preg_replace('/\\?.*/i', '', $request_uri);
        if($request_uri === '/'){
            // Default action;
            $this->server = array('Auth');
            // Don't need to trow now.
            // throw new \RuntimeException("No class in request URL was given");
        }else{
            $this->server = explode('/', trim($request_uri, '/'));
        }
    }

    /**
     * Load class by $this->server array (first and second segment)
     * Look for file in all addon_dirs when upload from extension
     * @return object
     */
    public function loadClass()
    {
        if (!empty($this->server[1]) && ($this->isFileExsists())) {
            $this->_createClass($this->server[1]);
            array_shift($this->server);
        } else {
            $this->_createClass($this->server[0]);
        }

        return $this->class;
    }

    private function _createClass($controllerName){
        $className = $this->server[0] . '\\' . $controllerName . 'Controller';
        $this->class = new $className;
        array_shift($this->server);
    }

    /**
     * Call loading class method from $this->server array
     * If nothing call method from DEFAULT_METHOD
     * @throws \RuntimeException
     */
    public function loadMethod(){
        $method = array_shift($this->server);
        if($method === null){
            $method = self::DEFAULT_METHOD;
        }
        if(method_exists($this->class,$method)){
            call_user_func(array($this->class,$method),$this->server);
        }else{
            throw new \RuntimeException('No method ' . $method . ' in class ' . get_class($this->class));
        }
    }

    /**
     * Check is there readable class file in addon directories
     * @return bool
     */
    private function isFileExsists(){
        foreach($this->addon_dirs as $item){
            if(is_readable(PROJECT_PATH . $item . '/' . $this->server[0] . '/' . $this->server[1] . '.php')){
                return true;
            }
        }
        return false;
    }
}
