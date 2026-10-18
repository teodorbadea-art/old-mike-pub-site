# Instalare de test M2.1

Pachet generat: output/old-mike-cpanel-m21-test.zip, prin scripts/package_cpanel.py. Aproximativ5.2MB;60fișiere și manifest SHA256. Nu se încarcă repository-ul întreg.

1. Se încarcă/extrage pachetul în afara public_html, într-un dosar dedicat verificării. Nu se rulează recursiv operații pe public_html.
2. Se verifică inexistența /home/oldmikero/public_html/oldmike-test. Se copiază strict conținutul public/ în această zonă nouă; .htaccess începe cu Require all denied. Nu se schimbă index.php sau php.ini existente, nici folderele Meniu/Club.
3. Înainte de configurare se verifică blocarea HTTP403 a noii zone. Dacă serverul nu aplică protecția, se oprește instalarea și nu se activează accesul.
4. Se verifică sintaxa setup.php și se rulează MANUAL din terminal, în afara public_html. Utilizatorul alege numele și parola; agentul nu citește/introduce/se păstrează parolele. Setup nu suprascrie config existentă. Creează configurația privată0600 și parola hash, plus Basic Auth pentru întregul staging. Nu folosește autentificarea cPanel pentru site.
5. După configurare se verifică HTTP401 anonim, accesul autenticat și refuzul configurației/date private. Cererile și emailurile rămân dezactivate. Se testează administrarea cu postere fictive, inclusiv imagini nevalide, acces neautorizat și expirare.
6. Activarea cererilor necesită completarea operatorului și politicii de confidențialitate, stabilirea păstrării/ștergerii datelor, teste HTTP end-to-end și testarea notificării office. Nu se interpretează mail accepted drept livrat.

Lansarea finală este separată: backup doar fișiere afectate, pregătire index.php/DirectoryIndex și revenire, HTTPS local și cache verificate, date business, politica finală, probe mobil/browser și indexare. Basic Auth staging nu se elimină implicit. Nicio comandă din acest ghid nu modifică Berăria sau celelalte aplicații.

Limite: stocare JSON cu lock separat și rename atomic (volum mic,2000cereri/500evenimente; nu este CRM). Nu există încă administrare utilizatori multipli/resetare parolă, automatizare postere sau livrare SMTP dedicată. Posterele sunt decodate și re-encodate JPEG, maximum8MB sursă/12MP/1600px (M2.5; PHP upload8M/post10M în directorul site-ului); fără nume arbitrare ori SVG. Păstrarea datelor și eventualele retransmiteri după rezultat email incert trebuie rezolvate explicit înainte de activare.
