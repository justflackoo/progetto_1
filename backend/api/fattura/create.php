<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");

//Specifico gli header di richiesta consentiti
header("Access-Control-Allow-Headers: Content-Type");


include_once '../../config/database.php';
include_once '../../models/fattura.php';

$database = new Database();
$db = $database->getConnection();

$fattura  = new Fattura($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id_cliente) && !empty($data->data_acquisto) && isset($data->totale) && !empty($data->stato)){

   // Verifica che lo stato ricevuto sia tra quelli ammessi nel dizionario (whitelist)
   if(array_key_exists($data->stato, Fattura::$stati_label)){

                  $fattura->id_cliente = $data->id_cliente;
                  $fattura->data_acquisto = $data->data_acquisto;
                  $fattura->totale = $data->totale;
                  $fattura->stato = $data->stato;

                     if($fattura->create()){
                      http_response_code(201); //Setto il codice di risposta 201 --> CREATO
                      echo json_encode(array("message" => "Fattura creata con successo."));//E lo comunico all'utente
                   }else{
                      http_response_code(503);//Entro in questo blocco se non sono riuscito a creare la fattura  
                      echo json_encode(array("message" => "Non è stato possibile creare la fattura."));//E lo comunico all'utente :(
                        }
            }else{ //Entro in questo blocco se lo stato della fattura (che ho inserito) non fa parte del dizionario degli stati ammessi
            
               http_response_code(400);
               echo json_encode(array("message" => "Stato non valido. Valori ammessi: " . implode(", ", array_keys(Fattura::$stati_label))));

            }

   }else{
      //Se l'utente non ha inserito tutti i dati entriamo in questo else
        http_response_code(400); 
        echo json_encode(array("message" => "Dati incompleti. Impossibile creare una nuova fattura."));
   }

?>