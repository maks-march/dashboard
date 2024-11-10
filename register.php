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
    <link rel="stylesheet" href="styles/style_register.css">
    <title>Dashboard</title>
</head>
<body>
    <header>

    </header>

    <main>
        <div class="container">
            <h3>Регистриция</h3>
                <form method="POST" class="form" data-form-title="Form Name">
                    <input autocomplete="off" type="email" name="email" placeholder="Почта" class="form-control" value="" id="email-form5-2">
                    <input autocomplete="off" type="text" name ="login" required value="" placeholder="Логин">
                    <input autocomplete="off" type="password" name="password" required placeholder="Пароль">
                    <input autocomplete="off" type="password" name="password2" required placeholder="Повторите пароль">
                    <button>Далее</button>
                    <div class="enter">
                        <a href="enter.php">Уже зарегистрированы?</a>
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
                            //header('Location:mail.php');
                            $sql = "INSERT INTO `users` (`login`, `password`,`tables_ids`) VALUES ('".$_POST['login']."','".$_POST['password']."','')";
                            mysqli_query($conn,$sql);
                            header('Location:index.php?no_start');
                        }
                    }
                }
                    
                ?>
                </form>
        </div>
        <div class="navigation">
            <a href = "index.php?no_start" class="add_files">
                К файлам
            </a> 
        </div>
    </main>
    
</body>
</html>