<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/dettaglio_fattura.php';

$database = new Database();
$db = $database->getConnection();

$dettaglio = new Dettaglio_Fattura($db);

/*Per leggere 4 ad es. in "api/dettaglio_fattura/read.php?id_fattura=4" e capire che id_fattura == 4.
  La combinazione isset - !empty è per verificare che ESISTA e NON SIA NULLO (non ho id_fatture <=0)*/
  if(isset($_GET['id_fattura']) && !empty($_GET['id_fattura'])){

    $dettaglio->id_fattura = $_GET['id_fattura']; //L'utente ha specificato l'id_fattura, lo assegno a $dettaglio

    $stmt = $dettaglio->read(); //Prima eseguo la query
    $num = $stmt->rowCount(); //E poi conto le corrispondenze: se non ci sono corrispondenze non esiste la fattura

    if($num > 0){

        //Creo l'array principale, lo scontrino finale che contiene TUTTE le righe
        $dettagli_arr = array(); 

        //Dividerò lo scontrino in due sezioni: il dettaglio dei prodotti, cioè "records", e la sezione finale in cui considererò solo il totale
        $dettagli_arr["records"] = array(); 

        $totale_fattura = 0;

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        extract($row);

            //dettaglio_item rappresenta la singola riga (relativa ad esempio alla canotta di X giocatore) all'interno dello scontrino
            $dettaglio_item = array(
                "id_dettaglio" => $id_dettaglio,
                "id_fattura" => $id_fattura,
                "id_giacenza" => $id_giacenza,
                "id_canotta" => $id_canotta,
                "id_taglia" => $id_taglia,
                "giocatore" => $giocatore,
                "squadra" => $squadra,
                "nome_taglia" => $nome_taglia,
                "quantita_acquistata" => $quantita_acquistata,
                "prezzo_unitario_acquisto" => $prezzo_unitario_acquisto,
                "sub_totale" => $sub_totale
            );

            array_push($dettagli_arr["records"], $dettaglio_item);

            $totale_fattura += $sub_totale;

        }

        $dettagli_arr["totale_complessivo"] = $totale_fattura;

        http_response_code(200);
        echo json_encode($dettagli_arr, JSON_PRETTY_PRINT);


    }else {
        http_response_code(404);
        echo json_encode(array("message" => "Nessun dettaglio trovato per la fattura.".$dettaglio->id_fattura));
    }

  }else{
    http_response_code(400);
    echo json_encode(array("message" => "Assicurati di aver specificato l'ID della fattura nell'URL."));
  }

?>