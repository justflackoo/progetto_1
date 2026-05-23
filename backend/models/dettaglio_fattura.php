<?php

class Dettaglio_Fattura{

    private $conn;
    private $table_name = "dettaglio_fattura";

    public $id_dettaglio;
    public $id_fattura;
    public $id_giacenza;
    public $quantita_acquistata;
    public $prezzo_unitario_acquisto;

    public function __construct($db){
        $this->conn = $db;
    }

    /*Lo scopo del create() in dettaglio_fattura sarà duplice: da un lato "aggiungo" elementi all'interno della fattura,
      dall'altro rimuovo gli stessi elementi dal magazzino. */

    function create(){

      try{
        $this->conn->beginTransaction();

        $query_insert = "INSERT INTO " . $this->table_name . "   
                             SET id_fattura = :id_fattura, 
                                 id_giacenza = :id_giacenza, 
                                 quantita_acquistata = :quantita_acquistata, 
                                 prezzo_unitario_acquisto = :prezzo_unitario_acquisto";

        $stmt_insert = $this->conn->prepare($query_insert);

        //Sanitizzazione
        $this->id_fattura = htmlspecialchars(strip_tags($this->id_fattura));
        $this->id_giacenza = htmlspecialchars(strip_tags($this->id_giacenza));
        $this->quantita_acquistata = htmlspecialchars(strip_tags($this->quantita_acquistata));
        $this->prezzo_unitario_acquisto = htmlspecialchars(strip_tags($this->prezzo_unitario_acquisto));

        //Binding
        $stmt_insert->bindParam(":id_fattura", $this->id_fattura);
        $stmt_insert->bindParam(":id_giacenza", $this->id_giacenza);
        $stmt_insert->bindParam(":quantita_acquistata", $this->quantita_acquistata);
        $stmt_insert->bindParam(":prezzo_unitario_acquisto", $this->prezzo_unitario_acquisto);

        // Eseguiamo l'inserimento nella tabella dettaglio_fattura
        $stmt_insert->execute();

        /*Adesso devo gestire la seconda parte della transazione, quella in cui scalo la quantità dal magazzino (a patto che ci siano
        elementi sufficienti)*/

        $query_update = "UPDATE magazzino 
                             SET quantita_disponibile = quantita_disponibile - :quantita_acquistata 
                             WHERE id_giacenza = :id_giacenza 
                             AND quantita_disponibile >= :quantita_check"; //quantita_disponibile >= :quantita è fondamentale

        $stmt_update = $this->conn->prepare($query_update);

        $stmt_update->bindParam(":quantita_acquistata", $this->quantita_acquistata);
        $stmt_update->bindParam(":quantita_check", $this->quantita_acquistata);
        $stmt_update->bindParam(":id_giacenza", $this->id_giacenza);

        $stmt_update->execute();

        //Adesso tocca verificare se il magazzino è stato aggiornato, se non ci sono righe allora la modifica non è andata in porto
        if($stmt_update->rowCount() == 0){
                // Qualcosa è andato storto (non c'è stock sufficiente).
                $this->conn->rollBack();
                return false;
            }

            //Se arrivo qui entrambe le query sono andate a buon fine, posso confermare tutto con commit()
            $this->conn->commit();
            return true;



      }catch(Exception $e){ //Entro in questo blocco se ho riscontrato problemi con una delle operazioni presenti nel blocco try

                $this->conn->rollBack(); //Ripristino lo stato del DB a quello in cui si trovava prima dell'inizio della transazione
                return false;
      }

    }

   // Metodo per leggere i dettagli di una specifica fattura
    function read(){
        
        // Creiamo una query complessa con delle INNER JOIN per recuperare il nome del giocatore e la taglia
        $query = "SELECT 
                    df.id_dettaglio,
                    df.id_fattura,
                    df.id_giacenza,
                    c.id_canotta,
                    t.id_taglia,
                    c.giocatore,
                    c.squadra,
                    t.nome_taglia,
                    df.quantita_acquistata,
                    df.prezzo_unitario_acquisto,
                    (df.quantita_acquistata * df.prezzo_unitario_acquisto) AS sub_totale
                  FROM " . $this->table_name . " df
                  INNER JOIN magazzino m ON df.id_giacenza = m.id_giacenza
                  INNER JOIN canotta c ON m.id_canotta = c.id_canotta
                  INNER JOIN tabella_taglie t ON m.id_taglia = t.id_taglia
                  WHERE df.id_fattura = :id_fattura";

        $stmt = $this->conn->prepare($query);

        //Sanitizzazione dell'id della fattura ricevuta in input
        $this->id_fattura = htmlspecialchars(strip_tags($this->id_fattura));

        $stmt->bindParam(":id_fattura", $this->id_fattura);

        $stmt->execute();

        return $stmt;
    }
}

?>