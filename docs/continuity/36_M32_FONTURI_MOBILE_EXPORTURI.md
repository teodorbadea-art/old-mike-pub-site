# M3.2 — fonturi comune și corecții suplimentare, nepublicate

Utilizatorul a rulat PHP-ul M3 local și a cerut suplimentar: repararea dependenței fonturilor pe restul site-ului, sursa mobilă verificată după fișierul livrat, fotografie Porc din poza reală, normalizare alcool, PHP8.1+cURL la primul pas al instalării. A ANULAT exporturile2000px: originale1536cu480/800sunt suficiente. Aceste cerințe au prioritate față de M3.1.

## Implementat

- Prima linie `assets/site.css`: `@import url('oldmike-design.css');`. `--sans`/`--serif` au familii de rezervă. Fonturi locale; nu depind de linkul separat din HTML.
- `picture()` verifică `-mobile-800.webp`, fără să ceară un master `-mobile.webp` inexistent. Mobile sources AVIF/WebP480/800. Hero-ul nou rămâne imagine completă16:9deasupra textului pe mobil; niciun bucătar nu este acoperit/decupat.
- 20ilustrații ×native+480/800 ×WebP/AVIF, plus4surse mobile =124fișiere. Masterele au maximum1536px; cele de1200 rămân1200, fără upscale. Exporturile1200/2000cu sufix au fost șterse din ramura curentă. Manifestcurent:m32-image-manifest.json; manifestulM3.1nu se livrează.
- Porc are secțiune proprie, link în navigare și mapare `meniu/cat-porc`, fără să cadă în Și altele. Când nu există fișierul foto, secțiunea este text, fără placeholder tehnic sau spațiu gol. Apariția fișierului activează figura automat.
- 40%alc/alcool este normalizat la40%în afișare și JSON-LD. Valorile necunoscute nu sunt inventate. Datele din Supabase rămân neschimbate.
- Primul pas al instalării: PHP minimum8.1+cURL. `meniu.php` rămâne complet/autonom, fără require/include la runtime.

## BLOCAJ imagine Porc — sursa reală lipsește

Verificat20.10în RPC și SQL numai citire, strict OldMike:
- produs publicOM-0080„Coaste de Porc cu Sos Bbq si Cartofi Steak House”: media_storage_path și media_alt_text NULL;
- restaurant94e9c174-e9d6-4cdb-a912-b2a7e7a8f2db, produsfb1d1657-01da-45d9-82ac-cf5300bd83b9;
- media_assets pentru produs sau nume/alt conținândcoast: niciun rând;
- products: o singură înregistrare corespunzătoare, fără alt produs duplicat cu fotografie.

Nu s-a generat alt preparat. Cere fotografia reală utilizatorului; după primire: imagine cu bucătar miniatural care glazurează coastele cu bidineaua, fără schimbarea farfuriei/ingredientelor, apoi fișierecat-porc.webp/.avif și480/800. Aceasta rămâne singura cerință vizuală neînchisă. Nu confunda secțiunea pregătită cu o imagine deja generată.

## Verificări PASS

Revizie87f93ef385b37a2f56c8418c498aa66f6b62dc43.
Rulare https://github.com/teodorbadea-art/old-mike-pub-site/actions/runs/39371579268

- 50probePHP: cache rece/cald/expirare/întrerupere/invalid/newcategory/XSS,257HTML+JSON-LD,ABV,taburi,Gin,mobilefilecheck,srcset,nobase64,nofiles2000.
- PHP autonom identic rendererului modular, build --check PASS.
- PHPcURLHTTPSreal:257produse la04:49UTC/07:49București.
- Chrome1440/390/360: meniu fără overflow,257produse,fonturi,hero,mobile source ales efectiv,details,taburi/keyboard,navigare,reducedmotion,fărăJS.
- Fonturi pe index.html,evenimente.html,galerie.html,contact.html, fiecare1440/390. LinkulHTMLdirectoldmike-design.css?v=1este intenționat blocat; importulsite.cssîncarcă Manrope și Cormorant. Sunt verificate CSSvars,computedfamily și FontFace.status loaded.8probePASS însite-font-checks.json.
- Lighthouse95performanță/100accesibilitate/100bunepractici/100SEO, pe HTMLproducție cu gzip înCI. Nu este audit cPanel.
- Capturi inspectate: index-fonturi-1440.png,index-fonturi-390.png,viewport-390.png.

## Livrare și limite

Directmeniu.phpînrepoșiZIPoutput/menu-m3/Old-Mike-Meniu-M3-Pachet.zip. ZIPactualizatcuPHPautonom,CSS/fonturi,124media,cacheinițialreal,previzualizare.htmlcuassetsexterne,instrucțiuni/prompturi și4rapoarteCI. Fărăbase64. Dezarhivează înainte de deschiderea previzualizării.

Nicio modificare cPanel,Apache,DNS,producție sau bază de date. Ramura design/m3-live-menu-editorial rămâne exclusă de laVercel. Nu publica înainte de aprobarea utilizatorului. Oferta de prânz rămâne configurată separat (RPC nu o expune),33,99lei,luni–vineri12–16,confirmată anterior.
