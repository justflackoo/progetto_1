package com.nba.gestionale.models;

import java.math.BigDecimal;

public class Fattura {
    private Integer idFattura;
    private Integer idCliente;
    private String dataAcquisto; // Utilizziamo String per semplicità di mapping JSON iniziale
    private BigDecimal totale;
    private Integer stato;

    public Fattura() {}

    public Fattura(Integer idFattura, Integer idCliente, String dataAcquisto, BigDecimal totale, Integer stato) {
        this.idFattura = idFattura;
        this.idCliente = idCliente;
        this.dataAcquisto = dataAcquisto;
        this.totale = totale;
        this.stato = stato;
    }

    public Integer getIdFattura() { return idFattura; }
    public void setIdFattura(Integer idFattura) { this.idFattura = idFattura; }

    public Integer getIdCliente() { return idCliente; }
    public void setIdCliente(Integer idCliente) { this.idCliente = idCliente; }

    public String getDataAcquisto() { return dataAcquisto; }
    public void setDataAcquisto(String dataAcquisto) { this.dataAcquisto = dataAcquisto; }

    public BigDecimal getTotale() { return totale; }
    public void setTotale(BigDecimal totale) { this.totale = totale; }

    public Integer getStato() { return stato; }
    public void setStato(Integer stato) { this.stato = stato; }
}