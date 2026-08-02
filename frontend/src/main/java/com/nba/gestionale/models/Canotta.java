package com.nba.gestionale.models;

public class Canotta {
    private int idCanotta;
    private String giocatore;
    private String squadra;
    private int numero;
    private TipoCanotta tipo; 
    private int anno;
    private double prezzoOriginale;
    private double percentualeSconto;

    public Canotta() {}

    public Canotta(int idCanotta, String giocatore, String squadra, int numero, TipoCanotta tipo, int anno, double prezzoOriginale, double percentualeSconto) {
        this.idCanotta = idCanotta;
        this.giocatore = giocatore;
        this.squadra = squadra;
        this.numero = numero;
        this.tipo = tipo;
        this.anno = anno;
        this.prezzoOriginale = prezzoOriginale;
        this.percentualeSconto = percentualeSconto;
    }

    public int getIdCanotta() { return idCanotta; }
    public void setIdCanotta(int idCanotta) { this.idCanotta = idCanotta; }

    public String getGiocatore() { return giocatore; }
    public void setGiocatore(String giocatore) { this.giocatore = giocatore; }

    public String getSquadra() { return squadra; }
    public void setSquadra(String squadra) { this.squadra = squadra; }

    public int getNumero() { return numero; }
    public void setNumero(int numero) { this.numero = numero; }

    public TipoCanotta getTipo() { return tipo; }
    public void setTipo(TipoCanotta tipo) { this.tipo = tipo; }

    public int getAnno() { return anno; }
    public void setAnno(int anno) { this.anno = anno; }

    public double getPrezzoOriginale() { return prezzoOriginale; }
    public void setPrezzoOriginale(double prezzoOriginale) { this.prezzoOriginale = prezzoOriginale; }

    public double getPercentualeSconto() { return percentualeSconto; }
    public void setPercentualeSconto(double percentualeSconto) { this.percentualeSconto = percentualeSconto; }
}