import api.ApiClient;

public class Main {

    public static void main(String[] args) {

        ApiClient client = new ApiClient();

       /* System.out.println("--- 1. TEST POST (CREATE) ---");
        
        String jsonCreate = "{\"giocatore\":\"Michael Jordan\", \"squadra\":\"Bulls\", \"numero\":23, \"tipo\":\"Icon\", \"anno\":1998, \"prezzo_originale\":150.00, \"percentuale_sconto\":0.00}";
        String response = client.post("canotta/create.php", jsonCreate);
        System.out.println(response); 


        System.out.println("--- 2. TEST GET (READ) ---");
        System.out.println(client.get("canotta/read.php"));

        System.out.println("--- 3. TEST PUT (UPDATE) ---");
        String jsonUpdate = "{\"id_canotta\":7, \"giocatore\":\"Michael Jordan\", \"squadra\":\"Bulls\", \"numero\":23, \"tipo\":\"Icon\", \"anno\":1998, \"prezzo_originale\":200.00, \"percentuale_sconto\":10.00}";
        System.out.println(client.put("canotta/update.php", jsonUpdate)); 

        System.out.println("--- 2. TEST GET (READ) ---");
        System.out.println(client.get("canotta/read.php")); 

        System.out.println("\n--- 4. TEST DELETE (DELETE) ---");
        String jsonDelete = "{\"id_canotta\":7}"; 
        System.out.println(client.delete("canotta/delete.php", jsonDelete));*/

        System.out.println("--- 2. TEST GET (READ) ---");
        System.out.println(client.get("canotta/read.php")); 

    }

}