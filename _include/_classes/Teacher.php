<?php
  class Teacher{
    private $id;
    private $username;
    private $name;

    public function Teacher($id,$username,$name){
      $this->id=$id;
      $this->username=$username;
      $this->name=$name;
    }

    public function get_id(){
      return $this->get_id;
    }

    public function get_username(){
      return $this->username;
    }

    public function get_name(){
      return $this->name;
    }
  }
?>
