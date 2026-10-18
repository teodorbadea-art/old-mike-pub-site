# M2.2 — Introduceri cinematice, 19.10.2026

## Cerință confirmată
USER VALIDATED: prima vedere în Meniu, Galerie și Evenimente era prea goală. Fotografia trebuie integrată în jumătatea dreaptă, cu fade spre fundalul negru din stânga; descrierea mică albă peste fotografie, titlul pe zona întunecată. Captura utilizatorului image(20261018-215932).png delimitează exact zona. Contact primește aceeași familie vizuală. Acasă păstrează compoziția aprobată, statică, cu burger simplu și doi bucătari.

## Implementat
- Helper comun page_cover în scripts/site_helpers.py; patru pagini folosesc aceeași structură semantică și imagini responsive eager/high priority.
- Meniu: imagine nouă generată, masă cu burger simplu, pizza, platou de grătar cu cartofi prăjiți și bere fără marcă; fără produse cu două patties sau cartofi la cuptor. Categoriile cu bucătari miniaturali rămân intacte.
- Galerie: atmosfera-gasca-v1; Evenimente: atmosfera-meci-v1; Contact: atmosfera-terasa-v1. Scenele sunt generate, nu fotografii documentare cu clienți.
- CSS desktop: fotografie spre dreapta, gradient negru în stânga și spre marginea inferioară, descriere peste imagine. Mobil: imagine sus, trecere verticală spre titlu și descriere; fără mișcarea fotografiei.
- Contact: datele practice sunt sub introducere; harta, programul și formularul sunt păstrate.
- Versiune M2.2; preview-urile autonome regenerate. Scriere atomică a fișierelor de evaluare pentru a evita fișiere intermediare goale.

## Aplicare cPanel — FACT
Actualizat exclusiv /home/oldmikero/public_html/oldmike-test prin server/update-visual-m22.php, rulat CLI din director privat. Patch limitat la 11 fișiere vizuale, cu verificarea hash-urilor înainte/după, backup privat și înlocuire atomică. Raport observat în cPanel: «OK M2.2: 11 fisiere vizuale actualizate, hash-uri verificate; backup privat creat. Protectia si backend-ul sunt pastrate.»

Sintaxă PHP updater PASS pe găzduire. Acces anonim după actualizare: HTTP401. Nu s-au modificat .htaccess, configurația, administrarea, API-ul, datele, parola, pagina principală de producție, Meniu Electronic, Club sau Berăria. Niciun build/deploy Vercel.

## Verificat
- python3 tests/validate_site.py PASS: 8 pagini, resurse, ancore, metadate, JSON-LD, fonturi, logo, imagini, hartă, review-uri; cele patru spații cinematice v2 păstrate; patru cover-uri și compoziție Acasă neschimbată.
- node tests/hours-and-form.cjs PASS: 10 cazuri program/DST/miezul nopții și regresii formular.
- python3 tests/package_cpanel.py PASS: arhivă, checksum-uri, resurse și configurare izolată.
- git diff --check PASS.
- Utilizatorul a confirmat accesul la staging și deschiderea linkurilor înainte de patch.

## Limite și pași următori
Nu declarăm QA vizual în browser PASS: browserul automat nu poate deschide autentificarea Basic a stagingului, iar capturile primite sunt versiunea precedentă. Nu elimina protecția pentru verificare. Utilizatorul poate reîncărca pagina deja deschisă pentru evaluarea M2.2.

Încărcarea posterelor, salvarea ciornei, publicarea și ascunderea trebuie verificate prin interfața administrării /oldmike-test/admin/. Testele PHP CLI M2.1 nu înlocuiesc aceste probe HTTP. Cererile pentru grupuri și emailurile rămân inactive. Înainte de producție: teste HTTP, date firmă/social, politica de confidențialitate finală, notificări, verificare mobil și plan de revenire. Nu publica în producție implicit.
