<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 31.10.2015
 * Time: 13:55
 */
namespace App;

class Request{
    const HTTP_GET = 'GET';
    const HTTP_POST = 'POST';
    const HTTP_PUT = 'PUT';
    const HTTP_DELETE = 'DELETE';

    protected static $_instance;

    private function __construct(){}
    private function __clone(){}

    public static function getInstance() {
        // ��������� ������������ ����������
        if (null === self::$_instance) {
            // ������� ����� ���������
            self::$_instance = new self();
        }
        // ���������� ��������� ��� ������������ ���������
        return self::$_instance;
    }
    /**
     * @return mixed
     */
    public function getRequestUri(){
        return '/' . trim($_SERVER['REQUEST_URI'], '/');
    }

    public function getRequestMethod(){
        return $_SERVER['REQUEST_METHOD'];
    }
}