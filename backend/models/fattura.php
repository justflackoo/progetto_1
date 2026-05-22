<?php

class Fattura{

    private $conn;
    private $table_name = "fattura";

    public $id_fattura;
    public $id_cliente;
    public $data_acquisto;
    public $totale;
    public $stato;

    // Dizionario per il mapping degli stati di una fattura (in futuro potrei ampliarlo)
    public static $stati_label = [
        1 => "BLOCCATA",
        2 => "IN ATTESA",
        3 => "SPEDITO"
    ];

    public function __construct($db){
    $this->conn = $db;}

    /*se l'ID passato non esiste nell'array restituisce la stringa di default "SCONOSCIUTO" */
    public static function getStatoLabel($id) {
    return self::$stati_label[$id] ?? "SCONOSCIUTO";
}

   function create(){
      $query = "INSERT INTO "  .$this->table_name . "   
      SET id_cliente =:id_cliente, data_acquisto =:data_acquisto, totale =:totale, stato =:stato";

     $stmt = $this->conn->prepare($query);

     $this->id_cliente = htmlspecialchars(strip_tags($this->id_cliente));
     $this->data_acquisto = htmlspecialchars(strip_tags($this->data_acquisto));
     $this->totale = htmlspecialchars(strip_tags($this->totale));
     $this->stato = (int)($this->stato);

     $stmt->bindParam(":id_cliente", $this->id_cliente);
     $stmt->bindParam(":data_acquisto", $this->data_acquisto);
     $stmt->bindParam(":totale", $this->totale);
     $stmt->bindParam(":stato", $this->stato);

     // Esecuzione
          if($stmt->execute()){
            /*Senza questa riga non potrei gestire in maniera corretta il contenuto della fattura con uno
            specifico ID: saprei che una fattura è stata creata ma non conoscerei l'id */
              $this->id_fattura = $this->conn->lastInsertId();
              return true;
          }
          return false;

      
  }

  function read(){
    $query = "SELECT u.nome, u.cognome, f.id_fattura, f.id_cliente ,f.data_acquisto, f.totale, f.stato 
              FROM ".$this->table_name. " AS f 
              INNER JOIN utente u 
              ON f.id_cliente = u.id_utente
              ORDER BY f.data_acquisto DESC";

    //Preparo la query
    $stmt = $this->conn->prepare($query);

    $stmt->execute();

    return $stmt;

  }

  //Ha senso aggiornare solo lo stato
  function update() {
    $query = "UPDATE " . $this->table_name . "
              SET stato = :stato
              WHERE id_fattura = :id_fattura";

    $stmt = $this->conn->prepare($query);

    // Sanificazione
    $this->stato = (int)$this->stato;
    $this->id_fattura = htmlspecialchars(strip_tags($this->id_fattura));

    $stmt->bindParam(":stato", $this->stato);
    $stmt->bindParam(":id_fattura", $this->id_fattura);

    return $stmt->execute();
}

  

}

?>