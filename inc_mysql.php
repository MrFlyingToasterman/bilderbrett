<?php
    $host = "localhost";
    $user = "USER";
    $pass = "PASSWORD";
    $database = "DATABASE";
    $dz = mysql_connect($host, $user, $pass);
    mysql_select_db($database, $dz);
?>
