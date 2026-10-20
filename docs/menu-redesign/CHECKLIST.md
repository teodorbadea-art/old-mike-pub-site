# Verificare M3.1 — înainte de lansare

## Verificat local

- [x] `meniu.php` autonom: fără `require`/`include` la runtime, randare identică modulelor sursă.
- [x] 44 de probe PHP: cache rece/cald, expirare, întrerupere simulată, ultima copie bună, răspuns invalid, categorie nouă, escaping și toate cele 257 de produse în HTML/JSON-LD.
- [x] Cantitate și alcool inline numai când valorile există; lipsurile nu sunt inventate, procentul de fructe nu este tratat drept alcool.
- [x] Spirtoase: 8 taburi, inclusiv Gin separat din categoria comună, fără pierderi sau duplicări de produse.
- [x] 20 de ilustrații, fiecare în WebP și AVIF la 800/1200/2000 px: 120 de fișiere separate în `assets/images/meniu/`. Dimensiuni și decodare verificate; fără base64.
- [x] Hero diferit de prima pagină și Bere cu bucătari miniaturali; hero eager și `fetchpriority="high"`.

## Verificare browser M3.1

- [x] Chrome la **1440/390/360 px**: fără scroll orizontal, 257 de produse, imagini/fonturi/header/subsol corecte.
- [x] Pe telefon, marginea de sus a etichetei este la cel puțin 16 px sub imaginea hero; fotografie integrală, fără crop.
- [x] Taburi: un singur panou vizibil, Gin are cele trei produse, click și săgeți de tastatură funcționale; fără JavaScript toate listele sunt vizibile și cele 257 de produse rămân în HTML.
- [x] Liste extensibile, navigare activă și mobilă, reducerea animațiilor.
- [x] **PHP cURL HTTPS → RPC real: 257 de produse**, 20.10.2026 04:38 UTC, numai citire.
- [x] Lighthouse mobil pe HTML de producție cu gzip în CI: **96 performanță / 100 accesibilitate / 100 bune practici / 100 SEO**.
- [x] Capturile hero desktop/telefon, Bere și Spirtoase au fost inspectate vizual.

Revizie verificată: `8f6b552f9941fe95383c0eb231f656e45bc1e4b2`.
Rulare: https://github.com/teodorbadea-art/old-mike-pub-site/actions/runs/39370595552

## Înainte de publicare

- [ ] Evaluarea utilizatorului pentru noul hero, Bere și restul paginii.
- [ ] Fotografia reală a coastelor BBQ cu cartofi prăjiți; Porc rămâne temporar listă fără fotografie.
- [ ] PHP 8.1+, extensia cURL, permisiuni de scriere în cache și compresie pe hosting.
- [ ] Instalare și verificarea conexiunii PHP → Supabase pe cPanel Garden, apoi confirmarea reîmprospătării cache-ului după 10 minute.
- [ ] Rutarea `meniu.html` doar după acordul explicit de lansare.
- [ ] Verificare pe telefon real și Lighthouse pe domeniul final; CI nu este dovada performanței cPanel.
