# M2.3 — Meniu în rânduri compacte, 19.10.2026

## Cerință și decizie
USER VALIDATED: imaginile mari în grilă, două alăturate, făceau pagina Meniu să pară album; capturile image(20261018-220914).png și image(20261018-221043).png arată problema la zoom100%. Utilizatorul a aprobat reorganizarea propusă în rânduri compacte, fotografie lângă titlu/descriere/acces la meniu, alternare discretă pe desktop și imagini de dimensiune controlată pe mobil.

## Implementare locală — FACT
- Șase categorii cu aceeași structură: Burgeri, Pizza, Platouri, Grătar de vită, Deserturi, Băuturi.
- Eliminată secțiunea supradimensionată a burgerului și grila de două fotografii mari. Helper comun menu_category în scripts/build_pages.py.
- Desktop: listă centrată maximum1120px, fotografie maximum510px, titlu/descriere/link în coloana alăturată. Poziția fotografiei alternează; ordinea semantică rămâne constantă.
- Mobil: o coloană, fotografie maximum360px, proporții3:2 fără crop; textul imediat sub ea. Fără animații sau dependențe noi.
- Margini estompate prin CSS; păstrate toate cele șase imagini existente, bucătarii miniaturali, burgerul simplu și cartofii prăjiți la vită. Nicio regenerare de imagini.
- Linkul pe fiecare rând este «Vezi preparatele și prețurile» și deschide aceeași aplicație Meniu Electronic. Ancorele și butonul principal de acces la meniul complet rămân.
- Intro-ul cu fotografie M2.2 și celelalte pagini își păstrează compoziția. Versiunea site-ului și a fișierelor autonome de evaluare: M2.3.

## Verificări efectuate — FACT
python3 tests/validate_site.py PASS: 8 pagini, resurse/ancore, metadate, JSON-LD, fonturi/logo, active culinare, hartă, review-uri; 4 imagini cinematice ale spațiilor păstrate, cover-uri responsive și compoziție Acasă păstrate.

python3 scripts/package_cpanel.py și python3 tests/package_cpanel.py PASS: arhivă, checksum-uri, resurse, excludere secrete și integrare runtime. Probă structurală: exact6rânduri cu ancorele în ordinea burgeri/pizza/platouri/gratar/deserturi/bauturi, fără menu-feature. git diff --check PASS.

Nu s-a făcut QA vizual în browser: mediul nu are control-browser pentru preview local, iar stagingul protejat Basic nu poate fi deschis automat. Aceste verificări nu confirmă încă aspectul pe dispozitivele utilizatorului.

## Starea găzduirii — BLOCKED
IMPORTANT: M2.3 NU este aplicat pe cPanel. Stagingul rămâne M2.2. Tentativa de upload prin cPanel File Upload a fost refuzată de dialogul de permisiuni; instrumentul a raportat că utilizatorul a refuzat aprobarea. Nu ocoli refuzul prin terminal, altă cale de transfer sau API. Reluarea încărcării necesită autorizare nouă a utilizatorului.

Pachetul restrâns este pregătit local în output/old-mike-visual-m23.zip. Include doar7HTML și assets/site.css, manifestul before/after și updaterul CLI server/update-visual-m23.php. Pe celelalte6pagini HTML diferența este versiunea; corecția vizuală privește Meniu. Nu transferă imagini, backend, .htaccess, configurație, parole sau date. Destinația updaterului este exclusiv /home/oldmikero/public_html/oldmike-test; verifică hash-uri înainte/după și creează backup privat.

Reproducere: python3 scripts/build_pages.py, python3 scripts/package_cpanel.py, python3 scripts/package_visual_m23.py. Manifestul fix al patchului este docs/launch/M23_PATCH.json; nu adapta hash-urile «before» pentru a forța suprascrierea unei versiuni necunoscute.

## Continuare
După autorizarea uploadului: încărcare în directorul privat oldmike-site-check-m21; extragere visual-m23; php -l visual-m23/update-visual-m23.php; aplicare CLI cu raport privat, verificare rezultat8fișiere și HTTP401 anonim. Nu declara aplicat până la observarea raportului.

Apoi evaluare desktop100% și mobil, testarea încărcării/publicării posterelor. Cererile online și emailurile rămân inactive; lansarea principală nu este inclusă. Meniu Electronic, Club și Berăria nu au fost modificate.
