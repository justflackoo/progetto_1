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
                  WHERE df.id_fattura = :id_fattura AND df.is_annullato = 0";

        $stmt = $this->conn->prepare($query);

        //Sanitizzazione dell'id della fattura ricevuta in input
        $this->id_fattura = htmlspecialchars(strip_tags($this->id_fattura));

        $stmt->bindParam(":id_fattura", $this->id_fattura);

        $stmt->execute();

        return $stmt;
    }

  //Fattura e scontrino li considero sinonimi
  //La funzione delete in questo caso non elimina un elemento ma l'opposto. Se un utente elimina l'elemento dal proprio scontrino, lo stesso
  //elemento dovrà fare il percorso inverso, cioè ritornare nel magazzino.
  //In particolare delete() si rivolge ad una singola riga all'interno della fattura, 
  // così posso fare il reso di parte dello scontrino (e non necessariamente l'intero scontrino)
    function delete(){
        try{
            $this->conn->beginTransaction(); //"Bloccco" il DB, le modifiche non sono aggiornate finchè non faccio commit

            //Vado a leggere lo scontrino contenente tutti gli elementi
            $query_info = "SELECT id_giacenza, quantita_acquistata 
                           FROM " . $this->table_name . " 
                           WHERE id_dettaglio = :id_dettaglio AND is_annullato = 0"; 

              //Ho aggiunto un WHERE is_annullato = 0 perchè se un ordine è stato precedentemente annullato la colonna is_annullato varrebbe 1

            $stmt_info = $this->conn->prepare($query_info);

            $this->id_dettaglio = htmlspecialchars(strip_tags($this->id_dettaglio));

            $stmt_info->bindParam(":id_dettaglio", $this->id_dettaglio);

            $stmt_info->execute();

            if($stmt_info->rowCount() == 0){ //Se non trovo risultati, la riga non esiste o è già stata annullata in precedenza. Blocco l'operazione.
                $this->conn->rollBack();
                return false;
            }

            //Trasformo la riga appena trovata in un array associativo così che possa leggere il contenuto dei vari campi
            $row = $stmt_info->fetch(PDO::FETCH_ASSOC);

            $id_giacenza_da_ripristinare = $row['id_giacenza'];
            $quantita_da_ripristinare = $row['quantita_acquistata'];


            //Ora per l'elemento preciso all'interno di dettaglio_fattura setto is_annullato = 1 
            $query_delete = "UPDATE " . $this->table_name . " 
                             SET is_annullato = 1 
                             WHERE id_dettaglio = :id_dettaglio";
            
            $stmt_delete = $this->conn->prepare($query_delete);
            $stmt_delete->bindParam(":id_dettaglio", $this->id_dettaglio);
            $stmt_delete->execute();


            //Quantita da ripristinare contiene la quantita di elementi che l'utente ha annullato, li riaggiungo al magazzino
            //id_giacenza identifica un preciso incrocio giocatore-taglia
            $query_update = "UPDATE magazzino 
                             SET quantita_disponibile = quantita_disponibile + :quantita_da_ripristinare 
                             WHERE id_giacenza = :id_giacenza";
            
            $stmt_update = $this->conn->prepare($query_update);
            $stmt_update->bindParam(":quantita_da_ripristinare", $quantita_da_ripristinare);
            $stmt_update->bindParam(":id_giacenza", $id_giacenza_da_ripristinare);
            $stmt_update->execute();

            // 5. Entrambe le operazioni sono andate a buon fine: salvo definitivamente!
            $this->conn->commit();
            return true;




        }catch(Exception $e){ //Entro in questo blocco se c'è stato qualche problema nel try
            $this->conn->rollBack();
            return false;
        }

    }
}

?>