# M2.4 — Meniu, trei perechi compacte, 19.10.2026

## Cerință și evaluare
USER VALIDATED: utilizatorul a evaluat M2.3 din fișierul autonom și a respins golurile mari; capturile image(20261018-222249).png și image(20261018-222444).png documentează problema. A autorizat o încercare cu perechi compacte, fără lista repetitivă și fără decalajele verticale. M2.3 nu este design aprobat.

FACT: codul M2.3 rezerva coloane neocupate, limita separat lățimile imaginilor și cobora Pizza/Desert cu90/76px. Aceste reguli sunt eliminate din stilul curent al compoziției.

## Implementare locală
- Perechi Burgeri/Pizza, Platouri/Grătar, Deserturi/Băuturi. Grilă20coloane, ocupată integral de fiecare pereche:11+9,9+11,11+9. Nicio coloană intermediară rezervată golului.
- Lățime maximă1240px, distanțe28px între coloane și perechi;18/22px pe desktop îngust. Toate categoriile pornesc la aceeași înălțime în pereche, fără margin-top individual.
- Fotografiile sunt limitate la205–255px înălțime CSS pe desktop, object-fit:contain pentru a păstra produsul și bucătarii integral. Nu se întind și nu se decupează. Margini estompate în fundal.
- Titluri30–40px, descriere15px și link14px cu suprafață de interacțiune44px, imediat sub fotografie. Înălțimea rezervată imaginilor este egală în fiecare pereche; titlurile se aliniază.
- Mobil: o coloană în ordinea semantică, fotografii190–270px, descriere16px și titlu34px. Fără scroll orizontal intenționat sau animații.
- Butonul principal și ancorele categoriilor păstrate; banda de acces compactată. Cele șase imagini sunt identice; burger simplu, bucătari și vită cu cartofi prăjiți păstrate. Acasă, intro-urile M2.2 și galeria nu sunt reproiectate.
- VersiuneM2.4. Fișierul autonom desktop se deschide direct în Meniu pentru această evaluare; și previzualizarea mobilă este regenerată.

## Verificări FACT
python3 tests/validate_site.py PASS:8pagini, resurse/ancore, metadate/JSON-LD, fonturi/logo, imagini, hartă, review-uri; cele4spații cinematice și cover-urile păstrate. python3 scripts/package_cpanel.py și python3 tests/package_cpanel.py PASS:arhivă, checksum-uri, resurse și configurare izolată. git diff --check PASS.

Limita verificării: nu există QA vizual automat în browser pentru această variantă. Capturile observate suntM2.3; M2.4 este o încercare pentru evaluare, nu se declară aprobată sau verificată pe laptopul utilizatorului.

## Stare și continuare
NU a fost încărcată pe cPanel. Stagingul rămâneM2.2. Uploadul precedent a fost refuzat de dialogul browserului; nu ocoli refuzul. Utilizatorul a autorizat aici încercarea de design și previzualizarea, nu a confirmat explicit reluarea încărcării blocate.

Pachet M2.2→M2.4 pregătit local: output/old-mike-visual-m24.zip; doar7HTML+CSS, manifest before/after, updaterCLI cu destinație fixăoldmike-test, backup privat și verificare hash-uri. Configurația/.htaccess/backend/datele nu sunt incluse. Reproducere după build și package_cpanel: python3 scripts/package_visual_m24.py. Înainte de aplicare se verifică sintaxa PHP pe găzduire; nu s-a făcut încă această verificare.

Urmează evaluarea vizuală a încercării M2.4, apoi autorizarea/reluarea uploadului, verificarea instalării șiHTTP401. Testul posterelor, formularul online/emailurile, datele firmei/social și lansarea principală rămân în plan. Nicio schimbare la Meniu Electronic, Club, Berăria sau Vercel.
