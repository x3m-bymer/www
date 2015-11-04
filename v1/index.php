<?php
require_once "app/App.php";
require_once "Db.php";
require_once "AD.php";
require_once "UserLDAP.php";
require_once "Helper.php";
require_once "Session.php";

$app = new App();

$db = new \App\Db();
$ad = new AD('proxyuser', 'testing123', '@dbes.ukrenergo.ent', 'dbes.ukrenergo.ent', 'dc=DBES,dc=ukrenergo,dc=ent');
$session = new Session($db);

$app::setItem('db', $db);
$app::setItem('ad', $ad);
$app::setItem('session', $session);

//Аутентификация по LDAP
$app->post('/v1/auth/', function($app){
    //Устанавливаем время сессии
    $session_expire = 60;
    $session_expire = time() + intval($session_expire);

    $db = $app::getItem('db');
    $ad = $app::getItem('ad');
    $session = $app::getItem('session');

    $input = Helper::get_input_data();

    if(isset($input['login']) || isset($input['pass'])){
        $user = new UserLDAP($ad, $db);
        $auth = $user->authentication($input['login'], $input['pass']);

        if($auth === false){
            $app->getResponse()->write(array('error'=> $user->error()), 401);
        } else {
            //Создаем сессию
            $session_id = $session->create($input['login'], $session_expire);

            $app->getResponse()->write(array('session' => $session_id));
        }
    } else {
        $app->getResponse()->write(array('error' => 'Login or pass empty'), 400);
    }
})
;

$app->post('/app/users/:name', function(){
    echo 'i am post';
});


$app->run();
