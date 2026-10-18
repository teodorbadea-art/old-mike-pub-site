# M1.6 — Galerie: Atmosfera și Spațiile

18.10.2026. Bază e0806de8b069d0738ca13d57d14d9ef9ede5b607, ramura design/m1-static-cinematic.

## USER VALIDATED / clarificarea cerinței

Utilizatorul a cerut două zone în aceeași Galerie: atmosfera cu oameni și prezentarea existentă a spațiilor. Scene discutate: meci la TV, tineri adulți/studenți în conversație și râsete, terasă de seară, moment de socializare la masă. Aspectul trebuie să pară spontan, nu regizat.

Clarificare finală: „reali” însemna **realistici**; utilizatorul nu va realiza fotografii noi și cere explicit generarea oamenilor pe baza pozelor existente, în stilul cinematic al spațiilor. Nu mai cere poze reale cu clienți înainte de această etapă. Persoanele sunt fictive generate; nu documentează prezența unor clienți sau evenimente reale. Cerința de a păstra arhitectura recognoscibilă rămâne.

## Implementat

Patru compoziții fotorealiste bazate fiecare pe originalul locației și pe reinterpretarea cinematică existentă a aceleiași zone:

- Meci: IMG_6685, terasa acoperită; televizorul existent în poziția din referință arată un meci generic, persoane privesc din scaune, fără cluburi/scoruri/dată declarate.
- Gașcă: IMG_6690, interiorul cu banchete verzi, stâlpi și vegetație; tineri adulți în conversație, pizza împărțită, băuturi variate.
- Terasă: IMG_6692, pergola, pavajul, fațada și literele Old Mike; grupuri la mese și lumină de seară.
- Împreună: IMG_6690, cadru apropiat la masă, două pahare ciocnite firesc și prieteni în plan secund.

Active versionate `assets/images/atmosfera-{meci,gasca,terasa,impreuna}-v1.webp`, 1200×800, WebP quality84, fără crop. Prompturi/surse/hashuri în `../design/ASSET_PROMPTS_M16.json`. Fișierele originale și cele patru imagini existente ale spațiilor nu au fost modificate.

Galeria are navigare Atmosfera / Spațiile restaurantului, două secțiuni cu H2, imagini cu H3 și lightbox-ul existent. Fiecare zonă are patru imagini; grid de două coloane desktop / una mobil, imagini integrale. O notă discretă indică „Scene ilustrative, inspirate de spațiile Old Mike”; alt-urile indică generarea. Nu sunt prezentate ca fotografii documentare sau testimoniale.

La final: CTA de rezervări 0787 366 166 și cerere de grup. Nu se adaugă secțiunea de atmosferă în homepage în acest milestone, nefiind cerută implementarea ei în clarificarea curentă. Celelalte pagini rămân identice.

Ambele preview-uri regenerate. Varianta pentru telefon continuă fără JS/iframe, include toate imaginile și ascunde îndemnul de mărire (nu are lightbox). Este aproximativ3,7MB; desktop aproximativ7MB. Nu se pretinde că problema de deschidere pe telefon este demonstrat rezolvată.

## Verificări și limite

Inspecție vizuală a celor patru rezultate: scene distincte, oameni adulți cu aspect fotografic, fără poze de grup frontale; reperele principale ale spațiilor se recunosc. Există diferențe de perspectivă, mobilier minor, densitate a oamenilor, text/logo din fundal sau lumină pe care generarea le poate schimba. Nu declarăm identitate geometrică exactă sau identitatea fiecărui detaliu de semnalistică. Logo-ul oficial din header rămâne fișierul exact, nemodificat.

Verificări statice: generatoare, resurse/ancore/ID-uri, structură H1/H2/H3, opt imagini în Galerie (4+4), dimensiuni/decodare/lazy, sintaxă JS și ambele preview-uri; comparație cu HEAD pentru celelalte pagini și imaginile existente; git diff --check. Browser QA, testarea reală a lightbox-ului pe dispozitive, Lighthouse și deschiderea pe telefonul utilizatorului nu au fost executate: control-browser indisponibil în mediul managed.

## Următorii pași

Evaluarea vizuală și fidelitatea locației de către proprietar, apoi verificarea hostingului cPanel și implementarea backend-ului pentru postere/cereri/email. Formularul rămâne fără trimitere. Niciun deploy sau modificare Vercel/cPanel/DNS/live; Berăria, Meniul Electronic și Old Mike Community rămân neatinse. Lansarea necesită autorizare separată.
