# Lansare oprită: oldmike.ro este pagina grupului — 19.10.2026

## Cerință prioritară USER VALIDATED
La07:12Europe/Bucharest utilizatorul a clarificat că oldmike.ro este pagina grupului, realizată de alt administrator, cu două butoane către locații. Pagina și munca existentă trebuie păstrate intacte. A retras lansarea imediată: «Dacă nu ai apucat să lansezi mai stai puțin să mai continuăm cu ce aveam rămas». Nu publica și nu înlocui pagina grupului.

## Starea reală FACT
Lansarea NU a fost executată. Ultima acțiune de noapte a fost transferul100% al arhivei old-mike-production-m26.zip (~5,25MB) prin cPanelFileUpload în directorul PRIVAT /home/oldmikero/oldmike-site-check-m21. Nu s-a executat unzip pentruM26, nici php install-production.php --check sau instalarea. Nu s-a creat production-config.php, backupM26 sau site la rădăcină prin acest flux. Pachetul și scripturile erau pregătite local, dar încă necomise în GitHub când utilizatorul a intervenit. HEAD înainte de acest checkpoint:723184bf11007fb606bba915c4973d482c88843b(M2.5).

Verificare publică read-only19.10: https://oldmike.ro/ afișează «Old Mike - Alege locația», două locații și pagina grupului, potrivit capturii image(20261019-040806).png. Legătura «Intră pe Old Mike Garden» indică https://garden.oldmike.ro/; Berăria indică propriul subdomeniu. Nu am accesat aceste destinații în verificare.

Site-ul nou rămâne exclusiv în stagingul protejat https://oldmike.ro/oldmike-test/ cu designM2.2 și limita posterelor8MB(M2.5). Phone preview a fost confirmat de utilizator. Utilizatorul a ajuns la o ciornă cu fișier greșit; ștergerea/înlocuirea ciornei lipsește. Nu certificăm încă publicarea/afișarea posterului corect sau uploadul2,5MB dupăM2.5.

## Măsuri luate
server/install-production.php are acum oprire necondiționată înainte de orice acces/modificare la rădăcină. scripts/package_production.py este dezactivat în același mod. Ambele se păstrează în Git doar ca istoric al candidatului greșit, nu sunt un flux de deployment utilizabil.

Arhiva locală a fost redenumită output/RETIRED_DO_NOT_DEPLOY_old-mike-production-m26.zip, iar exemplarul local extras al installerului a fost înlocuit cu cel blocat. IMPORTANT: exemplarul vechi al arhivei transferat pe server rămâne în directorul privat; nu îl extrage/rula. Nu s-a încercat publicare sau curățare pe server după instrucțiunea de oprire.

CandidatulM26 ar fi eliminat formularul public pentru grupuri și ar fi păstrat telefon/email, cu profil de producție separat. Aceste schimbări NU sunt active pe site, iar documentația lor nu trebuie confundată cu lansare reușită.

## Continuare
1. Păstrează oldmike.ro și toate fișierele/configurațiile sale de grup, inclusiv index.php, php.ini și.htaccess; fără modificări pentru instalarea site-ului nou. Berăria rămâne exclusă complet. Meniu Electronic și Club nu se modifică.
2. Continuă testarea posterelor și implementarea ștergerii/înlocuirii ciornelor în proiectul dedicat, cu milestoneGitHub. Nu șterge postere/date ale utilizatorului fără instrucțiune exactă.
3. Stabilește cu utilizatorul destinația separată a restaurantului. garden.oldmike.ro este doar candidat observat în linkul existent; nu presupune că e gol sau că document root-ul este disponibil. Nu crea subdomeniu/DNS și nu atinge această destinație înainte de clarificare/autorizare.
4. După aprobarea destinației, pregătește un installer nou, strict pentru document root-ul confirmat. Actualizează canonical/OG/sitemap/configul, verifică izolarea și backup-ul; apoi cere/folosește autorizarea de publicare specifică destinației. Pagina grupului și linkurile sale vor putea fi gestionate ulterior de administratorul ei, conform utilizatorului.

Restanțe: publicare/ascundere posterHTTP, ștergere/înlocuire ciornă, formular cereri+notificări+politică înainte de activare, date firmă/social, probe detaliate și SEO. Nu declara site-ul lansat sau testele amânate PASS.
