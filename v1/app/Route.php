<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 31.10.2015
 * Time: 13:55
 */
namespace App;

class Route{
    protected static $_instance;
    private $_callback;
    private $_pattern;
    private $_http_method;
    private $_param_names = array();
    private $_param_values = array();

    /**
     * @return array
     */
    public function getParamNames()
    {
        return $this->_param_names;
    }

    /**
     * @return array
     */
    public function getParamValues()
    {
        return $this->_param_values;
    }

    /**
     * @param array $param_values
     */
    public function setParamValues($param_values)
    {
        $this->_param_values = $param_values;
    }

    /**
     * @param array $param_names
     */
    public function setParamName($param_name)
    {
        $this->_param_names[] = $param_name;
    }


    /**
     * @return mixed
     */
    public function getCallback()
    {
        return $this->_callback;
    }

    /**
     * @param mixed $callback
     */
    public function setCallback($callback)
    {
        $this->_callback = $callback;
    }

    /**
     * @return mixed
     */
    public function getPattern()
    {
        return '/'.trim($this->_pattern, '/');
    }

    /**
     * @param mixed $pattern
     */
    public function setPattern($pattern)
    {
        $this->_pattern = $pattern;
    }

    /**
     * @return mixed
     */
    public function getHttpMethod()
    {
        return $this->_http_method;
    }

    /**
     * @param mixed $http_method
     */
    public function setHttpMethod($http_method)
    {
        $this->_http_method = $http_method;
    }

    public function matches($resourceUri, $resourceHttpMethod){
        if($resourceHttpMethod != $this->getHttpMethod()){
            return false;
        }

        if($resourceUri == $this->getPattern()){
            return true;
        }

        $patternAsRegex = preg_replace_callback('#\:([\w]+)#', array($this, 'matchesCallback'),
            str_replace(')', ')?', $this->getPattern()));


        if (!preg_match('#^' . $patternAsRegex . '$#', $resourceUri, $paramValues)) {
            return false;
        }

        $this->setParamValues($paramValues);
        return true;
    }
    protected function matchesCallback($m){
        $this->setParamName($m[1]);
        return '(?P<' .$m[1]. '>[^/]+)';
    }
}