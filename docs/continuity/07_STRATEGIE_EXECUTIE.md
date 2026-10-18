# M0.1 — Strategie de execuție Old Mike Pub

**SUPRASEDAT PARȚIAL prin M0.2:** `08_DIRECTIE_VALIDATA_SI_NAVIGARE.md` înlocuiește homepage-ul cu secțiuni culinare, planul pe straturi în mișcare și structura într-o singură pagină. Static hiperrealist este baza; Meniu este pagină vizuală proprie; Evenimente are upload. Restul documentului păstrează istoricul propunerii și regulile de hosting/costuri.

Data: 18.10.2026. Scope: strategie, resurse, instrumente și continuitate. Nicio implementare UI, generare de imagini, instalare de aplicații sau publicare în acest milestone.

## 1. Cerințe noi, validate de utilizator

- Găzduire finală pe cPanel-ul existent, la `oldmike.ro`, fără dependență Vercel sau plăți suplimentare. GitHub rămâne sursa versiunilor și documentației.
- `beraria.oldmike.ro` este complet exclus: nu se accesează și nu se modifică site-ul, fișierele sau setările lui; nu se aplică schimbări globale contului.
- Utilizatorul are experiență web zero și dorește să învețe. Explică fiecare pas prin scop, rezultat și acțiunea necesară lui, fără jargon. Nu îi delega alegerile tehnice de rutină.
- Eficiență: o singură direcție recomandată, susținută de exemplele existente; prezentare completă desktop/mobil/mișcare, feedback grupat. Nu promite absența oricărei iterații, dar evită serii de experimente fără obiectiv.

## 2. Strategia comercială propusă

Obiectiv: vizitatorul înțelege rapid ce oferă localul, vrea să vină și poate deschide meniul sau suna. Ipotezele de mesaj de mai jos sunt raționament de design, nu rezultate demonstrate prin teste de conversie.

| Situația vizitatorului | Ce îi arătăm | Acțiune firească |
|---|---|---|
| Caută unde să mănânce | Preparat apetisant și acces direct la meniul real | Vezi meniul |
| Organizează o ieșire cu prietenii | Mâncare de împărțit, bere, fotografii autentice ale spațiului | Rezervă telefonic |
| Caută unde să vadă un meci | Atmosferă și facilități de vizionare confirmate | Rezervă; fără calendar neactualizat |
| Este client și poate reveni | Acces vizibil la Old Mike Club | Deschide Clubul |

Ierarhie: poftă → încredere în locul real → acțiune simplă. Rezervările și meniul sunt principale; Clubul este accesibil, fără să preia primul ecran. Nu inventa popularitate, testimoniale, reduceri, capacitate, metode de preparare sau program sportiv.

## 3. Direcție de artă recomandată, încă de prezentat

Punct de plecare: **M06** (`Old Mike Pub: Mâncare, Bere și Meciuri(1).png`), simplificat pentru direcția minimalistă. Alegerea este recomandarea curentă a asistentului, nu o aprobare istorică recuperată. Păstrează convențiile și materialele deja utile din cod, dar aliniază compoziția cu referințele.

- Scenă culinară amplă: burger, cartofi și bere, cu lumină caldă și profunzime. În zona de text trebuie să existe spațiu și contrast.
- Fundal închis în primul ecran, auriu/amber moderat, crem și secțiuni mai luminoase ulterior. Fără suprasaturare și aglomerare de ornamente.
- Logo oficial separat de fundal, integral. Titlu scurt și text real în HTML, nu desenat în imagine. Alegerea fonturilor urmează să fie verificată vizual.
- Două acțiuni principale: „Vezi meniul” și „Rezervă o masă”. Propunere de mesaj: „Mâncare bună. Oameni faini. Seri de ținut minte.” Nu este copy final aprobat.
- Mobilul are compoziție proprie: text lizibil, produs dominant și butoane ușor de atins; nu micșora mecanic captura desktop.

### Structura recomandată a paginii

