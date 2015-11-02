<?php

require_once "app/App.php";
require_once "app/Db.php";

$app = new App();

\App\Db::setDbHost('10.1.7.23');
\App\Db::setUserPw('dc832921');
\App\Db::setDbUser('kurilov');
\App\Db::setDbName('kurilov');

//$db = \App\Db::getInstance();

//$db->query('SELECT * from users');

$app->get('/v1/login/:name/4545', function($name, $app){
    $app->getResponse()->write('ok '.$name);
})
;

$app->post('/app/users/:name', function(){
    echo 'i am post';
});


$app->run();
