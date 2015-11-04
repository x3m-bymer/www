<?php
require_once 'AD.php';

class UserLDAP
{
    private $_conn;
    private $_error;

    public function error()
    {
        return $this->_error;
    }

    function __construct($ad, $db)
    {
        $this->_conn = $ad;
        $this->_db = $db;
    }

    public function authentication($user, $pass)
    {
        try {
            if ($this->_conn->bind($user, $pass)) {
                return true;
            }
        } catch (Exception $e) {
            $this->_error = $e->getMessage();
        }

        return false;
    }
}