1. Hero — prima scenă: identitate, preparat, două acțiuni.
2. Selecție scurtă de preparate — 3–4 imagini relevante, legate de meniul existent; nu catalog duplicat cu prețuri de întreținut în două locuri.
3. Atmosfera Old Mike — fotografii reale ale interiorului și terasei; fără a înlocui localul cu unul fictiv.
4. Seri cu prietenii / sport — mesaj durabil, fără a necesita actualizare la fiecare meci. Evenimentele cu date se adaugă doar cu responsabil de actualizare stabilit.
5. Old Mike Club — explicație scurtă și link, fără copierea logicii programelor/ofertei.
6. Contact — telefon, program și adresă validate, link hartă, social media confirmate.

## 4. Cum obținem mișcarea din exemple

Capturi recuperate și revăzute în M0.1:

- `275BCAB0-7573-45BE-BB72-708799D9EF0C.jpeg`: pizza cu ingrediente suspendate, referință de efect culinar.
- `A973C68C-C34A-492B-AD81-5918640FFE61.jpeg`: prezentare burger într-o scenă cu profunzime și etichetă; screenshot dintr-un reel, nu un model 3D utilizabil.
- Alte capturi includ paste, sosuri și comparații „Original / Omni Flash”. Nu sunt clipuri originale sau active de publicat.

Plan principal: fotografie/elemente decupate în straturi, cu mișcare controlată la intrare și la derulare. De exemplu: produsul apare ușor, câteva elemente se așază, fundalul se mișcă mai lent, textul intră discret. O singură scenă vedetă, apoi secțiuni liniștite. Nu bloca accesul la butoane până se termină animația. Include variantă cu mișcare redusă.

Limita tehnică explicită: straturile foto nu produc singure turnare realistă de sos, topire, abur natural sau transformarea formei alimentului. Pentru acel tip de mișcare este necesar un clip real/generat verificat. Nu promite echivalență între o imagine animată prin CSS și un video culinar realist.

Un eventual clip este opțional și local, scurt, fără sunet, cu imagine de rezervă; nu se bazează pe player extern sau abonament. Nu este condiție pentru prima propunere. Dacă accesul existent nu oferă un export adecvat și utilizabil comercial fără cost nou, păstrăm planul pe straturi.

## 5. Instrumente și decizia privind aplicațiile

| Necesar | Alegere curentă | Conectare / cost nou |
|---|---|---|
| Design și cod | HTML/CSS/JavaScript, imagini optimizate, animații în browser | Nu |
| Imagini | Materialele reale și instrumentele de imagine deja disponibile în sesiune, dacă este necesar | Nu se solicită cont/abonament nou; limitele efective nu sunt presupuse nelimitate |
| Verificare | Browser local, capturi desktop/mobil și audit de performanță | Fără serviciu nou obligatoriu |
| Versionare | Repository GitHub existent | Nicio integrare nouă |
| Publicare | cPanel existent | Acces de reverificat, fără modificări globale |
| Flair.ai | Nu îl includem în fluxul principal | Nu conectăm și nu cumpărăm |
| Lovable / Canva | Excluse conform utilizatorului | Nu folosim |
| Vercel | Deployment anterior păstrat doar ca istoric | Nu creăm deployment-uri noi |

Flair.ai este o posibilă interpretare a numelui „flair”; istoricul nu a confirmat că aceasta era exact aplicația menționată. Verificare publică 18.10.2026: pagina de pricing listează free cu 5 imagini generate și 1 generare video; pagina de licențiere leagă licența comercială pentru companii de planuri plătite. Termenii publici au formulări neuniforme privind free/comercial. Nu bazăm proiectul comercial cu buget zero pe această neclaritate.

Surse primare consultate: https://flair.ai/pricing , https://flair.ai/license , https://flair.ai/terms-of-service . Acestea sunt informații publice, nu o verificare a contului utilizatorului.

## 6. Necesar de materiale

