package com.nba.gestionale.models;

public class Utente {
    private Integer idUtente;
    private String nome;
    private String cognome;
    private String email;
    private String password;
    private Integer idRuolo; 

    public Utente() {}

    public Utente(Integer idUtente, String nome, String cognome, String email, String password, Integer idRuolo) {
        this.idUtente = idUtente;
        this.nome = nome;
        this.cognome = cognome;
        this.email = email;
        this.password = password;
        this.idRuolo = idRuolo;
    }

    public Integer getIdUtente() { return idUtente; }
    public void setIdUtente(Integer idUtente) { this.idUtente = idUtente; }

    public String getNome() { return nome; }
    public void setNome(String nome) { this.nome = nome; }

    public String getCognome() { return cognome; }
    public void setCognome(String cognome) { this.cognome = cognome; }

    public String getEmail() { return email; }
    public void setEmail(String email) { this.email = email; }

    public String getPassword() { return password; }
    public void setPassword(String password) { this.password = password; }

    public Integer getIdRuolo() { return idRuolo; }
    public void setIdRuolo(Integer idRuolo) { this.idRuolo = idRuolo; }
}