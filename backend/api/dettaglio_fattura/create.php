<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");

//Specifico gli header di richiesta consentiti
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/dettaglio_fattura.php';

$database = new Database();
$db = $database->getConnection();

$dettaglio = new Dettaglio_Fattura($db);

$data = json_decode(file_get_contents("php://input"));

//Per il prezzo scelgo isset in caso fosse 0 (es. un omaggio)
if(!empty($data->id_fattura) && !empty($data->id_giacenza) && !empty($data->quantita_acquistata) && isset($data->prezzo_unitario_acquisto) ){
    
    $dettaglio->id_fattura = $data->id_fattura;
    $dettaglio->id_giacenza = $data->id_giacenza;
    $dettaglio->quantita_acquistata = $data->quantita_acquistata;
    $dettaglio->prezzo_unitario_acquisto = $data->prezzo_unitario_acquisto;

          if($dettaglio->create()){
              // 201 Created, Transazione andata a buon fine
              http_response_code(201);
              echo json_encode(array("message" => "Prodotto aggiunto alla fattura e scorte aggiornate con successo."));
          } else {
              // 503 Service Unavailable, Transazione fallita 
              http_response_code(503);
              echo json_encode(array("message" => "Impossibile aggiungere il prodotto. Verifica le scorte in magazzino."));
          }


}else{
    // 400 Bad Request - Il JSON inviato era incompleto
    http_response_code(400);
    echo json_encode(array("message" => "Dati incompleti. Assicurati di aver fornito id_fattura, id_giacenza, quantita_acquistata e prezzo_unitario_acquisto."));
}

?>