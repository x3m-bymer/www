<?php
class Session
{
    private $tbl = 'tbl_session';
    private $_db;

    function __construct($db)
    {
        $this->_db = $db;
    }

    public function check($session, $LINK)
    {
        try
        {
            $stmt = $LINK->prepare("SELECT * FROM $this->tbl WHERE session=:session");
            $stmt->execute(array(':session'=>$session));

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if(empty($result))
                die (Helper::sendResult(array('error' => 'NO_VALID_SESSION'), 403));

            if($result['expire'] <= time())
            {
                die (Helper::sendResult(array('error' => 'SESSION_EXPIRED'), 403));
            }
        }
        catch(PDOException $e)
        {
            die (Helper::sendResult(array('error' => 'DB_ERROR'), 403));
        }

        return $result;

    }

    public function get_uid_by_session($session)
    {
        $query = "SELECT * FROM $this->tbl WHERE session = '$session' LIMIT 1";
        $this->_db->query($query);
        $res = $this->_db->assoc();

        if(empty($res)){
            return false;
        }

        return $res;
    }

    public function delete($session_id, $LINK)
    {
        $stmt = $LINK->prepare("DELETE FROM $this->tbl WHERE `session`=:session_id");
        $stmt->execute(array(':session_id'=>$session_id));
        if(!$stmt)
            die(Helper::sendResult($LINK->errorInfo(), 500));

        return true;

    }

    public function clear_sessions($uid, $LINK)
    {
        $stmt = $LINK->prepare("DELETE FROM $this->tbl WHERE `uid`=:uid");
        $stmt->execute(array(':uid'=>$uid));
        if(!$stmt)
            die(Helper::sendResult($LINK->errorInfo(), 500));

        return true;

    }

    public function create($login, $session_expired)
    {
        $session_id = md5(uniqid(rand(), true));
        $ip = $_SERVER['REMOTE_ADDR'];

        $query = "INSERT INTO $this->tbl (uid, ip, session, expire) VALUES ('$login', '$ip', '$session_id', '$session_expired')";
        $res = $this->_db->query($query);

        if($res === false){
            return false;
        }

        return $session_id;

    }
}