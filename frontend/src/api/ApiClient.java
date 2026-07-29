package api;

import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;

public class ApiClient {
  private static final String BASE_URL = "http://localhost/project/backend/api/";
  private final HttpClient client;  

  public ApiClient() {
        this.client = HttpClient.newHttpClient();
    }

  public String get(String endpoint) {
        return sendRequest(endpoint, "GET", null);
    }

  public String post(String endpoint, String jsonBody) {
        return sendRequest(endpoint, "POST", jsonBody);
    }

  public String put(String endpoint, String jsonBody) {
        return sendRequest(endpoint, "PUT", jsonBody);
    }

  public String delete(String endpoint, String jsonBody) {
        return sendRequest(endpoint, "DELETE", jsonBody);
    }

  private String sendRequest(String endpoint, String method, String jsonBody) {
    try{

        HttpRequest.Builder requestBuilder = HttpRequest.newBuilder()
                    .uri(URI.create(BASE_URL + endpoint))
                    .header("Content-Type", "application/json");


        if (jsonBody == null || jsonBody.isEmpty()) {
                    requestBuilder.method(method, HttpRequest.BodyPublishers.noBody());
                } else {
                    requestBuilder.method(method, HttpRequest.BodyPublishers.ofString(jsonBody));
                }

        HttpResponse<String> response = client.send(requestBuilder.build(), HttpResponse.BodyHandlers.ofString());


        if (response.statusCode() == 200 || response.statusCode() == 201) {
                return response.body();
            } else {
                return "Errore HTTP: " + response.statusCode();
            }

    } catch (Exception e) {
            return "Eccezione durante la richiesta: " + e.getMessage();
        }
  }


}
