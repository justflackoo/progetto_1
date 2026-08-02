### Gestione del campo "Tipo" (Perché un Enum e non una String?)

Nel database, il campo `tipo` della tabella `canotta` è un semplice testo libero (`varchar(20)`). Se avessi usato una classica `String` nel modello Java, chiunque avrebbe potuto inserire valori sporchi per errore (es. "Vintage", "vintage", "Vintag"), causando casini e dati duplicati.

Invece di stravolgere il database o riempire il backend PHP di controlli inutili, ho risolto il problema alla radice nel frontend introducendo l'Enum `TipoCanotta`. 

Lato client vengono accettati esclusivamente le varianti predefinite, bloccando di fatto i dati sbagliati prima ancora di far partire la richiesta HTTP verso il server.
Al momento dell'invio, l'Enum viene convertito in automatico in una stringa pulita che il backend PHP digerisce senza problemi.