# M3.1 — șase corecții cerute de utilizator, nepublicate

După evaluarea pozitivă a M3, utilizatorul a cerut: PHP complet, fără base64; exporturi WebP+AVIF 800/1200/2000; text mobil în afara fotografiei; Bere cu bucătari la dozator/spumă; hero diferit de homepage, cu masă bogată; cantitate și alcool inline plus taburi la Spirtoase. Toate implementate. Nu a autorizat publicarea.

## Rezultat

`meniu.php` este acum fișier PHP AUTONOM de ~32 KB. Include proxy-ul, validarea, cache-ul de600s, fallback-ul, mapping/config, header/footer și HTML/JSON-LD. Fără require/include în producție. Modulele `server/lib/menu/` rămân surse de dezvoltare; `scripts/build_menu_php.py` generează PHP-ul. Rulează `--check` pentru a evita divergența. Nu edita independent ambele variante.

20 ilustrații (19 categorii +hero),120fișiere în `assets/images/meniu/`, trei lățimi ×douăformate. Hero AVIF-first/WebPfallback, fetchpriority high/eager; categorii lazy. Toate exporturile verificate. Originalele vechi păstrate. Sursele nu sunt nativ2K: imagine nouăBere1536×1024,hero1672×941; exportul2000este mărit, fără promisiune de detaliu nativ2K. Manifest și prompturi în docs/menu-redesign/m31-*.

Hero: masă întreagă cu single-pattyburger,pizza,platou,halbe,5bucătari. Desktop: titlu/CTA deasupra imaginii mari; mobil: imagine completă16:9, text dedesubt. Nicio suprapunere peste bucătari. Bere: generică, fără etichete/mărci; bucătar la dozator +bucătar cu lopățică de spumă.

Cantitate +ABVinline (40 ml ·40%) numai din valori publicate explicit în descriere sau nume. Procentul nu este inventat. Nu se confundă procentul de fructe cu alcoolul. Din categoria comunăLichior/vermuth/gin,3ginuri sunt separate în afișare prinregula de nume dinconfig. Celelalte rămân înLichiorși vermut.8taburi ARIA, rovingtabindex,săgeți/Home/End; fără JS toate listele vizibile. Datele sursă și categoriile publicate nu au fost modificate. Martini categorisit Tequilla este anomalie sursă documentată, nu corectată implicit.

## Verificări

- 44 PHP probe: cache rece/cald/întrerupere/invalid/actualizare/categorie nouă/XSS,257rânduriHTML și JSON-LD, ABV, Gin, completitudine, srcset, zero base64.
- PHP autonom și renderer modular produc același main/footer; build --check PASS.
- PHP cURL real HTTPS →Supabase:257produse,20.10.2026 04:38UTC.
- Chrome1440/390/360: fără overflow,imagini/fonturi,header/footer,details,navigareactivă/mobilă,reducedmotion,taburi/tastatură; hero mobil separat cu minimum16px; fără JS257produse și panouri vizibile.
- Lighthouse peHTMLdeproducție cu gzip înCI:96/100/100/100. Nu este audit cPanel.
- Revizie verificată:8f6b552f9941fe95383c0eb231f656e45bc1e4b2.
- Rulare: https://github.com/teodorbadea-art/old-mike-pub-site/actions/runs/39370595552
- Capturi inspectate:output/menu-m3/{viewport-390,preview-desktop-top,spirtoase-390,bere-390}.png.

## Livrare și continuare

ZIP actualizat:output/menu-m3/Old-Mike-Meniu-M3-Pachet.zip. Conține public/meniu.php autonom, assets externe, cache inițial real, previzualizare.html cu fișiere relative (dezarhivează înainte de deschidere), instrucțiuni/prompturi/manifest și rapoarte. Nu mai include previzualizare base64. Rutarea Apache și MIME/compresie sunt într-un fragment separat INACTIV, numai după aprobare. Builder:scripts/package_menu_m3.py.

Nu s-au atins cPanel,Apache,DNS,MeniuElectronic sau DB. Ramura design/m3-live-menu-editorial este exclusă de laVercel. Fotografia reală a coastelorBBQ încă lipsește; Porc este listă fărăimagine. Oferta de prânz este separată deRPC:33,99lei,luni–vineri12:00–16:00,confirmată anterior de utilizator.
