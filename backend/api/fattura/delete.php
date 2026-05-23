<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); //L'API accetta e restituisce file .json
header("Access-Control-Allow-Methods: DELETE"); //Aggiorno il metodo consentito: devo eliminare, quindi DELETE
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../../config/database.php';
include_once '../../models/fattura.php';

$database = new Database();
$db = $database->getConnection();

$fattura = new Fattura($db);

//json_decode va a decodificare il corpo grezzo della richiesta HTTP (il JSON che viene inviato tramite Postman).
$data = json_decode(file_get_contents("php://input"));

// Simulazione controllo ruolo, più in la configurerò un metodo in cui tale valore viene prelevato dalla sessione  
$id_ruolo_utente = isset($data->id_ruolo) ? $data->id_ruolo : 1;

if($id_ruolo_utente == 2){ //Solo gli ADMIN possono aver accesso a questa sezione

        if(!empty($data->id_fattura)){ //Sono un ADMIN e sono dentro: controllo se l'id della fattura sia valido

                      $fattura->id_fattura = $data->id_fattura;

                      if($fattura->delete()){// Risposta 200 OK: eliminazione riuscita

                              http_response_code(200);
                              echo json_encode(array("message" => "Fattura eliminata correttamente."));

                      } else {// Risposta 404 Not Found: l'ID non esiste o è già stato eliminato      

                              http_response_code(404);
                              echo json_encode(array("message" => "Fattura non trovata."));

                      }

        }else{

                // Risposta 400 Bad Request: manca l'ID nel JSON inviato
                http_response_code(400);
                echo json_encode(array("message" => "Dati incompleti: specificare id_fattura."));
        }

}else{
        //Entro in questo blocco se ho tentato di accedere senza avere i permessi adatti
        http_response_code(403);
        echo json_encode(array("message" => "Accesso negato: solo gli amministratori possono eliminare."));

}
?>