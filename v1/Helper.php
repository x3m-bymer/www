<?php

class Helper
{
    static function redirectTohttps() {
        if($_SERVER['HTTPS']!="on") 
        {
            $redirect= 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            header("Location:$redirect");
        }
    }
    public function deleteParamGet($name, $url)
    {
        return preg_replace('/&?'.$name.'=[^&]*/', '', $url);
    }
    public function setCoockie($name, $value, $expire)
    {
        unset($_COOKIE[$name]);
        setcookie($name, null, -1, '/');
        setcookie ($name, $value, $expire);
    }

    public function delCookie($name)
    {
        unset($_COOKIE[$name]);
        setcookie($name, null, -1, '/');
    }

    public function getCookie($name)
    {
        if(empty($_COOKIE[$name]))
            return false;

        return $_COOKIE[$name];

    }
    
    static function normJsonStr($str)
    {
        $str = preg_replace_callback('/\\\u([a-f0-9]{4})/i', create_function('$m', 'return chr(hexdec($m[1])-1072+224);'), $str);
        return iconv('cp1251', 'utf-8', $str);
    }

    static function Redirect($url)
    {
        header("Location: $url");
    }
    static function sendResult($array, $code = 200)
    {
        header('Content-type: application/json; charset=utf-8');
        switch ($code) {
            case 500:
                header('x', true, 500);
                break;

            case 403:
                header('x', true, 403);
                break;

            default:
                header('HTTP/1.1 200 OK');
                break;
        }
        echo self::normJsonStr(json_encode( $array ));
    }
    
    static function match_one($line, $start, $end)
    {
        $pos_start = strpos($line, $start);
        $line = substr($line, $pos_start);
        $pos_start=0;
        $pos_end = strpos($line, $end);

        $pos_start = $pos_start + strlen($start);
        $len_str = $pos_end - $pos_start;

        $rest = substr($line, $pos_start, $len_str);
        if($rest)
            return $rest;
        return null;
    }

	static function get_input_data()
	{
		$params_input = array();
		
		if (!empty($_POST) AND is_array($_POST)) {
			foreach ($_POST as $k => $v) {
				$params_input[$k] = $v;
			}
		}
		
		if (!empty($_GET) AND is_array($_GET)) {
			foreach ($_GET as $k => $v) {
				$params_input[$k] = $v;
			}
		}
		
        $params = json_decode(file_get_contents('php://input'), true);
		
        if (!empty($params) AND is_array($params)) {
			foreach ($params as $k => $v) {
				$params_input[$k] = $v;
			}
		}
        
		/*if (!empty($params_input)) {
			foreach ($params_input as $k => $v) {
				$params_input[$k] = trim(iconv("UTF-8", "KOI8-U//TRANSLIT", $v));
			}
		}*/
		
		return $params_input;
	}
static function add_query_arg() {
    $args = func_get_args();
    if ( is_array( $args[0] ) ) {
        if ( count( $args ) < 2 || false === $args[1] )
            $uri = $_SERVER['REQUEST_URI'];
        else
            $uri = $args[1];
    } else {
        if ( count( $args ) < 3 || false === $args[2] )
            $uri = $_SERVER['REQUEST_URI'];
        else
            $uri = $args[2];
    }
 
    if ( $frag = strstr( $uri, '#' ) )
        $uri = substr( $uri, 0, -strlen( $frag ) );
    else
        $frag = '';
 
    if ( 0 === stripos( $uri, 'http://' ) ) {
        $protocol = 'http://';
        $uri = substr( $uri, 7 );
    } elseif ( 0 === stripos( $uri, 'https://' ) ) {
        $protocol = 'https://';
        $uri = substr( $uri, 8 );
    } else {
        $protocol = '';
    }
 
    if ( strpos( $uri, '?' ) !== false ) {
        list( $base, $query ) = explode( '?', $uri, 2 );
        $base .= '?';
    } elseif ( $protocol || strpos( $uri, '=' ) === false ) {
        $base = $uri . '?';
        $query = '';
    } else {
        $base = '';
        $query = $uri;
    }
 
    #print $query;
    parse_str($query, $qs);
    if ( is_array( $args[0] ) ) {
        foreach ( $args[0] as $k => $v ) {
            $qs[ $k ] = $v;
        }
    } else {
        $qs[ $args[0] ] = $args[1];
    }
 
    foreach ( $qs as $k => $v ) {
        if ( $v === false )
            unset( $qs[$k] );
    }
 
    $ret = http_build_query( $qs );
    $ret = trim( $ret, '?' );
    $ret = preg_replace( '#=(&|$)#', '$1', $ret );
    $ret = $protocol . $base . $ret . $frag;
    $ret = rtrim( $ret, '?' );
    return $ret;
}

    static function request_url()
    {
      $result = ''; // Пока результат пуст
      $default_port = 80; // Порт по-умолчанию
     
      // А не в защищенном-ли мы соединении?
      if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on')) {
        // В защищенном! Добавим протокол...
        $result .= 'https://';
        // ...и переназначим значение порта по-умолчанию
        $default_port = 443;
      } else {
        // Обычное соединение, обычный протокол
        $result .= 'http://';
      }
      // Имя сервера, напр. site.com или www.site.com
      $result .= $_SERVER['SERVER_NAME'];
     
      // А порт у нас по-умолчанию?
      if ($_SERVER['SERVER_PORT'] != $default_port) {
        // Если нет, то добавим порт в URL
        $result .= ':'.$_SERVER['SERVER_PORT'];
      }
      // Последняя часть запроса (путь и GET-параметры).
      $result .= $_SERVER['REQUEST_URI'];
      // Уфф, вроде получилось!
      return $result;
    }

}