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
    <link rel="stylesheet" href="css/enter_style.css">
    <title>JustImport</title>
</head>
<body>
    <header>

    </header>
    <main>
        <div class="container">
            <h3>Вход</h3>
            <form method="POST"  data-form-title="Form Name">
                <input autocomplete="off" type="text" name ="login" required value="" placeholder="Логин">
                <input autocomplete="off" type="password" name="password" required placeholder="Пароль">
                <div class="buttons">
                    <button>Далее</button>
                    <a href="register.php" class="enter">Не зарегистрированы?</a>
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
                    setcookie('log', $_POST['login'], time() + 36000);
                    header('Location:index.php?no_start');
                } else {
                    echo 'Неправильный логин или пароль!';
                }
            }
                
            ?>
            </form>
        </div>
    </main>
</body>
</html>