<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); //L'API accetta e restituisce file .json
header("Access-Control-Allow-Methods: PUT"); //Aggiorno il metodo consentito: devo modificare, quindi PUT
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../../config/database.php';
include_once '../../models/fattura.php';

$database = new Database();
$db = $database->getConnection();

$fattura = new Fattura($db);

// file_get_contents legge i dati grezzi della richiesta, json_decode li trasforma in un oggetto PHP
$data = json_decode(file_get_contents("php://input"));

// Validazione: servono almeno l'ID della fattura e il nuovo stato
if(!empty($data->id_fattura) && isset($data->stato)){

    // Validiamo lo stato tramite il dizionario del modello
    if(array_key_exists($data->stato, Fattura::$stati_label)){

        $fattura->id_fattura = $data->id_fattura;
        $fattura->stato = $data->stato;

            if($fattura->update()){
                http_response_code(200);
                echo json_encode(array("message" => "Stato fattura aggiornato con successo."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Impossibile aggiornare lo stato della fattura."));
            }

    }else{
          http_response_code(400);
          echo json_encode(array("message" => "Stato non valido."));

    }

}else{
    http_response_code(400);
    echo json_encode(array("message" => "Dati incompleti. Specificare id_fattura e stato."));
}

?>