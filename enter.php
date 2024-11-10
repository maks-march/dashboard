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
            <h3>Вход</h3>
                <form method="POST" class="form" data-form-title="Form Name">
                    <input autocomplete="off" type="email" name="email" placeholder="Почта" class="form-control" value="" id="email-form5-2">
                    <input autocomplete="off" type="text" name ="login" required value="" placeholder="Логин">
                    <input autocomplete="off" type="password" name="password" required placeholder="Пароль">
                    <button>Далее</button>
                    <div class="regiater">
                        <a href="register.php">Не зарегистрированы?</a>
                    </div>
                <?php
                if (isset($_POST['login'])) {
                    $sql_sr = "SELECT * FROM `users`";
                    $query_sr = mysqli_query($conn,$sql_sr);
                    $results_all = mysqli_fetch_all($query_sr, MYSQLI_ASSOC);
                    $flag = 0;
                    for ($i=0; $i < count($results_all); $i++) { 
                        if ($results_all[$i]['login'] == $_POST['login'] && $_POST['password'] == $results_all[$i]['password']){
                            $flag = 1;
                            break;
                        }
                    }
                    if ($flag == 1){
                        setcookie('log', $_POST['login'], time() + 3600);
                        header('Location:index.php?no_start');
                    } else {
                        echo 'Неправильный логин или пароль!';
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