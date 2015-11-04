<?php
class AD
{
    private $_conn;
    private $_prefix;


    public function __construct($login, $pass, $prefix, $server, $basedn, $port = 389)
    {
        $this->_prefix = $prefix;
        $this->_basedn = $basedn;
        $this->_conn =  ldap_connect($server, $port);

        ldap_set_option($this->_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->_conn, LDAP_OPT_REFERRALS, 0);

        $this->bind($login, $pass, $prefix);
    }

    public function bind($login, $pass)
    {
        @ldap_bind($this->_conn, $login . $this->_prefix, $pass);
        $error = ldap_error($this->_conn);

        if($error && ($error != 'Success'))
        {
            return $error;
        }

        return true;
    }

    /*
    *@val
    *@key employeeID|samaccountname
    */
    public function get_info($val, $key = 'samaccountname')
    {
        $filter= $key . '=' . $val;
        $sr=@ldap_search($this->_conn, $this->_basedn, $filter);
        $info = ldap_get_entries($this->_conn, $sr);

        $error = ldap_error($this->_conn);
        if($error && ($error != 'Success'))
        {
            throw new Exception ($error);
        }

        return $info;
    }

}