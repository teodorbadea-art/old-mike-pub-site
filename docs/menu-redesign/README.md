# Old Mike — M3.2, pachet PHP complet pentru evaluare

Pagina oficială nu este înlocuită. Acest pachet corectează M3 cu un hero nou, Bere cu bucătari miniaturali, imagini separate WebP/AVIF, text mobil separat de fotografie și taburi pentru Spirtoase.

## Fișierele de folosit

- **`public/meniu.php` este complet și autonom**, aproximativ 32 KB. Include proxy-ul public Supabase, validarea răspunsului, cache-ul de 600 de secunde, fallback-ul, toate textele, header/subsol, HTML complet și JSON-LD. Nu are nevoie de module PHP externe sau de Composer. CSS, JavaScript, fonturile și imaginile sunt fișiere externe. **Cerință minimă: PHP 8.1 + cURL** (`array_is_list`, `mixed`); aceasta este prima verificare din instrucțiunile de instalare.
- `public/assets/images/meniu/`: 20 de ilustrații × 3 dimensiuni (original, 480 și 800 px) × 2 formate (WebP și AVIF), plus 4 variante mobile ale hero-ului: 124 de fișiere. Originalele sunt de maximum 1536 px; nu există mărire artificială la 2000 px. `picture` folosește AVIF cu fallback WebP; `srcset` conține cele două variante și lățimea originalului. Hero-ul are `fetchpriority="high"` și încărcare eager; celelalte imagini sunt lazy.
- `public/assets/site.css`: stilurile comune plus blocul M3/M3.2 comentat. Același bloc este separat în `instructiuni-si-prompturi/meniu-adaugiri.css`.
- `public/cache/menu.json`: copia publică reală de pornire, pentru funcționare și la prima întrerupere. `cache/.htaccess` blochează citirea ei din browser.
- `public/previzualizare.html`: copia pentru evaluare, cu aceleași imagini externe. Dezarhivează pachetul înainte de deschidere și păstrează folderul `assets` lângă HTML. Nu este meniu live. Nu există imagini base64 în PHP sau în previzualizare.

## Date live și cache

Endpoint-ul public `get_public_menu` este apelat exclusiv din PHP pentru `old-mike`, locația `loc-oldmike-matei`, cu cheia publicabilă furnizată. Nu sunt folosite chei secrete și nu se scrie în Supabase.

La acces după 10 minute, PHP încearcă să actualizeze copia. Cache-ul este scris atomic, cu lock. La răspuns invalid sau întrerupere rămâne ultima copie bună; nu apar erori tehnice clientului. Fără nicio copie disponibilă rămân telefonul și linkul către meniul electronic. Actualizarea se face la primul acces după expirarea cache-ului, nu prin push instantaneu.

Oferta de prânz rămâne configurată separat: **33,99 lei, ciorbă + fel de bază + garnitură, luni–vineri 12:00–16:00**, conform confirmării utilizatorului. RPC-ul produselor nu furnizează această ofertă.

## Băuturi și taburi

Cantitatea și procentul de alcool sunt afișate inline lângă nume, de exemplu `40 ml · 40%`. Procentul este extras numai din textul public care spune explicit „alcool”, „alc” sau „vol”; lipsurile nu sunt completate din presupuneri. Restul descrierii rămâne afișat. Procentele de fructe nu sunt interpretate ca alcool.

Spirtoasele sunt grupate în Whisky, Rom, Vodka, Gin, Tequila, Coniac, Shot-uri și Lichior și vermut. În catalog, „Lichior/vermuth/gin” este o categorie comună; trei ginuri sunt separate în afișare prin regula de nume din configurare. Nu se schimbă categoria produselor din Supabase. Produsele cu denumiri necunoscute nu sunt eliminate: rămân în tabul categoriei sursă. Anomaliile catalogului (de exemplu Martini la Tequilla) sunt păstrate ca date, nu corectate implicit.

Taburile suportă click, tastatură (săgeți, Home, End), focus și roluri ARIA. Fără JavaScript, toate listele rămân în HTML și sunt vizibile, pentru acces și indexare. În cache și în schema JSON-LD rămân toate cele 257 de produse reale din copia de evaluare.

## Imagini și rezoluție

Hero-ul nou arată o masă cu burger cu o singură bucată de carne, pizza, platou, halbe și cinci bucătari. Berea are un bucătar la dozator și unul care transportă spuma cu o lopățică, fără mărci lizibile. Pe mobil, fotografia integrală este deasupra textului; eticheta nu se suprapune peste bucătari.

Originalele au maximum 1536 px (sursele de 1200 px sunt păstrate la 1200); variantele au 480 și 800 px. Hero-ul nou de 1672 px a fost redus la 1536, fără crop. Nu se livrează exporturi mărite la 2000 px. `m32-image-manifest.json` este manifestul curent; documentele M3.1 păstrează istoricul cerinței anterioare de 2000 px, acum anulată.

`picture()` verifică fișierul real `hero-meniu-mobile-800.webp`, nu un master `-mobile.webp` inexistent. Sursele mobile AVIF/WebP sunt incluse și arată exact cadrul întreg, separat de text. Variantele mobile de 480 și 800 sunt fișiere separate, nu date base64.

Pentru Porc, produsul public **OM-0080** nu are fotografie asociată; verificarea catalogului media nu a găsit o fotografie a coastelor. Este necesară fotografia reală pentru generarea cu bidineaua. Categoria **Porc are deja secțiune proprie și intrare în navigare**, în afara „Și altele”, afișată elegant ca listă până la primirea fotografiei. Maparea `meniu/cat-porc` este pregătită; când fișierul există, figura apare automat. Nu s-a inventat o fotografie a preparatului.

Tomahawk-ul din fotografia sursă este de porc. Fotografiile din catalog pentru „PENNE Carbonara Original” și dorada cu orez nu corespund complet denumirilor; ilustrațiile au urmat fotografiile. Vezi prompturile și limitele documentate; nu s-au modificat datele.

## Fonturile pe întregul site

Prima linie din `assets/site.css` este `@import url('oldmike-design.css');`. Astfel se încarcă fonturile și variabilele chiar dacă o pagină HTML mai veche nu include separat acest CSS. Variabilele `--sans` și `--serif` au și familii de rezervă. Pachetul conține CSS-ul comun și fișierele locale de font; urcă-le împreună.

Testele browser verifică Acasă (`index.html`), Evenimente, Galerie și Contact la 1440 și 390 px. Linkul direct către `oldmike-design.css?v=1` este blocat intenționat în acea probă: importul din `site.css` trebuie să fie suficient. Se verifică și încărcarea efectivă a fonturilor, nu doar numele din CSS.

## Surse de dezvoltare

În Git, implementarea se întreține în `server/lib/menu/`; `scripts/build_menu_php.py` generează fișierul autonom `meniu.php`. Configurarea categorie → imagine este în `server/lib/menu/config.php` și într-un singur bloc în PHP-ul livrat. Nu edita ambele variante separat: pentru dezvoltare editează modulul și regenerează; pentru o instalare simplă poți edita direct blocul de configurare din PHP.

Instrucțiunile cPanel și lista de verificare sunt alături. Activarea rutării oficiale se face numai după aprobarea previzualizării. Nu s-au modificat `meniu.html`, Apache, DNS, baza de date sau Meniul Electronic.
