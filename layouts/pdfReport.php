<?php
$htmlReportPage = '
<table style="width: 100%">
    <tr>
        <td valign="top" style="width: 50%;">
        
          <!--  <img src="'/* . dirname(__DIR__, 1) */. '/assets/images/logo.png" alt="FTP Scraper Logo" width="50"/>-->
            
            
        </td>
        <td style="width: 50%;" align="right">
            <h3>FTP Scraper v1.0.0</h3>
            <p style="font-size: small;">
                Sorgente:
                <a href="https://github.com/ax3lfernus/ftp_scraper" style="margin-bottom: 5px;">https://github.com/ax3lfernus/ftp_scraper</a><br/>
            </p>
        </td>
    </tr>

</table>
<hr style="border: 1px solid #000; margin: 10px 0 20px 0;"/>
<table style="width: 100%">
    <tr>
        <td style="width: 50%">
            <strong>Dati utente loggato: </strong>
            <ul style="list-style-type:none;">
                <li style="padding-bottom: 5px;">ID: ' . (isset($_SESSION['id']) ? $_SESSION['id'] : ' ') . '</li>
                <li style="padding-bottom: 5px;">Username: ' . (isset($_SESSION['ftp_vars']['username']) ? $_SESSION['ftp_vars']['username'] : ' ') . '</li>
                <li style="padding-bottom: 5px;">Server: ' . (isset($_SESSION['ftp_vars']['server']) ? $_SESSION['ftp_vars']['server'] : ' ') . '</li>
                <li style="padding-bottom: 5px;">Protocollo: ' . (isset($_SESSION['ftp_vars']['protocol']) ? ($_SESSION['ftp_vars']['protocol']) ? 'FTPS' : 'FTP' : ' ') . '</li>
                <li style="padding-bottom: 5px;">Porta: ' . (isset($_SESSION['ftp_vars']['port']) ? $_SESSION['ftp_vars']['port'] : ' ') . '</li>
                </ul>
        </td>
        <td style="width: 50%" align="right">
			<ul style="list-style-type:none;">
					<li style="padding-bottom: 5px;"><strong>Dati richiesti il:</strong> ' . $request_date . ' GMT</li>
					<li style="padding-bottom: 5px;"><strong>Download terminato il:</strong> ' . gmdate("d-m-Y H:i:s") . ' GMT</li>
					<li style="padding-bottom: 5px;"><strong>Totale file scaricati: </strong> ' . $num_file . '</li>

			</ul>
        </td>
    </tr>
</table>
<hr/>
<strong>Info sul file zip: </strong>
<ul style="list-style-type:none;">
    <li style="padding-bottom: 5px;"><b>Nome:</b> download.zip</li>
    <li style="padding-bottom: 5px;"><b>MD5:</b> ' . $_SESSION['MD5'] . '</li>
    <li><b>SHA256:</b> ' . $_SESSION['SHA'] . '</li>
</ul>';