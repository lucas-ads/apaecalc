<?php
    session_start();

    if(isset($_SESSION['estudante'])){
        unset($_SESSION['estudante']);
    }
    if(isset($_SESSION['partida'])){
        unset($_SESSION['partida']);
    }

    header("location:index.php");
?>