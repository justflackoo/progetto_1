package com.nba.gestionale.models;

import java.math.BigDecimal;

public class DettaglioFattura {
    private Integer idDettaglio;
    private Integer idFattura;
    private Integer idGiacenza;
    private Integer quantitaAcquistata;
    private BigDecimal prezzoUnitarioAcquisto;
    private Boolean isAnnullato;

    public DettaglioFattura() {}

    public DettaglioFattura(Integer idDettaglio, Integer idFattura, Integer idGiacenza, Integer quantitaAcquistata, BigDecimal prezzoUnitarioAcquisto, Boolean isAnnullato) {
        this.idDettaglio = idDettaglio;
        this.idFattura = idFattura;
        this.idGiacenza = idGiacenza;
        this.quantitaAcquistata = quantitaAcquistata;
        this.prezzoUnitarioAcquisto = prezzoUnitarioAcquisto;
        this.isAnnullato = isAnnullato;
    }

    public Integer getIdDettaglio() { return idDettaglio; }
    public void setIdDettaglio(Integer idDettaglio) { this.idDettaglio = idDettaglio; }

    public Integer getIdFattura() { return idFattura; }
    public void setIdFattura(Integer idFattura) { this.idFattura = idFattura; }

    public Integer getIdGiacenza() { return idGiacenza; }
    public void setIdGiacenza(Integer idGiacenza) { this.idGiacenza = idGiacenza; }

    public Integer getQuantitaAcquistata() { return quantitaAcquistata; }
    public void setQuantitaAcquistata(Integer quantitaAcquistata) { this.quantitaAcquistata = quantitaAcquistata; }

    public BigDecimal getPrezzoUnitarioAcquisto() { return prezzoUnitarioAcquisto; }
    public void setPrezzoUnitarioAcquisto(BigDecimal prezzoUnitarioAcquisto) { this.prezzoUnitarioAcquisto = prezzoUnitarioAcquisto; }

    public Boolean getIsAnnullato() { return isAnnullato; }
    public void setIsAnnullato(Boolean isAnnullato) { this.isAnnullato = isAnnullato; }
}