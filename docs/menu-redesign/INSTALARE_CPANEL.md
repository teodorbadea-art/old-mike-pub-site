# Instalare — numai după aprobarea previzualizării

Acum nu trebuie schimbat nimic online.

1. **Verifică mai întâi PHP minimum 8.1 și extensia cURL activă** în cPanel → Select PHP Version / MultiPHP Manager. Codul folosește `array_is_list` și `mixed`; mbstring este recomandată pentru formatarea numelor. Apoi, în cPanel → File Manager, intră în folderul domeniului **garden.oldmike.ro** (în configurația actuală: `public_html/garden.oldmike.ro`). Verifică document root-ul domeniului înainte de upload. Salvează copii ale `meniu.html`, `assets/site.css` și `.htaccess` în afara folderului public.
2. Urcă **conținutul** folderului `public` din pachet: `meniu.php`, `cache/`, fonturile, CSS/JS și imaginile. Păstrează structura; nu crea încă un nivel numit `public` în domeniu. Nu modifica site-ul grupului sau Berăria.
3. Folderul `cache` trebuie să permită scrierea pentru PHP. În cPanel, de obicei este suficient **0755**. Dacă găzduirea cere scriere prin grup, folosește **0775** cu grupul corect; nu folosi 0777. Fișierele obișnuite: **0644**. Păstrează `cache/.htaccess`. Fișierul `meniu.php` este autonom și nu cere module în `lib/`. `cache/menu.json` este copia reală de pornire, cu timestamp-ul original, pentru cazul în care prima conexiune nu reușește.
4. Deschide local `public/previzualizare.html` numai după dezarhivare, cu folderul `assets` lângă el. PHP-ul live se testează pe un server PHP, nu prin deschiderea directă a fișierului în browser.
5. Verifică mai întâi `https://garden.oldmike.ro/meniu.php`: produse, prețuri, imagini, telefon și navigare. Verifică dacă timestamp-ul cache-ului se actualizează după acces. Dacă nu, verifică permisiunile și conexiunea HTTPS către Supabase în logul PHP al găzduirii. Nu activa afișarea erorilor pe pagina publică.
6. După verificare și acordul de lansare, adaugă în `.htaccess` **al domeniului Garden**, după regulile HTTPS și înaintea regulilor generale care pot captura pagina:

```apache
# BEGIN OLDMIKE MENU M3
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule ^meniu\.html$ meniu.php [END]
</IfModule>
# END OLDMIKE MENU M3
```

Adresa publică rămâne `garden.oldmike.ro/meniu.html`. Nu înlocui întregul `.htaccess`, nu schimba `DirectoryIndex` și nu activa PHP pentru toate fișierele HTML.

7. Verifică `meniu.html`, Acasă, Evenimente și Contact. Publică o modificare de preț în Meniul Electronic: site-ul o preia la primul acces după expirarea celor 10 minute. Dacă Supabase este indisponibil, pagina păstrează ultima copie bună.
8. Revenire: elimină doar blocul `OLDMIKE MENU M3` și restaurează CSS-ul din backup dacă este necesar. Pagina HTML veche rămâne păstrată. Instalarea nu modifică datele Supabase.

## Formate și compresie pentru performanță

Pachetul include un fragment Apache, separat și inactiv, cu MIME `image/avif`/`image/webp` și `mod_deflate` pentru CSS/JS/HTML. După aprobare, activează această compresie dacă hostingul nu o aplică deja. PHP negociază gzip pentru HTML când compresia PHP nu este deja activă. Auditul Lighthouse simulează această configurare; scorul final depinde și de hosting.

## Modificări ulterioare

- Produse, prețuri și disponibilitate: în Meniul Electronic, apoi publicare.
- Categorie nouă: apare automat ca listă. Pentru ilustrație, adaugă maparea în blocul „CATEGORY → IMAGE” din `meniu.php` și originalul și variantele de 480/800 în WebP și AVIF, în `assets/images/meniu/`.
- Oferta de prânz și intervalul: cheia `lunch` din blocul de configurare al `meniu.php`, separat de produsele live.
- Design: blocul comentat de la finalul `assets/site.css`; culorile și fonturile comune sunt în `assets/oldmike-design.css`.
