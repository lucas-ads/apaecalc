<?php
    session_start();

    if(isset($_SESSION['professor'])){
        unset($_SESSION['professor']);
    }
    
    header("location:index.php");
?>
