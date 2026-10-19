# Old Mike — meniu editorial M3, pentru aprobare

Pagina oficială nu a fost înlocuită. Previzualizarea autonomă se deschide fără instalare și conține produsele și prețurile reale din copia de evaluare. Fișierul PHP este varianta care citește datele live.

## Ce conține pachetul

- `public/meniu.php`: intrarea PHP, cu HTML complet pentru vizitatori și motoare de căutare.
- `public/lib/menu/data.php`: apelul public Supabase și cache-ul de 10 minute, cu ultima copie bună la întrerupere. Nu scrie în baza de date și nu folosește chei secrete.
- `public/lib/menu/config.php`: unicul loc pentru maparea categorie → imagine, texte editoriale și oferta de prânz. Categoriile necunoscute apar automat în „Și altele”.
- `public/lib/menu/render.php`: afișarea și JSON-LD Restaurant/Menu, cu toate produsele și prețurile.
- `public/assets/site.css`: stilurile existente, urmate de blocul comentat „OLD MIKE M3 · MENIU EDITORIAL LIVE”. Același bloc este livrat separat în `instructiuni-si-prompturi/meniu-adaugiri.css`.
- Fonturi locale Manrope și Cormorant, navigarea comună, ilustrații WebP și variante responsive.
- 13 ilustrații noi: 8 pentru mâncare și 5 pentru băuturi. Imaginile existente sunt păstrate. Prompturile exacte și textele alternative sunt în documentele de imagini; textele alternative folosite pe pagină sunt în configurare.

## Actualizarea datelor

RPC-ul public întoarce numai produsele publicate și disponibile pentru restaurantul `old-mike`, locația `loc-oldmike-matei`. Copia folosită la evaluare are 257 de produse; PHP nu fixează acest număr și acceptă produse/categorii noi.

Cache-ul se reîmprospătează la primul acces după expirarea celor 10 minute. Nu este actualizare instantanee prin push. Scrierea este atomică și protejată prin lock. Un răspuns invalid nu înlocuiește copia bună. La întrerupere se păstrează ultima copie, chiar dacă are prețuri mai vechi. O listă goală validă este tratată ca meniu gol legitim. Fără nicio copie disponibilă rămân telefonul și legătura spre meniul electronic, fără mesaje tehnice.

Oferta de prânz este configurată separat: **33,99 lei, ciorbă + fel de bază + garnitură, luni–vineri, 12:00–16:00**, conform confirmării utilizatorului. RPC-ul produselor nu expune această ofertă; schimbarea ei se face în `config.php`.

## Fotografii și limite

Imaginile de mâncare au pornit de la fotografii reale publicate în Meniul Electronic. Tomahawk-ul fotografiat este de porc. Produsul „PENNE Carbonara Original” are o fotografie cu paste panglică; dorada menționată cu orez în nume apare fotografiată cu legume și midii. Ilustrațiile au urmat fotografiile; datele catalogului nu au fost modificate. Aceste nepotriviri merită verificate separat în catalog.

**Lipsește fotografia reală a coastelor BBQ cu cartofi prăjiți.** Categoria porc apare temporar ca listă, fără fotografie și fără spațiu gol. Este necesară fotografia restaurantului pentru ultima ilustrație. Generarea reconstruiește fotografia și poate modifica detalii fine; imaginile trebuie aprobate vizual înainte de lansare.

## Verificări și lansare

32 de verificări PHP au trecut: cache rece/cald, întrerupere simulată, răspuns invalid, categorie nouă, formatare, escaping, toate cele 257 de produse în HTML și JSON-LD. Endpoint-ul real a returnat 257 de produse prin PHP cURL HTTPS în CI. Conectivitatea PHP de pe cPanel trebuie verificată înainte de lansare.

Chrome a verificat afișarea la 1440, 390 și 360 px, fără scroll orizontal, fonturile, imaginile, listele extensibile, navigarea activă, meniul mobil și reducerea animațiilor. Scorurile Lighthouse și verificările rămase sunt în `CHECKLIST.md`.

Nu s-au schimbat `meniu.html`, rutarea Apache, DNS sau baza de date. Ramura `design/m3-live-menu-editorial` nu publică automat pe Vercel. Instrucțiunile cPanel se execută numai după aprobarea previzualizării.