| Material / informație | Avem | Acțiune |
|---|---|---|
| Direcție și exemple | 7 mock-up-uri + capturi culinare | Folosim M06 ca bază recomandată, cu motion definit mai sus |
| Imagini locale | 10 fotografii reale | Selecție editorială; verificare sursă și export, fără a inventa un local diferit |
| Fotografii preparate | 5 imagini AI existente; surse suplimentare în meniul electronic | Recuperare read-only a originalelor produselor alese; fidelitate înainte de publicare |
| Logo oficial | Fișier sursă identificat în repository-ul meniului | Recuperare și copie identică, fără a modifica meniul |
| Hero master pentru site | Nu există încă un export final aprobat | Pregătim un master și crop separat pentru mobil din surse potrivite |
| Telefon, link meniu, link Club | Confirmate | Integrare directă |
| Program, adresă exactă, hartă și social media | Nevalidate complet | Recuperare din surse business disponibile, apoi o singură listă scurtă de confirmări utilizatorului |
| Clipuri originale pentru efecte organice | Nerecuperate | Opțional, nu blocant pentru prima versiune |
| Acces cPanel actual | Acces relatat din audit anterior; sesiune actuală neverificată | Verificare read-only la etapa de hosting; autentificare asistată dacă e necesară |

Nu cerem fotografii noi înainte de a epuiza materialele deja disponibile. Pozele de zi cu mese goale se folosesc ca prezentare a spațiului, nu ca dovadă a unei atmosfere aglomerate. Un cadru suplimentar autentic de seară ar fi util ulterior, nu este obligatoriu pentru start.

## 7. Execuție eficientă și criterii de acceptare

### M1 — propunere completă, locală

Un concept vizual aplicat la primul ecran și la firul întregii pagini. Pregătim master-ul de imagine, varianta desktop și mobil și o demonstrație a mișcării în același pachet. Facem intern corecțiile de proporții, lizibilitate și compoziție înainte de prezentare. Utilizatorul evaluează un rezultat concret, nu întrebări despre fiecare culoare sau bibliotecă.

### M2 — finisare și verificare

Aplicăm feedback-ul grupat. Finalizăm conținutul și verificăm layout la 360/390/768/1440 px, tastatură, reduced motion, contrast, apel, meniu, Club și imagini. Bugete de proiect propuse: imagine principală mobil sub aproximativ 350 KB, pagina fără video sub aproximativ 1,5 MB, țintă Lighthouse Performance mobil ≥90 în condițiile de test documentate. Sunt ținte, nu rezultate măsurate sau garanții pe orice telefon. Nu încărca galeria completă înainte de nevoie.

### M3 — hosting și lansare

Verificăm document root-ul `oldmike.ro`, permisiunile, spațiul și HTTPS. Istoricul indică `/home/oldmikero/public_html`; nu tratăm această relatare drept confirmare actuală. Stabilim dacă vechiul `index.php` redirecționează și ce variantă trebuie păstrată înainte de înlocuire. Nu presupune că simpla adăugare a `index.html` înlocuiește automat pagina existentă.

Dacă este necesară o zonă de test pe cPanel, delimităm accesul și indexarea înainte de încărcare. Salvăm versiunea veche și pregătim revenirea. După prezentarea rezultatului și autorizarea lansării, publicăm numai site-ul principal. Nu atingem Berăria, aplicațiile meniu/Club, emailul sau DNS-ul comun. Administratorul devine necesar numai pentru un drept lipsă ori o configurație din afara accesului nostru.

## 8. Ghidarea utilizatorului

Asistentul pregătește și verifică munca tehnică. Utilizatorul intervine pentru autentificare când e necesar, validarea informațiilor restaurantului și evaluarea/publicarea rezultatului. La fiecare pas: „ce facem”, „de ce”, „ce vei vedea”, „ce trebuie să faci tu”. Explică simplu: GitHub este dosarul cu versiuni; cPanel este panoul găzduirii; hero este prima scenă a paginii. Nu trimite parole în documentație sau repository.

## 9. Verificări M0.1 și limite

Revăzute: catalog M01–M07, capturile pizza/ingrediente și burger/scenă, fotografie reală de terasă IMG_6692, documentele de continuitate și HEAD remote. Consultate paginile publice oficiale Flair; nu s-a conectat contul și nu s-au consumat generări. Alegerea M06 este propunere, nu aprobare a utilizatorului. Nu s-au făcut teste UI sau măsurători de viteză pentru un design nou, fiindcă implementarea nu a fost modificată.

Acest milestone actualizează documentația și ghidajul. Nu modifică `index.html`, `img/*`, configurația Vercel, cPanel, producția sau celelalte aplicații. Progresul se salvează în GitHub înainte de prezentarea strategiei.
