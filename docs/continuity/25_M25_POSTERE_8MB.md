# M2.5 — Postere de maximum8MB, 19.10.2026

USER VALIDATED: utilizatorul are un poster2,5MB, refuzat de limita1,8MB. A cerut explicit creșterea la8MB pentru test.

## Implementat și aplicat — FACT
server/lib/app.php: dimensiune sursă maximă8.000.000bytes, mesaj8MB. server/admin/index.php: etichetă8MB. Păstrate formateleJPG/PNG/WebP, verificarea uploadului real, decodareaGD, maximum12MP/6000px și re-encodareaJPEG85% cu latura maximă1600px; fișiere private și nume aleatoare.

server/staging.user.ini: upload_max_filesize=8M, post_max_size=10M. Instalată exclusiv în /home/oldmikero/public_html/oldmike-test/.user.ini; nu s-a schimbat configurațiaPHP globală sau a altor aplicații. package_cpanel.py include acest fișier pentru instalările viitoare ale site-ului.

Pachet restrâns old-mike-upload-m25.zip transferat în directorul privat oldmike-site-check-m21; updaterCLI server/update-upload-m25.php cu allowlist3fișiere, hash-uri înainte/după și backup privat. Raport observat în cPanel: «OK M2.5: 3 fisiere pentru limita posterelor actualizate, hash-uri verificate; backup privat creat.»

Verificări: updaterPHP lint PASS;41teste backendCLI PASS, date sintetice fără email sau date producție, PHP8.3.35. Pachet cPanel+checksum-uri PASS local; git diff --check PASS. Acces anonim staging după modificareHTTP401. Designul rămâneM2.2 și nu s-au modificat paginile sau activele vizuale.

## Limită verificare și continuare
Limitele PHP sunt configurate pe director prin.user.ini. PHP-FPM le poate reciti cu întârzierea implicită de300secunde; sursă oficială https://www.php.net/manual/en/configuration.file.per-user.php . Nu am verificat valorile efective dintr-o cerereHTTP autentificată și nici uploadul real al posterului de2,5MB: utilizatorul trebuie să reîncarce administrarea și să reia testul. Dacă persistă eroarea după5minute, se verifică valorilePHP efective și eventualele limite suprascrise pentru subdirector; nu se declară automat problemă de fișier.

Nu sunt activate cererile online sau notificările. Nu există schimbări în producția de la rădăcină, Meniu Electronic, Club, Berăria ori Vercel. Variantele de designM2.3/M2.4 rămân respinse.
