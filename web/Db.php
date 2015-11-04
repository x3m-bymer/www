<?php
/**
 * Created by PhpStorm.
 * User: noname
 * Date: 31.10.2015
 * Time: 13:55
 */

namespace App;

class Db{
    protected static $_instance;

    private $_db;
    private $_query;

    private $db_host = 'localhost';
    private $db_name = 'kurilov';
    private $db_user = 'root';
    private $user_pw = '';

    public function __construct(){
        try{
            $this->_db = new \PDO('mysql:host='.$this->db_host.'; dbname='.$this->db_name, $this->db_user, $this->user_pw);
            $this->_db->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
            $this->_db->exec("SET CHARACTER SET utf8");
        } catch(\Exception $e){
            throw new \Exception('Error connect to database');
        }
    }

    /**
     * @return mixed
     */
    public function getDbHost()
    {
        return self::$db_host;
    }

    /**
     * @param mixed $db_host
     */
    static function setDbHost($db_host)
    {
        self::$db_host = $db_host;
    }

    /**
     * @return mixed
     */
    static function getDbName()
    {
        return self::$db_name;
    }

    /**
     * @param mixed $db_name
     */
    static function setDbName($db_name)
    {
        self::$db_name = $db_name;
    }

    /**
     * @return mixed
     */
    static function getDbUser()
    {
        return self::$db_user;
    }

    /**
     * @param mixed $db_user
     */
    static function setDbUser($db_user)
    {
        self::$db_user = $db_user;
    }

    /**
     * @return mixed
     */
    static function getUserPw()
    {
        return self::$user_pw;
    }

    /**
     * @param mixed $user_pw
     */
    static function setUserPw($user_pw)
    {
        self::$user_pw = $user_pw;
    }

    private function __clone(){}

    public static function getInstance() {
        // проверяем актуальность экземпляра
        if (null === self::$_instance) {
            // создаем новый экземпляр
            self::$_instance = new self();
        }
        // возвращаем созданный или существующий экземпляр
        return self::$_instance;
    }

    public function query(){
        $args = func_get_args();

        $sql = array_shift($args);

        try{
            $this->_query = $this->_db->query($sql);
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }
    public function assoc(){
        if($this->_query === false){
            return false;
        }
        return $this->_query->fetch(\PDO::FETCH_ASSOC);
    }

    public function all(){
        if($this->_query === false){
            return false;
        }

        return $this->_query->fetchall(\PDO::FETCH_ASSOC);
    }
}