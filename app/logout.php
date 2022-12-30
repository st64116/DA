<?php
session_start();
session_destroy();
session_unset($_SESSION['ROLE']);
session_unset($_SESSION['LOGIN']);
header("Location:login.php");
die();
?>