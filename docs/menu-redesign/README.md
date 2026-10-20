# Old Mike — M3.1, pachet PHP complet pentru evaluare

Pagina oficială nu este înlocuită. Acest pachet corectează M3 cu un hero nou, Bere cu bucătari miniaturali, imagini separate WebP/AVIF, text mobil separat de fotografie și taburi pentru Spirtoase.

## Fișierele de folosit

- **`public/meniu.php` este complet și autonom**, aproximativ 32 KB. Include proxy-ul public Supabase, validarea răspunsului, cache-ul de 600 de secunde, fallback-ul, toate textele, header/subsol, HTML complet și JSON-LD. Nu are nevoie de module PHP externe sau de Composer. CSS, JavaScript, fonturile și imaginile sunt fișiere externe.
- `public/assets/images/meniu/`: 20 de ilustrații × 3 lățimi (800/1200/2000 px) × 2 formate (WebP și AVIF), în total 120 de fișiere. `picture` folosește AVIF cu fallback WebP; `srcset` conține toate cele trei lățimi. Hero-ul are `fetchpriority="high"` și încărcare eager; celelalte imagini sunt lazy.
- `public/assets/site.css`: stilurile comune plus blocul M3/M3.1 comentat. Același bloc este separat în `instructiuni-si-prompturi/meniu-adaugiri.css`.
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

Exporturile au lățimile cerute. Sursele existente au 1200–1536 px, iar cele noi au 1536 px (Bere) și 1672 px (hero); exportul de 2000 px este mărit, fără a pretinde detaliu nativ 2K. Manifestul include dimensiunile sursei și ale fiecărui export. Originalele nu sunt suprascrise și compoziția nu este decupată.

Lipsește fotografia reală a coastelor BBQ cu cartofi prăjiți. Porc rămâne listă fără imagine. Tomahawk-ul din fotografia sursă este de porc. Fotografiile din catalog pentru „PENNE Carbonara Original” și dorada cu orez nu corespund complet denumirilor; ilustrațiile au urmat fotografiile. Vezi prompturile și limitele documentate; nu s-au modificat datele.

## Surse de dezvoltare

În Git, implementarea se întreține în `server/lib/menu/`; `scripts/build_menu_php.py` generează fișierul autonom `meniu.php`. Configurarea categorie → imagine este în `server/lib/menu/config.php` și într-un singur bloc în PHP-ul livrat. Nu edita ambele variante separat: pentru dezvoltare editează modulul și regenerează; pentru o instalare simplă poți edita direct blocul de configurare din PHP.

Instrucțiunile cPanel și lista de verificare sunt alături. Activarea rutării oficiale se face numai după aprobarea previzualizării. Nu s-au modificat `meniu.html`, Apache, DNS, baza de date sau Meniul Electronic.
