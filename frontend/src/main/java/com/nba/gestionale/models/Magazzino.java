package com.nba.gestionale.models;

public class Magazzino {
    private int idGiacenza;
    private int idCanotta;
    private int idTaglia;
    private int quantitaDisponibile;

    public Magazzino() {}

    public Magazzino(int idGiacenza, int idCanotta, int idTaglia, int quantitaDisponibile) {
        this.idGiacenza = idGiacenza;
        this.idCanotta = idCanotta;
        this.idTaglia = idTaglia;
        this.quantitaDisponibile = quantitaDisponibile;
    }

    public int getIdGiacenza() { return idGiacenza; }
    public void setIdGiacenza(int idGiacenza) { this.idGiacenza = idGiacenza; }

    public int getIdCanotta() { return idCanotta; }
    public void setIdCanotta(int idCanotta) { this.idCanotta = idCanotta; }

    public int getIdTaglia() { return idTaglia; }
    public void setIdTaglia(int idTaglia) { this.idTaglia = idTaglia; }

    public int getQuantitaDisponibile() { return quantitaDisponibile; }
    public void setQuantitaDisponibile(int quantitaDisponibile) { this.quantitaDisponibile = quantitaDisponibile; }
}