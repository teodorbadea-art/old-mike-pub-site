# Verificare M3.2 — înainte de lansare

## Verificat local

- [x] `meniu.php` autonom: fără `require`/`include` la runtime, randare identică modulelor sursă.
- [x] 50 de probe PHP: cache rece/cald, expirare, întrerupere simulată, ultima copie bună, răspuns invalid, categorie nouă, escaping și toate cele 257 de produse în HTML/JSON-LD.
- [x] Cantitate și alcool inline numai când valorile există; lipsurile nu sunt inventate, procentul de fructe nu este tratat drept alcool.
- [x] Spirtoase: 8 taburi, inclusiv Gin separat din categoria comună, fără pierderi sau duplicări de produse.
- [x] 20 de ilustrații, fiecare în WebP și AVIF la dimensiunea originală și 480/800 px, plus patru surse mobile: 124 de fișiere separate în `assets/images/meniu/`. Dimensiuni și decodare verificate; fără base64.
- [x] Hero diferit de prima pagină și Bere cu bucătari miniaturali; hero eager și `fetchpriority="high"`.

## Verificări browser M3.2

- [x] Meniu în Chrome la **1440/390/360 px**: 257 produse, fără scroll orizontal, imagini/fonturi, taburi/tastatură, navigare și preferința reduced motion.
- [x] Hero mobil selectează efectiv `hero-meniu-mobile-480.avif` la 390 și 360 px; eticheta este sub fotografie, fără suprapunere. Fallback WebP și sursele de800 sunt prezente în pachet.
- [x] **index.html, evenimente.html, galerie.html, contact.html**, fiecare la1440și390px: Manrope și Cormorant încărcate efectiv. În test, linkul direct la `oldmike-design.css?v=1` a fost blocat intenționat; importul din `site.css` a fost suficient.
- [x] PHP cURL real → Supabase: **257 produse**, 20.10.2026 04:49UTC, numai citire.
- [x] Lighthouse mobil pe HTML de producție cu gzip înCI: **95 performanță / 100 accesibilitate / 100 bune practici / 100 SEO**. Scorul pe cPanel se măsoară după instalare.
- [x] Capturile Acasă desktop/telefon și meniu telefon au fost inspectate vizual.

Revizie verificată: `87f93ef385b37a2f56c8418c498aa66f6b62dc43`.
Rulare: https://github.com/teodorbadea-art/old-mike-pub-site/actions/runs/39371579268
Rapoartele din pachet includ proba separată `site-font-checks.json`.

## Înainte de publicare

- [ ] Evaluarea utilizatorului pentru noul hero, Bere și restul paginii.
- [ ] Fotografia reală a coastelor BBQ cu cartofi prăjiți; Porc are secțiune proprie, temporar fără fotografie.
- [ ] PHP 8.1+, extensia cURL, permisiuni de scriere în cache și compresie pe hosting.
- [ ] Instalare și verificarea conexiunii PHP → Supabase pe cPanel Garden, apoi confirmarea reîmprospătării cache-ului după 10 minute.
- [ ] Rutarea `meniu.html` doar după acordul explicit de lansare.
- [ ] Verificare pe telefon real și Lighthouse pe domeniul final; CI nu este dovada performanței cPanel.
