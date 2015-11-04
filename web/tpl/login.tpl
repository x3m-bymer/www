<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <!--<link rel="icon" href="favicon.ico">-->

    <title>Единая Авторизация</title>

    <!-- Bootstrap core CSS -->
    <link href="lib/bootstrap-3.0.2/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="lib/js/jquery-2.1.1.min.js"></script>
    <script>
        $( document ).ready(function() {
            if (!navigator.cookieEnabled){
                $( "#cookie_msg" ).show();
                $('#button_login').addClass('disabled');
            }
        });

    </script>
    <style>
        body {
        //padding-top: 40px;
        //padding-bottom: 40px;
            background-color: #eee;
        }

        .form-signin {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
        }
        .form-signin .form-signin-heading,
        .form-signin .checkbox {
            margin-bottom: 10px;
        }
        .form-signin .checkbox {
            font-weight: normal;
        }
        .form-signin .form-control {
            position: relative;
            height: auto;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            padding: 10px;
            font-size: 16px;
        }
        .form-signin .form-control:focus {
            z-index: 2;
        }
        .form-signin input[type="l"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>

<body>
<div id="cookie_msg" style="background-color:red; display: none;">
    <span style="color:white">Работа системы требует включенные куки, обратитесь к системному администратору!!!</span>
</div>
<div class="container">
    <form class="form-signin" method="post">
        <h2 class="form-signin-heading">Авторизация <a href="http://web723.db.energy.gov.ua/mediawiki/index.php/%D0%95%D0%B4%D0%B8%D0%BD%D0%B0%D1%8F_%D0%B0%D0%B2%D1%82%D0%BE%D1%80%D0%B8%D0%B7%D0%B0%D1%86%D0%B8%D1%8F" target="_blank"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a></h2>
        <div class="panel panel-primary">
            <div class="panel-heading"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span> <?php echo $_POST['service']?></div>
            <div class="panel-body">
                <div class="input-group">
                    <label for="login" class="sr-only">Логин</label>
                    <input type="login" name="login" id="login" class="form-control" placeholder="Логин" autofocus>
                    <input type="hidden" name="service" value="<?php echo $_POST['service']?>">
                    <input type="hidden" name="auth_type" value="db">
                    <input type="hidden" name="ldap_prefix" value="@dbes.ukrenergo.ent">
                    <span class="input-group-addon" id="basic-addon2">
                    @dbes.ukrenergo.ent
                    </span>
                </div>

                <label for="pass" class="sr-only">Пароль</label>
                <input type="password" name="pass" id="pass" class="form-control" placeholder="Пароль">
                <button class="btn btn-lg btn-primary btn-block"  id='button_login' type="submit">Вход</button>
            </div>
        </div>
        <?php if(!empty($_POST['message'])){?>
            <div class="alert alert-danger" role="alert"><?php echo $_POST['message']?></div>
        <?php } ?>
        <div class="checkbox">
            <!--<label>
              <input type="checkbox" value="remember-me"> Remember me
            </label>-->
        </div>
        <!--<a href="{$this->redirect_uri}">&larr; Вернуться назад</a>-->

    </form>
</div> <!-- /container -->

</body>
</html>
