# Old Mike Garden — audit SEO și plan de lucru, 23 octombrie 2026

**PUBLICAT LIVE după aprobarea utilizatorului.** Vezi [raportul final, backup-ul și testele live](2026-10-23_LANSARE_LIVE.md). Secțiunea de pregătire de mai jos păstrează cronologia; nu relua instalarea.

## Stare înainte de publicare (istoric)

- FACT: site public servit din cPanel/AffixHost, separat de site-ul grupului. DNS autoritativ Cloudflare: aldo.ns.cloudflare.com și connie.ns.cloudflare.com. TXT Google verificat prin două resolvere. DNS nu a fost modificat în acest lot.
- VALIDAT DE UTILIZATOR / capturi: proprietatea Domain `oldmike.ro` este verificată în Search Console; sitemap-ul Garden are status Success; pagina principală este indexată; testul live pentru meniu permite indexarea. Utilizatorul a confirmat solicitarea indexării meniului. Indexarea efectivă a meniului rămâne de verificat.
- DECIZIE: numele principal este Old Mike Garden. Old Mike Pub apare ca denumire alternativă în datele structurate, fără titlu combinat și fără o prezentare „cunoscut și ca”. Operatorul OLD MIKE PUB SRL este păstrat.
- FACT: corecțiile din acest lot sunt pregătite și testate; NU sunt publicate. Nicio schimbare la Berăria/Parc, grup, Meniu Electronic, Community, baze de date, CSS, DNS sau rutare.
- NECUNOSCUT: valorile inițiale de afișări/clicuri/poziții și datele actuale administrate în Google Business Profile. Nu inventăm trafic, recenzii sau rating.

## Audit și priorități

| Prioritate | Constatare verificată | Tratament |
|---|---|---|
| P1 | Titlurile, Open Graph și numele Restaurant foloseau Old Mike Pub | Garden pe cele șase pagini; Pub ca alternateName; nume coerent în header și footer |
| P1 | Pagina de meniu nu era cunoscută în GSC | Sitemap acceptat; test live reușit; solicitare de indexare confirmată de utilizator |
| P1 | Localitatea era prezentă, dar Sectorul 2 nu apărea clar | Text local firesc în introducerea paginii principale și în subsolul comun |
| P1 | Schema Restaurant trimitea la rădăcina aplicației de meniu | Legătură canonical spre `/meniu.html`; linkul electronic păstrează ruta publică a restaurantului |
| P2 | Homepage fără WebSite cu identitatea Garden | WebSite pe homepage, cu nume preferat și alternativ; fără rating inventat |
| P2 | Confidențialitatea spunea încă „versiune de evaluare” | Eliminată această formulare; informațiile operaționale și juridice păstrate |
| OK | Paginile testate au HTTP 200, canonical Garden și index,follow; fără X-Robots-Tag observat | Păstrate; un H1 pe pagină |
| OK | robots.txt permite crawl, protejează admin/api/lib și indică sitemap | Nu se înlocuiește |
| OK | sitemap.xml conține homepage, meniu, contact, evenimente, galerie | Nu se înlocuiește; fără lastmod artificial |
| OK | Imagini responsive, fonturi locale, site-runtime.js pe paginile statice | Păstrate integral |

Titlurile H1 editoriale aprobate („Poftă de / ceva bun.” etc.) rămân identice. Nu adăugăm pagini artificiale pentru fiecare cuvânt-cheie, evaluări fictive sau evenimente fără dată verificată. Distanța față de UNATC nu este afirmată numeric fără verificare.

## Ce conține lotul

Fișiere publice: `index.html`, `contact.html`, `evenimente.html`, `galerie.html`, `confidentialitate.html`, `meniu.php`. Cele cinci HTML-uri provin din snapshot-urile LIVE, nu din vechile pagini preview ale repository-ului. Acest lot SEO autorizează explicit editarea HTML-urilor, separat de pachetele M3 de meniu care interziceau înlocuirea lor.

