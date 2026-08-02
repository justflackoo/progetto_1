package com.nba.gestionale.models;

public class TabellaTaglie {
    private int idTaglia;
    private String nomeTaglia;

    public TabellaTaglie() {}

    public TabellaTaglie(int idTaglia, String nomeTaglia) {
        this.idTaglia = idTaglia;
        this.nomeTaglia = nomeTaglia;
    }

    public int getIdTaglia() { return idTaglia; }
    public void setIdTaglia(int idTaglia) { this.idTaglia = idTaglia; }

    public String getNomeTaglia() { return nomeTaglia; }
    public void setNomeTaglia(String nomeTaglia) { this.nomeTaglia = nomeTaglia; }
}