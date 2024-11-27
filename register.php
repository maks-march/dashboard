<?php
    include "conn.php";
    session_start();
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="css/register_style.css">
    <title>JustImport</title>
</head>
<body>
    <header>
        <h1>JustImport</h1>
    </header>

    <main>
        <div class="container">
            <h3>Регистриция</h3>
                <form method="POST" data-form-title="Form Name">
                    <input autocomplete="off" type="text" name ="login" required value="" placeholder="Логин">
                    <input autocomplete="off" type="password" name="password" required placeholder="Пароль">
                    <input autocomplete="off" type="password" name="password2" required placeholder="Повторите пароль">
                    <div class="buttons">
                        <button>Далее</button>
                        <a href="enter.php" class="enter">Уже зарегистрированы?</a>
                    </div>
                <?php
                if (isset($_COOKIE['error']))
                {
                    echo $_COOKIE['error'];
                    session_unset();
                    session_destroy();
                    setcookie('error','',time()-3600);
                    setcookie('log', 1, time() - 3600);
                    setcookie('pass', 1, time() - 3600);
                } else
                if (isset($_POST['login'])){
                    if ($_POST['password']!=$_POST['password2'])
                    {
                        echo '<div class="error">Пароли не совпадают!</div>';
                    } else {
                        $sql_sr = "SELECT `login` FROM `users`";
                        $query_sr = mysqli_query($conn,$sql_sr);
                        $results_all = mysqli_fetch_all($query_sr, MYSQLI_ASSOC);
                        $flag = 1;
                        for ($i=0; $i < count($results_all); $i++) { 
                            if ($results_all[$i]['login'] == $_POST['login']){
                                echo '<div class="error">Почта уже использована!</div>';
                                $flag = 0;
                                break;
                            }
                        }
                        if ($flag == 1){
                            setcookie('log', $_POST['login'], time() + 3600);
                            $sql = "INSERT INTO `users` (`login`, `password`,`tables_ids`) VALUES ('".$_POST['login']."','".$_POST['password']."','')";
                            mysqli_query($conn,$sql);
                            header('Location:index.php?no_start');
                        }
                    }
                }
                    
                ?>
                </form>
        </div>
    </main>
</body>
</html>