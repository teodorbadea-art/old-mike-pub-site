# Audit Meniu Electronic — 19 octombrie 2026

USER VALIDATED: utilizatorul cere verificarea raportului extern despre producție/main, branding, SEO/social, alergeni, denumiri și imagini. Auditul extinde citirea la Meniu Electronic și configurația Vercel/Supabase. Nu s-a modificat aplicația, configurația, datele sau producția. Corecția linkului Garden M2.11 rămâne neaplicată live din cauza erorii cPanel.

## Producție și continuitate

FACT — Vercel: proiect meniu_electronic_v4_4, deployment READY dpl_J8vYH2Mfe9ny2dTo9YqrR9gVfwJ7, alias meniu.oldmike.ro. Creat 17.10.2026 17:04 Europe/Bucharest, sursă feat/v552-unified-menu-import, commit 529efcdf030f51d7d715a0346ed4abd63b4cc1fc.

FACT — GitHub: main este cca82275663112f74e3317ae0a62d369f32e752a, 28.09.2026. Comparația main → commitul producției: ahead_by=1100, behind_by=0; main este strămoș al producției. Listarea returnează 72 ramuri. Numărul ramurilor nu este singur un defect funcțional.

FACT — documentația README/PROJECT_STATUS din commitul live afirmă încă Production=main/V5.0.5 și „nu s-a promovat”; informații depășite față de Vercel. Acesta este un risc real de continuitate.

UNKNOWN — câmpul Production Branch din setările Git Vercel nu este expus în răspunsul conectorului get_project. Nu declara verificată valoarea main din acel câmp. Metadatele deploymentului arată ramura/commitul, nu dovedesc singure metoda manuală a publicării.

