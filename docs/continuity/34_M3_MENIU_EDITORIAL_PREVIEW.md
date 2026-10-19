# M3 — meniu complet, evaluat separat de site-ul oficial

Utilizatorul a autorizat noul pas: toate produsele și prețurile în site, prin RPC-ul public Supabase, cu cache PHP. Aceasta înlocuiește amânarea de la M2.12. **Vrea să vadă înainte de postarea oficială. Nu publica acum.** Nu modifica meniul electronic, datele restaurantului, domenii/DNS sau Berăria.

## Implementare

Ramura GitHub: `design/m3-live-menu-editorial`, exclusă de la deploy Vercel. `meniu.html` și `.htaccess` oficiale sunt păstrate. Nu s-a făcut upload cPanel.

Intrare `meniu.php`; module `server/lib/menu/{data,config,render}.php`, header/footer comune. În pachet modulele sunt `public/lib/menu/`. Cache `cache/menu.json`, 600 secunde, lock și scriere atomică, ultima copie validă la întrerupere; copie inițială reală în pachet. Produse și prețuri server-rendered + JSON-LD; doar citire RPC public, fără chei secrete.

Unicul loc pentru categorie → imagine este `config.php`; necunoscutele devin liste compacte. 13 ilustrații noi, 8 mâncare + 5 băuturi, 39 WebP-uri. 6 imagini existente păstrate; hero existent. Variante responsive suplimentare pentru originale. Manrope și Cormorant locale, paleta cerută în `assets/oldmike-design.css`; bloc CSS M3 separat și în `docs/menu-redesign/meniu-adaugiri.css`.

Oferta de prânz confirmată de utilizator: **luni–vineri 12:00–16:00, 33,99 lei, ciorbă + fel de bază + garnitură**. Este configurare separată: RPC-ul produselor nu expune oferta.

## Verificat

- 32 teste PHP, inclusiv cache/outage simulat, categorie nouă, invalid JSON, escaping, 257 produse HTML și JSON-LD.
- RPC real din PHP cURL HTTPS în GitHub Actions: **257 produse**, 19.10.2026 22:19 UTC.
- Chrome 1440/390/360: fără scroll orizontal; fonturi, imagini, header/footer, details, navigare activă și mobilă, reduced motion.
- Lighthouse mobil pe HTML de producție servit cu gzip în CI: **99 performanță / 100 accesibilitate / 100 bune practici / 100 SEO**; FCP 1,1 s, LCP 2,0 s, CLS 0,008, TBT 0 ms. Rularea anterioară: 95/100/100/100.
- Revizie verificată: `ff584f822d8afc9ed4b8cbda12c7b0e76545d19f`.
- Workflow: https://github.com/teodorbadea-art/old-mike-pub-site/actions/runs/39334089152

Nu confunda CI cu cPanel. În runtime-ul local PHP cURL a avut timeout, dar Python HTTPS a funcționat și proba PHP din CI a trecut. Pe cPanel trebuie verificat separat cURL și dreptul de scriere; înainte de rutarea oficială se aprobă previzualizarea. Scorurile pe hosting se măsoară după instalare.

## Livrabile

`output/menu-m3/Old-Mike-Meniu-Previzualizare.html`: un fișier autonom, cu produse reale din snapshot și banner/noindex. Nu este live; versiunea PHP din ZIP este live.

`output/menu-m3/Old-Mike-Meniu-M3-Pachet.zip`: public/ cu surse, assets și cache inițial; instrucțiuni, CSS separat, prompturi/alt; rapoarte CI; fragment Apache separat INACTIV. Script: `scripts/package_menu_m3.py`; regenerare preview: `scripts/build_menu_preview.py` (PHP necesar). Nu publica arhiva ca întreg sau folderul de instrucțiuni în document root.

## Rest de lucru

1. Cere fotografia reală a coastelor BBQ cu cartofi prăjiți. Categoria Porc este listă, fără imagine. Nu genera din alt preparat.
2. Evaluarea utilizatorului pentru design și imagini.
3. Doar după acord: cPanel Garden, test PHP live/cache/compresie, rutare `meniu.html` → `meniu.php`, Lighthouse și telefon real.

Fotografiile-sursă pentru generare au fost descărcate din catalog. Tomahawk-ul este de porc. Sursa „PENNE Carbonara Original” arată paste panglică; dorada menționată cu orez este fotografiată cu legume și midii. Imaginile au urmat fotografiile, fără schimbări în catalog. Nepotrivirile sunt documentate în `food-image-prompts.md`.
