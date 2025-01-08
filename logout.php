<?php
session_start();
setcookie('log', 0, time() - 36000);
header('Location:index.php');