FACT — vercel.json din main nu conține deploymentEnabled. Commitul live permite automat numai preview/**, restul false. INFERENCE: publicarea main vechi poate readuce cod incompatibil cu starea curentă, inclusiv regresii de securitate și de backend, nu numai aspectul. Nu sincroniza prin publicare automată accidentală.

DECISION propusă: consemnează deploymentul și rollback-ul, verifică/blochează publicarea automată înainte de aliniere; aliniază main cu exact commitul live prin fast-forward, fără force și fără a include implicit alte modificări nepublicate. Actualizează handover-ul și definește o singură sursă de producție. Nicio operație din acest plan nu s-a executat.

## Branding și distribuire

FACT — pagina generală / afișează bara ME / Meniu Electronic / V5.0.53 multi-restaurant. Ruta dedicată /m/old-mike/loc-oldmike-matei omite complet bara, inclusiv comenzile administrative, conform shell() din cod. Ruta dedicată a fost verificată cu produse/prețuri în browser. Folosește M2.11 pentru linkul Garden.

FACT — restaurants.hero_path pentru Old Mike este NULL; în DOM există spațiul hero fără imagine. Soluție propusă: antet compact cu logo/nume când lipsește fotografia, nu spațiu gol sau o imagine foarte grea obligatorie.

FACT — HTML-ul inițial are titlul Meniu Electronic, fără description, Open Graph sau canonical. Browserul schimbă ulterior titlul în Old Mike PUB · Meniu; acest lucru nu înlocuiește metadatele livrate inițial pentru distribuirea socială. Nu s-a verificat cache-ul efectiv WhatsApp/Facebook.

Afirmația „Google nu vede produsele” nu este demonstrată. Google poate randa JavaScript; indexarea reală necesită dovadă Search Console. Referință oficială: https://developers.google.com/search/docs/crawling-indexing/javascript/javascript-seo-basics . Propunere: metadate și imagine socială în HTML-ul inițial pe ruta restaurantului, plus conținut public accesibil roboților, fără a transforma întregul SaaS într-un site Old Mike.

## Alergeni

FACT — interogări BEGIN READ ONLY / ROLLBACK, strict old-mike: 257 produse active publicate; 69 au liste de alergeni stocate; 0 au recipe_status sau allergen_status verified; toate 257 sunt draft pentru rețetă/nutriție/alergeni și toate au source_status.recipe care cere verificare manuală. get_public_menu expune 0 liste de alergeni. Niciun produs nu a fost validat sau publicat în audit.

FACT — funcția adminValidation() include butonul Validează în masă numai dacă există produse calculabile eligibile sau o procesare în curs. bulkValidationCandidateRows() exclude rețetele importate/marcate pentru review. Traseu: Restaurant Admin → Validare. Pentru acest lot, marcajele din sursă explică indisponibilitatea; nu este dovadă că butonul lipsește din aplicație.

DECISION propusă: fă vizibil numărul și motivul produselor excluse chiar și când sunt zero eligibile. Cele 69 liste nu sunt dovezi de corectitudine; verifică rețetele, ingredientele și etichetele furnizorilor înainte de confirmare. Nu șterge marcajele de review și nu face UPDATE în masă la verified pentru a forța afișarea. Folosește fluxul existent draft → verificare → publicare.

## Denumiri

FACT — browserul a afișat 103 produse Mâncare +149 Băuturi +5 Narghilea=257; numai o denumire conține diacritice. Confirmate Tomahwk, Chiken, Tuborg Drought, Vunul Casei și Formagi. Am numărat 23 denumiri cu cel puțin un cuvânt ASCII complet majuscul de minimum trei litere, nu exact 25; numărul depinde de criteriu, iar majusculele unor mărci sunt legitime.

Propunere: corecții editoriale RO cu mapare vechi→nou, diacritice și capitalizare coerentă; păstrează identitatea produselor, prețurile, rețetele și mărcile comerciale. Exemple Tomahawk, Chicken, Tuborg Draught (denumirea comercială finală verificată), Vinul casei roșu, Quattro formaggi. Nu aplica Title Case automat tuturor cuvintelor sau mărcilor.

## Fotografii

FACT — prin public-menu + media_assets, strict imaginile publice curente de Mâncare: 57 căi distincte, total 7.535.259 bytes =7,54 MB sau7,19 MiB; 4PNG, maxim2.466.598bytes. HEAD PNG confirmă lungimea2.466.598 și naturalWidth1254 în browser, pentru miniatură de106px în viewportul verificat. Nu am reprodus exact91px pe telefon.

FACT — imaginile din listă au loading=lazy, fără srcset. Nu toate cele7,2MiB sunt neapărat transferate la prima deschidere; totalul descrie fișierele asociate secțiunii. Nu s-a efectuat benchmark real4G/Lighthouse.

FACT — uploadMediaCore() din backend.js folosește originalul, fără redimensionare în acea funcție, cacheControl=3600. HEAD live pentru PNG și un JPG a returnat însă Cache-Control:no-cache, nu max-age3600. Este o diferență de verificat între setarea uploadului, metadatele obiectelor și livrarea CDN. no-cache cere revalidare, nu înseamnă obligatoriu redescărcare integrală de fiecare dată.

Propunere fără servicii plătite: variante WebP/JPEG de aproximativ400px pentru listă, separat o variantă mai mare pentru detaliu și original păstrat pentru print. Procesare locală/în browser, fără dependență de transformări server plătite. Cache lung pentru fișiere cu nume unice; la schimbare, URL nou. Optimizează și cele57 imagini existente, nu doar încărcările viitoare. Verifică apoi antetele HTTP reale și dimensiunea transferată.

## Ordine propusă

1. Siguranța publicării și documentația sursei live.
2. Link public Garden; antet compact și metadate restaurant.
3. Miniaturi și corecțiile editoriale, pregătite într-un lot reviewabil.
4. UX Validare și verificarea conținutului celor69 produse, cu dovezi din rețete.
5. Un singur candidat preview după verificări locale, apoi aprobarea publicării.

STARE finală: audit numai citire, nicio schimbare în Meniu Electronic/DB/Vercel sau pe cPanel. Raport salvat în repository-ul de continuitate al site-ului; implementarea în repo-ul meniului necesită reluarea cu documentele lui curente și planul prezentat utilizatorului.
