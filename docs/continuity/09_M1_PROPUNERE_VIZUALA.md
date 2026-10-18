# M1 — propunere vizuală locală, 18.10.2026

## Stare și scope

Ramură: `design/m1-static-cinematic`, bazată pe checkpoint-ul M0.2 `22ab6d8a993c64ee30275813935b064185bb02ee`. Acesta este un concept implementat pentru evaluare, **nu o lansare**. Nu s-a schimbat `main`, ramura hero-ului anterior sau vreun site live.

## Implementat

- Acasă: un singur burger hero static, imagine desktop și derivat mobil, logo oficial intact, text HTML și două CTA: pagina Meniu / apel rezervări. Fără grile, galerie, postere sau alte produse pe homepage. Fără flotare, parallax, zoom sau animații CSS.
- Meniu: pagină vizuală proprie cu burger, pizza și bere; link-uri către meniul complet; fără catalog duplicat de prețuri. Imaginile culinare sunt de concept și necesită comparație cu oferta reală înainte de publicare.
- Galerie: două editări cinematice din fotografiile reale de terasă și bar. Mărire prin dialog nativ și închidere din buton/Escape. Reperele majore păstrate vizual, validarea detaliilor de către proprietar încă necesară.
- Evenimente: structură cu filtre Sport / Alte evenimente și stare goală explicită. Nu s-au inventat evenimente, date sau postere. `data/events.json` este gol; alimentarea reală și filtrarea datelor rămân de implementat cu backend-ul.
- Contact: telefonul confirmat și link-urile utile. Adresa completă, programul, harta și social media sunt omise până la confirmare; pagina Contact este încă incompletă pentru producție.
- `administrare-demo.html`: demonstrație **locală**, fără autentificare și fără backend, cu upload în memoria browserului, titlu/categorie/datǎ și previzualizare. Marcaj vizibil că nu salvează și nu publică. Nu trebuie livrată ca administrare reală și nu se expune în navigarea publică.
- CSS comun, navigare responsive și JS pentru meniu/dialog/filtre. Fără fonturi sau scripturi de la CDN. Aplicațiile Meniu Electronic și Club rămân doar link-uri externe.
- Guard Vercel adăugat pentru ramura de design înainte de crearea ei remote, pentru evitarea deployment-ului automat. Nicio schimbare în setările Vercel.

## Cum se deschide propunerea

`python3 scripts/build_pages.py` generează paginile locale, apoi `python3 scripts/build_review.py` creează `output/old-mike-propunere-vizuala-m1.html`. Fișierul este autonom: conține paginile, CSS, JS și imaginile încorporate. Se deschide direct în browser și oferă selector de pagină și mod Desktop/Telefon (390 px). Link-urile externe și apelul telefonic rămân acțiuni reale; nu execută mutații.

Fișierul autonom nu necesită server sau deploy și este livrat separat utilizatorului. `output/` este ignorat în Git; generatorul și toate sursele sunt versionate, astfel încât livrabilul poate fi reprodus. Previzualizarea în iframe este pentru evaluare, nu codul destinat publicării pe cPanel.

## Verificări executate

- PASS: `git diff --check` și sintaxa `assets/site.js` prin Node.
- PASS: sintaxa JS inline pentru toate cele șase pagini și pentru fișierul autonom de evaluare.
- PASS: țintele locale `href/src/srcset` există; exact un H1 per pagină; toate imaginile HTML au atribut alt.
- PASS: homepage are un singur picture; nu conține pizza, terasa sau barul; CSS nu conține keyframes, animation sau transform de imagini.
- PASS: fișierele WebP pot fi decodate, JSON valid și fără evenimente fictive.
- PASS: logo identic cu blob-ul oficial; guard-ul de non-deploy există pentru ramura curentă.
- Imaginile generate au fost inspectate vizual; comparația fotografiilor localului a urmărit reperele principale, nu identitate pixel-cu-pixel.

### Limita verificării

**Nu s-au executat browser QA, capturi ale paginii, verificări efective la viewport-uri sau Lighthouse.** Mediul este managed-linux și skill-ul control-browser nu este disponibil; instrucțiunile Sites cer să nu se improvizeze altă cale de browser/server. Nu s-a pornit server de preview. CSS-ul și selectorul pentru telefon sunt implementate, dar aspectul lor nu se declară validat în browser.

Nu s-au verificat funcțional în browser meniul mobil, dialogul sau formularul. Verificările de sintaxă și structură nu demonstrează comportament end-to-end.

## Dimensiuni ale imaginilor de site

| Fișier | Bytes |
|---|---:|
| hero-burger-v1.webp | 267522 |
| hero-burger-mobile-v1.webp | 170184 |
| terrace-cinematic-v1.webp | 320106 |
| bar-cinematic-v1.webp | 212986 |
| pizza-concept.webp | 118368 |
| bere-concept.webp | 113914 |

Acestea sunt dimensiuni de fișier, nu măsurători de transfer sau performanță browser. Prompturile, sursele și limitele sunt în [ASSET_PROMPTS_M1.md](../design/ASSET_PROMPTS_M1.md).

## Următorul pas

1. Evaluare vizuală a conceptului de către utilizator; feedback grupat pentru desktop/mobil și paginile interioare.
2. Compară imaginile culinare cu preparatele reale; recuperează originale din meniu și confirmă selecția. Validează editările localului. Nu publica conceptul ca fotografie autentică de produs.
3. Completează informațiile confirmate din Contact și verifică browserul/performanța când este disponibilă calea autorizată.
4. Inspectează read-only cPanel-ul pentru document root, PHP/extensii/permisiuni și HTTPS. Nu presupune suportul fără verificare.
5. Implementează administrarea reală pentru evenimente: autentificare, validare server-side, stocare atomică, protecție upload și sesiuni, editare/retragere și expirare Europe/Bucharest. Nu se folosește demo-ul în locul acesteia.
6. După verificări și autorizarea publicării, pregătește lansarea pe hostingul existent. Fără dependență Vercel, fără schimbări la Berăria, Meniu Electronic sau Community.
