# M0.2 — Direcție vizuală și navigare, 18.10.2026

Acest document este autoritatea curentă pentru design și structură. Înlocuiește propunerile M0.1 privind homepage-ul lung și animațiile pe straturi. Nu anulează cerințele de hosting cPanel, cost zero suplimentar, identitate oficială și izolarea Berăriei și a celorlalte aplicații.

## USER VALIDATED — clarificări explicite

1. Exemplele furnizate sunt capturi din animații hiperrealiste. Utilizatorul nu dorește un substitut prin plutirea/deplasarea fotografiei, zoom continuu sau parallax al pozei. Dacă nu putem produce animație culinară la nivelul cerut, preferă imagine hiperrealistă statică.
2. Homepage aerisit, dominat de un singur produs hero. Fără alte fotografii culinare, grile de preparate sau recomandări pe homepage.
3. Fotografiile reale ale localului nu se publică brut. Se editează cinematic pentru Galerie și atmosferă, păstrând arhitectura, configurația și elementele care fac locul recognoscibil. Nu se substituie localul cu un pub fictiv.
4. Pagina Evenimente are încărcare simplă de postere. Actualizare săptămânală folosind posterele pregătite deja pentru social media. Automatizarea este doar o posibilitate ulterioară, nu un task de programat acum.
5. La întrebarea despre „Meniu”, utilizatorul a ales prin răspuns liber: pagină vizuală cu imagini hiperrealiste cu mâncare din meniu și băuturi, o prezentare de recomandări de tip hero, plus acces la Meniul Electronic pentru tot meniul.
6. La întrebarea despre Evenimente, utilizatorul a ales explicit sport și alte evenimente.

## Structura de navigare propusă de lead

| Intrare | Destinație și conținut |
|---|---|
| Acasă | Logo oficial, mesaj scurt, un singur produs hero static și două acțiuni: Meniu / Rezervă |
| Meniu | Pagină proprie cu recomandări culinare și băuturi; imagini mari, compoziții aerisite, buton permanent vizibil către meniul complet |
| Evenimente | Postere pentru evenimente sportive și altele, ordonate după dată |
| Galerie | Imagini editate cinematic ale spațiului real; interior, terasă, atmosferă |
| Contact | Program și adresă validate, telefon, hartă și social media confirmate |
| Old Mike Club | Link către aplicația independentă existentă |

Logo-ul poate duce și el la Acasă. Pe desktop: navigare și buton de rezervare; pe mobil: meniu compact și rezervare ușor accesibilă. „Despre noi” poate fi un text scurt în Galerie sau Contact; nu propunem încă o pagină separată fără conținut relevant. Această arhitectură de navigare este recomandare, nu dovadă a unei aprobări anterioare.

## Homepage: criterii de acceptare

- Un singur produs principal; fundal cinematic cu spațiu pentru text, logo separat și intact. Produsul trebuie să reprezinte un preparat real din meniu.
- Fără banner de postere, alte imagini cu mâncare, benzi de categorii sau feed de conținut sub hero. Un footer discret pentru datele esențiale este acceptabil ca propunere.
- Fără produs plutitor, mișcarea pozei ca întreg, ingrediente animate aproximativ, zoom automat sau slider automat. Efectele normale de buton/focus nu constituie animația culinară cerută.
- Static este planul de bază. Video hiperrealist numai dacă există un rezultat demonstrat satisfăcător și fără cost nou; nu este condiție pentru progres și nu se prezintă ca deja disponibil.
- Compoziție adaptată desktop/mobil; text și butoane reale în pagină, nu incluse în rasterul hero-ului.

M06 rămâne o referință pentru lumină și identitate vizuală, nu pentru densitatea/layout-ul paginii sau pentru aprobarea integrală. Clarificarea utilizatorului are prioritate asupra machetelor.

## Meniu vizual: criterii de acceptare

Recomandările sunt o selecție editorială de produse reale, nu o copie a catalogului. Pregătim scene mari pentru mâncare și băuturi, separate de homepage. Denumirile și ingredientele trebuie verificate în meniul existent. Nu afirmăm automat că sunt „cele mai vândute” și nu duplicăm prețurile în site în prima versiune. Buton clar „Vezi meniul complet” către `https://meniu.oldmike.ro/`.

Recomandare de lead pentru prima compoziție: burger ca produs hero pe Acasă, iar pizza, platourile și băuturile sunt candidați pentru Meniu. Selecția exactă nu a fost încă aprobată; se verifică disponibilitatea fotografiilor și produselor reale înainte de producție. Nu modificăm Meniul Electronic pentru această pagină.

## Galerie și atmosferă

Pregătim editări cinematice ale originalelor: lumină, culoare, contrast, perspectivă și compoziție coerente. Păstrăm acoperișul/pergola, barul, mobilierul și reperele reale. Nu inventăm arhitectură sau o locație diferită. Verificăm fiecare editare față de sursă înainte de prezentare. Originalele rămân păstrate.

## Evenimente: administrare simplă, propunere tehnică

Flux propus: autentificare → Adaugă eveniment → poster + titlu + categorie + data/ora + data de încheiere → previzualizare → Publică. Editare, retragere și înlocuire poster din același ecran. Formularul folosește mesaje naturale, nu termeni tehnici.

Categoria poate fi sport / alte evenimente. Posterele sunt afișate integral în proporțiile lor și se pot mări pentru citire. Titlul și data rămân și text în pagină, astfel încât conținutul nu depinde exclusiv de citirea posterului. Planul propune ascunderea evenimentelor după încheiere, cu date interpretate Europe/Bucharest; mecanismul exact se verifică la implementare. Fără evenimente fictive publice ca demonstrație.

Propunere pentru găzduirea existentă: frontend static și modul mic PHP pentru administrarea evenimentelor, cu stocare locală structurată, fără Supabase, Vercel sau serviciu plătit. Aceasta extinde propunerea anterioară „fără backend”, fiind justificată de încărcarea din browser. Suportul PHP, extensiile și permisiunile se verifică înainte de alegerea implementării finale; nu sunt confirmate curent.

Implementarea viitoare trebuie să includă acces protejat, sesiuni și control al cererilor, validarea reală a imaginilor, limite de fișier și protecție împotriva executării upload-urilor, scriere atomică și copie de siguranță a listei de evenimente. Secretele nu intră în GitHub. Acestea sunt cerințe interne, nu pași suplimentari pentru utilizator.

Nu construim integrare social media sau programare automată în această etapă. Formatele reutilizate din social se testează pe mobil fără decuparea textului sau a telefonului.

## Următorul livrabil

Un singur concept coerent cu: Acasă static, Meniu vizual, Evenimente, Galerie și Contact, plus navigare și acces Club. Prima prezentare arată homepage desktop/mobil și direcția celorlalte pagini, folosind active potrivite și etichetând orice material provizoriu. Nu publicăm live până la prezentare și autorizare.

Milestone M0.2: numai documentație și clarificări; fără modificări la HTML, imagini runtime, configurare, cPanel sau alte site-uri. Nu s-au editat încă fotografii și nu s-au generat active. Verificări: coerență documentară, link-uri locale și integritatea fișierelor runtime. Rezultatele UI/performance se raportează numai când sunt executate.
