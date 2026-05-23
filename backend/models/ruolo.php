<?php
class Ruolo{
  private $conn;
  private $table_name = "ruolo";

  public $id_ruolo;
  public $nome_ruolo;


  public function __construct($db){
    $this->conn = $db;
  }

  function create(){

      $query = "INSERT INTO " . $this->table_name . " SET nome_ruolo=:nome_ruolo";

      $stmt = $this->conn->prepare($query);

      $this->nome_ruolo = htmlspecialchars(strip_tags($this->nome_ruolo));

      $stmt->bindParam(":nome_ruolo", $this->nome_ruolo);

      if($stmt->execute()){
        return true;
      }
      return false;

  }

  function update(){
    $query = "UPDATE " . $this->table_name . "SET nome_ruolo = nome_ruolo WHERE id_ruolo = id_ruolo";

    $stmt = $this->conn->prepare($query);

    $this->id_ruolo = htmlspecialchars(strip_tags($this->id_ruolo));
    $this->nome_ruolo = htmlspecialchars(strip_tags($this->nome_ruolo));

    $stmt->bindParam(":id_ruolo", $this->id_ruolo);
    $stmt->bindParam(":nome_ruolo", $this->nome_ruolo);
    
    if($stmt->execute()){
        return true;
      }
      return false;

  }


  function read(){
    $query = "SELECT id_ruolo, nome_ruolo FROM " . $this->table_name;

    $stmt = $this->conn->prepare($query);

    $stmt->execute();
        
    return $stmt;
  }


  function delete(){
    $query = "DELETE FROM" .$this->table_name . "WHERE id_ruolo = :id_ruolo";

    $stmt = $this->conn->prepare($query);

     //Sanitizzazione
    $this->id_ruolo = htmlspecialchars(strip_tags($this->id_ruolo));

    //Binding dell' ID
    $stmt->bindParam(":id_ruolo", $this->id_ruolo);

    if ($stmt->execute()) {
        return true;
    }
    return false;
    
  }

}
?>