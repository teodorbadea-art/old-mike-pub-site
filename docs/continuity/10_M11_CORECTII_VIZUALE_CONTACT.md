# M1.1 — corecții vizuale, hartă și cerere de grup

Data: 18.10.2026. Bază: `c907b51c332e418ab856331ff6880fb970152097`, ramura `design/m1-static-cinematic`. Fără deploy sau modificarea altor aplicații.

## USER VALIDATED în această rundă

- Acasă poate avea burger descompus în formare, artistic, hiperrealist și static; nu reutiliza exact hero-ul asamblat din Meniu.
- Categoria Pizza trebuie să rămână generică; elimină referința Diavola inclusiv din imagine.
- Nu utiliza cuvântul „Noroc” pentru bere, pentru a evita confuzia cu marca.
- Galerie: refacere panoramică/cinematică cu arhitectura recognoscibilă; patru zone — terasă exterioară, terasă acoperită, interior, bar. Integrare în fundal, fără aspect de imagini lipite pe negru și fără crop-uri arbitrare.
- Acces mai vizibil și accesibil către meniul complet.
- Formular pentru cereri de grup/evenimente, plus hartă în Contact.
- Răspuns explicit la clarificare: cereri în administrare și notificări email. Adresa email destinatară nu a fost furnizată.
- Adresă confirmată explicit de utilizator: **Strada Matei Voievod 67, București**.
- Utilizatorul a reamintit accesul read-only la fotografiile produselor din Meniul Electronic.

## Implementat în propunere

### Acasă și Meniu

Nou hero static `hero-exploded-v2.webp` și derivat mobil. Burgerul asamblat v1 rămâne doar în pagina Meniu. CSS `contain` și compoziția mobilă urmăresc păstrarea integrală a straturilor; acest lucru necesită încă verificare vizuală în browser.

Pizza folosește o reinterpretare cinematică bazată pe fotografia asociată produsului din meniul existent, fără meniu tip carte, nume de rețetă sau text în imagine. Titlu public generic „Pizza.” Bere: „Bere rece.” și imagine preluată din meniul existent, fără cuvântul exclus. Nu publicăm prețuri duplicate.

CTA „Vezi meniul complet” este acum în partea de sus, imediat după introducere, într-o bandă sticky; repetat central la final. Nu mai depinde de un colț din dreapta jos.

### Galerie

Patru reinterpretări landscape 1536×1024 după originale. Imagini afișate integral la raport 3:2, fără înălțimi arbitrare de crop; fundal cald charcoal, estompare discretă a marginilor și legendă editorială, fără rame rigide. Dialogul păstrează imaginea întreagă pentru inspectare. Reperele principale ale arhitecturii au fost comparate vizual cu sursele.

**Limită:** generarea poate modifica detalii mici, semnalistică și persoane; nu declarăm fotografii identice sau conservarea exactă a fiecărui obiect. Validarea proprietarului înainte de publicare rămâne necesară. Originalele nu au fost editate sau suprascrise.

### Contact și hartă

Adresa confirmată este afișată. Iframe Google Maps pentru adresa respectivă și link-uri independente de căutare/indicații; fără cheie API introdusă. Harta are nevoie de internet și nu a fost verificată în browser în acest mediu. Nu este o hartă desenată/generată și nu pretinde coordonate verificate separat.

Formularul conține nume, telefon, email, data/ora, număr estimat de persoane, ocazie, zonă preferată, detalii și acordul de a fi contactat pentru cerere. Fără acord marketing. Mesaj explicit: cererea nu este o rezervare confirmată; disponibilitatea se discută cu echipa.

**Mod curent: previzualizare locală.** Butonul este „Previzualizează cererea”, afișează un rezumat cu textContent și nu face POST, email sau persistență. Nu am simulat succesul unei trimiteri. Primirea în administrare și notificările email sunt cerințe înregistrate, nu funcții activate. Trebuie implementate și testate server-side după verificarea hostingului și confirmarea inboxului.

## Recuperarea fotografiilor din Meniul Electronic

Repository-ul sursă conține un export istoric cu candidaturi de imagini, nu toate imaginile curente. Am verificat separat media în Supabase prin **SELECT-uri read-only**. Proiectul și restaurantul au fost identificate din configurația existentă și metadate. Nu s-a schimbat nimic în baze de date, Storage, meniuri sau versiuni.

Au fost recuperate fișierele asociate și marcate verified pentru Pizza Margherita și Bere Tuborg la Metru 8 Halbe 0,4L. Pizza este folosită doar ca referință de formă/textură pentru categoria generică; fotografia de bere este copiată și decupată pentru prezentare. Aceste fișiere sunt media existente ale meniului; marcajul verified nu dovedește separat că sunt fotografii documentare ale produselor.

În căutările efectuate nu s-a găsit o imagine verified asociată unui produs cu denumire/fișier burger în LIVE/UAT; nu se afirmă că nu există în alte surse. Hero-ul burger rămâne concept artistic, nu este prezentat ca dovadă a rețetei servite. Înainte de lansare se aliniază cu preparatul ales și fotografia-sursă identificată.

Surse și prompturi efective: [ASSET_PROMPTS_M11.json](../design/ASSET_PROMPTS_M11.json). Nicio cheie, configurare secretă sau date de client nu intră în acest repository.

## Verificări executate

- PASS: generarea celor 6 pagini și a fișierului autonom; `git diff --check`; sintaxa JavaScript comun și inline, inclusiv pachetul de review.
- PASS: legături și resurse locale, un H1 per pagină, alt pentru imagini, decodarea tuturor asset-urilor noi.
- PASS: hero distinct de burgerul din Meniu; fără Noroc/Diavola în pagina Meniu; CTA principal înaintea primei imagini; patru zone în Galerie; hartă și adresă; formular explicit preview-only.
- PASS: test funcție de validare — un caz valid și nouă cazuri respinse: nume gol, telefon scurt, email invalid, dată trecută, lună invalidă, zi inexistentă, număr prea mic/neîntreg și lipsa acordului. Test cu date sintetice, fără transmitere/persistență.
- Imaginile regenerate au fost inspectate vizual; nu sunt capturi din browser.
- **Browser QA, harta live, comportamentul CSS pe dispozitive și Lighthouse nu au fost executate**, aceeași limită managed/control-browser ca la M1. Verificările de cod nu sunt teste end-to-end.

## Rămâne

Evaluare vizuală M1.1, validarea fidelității locației/produselor, confirmarea emailului destinatar, verificarea hostingului și realizarea administrării reale pentru evenimente/cereri, livrarea email și informarea completă privind datele înainte de activarea formularului. Program/social media încă de confirmat. Fără publicare până la autorizarea utilizatorului. Berăria și celelalte aplicații sunt excluse de la modificări.
