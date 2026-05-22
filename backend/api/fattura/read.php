<?php
header("Access-Control-Allow-Origin: *"); //Consento l'accesso da tutti i browser
header("Content-Type: application/json; charset=UTF-8");  //L'API accetta e restituisce file .json
header("Access-Control-Allow-Methods: GET"); 
header("Access-Control-Max-Age: 3600"); //Per quanti secondi il browser può memorizzare questa autorizzazione

include_once '../../config/database.php';
include_once '../../models/fattura.php';


$database = new Database();
$db = $database->getConnection();

$fattura = new Fattura($db);

/*È un API di lettura, mi richiamo il metodo read() della classe fattura.php che restituiva proprio un oggetto $stmt*/
$stmt = $fattura->read(); 

//Conto quanti fatture sono state trovate nel database
$num = $stmt->rowCount();


if($num > 0){ //Se ci sono elementi entro in questo blocco

            /// Array che conterrà tutte le fatture
              $fatture_arr = array();
              $fatture_arr["records"] = array();

              // Entriamo nel ciclo per ogni riga restituita dalla query
              while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

                      extract($row);

                      //Mi creo un JSON
                      $fattura_item = array(
                          "id_fattura" => $id_fattura, 
                          "id_cliente" => $id_cliente,
                          "nome_cliente" => $nome,
                          "cognome_cliente" => $cognome,
                          "data_acquisto" => $data_acquisto, 
                          "totale" => $totale,
                          "stato" => Fattura::getStatoLabel($stato) // Chiamata al metodo del modello
                      );


                      array_push($fatture_arr["records"], $fattura_item);

              }

            http_response_code(200);
            echo json_encode($fatture_arr);


}else{
      //Entro in questo blocco se non sono state trovate corrispondenze
      http_response_code(404); //404 --> Not Found, il server non è in grado di trovare la risorsa richiesta
      echo json_encode(array("message" => "Nessuna fattura trovata."));
}

?>