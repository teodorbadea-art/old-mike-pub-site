# M2.1 — Backend PHP și instalare de test blocată

Data19.10.2026, Europe/Bucharest. Bază fa73fcc2bb255358c9bc2028fcaf6ae52440ffd5. Designul M1.7.1 este păstrat.

## Implementat

- PHP8.3 fără framework, servicii plătite sau DB comună cu celelalte aplicații. Stocare JSON privată cu lock separat, scriere temporară și rename atomic; audit limitat fără PII, tokenuri de cerere și protecție la retrimitere.
- Administrare cu parolă hash, sesiune HTTPS/Secure/HttpOnly/SameSiteStrict, expirare inactivitate30minute/absolut8ore, CSRF, limită încercări și ieșire. Un singur cont în prima versiune, nu modul de roluri.
- Postere: titlu, categorie, început/încheiere București, upload JPG/PNG/WebP verificat și re-encodat JPEG; ciornă, publică, ascunde. Evenimentele încheiate nu sunt expuse de API. Postere private servite controlat; ciorne accesibile numai administratorului.
- Cereri: validare server, email opțional, salvare înaintea notificării, stare nouă/client contactat/închisă. Referință stabilă la retry, fără dublare. Formularul nu confirmă rezervarea. Notificarea către office conține doar referința opacă și linkul administrării; mail accepted nu înseamnă delivered. Fără retry automat în rezultate incerte.
- Formularul server și afișarea evenimentelor sunt activate doar în pachetul cPanel prin site-runtime.js. Previzualizările locale rămân inerte. Formularul se blochează cu mesaj și telefon dacă API este dezactivat/indisponibil.
- Pachet selectiv:60fișiere+manifest,5,203,108bytes ZIP; include share image/font license, exclude demo administrare și toate datele/configurațiile reale. Setup CLI și installer rămân în afara public_html. Ambele servicii de cereri/email dezactivate implicit.

## FACT — executat și observat

1. Teste statice existente PASS:8pagini, resurse/ancore, metadate, imagini/logo, fonturi, hartă; cele4spații generate/responsive păstrate;10cazuri program/DST/miezul nopții și regresii formular.
2. Package checks PASS: hash-uri, arhivă, configurație separată, deny-all inițial, selecție resurse, formular server numai în pachet. Node syntax PASS pentru site-runtime.js, Python compile PASS pentru packager; git diff --check PASS.
3. Pe găzduire, PHP CLI8.3.35: `PASS: 41 backend checks; synthetic data only; no mail or production data.` GD disponibil confirmat de test. Raport observat în cPanel View result.txt. Date fictive temporare șterse de finally în cadrul testului.
4. `php -l` PASS pentru toate cele7fișiere PHP ale pachetului final, inclusiv installer și setup. Raport candidate-lint.txt observat în cPanel.
5. Pachete încărcate/extrase numai în /home/oldmikero/oldmike-site-check-m21. Installerul verifică manifestul și refuză suprascrierea unui staging existent.
6. Copiat strict public/ în NOUL /home/oldmikero/public_html/oldmike-test; .htaccess Require all denied creat primul. Raport: OK, acces blocat, site principal nemodificat. Test HTTPS prin curl pe găzduire: HTTP403, observat în gate.txt.

Nu au fost modificate index.php/php.ini existente sau setări comune. Nu au fost deschise/modificate folderele Meniu/Club; Berăria complet neaccesată. Niciun deploy/build Vercel, DNS sau lansare pe pagina principală.

## Checkpoint de reluare

- cPanel autentificat în browser; terminalul se află în ~/oldmike-site-check-m21.
- Pachetul final extras în candidate/. Scriptul `php candidate/setup.php` este pregătit, verificat sintactic, DAR NECONFIGURAT la momentul acestui document.
- Pasul următor cere utilizatorului să aleagă personal utilizator/parolă în terminal, fără transmitere în chat și fără ca agentul să citească/introducă parola. Setup creează config privată0600 și hash bcrypt; protejează staging prin HTTPBasic și folosește același cont pentru administrare. Nu suprascrie config existentă. Parola nu este identică implicit cu parola cPanel.
- Înainte de executarea setup: blocaj403 confirmat. După configurarea de utilizator: verifică401 anonim și accesul autorizat; păstrează cereri/email inactive. Dacă utilizatorul întrerupe configurarea, verifică numai semnale publice/stare non-secretă, nu citi config/htpasswd.
- Commitul cu acest document este milestone-ul GitHub. Runtime public păstrează eticheta design M1.7.1; backend/instalare M2.1 sunt separate explicit.

## Ce NU este verificat / ce rămâne

- Nu s-au făcut teste HTTP/browser complete pentru login, uploaduri valide/nevalide, sesiune/CSRF prin HTTP, UI și cereri. Testele CLI nu sunt browser QA și nu demonstrează livrarea emailului.
- Nu s-a activat configurarea reală, nu s-a trimis email și nu s-au colectat cereri reale. Politica actuală de confidențialitate descrie încă prototipul; nu activa requests_enabled până la operator real, termeni/păstrare/ștergere, text și teste completate.
- Nu există încă retenție/ștergere automată, resetare parolă ori utilizatori multipli; limita2000cereri/500evenimente este pentru volum mic. Rezolvă procesul de retenție înainte de activare. JSON nu este substitut pentru CRM la volume mari.
- Revizuiește inscripțiile AI/fidelitatea imaginilor și datele business finale; QA mobil/desktop/tastatură/Lighthouse rămân de făcut.
- La lansarea finală sunt necesare backup strict al fișierelor înlocuite, plan de revenire, HTTPS/cache/404 și indexare; fără operații recursive pe public_html care ar include alte aplicații.

Ghid de instalare: ../launch/INSTALL_TEST_M21.md. Sursă tehnică primară consultată pentru sesiuni: https://www.php.net/manual/en/session.security.ini.php . Nicio afirmație despre livrare/ranking sau certificare juridică.
