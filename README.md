DataMarker
==============
Webová aplikace k označování vzorů v datech.

Instalace
--------------
Pro běh aplikace je potřeba php aplikační sever a spuštěná databáze postgress. Ověření zda aplikace na serveru poběží je možné spuštěním skriptu *doc/checker/checker.php* přímo na serveru.

V kořenovém adresáři ve složce *doc* je soubor *dump.sql* pro vytvoření databáze. Skript vytvoří postgres databázi s názvem *datamarker*. Databáze 
obsahuje pouze administrátorský účet, ke kterému se je možné ihned přihlásit.

**Administrátorský účet**:
admin / 7hm8bs

Postup instalace:

1. skopírování celé složky *datamarker* do složky webového serveru
2. v administraci postgress databáze spuštění skriptu *doc/dupm.sql* pro vytvoření databáze
3. úprava přihlašovacích údajů k databázi v souboru *config/config.neon* - pokud aplikace běží na produkčním serveru a nebo *config/config.local.neon* - pokud aplikace běží na localhostu

Testovací data
--------------

V adresáři *data* je umístěno několik souborů s testovacími daty. Tyto soubory je možné naimportovat pro přihlášení do administrace.

* 23.points - soubor se vstupními daty, který se importuje do aplikace v administrační částí *Data*.
* 23.points.pdf - vizualizace souboru *23.points*, slouží pro kontrolu.
* 24.points - soubor se vstupními daty, který se importuje do aplikace v administrační částí *Data*.
* 24.points.pdf - vizualizace souboru *24.points*, slouží pro kontrolu.
* names.txt - vstupní soubor, pro dávkové přidávání nových uživatelů. Importuje se v administrační části *Uživatelé*. Formát vstupního souboru je ve tvaru: Jmeno Prijmeni|Email|Pohlavi (m/w)|Vek|Skupina1, Skupina2. Pokud vstupni 
data neobsahuji nektery parametr, je mozne ho vynechat, povine je pouze jmeno uzivatele (Krestni jmeno a Prijmeni).
