# Web-Based Ftp Scraper
Un semplice Scraper FTP scritto in PHP e completamente Web!

## Installazione
### Requisiti: 
1. `PHP >= 7.4.x`
    * Modifiche al file `php.ini`:
        * Valore di `max_execution_time` impostato a `0`
        * Estensione `ftp`
        * Estensione `gd`
        * Estensione `mbstring`
3. [Composer](https://getcomposer.org/)
4. [Git](https://git-scm.com/downloads) (Facoltativo)

### Windows
1. Installare un WebServer (Es: [IIS](https://www.microsoft.com/en-us/download/details.aspx?id=48264), [XAMPP](https://www.apachefriends.org/download.html), [UwAmp](http://www.uwamp.com/en/), [WampServer](https://www.wampserver.com/en/)...)
2. Scaricare l'ultima versione di [FTPScraper](https://github.com/Ax3lFernus/FTP_Scraper/releases/latest)
3. Estrarre la cartella compressa
4. Eseguire, all'interno della root della cartella, il comando `composer install`
5. Spostare l'intero contenuto della cartella all'interno del WebServer
6. Recarsi tramite il browser all'url o all'IP del WebServer per avviare il tutto.

### Linux
1. Installare un WebServer (Es: Apache)
2. Posizionarsi nella cartella del WebServer ed eseguire il comando `git clone https://github.com/ax3lfernus/FTP_Scraper`
![Uso del comando git clone](/../screenshots/screenshots/terminal_1.png?raw=true "Uso del comando git clone")
4. Posizionarsi all'interno della cartella FTP_Scraper `cd FTP_Scraper`
6. Eseguire, all'interno della root della cartella scaricata, il comando `composer install`
![Uso del comando composer install](/../screenshots/screenshots/terminal_2.png?raw=true "Uso del comando composer install")
8. Recarsi tramite il browser all'url o all'IP del WebServer per avviare il tutto.

## Utilizzo del tool
### Accesso al server FTP
Aprire un Web Browser (es. Google Chrome) e recarsi all'url o all'IP del WebServer (es. http://127.0.0.1)
![Collegamento all'interfaccia Web](/../screenshots/screenshots/front_end_1.png?raw=true "Collegamento all'interfaccia Web")
Inserire i dati per accedere al server FTP:
1. Tipologia di connessione FTP/FTPS
2. Username
3. Indirizzo del server
4. Password
5. Numero di porta (quella predefinita è la 21)
Una volta inseriti i dati, cliccare sul pulsante "Accedi al server"
### Selezione delle cartelle
Effettuato il login, si presenterà l'interfaccia sottostante:
![Interfaccia Web del programma](/../screenshots/screenshots/front_end_2.png?raw=true "Interfaccia Web del programma")
Tramite la sezione "Seleziona i file" è possibile selezionare i file o le cartelle tramite le checkbox presenti su ogni riga, cercare tra i file visualizzati e navigare rapidamente nelle varie sotto-directory:
![Selezione dei file e delle estensioni](/../screenshots/screenshots/front_end_3.png?raw=true "Selezione dei file e delle estensioni")
Una volta selezionati i file è possibile scegliere i filtri presenti nella sezione "Seleziona se vuoi determinati tipi di file". Se l'estensione voluta non è presente, è possibile inserire una o più estensioni manuali separate da virgola all'interno della textbox sottostante.
Per far partire il download, è sufficiente cliccare sul pulsante "Download".
### Fase di download
Durante le operazioni di download sarà mostrata una barra di caricamento:
![Caricamento](/../screenshots/screenshots/front_end_4.png?raw=true "Caricamento")
A download completato, verranno mostrati i doppi hash calcolati sullo zip finale e i link per scaricare il file zip, il file di log e il report in formato PDF:
![Schermata di download completato](/../screenshots/screenshots/front_end_5.png?raw=true "Schermata di download completato")
### File di log
Il file di log che viene scaricato conterrà la lista di tutte le operazioni effettuate da quando ci si logga all'interno del server FTP
![Esempio file di log](/../screenshots/screenshots/log.png?raw=true "Esempio file di log")
### Report
Nel report sono presenti i dati non sensibili del server FTP, il timestamp di quando il sistema ha iniziato e finito il download con il numero di file scaricati e le info sul file zip.
![Esempio di report](/../screenshots/screenshots/report.png?raw=true "Esempio di report")