Surse PHP întreținute: `server/lib/menu/render.php`, `header.html`, `footer.html`; se reconstruiește PHP-ul standalone. `data.php` și `config.php` sunt identice cu ramura V564 remote: aceleași date, nutriție, cache 600 s și fallback. Nu urca sursele server, testele, capturile sau documentația în folderul public.

Lista și hash-urile pachetului sunt în `output/garden-seo-20261023/FISIERE_DE_URCAT.txt` și `manifest.json`. Pachetul public conține exact șase fișiere. CSS-ul live, inclusiv `.form-honeypot`, rămâne neatins.

## Verificări executate

- PHP 8.4 CLI: lint `meniu.php` PASS; 60 verificări ale meniului PASS; teste nutriție PASS; teste TTL/cache/fallback/refresh nutriție PASS. PHP-ul de hosting 8.3 a fost verificat în lansarea V564; verificarea runtime pe hosting pentru acest lot rămâne la publicare.
- `python tests/seo_garden.py`: PASS. Verifică pe cele cinci pagini titluri și descrieri unice, canonical, OG, index,follow, JSON-LD valid; compară exact imaginile/sursele responsive, CSS-urile incluse, formularele, scripturile runtime, H1, clasele și ID-urile cu live.
- `python scripts/build_menu_php.py --check`: PASS.
- Chromium: toate cele șase pagini la 390 și 1440 px, 12 verificări PASS. Un H1, fonturi Manrope/Cormorant, logo și hero încărcate unde există, fără depășire orizontală; navigarea mobilă statică funcționează. Capturi și raport `browser-results.json` în folderul de livrare.
- Testele de browser folosesc fișierele modificate servite local și resursele actuale ale site-ului. Nu reprezintă o publicare sau un nou scor Lighthouse. Nu au trimis formulare sau rezervări și nu garantează indexarea/rankingul Google.
- Revizie vizuală homepage și meniu, telefon/desktop: aspectul și compozițiile aprobate păstrate; noua denumire încape în header.

## Publicare controlată în cPanel — după aprobarea rezultatului

1. Folderul vizat exclusiv: `/home/oldmikero/public_html/garden.oldmike.ro`. Verifică hash-urile de dinainte din manifest pentru toate cele șase fișiere. Dacă unul diferă, oprește publicarea și refă diferențele pe noul live.
2. Backup al celor șase fișiere într-un folder nou în afara `public_html`, de exemplu `/home/oldmikero/oldmike-site-private/seo-garden-20261023-backup`. Păstrează și copie `.htaccess`, `assets/site.css`, `assets/site-runtime.js`, `robots.txt`, `sitemap.xml` pentru verificarea integrității, fără înlocuirea lor.
3. Încarcă numai cele șase fișiere listate, păstrând numele și permisiuni 0644. Nu înlocui foldere. Nu urca `meniu.html` static: ruta existentă continuă să livreze PHP-ul. Nu modifica `.htaccess`, cache, CSS, JavaScript sau imagini.
4. Verifică HTTP 200 și sursa pe `/`, `/index.html`, `/meniu.php`, `/meniu.html`, contact/evenimente/galerie/confidențialitate. Canonical Garden, index,follow, identitate Garden și date structurate corecte. Verifică 257 produse dacă publicarea meniului nu s-a schimbat între timp; nutriția continuă unde există, cache actualizat și `/cache/menu.json` cu 403.
5. Verifică telefon 390 și desktop 1440, meniul mobil, galeria, harta la cerere, încărcarea evenimentelor și butoanele telefonice. Nu activa formularul de grupuri. Compară fișierele protejate de la pasul 2: trebuie să fie identice.
6. În GSC solicită reindexare pentru homepage și meniu după ce sursa publică nouă este verificată. Sitemap-ul este deja trimis; nu este necesară o proprietate separată pentru subdomeniu.

