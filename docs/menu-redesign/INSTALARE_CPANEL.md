# Instalare M3.3.1 — numai după aprobarea previzualizării

**Nicio pagină HTML existentă nu se înlocuiește.** Păstrează integral `index.html`, `contact.html`, `evenimente.html`, `galerie.html`, `confidentialitate.html` și orice alt HTML live, inclusiv metadatele SEO, JSON-LD și încărcarea `assets/site-runtime.js`. Pachetul nu livrează pagini HTML în `public/`.

1. Verifică **PHP minimum 8.1 cu extensia cURL** în cPanel → Select PHP Version / MultiPHP Manager. Codul folosește `array_is_list` și `mixed`; mbstring este recomandată. Identifică document root-ul **garden.oldmike.ro** (configurația actuală: `public_html/garden.oldmike.ro`). Fă backup pentru fișierele CSS/JS care vor fi înlocuite, meniul vechi și `.htaccess`, în afara folderului public.
2. Din folderul `public/` al pachetului urcă **numai** următoarele, păstrând căile relative în document root-ul Garden:

| Fișier / folder livrat | Destinație |
|---|---|
| `meniu.php` | `meniu.php` |
| `assets/site.css` | `assets/site.css` |
| `assets/oldmike-design.css` | `assets/oldmike-design.css` |
| `assets/site.js` | `assets/site.js` |
| `assets/menu-editorial.js` | `assets/menu-editorial.js` |
| `assets/fonts/` — fonturile locale și licențele livrate | `assets/fonts/` |
| `assets/images/meniu/` — toate cele 132 de imagini WebP/AVIF livrate | `assets/images/meniu/` |
| `assets/brand/favicon-32.png`, `assets/brand/oldmike-logo-146.webp` | aceleași căi în `assets/brand/` |
| `cache/.htaccess` | `cache/.htaccess` |
| `cache/menu.json` — copia de pornire; doar la prima instalare | `cache/menu.json` |

Lista exactă a tuturor fișierelor publice este în `FISIERE_DE_URCAT.txt`, la rădăcina pachetului. Combină folderele cu cele existente; **nu șterge sau înlocui întregul folder `assets` ori `cache`**. Nu suprascrie `assets/site-runtime.js`, API-ul, administrarea sau configurațiile existente. La actualizări păstrează cache-ul live mai nou, nu îl înlocui cu copia de pornire.

3. Folderul `cache` trebuie să permită scrierea pentru PHP: de obicei **0755**; dacă hostingul cere grup comun, **0775** cu grupul corect. Nu folosi 0777. Fișiere obișnuite: **0644**. Păstrează `cache/.htaccess`, care blochează accesul public. PHP creează automat fișierele de lock/retry și `menu.stale-warning` pentru limitarea jurnalului la un avertisment pe oră.
4. Pentru evaluare offline, deschide `previzualizare.html` din rădăcina pachetului dezarhivat, cu folderul `public` lângă el. **Nu urca această previzualizare sau instrucțiunile în document root.** PHP live se testează prin server PHP.
5. Înainte de activarea rutei oficiale, verifică `https://garden.oldmike.ro/meniu.php`: produse/prețuri, imagini, navigare și telefon. Verifică și paginile existente, inclusiv Evenimente, Contact și Confidențialitate. Importul de pe prima linie din `site.css` încarcă fonturile fără editarea HTML-urilor. Pentru testarea formularului live nu trimite cereri fictive.
6. Numai după aprobarea lansării, adaugă în `.htaccess` **al domeniului Garden**, după regulile HTTPS și înaintea regulilor generale:

```apache
# BEGIN OLDMIKE MENU M3
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule ^meniu\.html$ meniu.php [END]
</IfModule>
# END OLDMIKE MENU M3
```

Nu înlocui întregul `.htaccess`, nu schimba `DirectoryIndex` și nu activa PHP pentru toate HTML-urile. Pagina `meniu.html` existentă rămâne păstrată; ruta o poate afișa prin PHP fără editarea ei. Site-ul grupului și Berăria nu fac parte din instalare.

7. La primul acces după 10 minute PHP reîmprospătează cache-ul din Supabase. La întrerupere folosește ultima copie bună. Verifică jurnalul PHP dacă nu se actualizează; nu activa afișarea erorilor pentru clienți. Un produs invalid este omis și înregistrat în jurnal, fără să blocheze celelalte produse. Un cache mai vechi de 60 de minute produce cel mult un avertisment pe oră.
8. Revenire: elimină doar blocul de rutare de mai sus și restaurează fișierele CSS/JS din backup dacă este necesar. Nu restaura pagini HTML din acest pachet: nu există astfel de pagini în folderul de urcat.

## Compresie și modificări ulterioare

Fragmentul Apache livrat separat este inactiv. După aprobare, verifică MIME pentru AVIF/WebP și compresia CSS/JS/HTML; PHP negociază gzip dacă nu este deja activ. Performanța finală depinde de hosting.

Produsele și prețurile se gestionează în Meniul Electronic. Oferta de prânz rămâne configurată separat în cheia `lunch` din `meniu.php`. Maparea categorie → imagine se află într-un singur bloc din PHP; culorile/fonturile sunt în `assets/oldmike-design.css`, iar stilurile meniului în blocurile comentate din `assets/site.css`.
