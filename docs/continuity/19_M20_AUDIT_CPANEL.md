# M2.0 — Audit cPanel autentificat, fără modificări
Data locală: 18–19.10.2026, Europe/Bucharest. Bază Git: 668a2cad7958d1a43b1847e272e921bc1ea61eca.

## FACT — observat în interfața cPanel
- Acces prin https://server.affixhost.co.uk:2083; autentificarea utilizatorului a reușit. Nu se păstrează parole, tokenuri sau URL-uri de sesiune în Git.
- Cont oldmikero, home /home/oldmikero. Domeniul principal oldmike.ro are document root /home/oldmikero/public_html.
- Certificat SSL afișat Active în pagina principală. Nu este încă testată ruta HTTPS a viitorului site.
- Domeniul principal este Not Redirected la nivelul Domains; Force HTTPS Redirect este Off. Nu s-a schimbat.
- Domeniul oldmike.ro rulează PHP8.3 (ea-php83), PHP-FPM activ în MultiPHP Manager.
- Statisticile afișate:44.44MB folosiți din20GB;0din3baze de date folosite. Instrumente File Manager, backup/restaurare, Directory Privacy, MySQL, Terminal și Git prezente; prezența instrumentului nu dovedește toate operațiile permise.
- public_html conține index.php (16.99KB), php.ini (375bytes), plus foldere club.oldmike.ro și meniu.oldmike.ro. Nu s-a intrat în aceste două foldere.
- php.ini citit prin View: logging activ către /home/oldmikero/logs/php.error.log. Nu s-a citit jurnalul.
- MultiPHP INI Editor pentru oldmike.ro: memory_limit128M, upload_max_filesize2M, post_max_size8M, max_execution_time30s, max_input_time60s, max_input_vars1000; display_errors Disabled, log_errors Enabled. Nicio apăsare Apply.

## DECISION — execuție
Site-ul rămâne HTML/CSS/JS portabil, completat cu backend PHP pentru administrare postere și cereri. Nu presupunem extensii PHP, email funcțional sau drepturi de scriere până la verificarea efectivă. Date private și backup în afara document root; nu încărcăm repo integral și nu includem folderele Meniu/Club într-un backup ori deploy recursiv.
Prima instalare va fi izolată de index.php public și protejată pentru evaluare. Protecția și metoda de livrare sunt de pregătit/verificat înainte de upload; noindex singur nu reprezintă control acces.
Limita2MB cere optimizare postere sau o eventuală ajustare strict locală, documentată; nicio modificare acum.
INFERENCE: accesul disponibil pare suficient pentru instalarea site-ului și un backend PHP simplu, fără servicii plătite. Nu există deocamdată un blocaj demonstrat ce necesită administratorul.

## Verificări și limite
Audit vizual/read-only în browser autentificat: domeniu, document root, PHP, limite, inventar public_html. Nicio creare/editare/upload/ștergere; nicio schimbare DNS/SSL/email/PHP. Niciun deploy Vercel sau publicare. Beraria exclus complet, neaccesată. Meniu și Club nu au fost deschise/modificate.
Nu s-a făcut încă backup, nu s-a inspectat sursa index.php, nu s-au validat extensiile PHP, SMTP/livrarea, restaurarea, HTTPS și ruta de test. Browser QA al noului site rămâne de făcut.

## Pași următori
1. Implementare și teste backend PHP (postere, login/admin, cereri și notificări), configurare privată fără secrete în Git.
2. Pachet instalabil minimal și verificări de compatibilitate, backup doar fișierele afectate, plan revenire.
3. Instalare de test izolată, verificare browser desktop/mobil și fluxuri server.
4. Date firmă/sociale/confidențialitate, verificare finală și lansare autorizată distinct.
Checkpoint: commitul GitHub care conține acest document; codul site-ului nu este modificat în acest milestone.
