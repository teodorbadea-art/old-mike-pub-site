# Verificare M3.2 — înainte de lansare

## Verificat local

- [x] `meniu.php` autonom: fără `require`/`include` la runtime, randare identică modulelor sursă.
- [x] 50 de probe PHP: cache rece/cald, expirare, întrerupere simulată, ultima copie bună, răspuns invalid, categorie nouă, escaping și toate cele 257 de produse în HTML/JSON-LD.
- [x] Cantitate și alcool inline numai când valorile există; lipsurile nu sunt inventate, procentul de fructe nu este tratat drept alcool.
- [x] Spirtoase: 8 taburi, inclusiv Gin separat din categoria comună, fără pierderi sau duplicări de produse.
- [x] 20 de ilustrații, fiecare în WebP și AVIF la original/480/800 px: 124 de fișiere separate în `assets/images/meniu/`. Dimensiuni și decodare verificate; fără base64.
- [x] Hero diferit de prima pagină și Bere cu bucătari miniaturali; hero eager și `fetchpriority="high"`.

## Verificări browser M3.2

Se rulează din nou Chrome 1440/390/360 pentru meniu și separat index.html, evenimente.html, galerie.html, contact.html la1440/390, inclusiv încărcarea fonturilor doar prin importul site.css. Rezultatele M3.1 (96/100/100/100) sunt istorice și nu se declară automat pentru M3.2.

## Înainte de publicare

- [ ] Evaluarea utilizatorului pentru noul hero, Bere și restul paginii.
- [ ] Fotografia reală a coastelor BBQ cu cartofi prăjiți; Porc are secțiune proprie, temporar fără fotografie.
- [ ] PHP 8.1+, extensia cURL, permisiuni de scriere în cache și compresie pe hosting.
- [ ] Instalare și verificarea conexiunii PHP → Supabase pe cPanel Garden, apoi confirmarea reîmprospătării cache-ului după 10 minute.
- [ ] Rutarea `meniu.html` doar după acordul explicit de lansare.
- [ ] Verificare pe telefon real și Lighthouse pe domeniul final; CI nu este dovada performanței cPanel.
