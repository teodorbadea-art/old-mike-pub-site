# Verificare finală — înainte de lansare

## Verificat

- [x] 257 de produse reale în copia de evaluare; conexiunea reală PHP cURL → Supabase verificată în CI, numai pentru citire.
- [x] 32 de verificări PHP trecute: cache rece/cald, întrerupere, răspuns invalid, formatare, escaping, categorie nouă și 257 de elemente în HTML/JSON-LD.
- [x] Supabase indisponibil, simulat: se afișează ultima copie bună; un răspuns invalid nu o înlocuiește.
- [x] Categorie nouă: apare automat în „Și altele”, fără imagine și fără schimbarea configurării.
- [x] Chrome la 390 și 360 px: fără scroll orizontal; liste extensibile, navigare mobilă și categorie activă funcționale.
- [x] Chrome la 1440 px: structură editorială, imagini mari, fonturi locale și header/subsol prezente.
- [x] Preferința pentru reducerea animațiilor este respectată; focus vizibil și controale principale de minimum 44 px.
- [x] 13 imagini noi, cu 39 de WebP-uri valide; imaginile existente și logo-ul oficial sunt păstrate, cu variante optimizate.
- [x] Lighthouse mobil, audit CI pe HTML-ul de producție cu gzip: **99 performanță, 100 accesibilitate, 100 bune practici, 100 SEO**. LCP 2,0 s; CLS 0,008; TBT 0 ms. Raport măsurat pentru revizia `ff584f822d8afc9ed4b8cbda12c7b0e76545d19f`.

Auditul folosește un server de evaluare comprimat și date reale din snapshot, nu găzduirea cPanel. Previzualizarea autonomă rămâne `noindex`; varianta PHP de producție are `index,follow`. Scorul de pe domeniul final se verifică după instalare, deoarece depinde și de hosting.

## De închis înainte de publicare

- [ ] Fotografia reală a coastelor BBQ cu cartofi prăjiți; apoi generarea și aprobarea ilustrației Porc. Între timp, categoria este listă fără imagine.
- [ ] Aprobarea vizuală a utilizatorului: desktop, telefon și toate ilustrațiile.
- [ ] Instalarea în document root-ul Garden; PHP 8.1+, cURL, cache cu drept de scriere și compresie activă.
- [ ] Verificarea transportului HTTPS PHP → Supabase de pe găzduirea cPanel; confirmarea reîmprospătării cache-ului după 10 minute.
- [ ] Activarea rutării `meniu.html` numai după acordul de lansare.
- [ ] Verificarea finală pe un telefon real și pe domeniul public: linkuri, apel, navigare, prețuri și Lighthouse.