Revenire: restaurează EXACT cele șase fișiere din backup-ul acestui lot. Nu restaura copii istorice M3 și nu scoate blocul rewrite de meniu: rutarea nu este modificată în lotul SEO. Repetă testele HTTP/runtime/cache.

## Google Business Profile — următorul pas care necesită acces de Manager

Se lucrează pe profilul existent Old Mike Garden, fără creare sau mutare. Asociatul acordă rol Manager contului ales de utilizator. După acces, se verifică în profil:

- website `https://garden.oldmike.ro/`;
- meniu `https://garden.oldmike.ro/meniu.html` (butonul din pagină deschide meniul electronic dedicat);
- numele real Old Mike Garden, categoria principală care descrie activitatea reală, adresa Strada Matei Voievod 67, București și telefonul 0787 366 166;
- program L–V 10–24, S–D 12–24, cu confirmarea programului special de sărbători;
- fotografii reale recente ale fațadei, terasei și preparatelor; facilități numai dacă sunt confirmate;
- Facebook/Instagram: aceeași denumire, adresă, telefon și link Garden. Nu schimbăm conturi ori directoare fără acces și verificare.

Nu s-au modificat setările profilului. Nu s-a confirmat încă website-ul/categoria/programul din contul administrat.

## Plan gratuit, ușor de ținut: primele 30 de zile

1. Săptămâna 1: publicare SEO verificată, verificare GBP; export GSC pe 28 zile cu filtru Page care începe cu `https://garden.oldmike.ro/`. Salvează separat căutări de brand și căutări locale. Exportă și datele GBP pe aceeași perioadă.
2. În fiecare săptămână: două postări utile reutilizate Facebook/Instagram, una despre prânzul real publicat și una despre terasă sau un preparat; două-trei story-uri cu imagini reale și CTA spre meniu/rezervare. Nu fixa prețuri în șabloane fără verificarea meniului curent.
3. Pentru fiecare eveniment confirmat: publică întâi pe pagina Evenimente, apoi distribuie linkul și posterul pe rețele. Meciurile se anunță numai după verificarea datei/orei și a transmisiunii la restaurant.
4. În local: invită firesc clienții să lase o recenzie prin linkul oficial al profilului existent; fără recompense sau selectarea exclusivă a clienților mulțumiți. Două sesiuni scurte pe săptămână pentru răspunsuri personale la recenzii.
5. La 28 de zile: compară două perioade egale și ajustează după paginile/căutările care aduc interes, nu după poziția văzută într-o singură căutare personalizată.

| Indicator | Sursă gratuită | Cum îl urmărim |
|---|---|---|
| Indexare pagini principale și meniu | GSC Pages / URL Inspection | Status pe cele cinci URL-uri din sitemap; început și după recrawl |
| Afișări, clicuri, CTR | GSC Performance, filtru Garden | 28 zile, brand separat de non-brand |
| Poziție medie | GSC, aceeași selecție de interogări | Tendință, nu promisiune de loc fix |
| Căutări și interacțiuni locale | GBP Performance | Apeluri, indicații, clicuri website unde sunt disponibile |
| Rezervări reale | Evidență simplă a echipei | Întrebare opțională „De unde ai aflat de noi?”; nu confundăm un clic cu o rezervare |

GSC/GBP nu necesită instalarea unui tracker pe site. Nu s-a adăugat analytics sau cookie de marketing. Obiectivele numerice de creștere se stabilesc după exportul inițial, nu pe trafic presupus.

## Referințe oficiale consultate

- [Google: site names și alternateName](https://developers.google.com/search/docs/appearance/site-names) — preferința de nume se indică prin WebSite și semnale coerente; rezultatul final este ales automat de Google.
- [Google: vizibilitate locală](https://support.google.com/business/answer/7091) — relevanță, distanță, notorietate; profil complet, recenzii autentice și fotografii. Nu există garanție sau taxă pentru poziție mai bună.
