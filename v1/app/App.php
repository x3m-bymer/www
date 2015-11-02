<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 31.10.2015
 * Time: 13:52
 */

require_once 'Request.php';
require_once 'Response.php';
require_once 'Route.php';
require_once 'Log.php';
require_once 'LogWriter.php';

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors','On');

class App{
    private $_request;
    private $_response;
    private $_routes;
    private $_route;
    private $_config;

    function __construct(){
        set_error_handler(array($this, 'ErrorCatcher'));
        $this->_config = $this->getDefaultSettings();

        $this->_request = \App\Request::getInstance();
        $this->_response = \App\Response::getInstance();
        //$this->_route = \App\Route::getInstance();
        $this->_logger = new \App\Log($this->_config['Logger']);
    }

    private function getDefaultSettings(){
        return array(
            'Logger' => new \App\LogWriter(@fopen('log.txt', 'a')),
        );
    }
    function get(){
        $args = func_get_args();
        $this->map($args, \App\Request::HTTP_GET);
    }

    function post(){
        $args = func_get_args();
        $this->map($args, \App\Request::HTTP_POST);
    }

    function map($args, $http_method){
        if(empty($args) || count($args) != 2){
            throw new Exception('error param');
        }

        $pattern = array_shift($args);
        $callback = array_pop($args);

        $route = new \App\Route();

        $route->setPattern($pattern);
        $route->setCallback($callback);
        $route->setHttpMethod($http_method);

        $hash = md5($route->getPattern().$route->getHttpMethod());
        $this->_routes[$hash] = $route;
    }


    function run(){
        $call = false;

        foreach($this->_routes as $route){
            if($route->matches($this->_request->getRequestUri(), $this->_request->getRequestMethod())){
                $this->call($route);
                $call = true;
            }
        }

        if($call === false){
            $this->_response->write('Page not found', 404);
        }

    }

    private function call($route){
        if(is_callable($route->getCallback())){
            try{
                $params = array();
                $paramValues = $route->getParamValues();

                if(!empty($paramValues)){
                    $paramNames = $route->getParamNames();
                    foreach($paramNames as $name){
                        if(isset($paramValues[$name])){
                            $params[$name] = $paramValues[$name];
                        }
                    }
                }

                $params['app'] = $this;
                call_user_func_array($route->getCallback(), $params);
                $this->_logger->info('request');
            } catch (\Exception $e){
                $this->_response->write('Server Error '.$e->getMessage(), 500);
                $this->_logger->fatal($e->getMessage());
            }
        }
    }

    /**
     * @return \App\Route
     */
    public function getRoute()
    {
        return $this->_route;
    }

    /**
     * @param \App\Route $route
     */
    public function setRoute($route)
    {
        $this->_route = $route;
    }

    static function test(){
        return 'ok';
    }

    function ErrorCatcher($err_no, $err_str){
        $error_type = array (
            1   =>  "Ошибка",
            2   =>  "Предупреждение",
            4   =>  "Ошибка синтаксического анализа",
            8   =>  "Замечание",
            16  =>  "Ошибка ядра",
            32  =>  "Предупреждение ядра",
            64  =>  "Ошибка компиляции",
            128 =>  "Предупреждение компиляции",
            256 =>  "Ошибка пользователя",
            512 =>  "Предупреждение пользователя",
            1024=>  "Замечание пользователя"
        );

        throw new \Exception ($error_type[$err_no] . ":" .$err_str);
    }

    /**
     * @return \App\Request
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * @return \App\Response
     */
    public function getResponse()
    {
        return $this->_response;
    }


}