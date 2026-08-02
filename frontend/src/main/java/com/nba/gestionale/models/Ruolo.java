package com.nba.gestionale.models;

public class Ruolo {
    private int idRuolo;
    private String nomeRuolo;

    // Costruttore vuoto obbligatorio per la futura deserializzazione JSON
    public Ruolo() {}

    public Ruolo(int idRuolo, String nomeRuolo) {
        this.idRuolo = idRuolo;
        this.nomeRuolo = nomeRuolo;
    }

    public int getIdRuolo() {
        return idRuolo;
    }

    public void setIdRuolo(int idRuolo) {
        this.idRuolo = idRuolo;
    }

    public String getNomeRuolo() {
        return nomeRuolo;
    }

    public void setNomeRuolo(String nomeRuolo) {
        this.nomeRuolo = nomeRuolo;
    }
}