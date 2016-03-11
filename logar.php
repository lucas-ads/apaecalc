<?php
    require_once '_include/_cruds/ConnectionDatabase.php';
    require_once '_include/_cruds/daoEstudante.php';
    require_once '_include/_classes/Teacher.php';
    require_once '_include/_cruds/daoTeacher.php';
    require_once '_include/_classes/Estudante.php';

    session_start();

    if(isset($_POST["username"])&&isset($_POST["password"])){
        $professor=efetuarLoginTeacher($_POST["username"],$_POST["password"]);
        if(is_numeric($professor)){
          $estudante=efetuarLogin($_POST["username"],$_POST["password"]);
          if(is_numeric($estudante)){
              if($estudante==0&&$professor==0){
                  header("location:index.php?error=0");
              }else{
                  header("location:index.php?error=-1&&username=".$_POST['username']);
              }
          }else{
              $_SESSION['estudante']=$estudante;
              header("location:PaginaInicial.php");
          }
        }else{
          $_SESSION['professor']=$professor;
          header("location:Admin.php");
        }
    }else{
      if(isset($_SESSION['estudante'])){
          header("location:PaginaInicial.php");
      }else{
        if(isset($_SESSION['professor'])){
          header("location:Admin.php");
        }else{
          header("location:index.php");
        }
      }
    }
?>
