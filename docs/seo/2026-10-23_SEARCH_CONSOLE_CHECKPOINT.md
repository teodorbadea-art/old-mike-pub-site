# PUBLICAT LIVE — 23 octombrie 2026

Utilizatorul a aprobat publicarea prin «ok, confirm». Cele șase fișiere Garden sunt LIVE; backup privat seo-garden-backup-20261023-172223. 257 produse, 148 nutriție, cache 403, 12 probe Chromium 390/1440 PASS. CSS/runtime/.htaccess/robots/sitemap identice; DNS și celelalte aplicații neatinse. Vezi [raportul final cu teste și revenire](2026-10-23_LANSARE_LIVE.md). Nu relua instalarea. Urmează acces de Manager la profilul Google Business Profile existent și verificarea lui. GSC este deja verificat și sitemap-ul acceptat; nu relua TXT-ul DNS. Indexarea efectivă a meniului rămâne de verificat în GSC.

---

## Istoric anterior publicării

# Actualizare finală — 23 octombrie 2026, SEO pregătit, nepublicat

FACT / capturi: proprietatea Domain oldmike.ro verificată, sitemap Garden Success, homepage indexată. Test live meniu: disponibil pentru Google. VALIDAT DE UTILIZATOR: indexarea meniului a fost solicitată; indexarea efectivă încă neconfirmată.

DECIZIE: Garden nume principal, Pub doar denumire alternativă în schema; fără titlu combinat sau prezentare «cunoscut și ca». Detalii complete, teste, pachet de șase fișiere și revenire: [audit și livrare SEO](2026-10-23_SEO_GARDEN.md). [PR #1 pentru revizie](https://github.com/teodorbadea-art/old-mike-pub-site/pull/1). Ramură feat/garden-seo-20261023, bazată pe feat/v564-menu-nutrition. PHP și 12 verificări browser 390/1440 PASS. Nu au fost executate deployment-uri sau modificări DNS în lot. Nu relua verificarea TXT; următorul pas este aprobarea pachetului și publicarea controlată în cPanel, apoi acces de Manager la profilul GBP existent.

---

## Istoric anterior acestei actualizări (pașii de mai jos nu mai sunt stadiul curent)

# SEO Garden — checkpoint 23 octombrie 2026

## Domeniu și pasul curent
FACT: două rezolvoare independente, Google DNS și Cloudflare DNS, au răspuns Status 0 pentru NS oldmike.ro:
- aldo.ns.cloudflare.com.
- connie.ns.cloudflare.com.

DECISION: verificarea Domain Search Console se face prin TXT în zona Cloudflare oldmike.ro, nu în zona locală cPanel/AffixHost. Nu se schimbă delegarea, nameserverele, DNSSEC, A/CNAME, MX, SPF sau DKIM/DMARC. Se adaugă un TXT separat la rădăcină (@), valoarea exactă google-site-verification furnizată de proprietar, TTL Auto. Google afișează corect Cloudflare. Fluxul manual Any DNS provider permite examinarea înregistrării înainte de salvare.

UNKNOWN: tokenul exact Google și accesul la contul Cloudflare/Search Console. Proprietarul a fost îndrumat să obțină valoarea TXT; nu s-a introdus sau salvat nimic în DNS și nu s-a verificat proprietatea.

## Verificare actuală live — fără scrieri
HTTP 200: /, /index.html, /meniu.html, /contact.html, /evenimente.html, /galerie.html, /confidentialitate.html, /robots.txt, /sitemap.xml. Nu s-a observat X-Robots-Tag în răspunsurile salvate.
Paginile verificate au robots index,follow, canonical și og:url pe garden.oldmike.ro. Fiecare are exact un H1. Restaurant JSON-LD există pe Acasă, Contact și Meniu; meniul conține produse/prețuri în HTML.
robots.txt live:
```
User-agent: *
Disallow: /admin/
Disallow: /api/
Disallow: /lib/
Sitemap: https://garden.oldmike.ro/sitemap.xml
```
sitemap.xml live conține cinci URL-uri corecte Garden: /, meniu.html, evenimente.html, galerie.html, contact.html.
Concluzie: nu s-a găsit un blocaj general de indexare prin aceste controale. Existența unor rezultate în motorul de căutare consultat nu dovedește starea sau poziția în indexul Google al utilizatorului; confirmarea se face în Search Console.

## Probleme confirmate / priorități
P1: identitatea publică rămâne Old Mike Pub în title, meta, og:site_name și Restaurant.name; trebuie armonizată cu Old Mike Garden, păstrând denumirea legală OLD MIKE PUB SRL.
P1: title/description/headline comercial pot preciza restaurant/terasă, Sector 2 și Matei Voievod, fără inventarea facilităților sau suprasaturare de cuvinte-cheie.
P1: Google Business Profile existent trebuie legat de https://garden.oldmike.ro/ și meniul actual, după acces Manager; nu se creează alt profil. Rating/număr recenzii din captura anterioară nu sunt valori actuale confirmate.
P2: schema pe Acasă/Contact indică meniul generic meniu.oldmike.ro/; de uniformizat cu meniul Garden și linkul public dedicat. Restaurant pe Meniu păstrează hasMenu cu prețurile live.
P2: confidențialitatea încă descrie o versiune de evaluare; trebuie corelată cu funcționarea reală, fără a inventa procese de date.
P2: Open Graph folosește aceeași imagine pe paginile statice; verificați obiectivul înainte de înlocuiri. Meniul are coperta proprie.
P2: conținut local vizibil și legături de la domeniul grupului — pregătire după verificarea sursei publicate; domeniul grupului nu se modifică fără delimitare și aprobare.
UNKNOWN: statistici GSC, canonical ales de Google, crawl Googlebot real, performanță mobilă actuală, profil GBP/website asociat/social — de verificat ulterior.

## Sursa efectivă / protecții
Repo: teodorbadea-art/old-mike-pub-site; Garden este servit din cPanel, document root confirmat anterior /home/oldmikero/public_html/garden.oldmike.ro.
Ultimul lot publicat, V564, a schimbat doar meniu.php și assets/site.css. Ramura feat/v564-menu-nutrition conține acel lot; NU este un pachet complet al site-ului live.
IMPORTANT: fișierele HTML/robots/sitemap locale vechi diferă de live (local robots are Disallow:/, sitemap pe oldmike.ro). Nu le urca și nu deduce starea publică din ele. Pentru corecții folosește copia live actuală, backup și verificări de amprente înainte de înlocuire.
Păstrează assets/site-runtime.js, formularele, cache 600s/fallback, cache/.htaccess și .form-honeypot. Nu atinge Parc, Meniu Electronic, Community sau datele Supabase.

## Ordine agreată
1. Proprietar obține TXT; examinare/adăugare separată în Cloudflare; control DNS public; Verify în Search Console.
2. GSC URL Inspection pentru homepage și meniu, sitemap; baseline de indexare/afișări/clicuri, fără promisiuni de poziție sau termen garantat.
3. Patch SEO pe sursa live, test și prezentare impact înainte de publicare.
4. GBP existent și coerență locală, apoi calendar organic gratuit și măsurare pe 28 zile.
Nicio schimbare live, DNS sau profil Google Business nu a fost executată în acest audit.

Surse primare:
- https://support.google.com/webmasters/answer/9008080
- https://developers.cloudflare.com/dns/manage-dns-records/how-to/create-dns-records/
