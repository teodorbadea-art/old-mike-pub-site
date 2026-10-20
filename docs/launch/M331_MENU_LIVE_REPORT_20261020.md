# Old Mike Garden — raport de lansare M3.3.1

**Publicat:** 20 octombrie 2026, după reluarea autorizată de la 13:14 (București).  
**Adresă oficială:** https://garden.oldmike.ro/meniu.html  
**Stare:** meniul este live; testul modificării unui preț de către utilizator este încă în așteptare.

## Fișiere instalate

146 de fișiere, exclusiv lista aprobată: 140 noi, 5 existente cu conținut identic și un singur fișier existent cu conținut modificat — `assets/site.css`. Lista exactă este la final.

Au fost instalate `meniu.php`, cele 132 de imagini în WebP/AVIF, CSS/JS/fonturi/logo/favicon și copia inițială de cache cu protecția sa Apache. CSS-ul păstrează importul comun pe prima linie și regula `.form-honeypot` la final. Fișierele au0644, folderul cache0755. Nu s-au înlocuit foldere întregi.

Nicio pagină HTML nu a fost urcată sau înlocuită. Cele7HTML-uri existente și `assets/site-runtime.js` sunt identice cu starea de dinaintea instalării, verificate SHA256. Site-ul grupului, Berăria, aplicația Meniu Electronic și datele Supabase nu au fost modificate.

## Pași și rezultate

| Pas | Rezultat |
|---|---|
|1. PHP și extensii|PASS: MultiPHP Manager pentru Garden PHP8.3; executabilul aferent raportează8.3.35, cURL și mbstring active.|
|2. Backup privat|PASS: site.css și .htaccess copiate și verificate în afara public_html; directoare0700, copii0600. Backupul a fost reverificat la reluarea de la13:14.|
|3. Instalare|PASS:146fișiere verificate înainte și după copiere; niciun HTML sau site-runtime.js modificat.|
|4. meniu.php înainte de activare|PASS: HTTPS200,257produse și prețuri reale; cache primește timestamp proaspăt,257rânduri; acces public la cache/menu.json403.|
|4. Imagini|PASS: toate132imaginile servite prinHTTPS200 și identice SHA256 cu fișierele aprobate; tipuri MIME image/avif și image/webp.|
|4. Browser live|PASS: Chrome pe pagina instalată la1440/390/360px,257produse, fără scroll orizontal, hero mobil4:5, navigare, details, taburi spirtoase și tastatură, fărăJS, reduced motion.|
|4. Fonturi pagini existente|PASS:10probe pe index/contact/evenimente/galerie/confidentialitate, la1440și390px. Linkurile directe cu query către design.css au fost blocate în test pentru a verifica importul din site.css.|
|5. Activare rută|PASS: blocul de mai jos este singura adăugire în .htaccess; eliminarea sa reproduce exact octeții configurației anterioare.|
|6. meniu.html după activare|PASS: HTTPS200,257produse, canonical https://garden.oldmike.ro/meniu.html, meta robots index,follow; Manrope/Cormorant și hero încărcate în browserul live.|
|6. Pagini și evenimente|PASS: toate cele5pagini publiceHTTPS200; APIevents200/JSONvalid; pagina Evenimente încarcă efectiv „Seara de fotbal” și „Seara de Champions League”. Formularul online rămâne în starea dezactivată de server; nu s-au trimis cereri sau emailuri de test.|
|7. Modificare preț de către utilizator|ÎN AȘTEPTARE. Utilizatorul schimbă și publică un preț, comunică produsul/prețul; se verifică pe site în maximum10minute, apoi utilizatorul îl restaurează și se verifică revenirea.|

Cache-ul este de600secunde și se reîmprospătează la primul acces după expirare, nu printr-un push. HTML-ul public are Cache-Control public,max-age=0,must-revalidate. Dacă sursa este indisponibilă rămâne ultima copie bună; această logică a fost verificată în testele PHP cu întrerupere simulată, nu prin oprirea Supabase de producție.

## Modificarea exactă în .htaccess

Inserată după `# END OLDMIKE GARDEN M29`, deci după regulile HTTPS existente și înaintea blocurilor PHP generate de cPanel:

```apache
# BEGIN OLDMIKE MENU M3
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule ^meniu\.html$ meniu.php [END]
</IfModule>
<IfModule mod_mime.c>
AddType image/avif .avif
AddType image/webp .webp
</IfModule>
# END OLDMIKE MENU M3
```

Nu s-au schimbat DirectoryIndex, regulile HTTPS, antetele sau configurarea PHP. Nu s-a adăugat altă regulă Apache.

## Revenire

Backup: `/home/oldmikero/oldmike-site-private/menu-m331-backup-20261020-053829`.

1. În `.htaccess` exclusiv Garden elimină blocul de la `# BEGIN OLDMIKE MENU M3` până la `# END OLDMIKE MENU M3`, inclusiv. Păstrează toate celelalte reguli.
2. Restaurează copia privată `site.css` în `/home/oldmikero/public_html/garden.oldmike.ro/assets/site.css`, cu0644.
3. Verifică meniul vechi la `/meniu.html` și celelalte pagini. HTML-ul vechi este încă pe server și nu a fost modificat. Fișierele noi pot rămâne pe disc; după scoaterea rutei nu înlocuiesc pagina veche. Celelalte5fișiere existente sunt identice cu versiunile dinaintea lansării. Backupul include și copii ale activelor existente.

Starea privată și manifestul instalării sunt în `/home/oldmikero/oldmike-site-private/menu-m331-launch-state.json`; nu sunt expuse public.

## Dovezi

Sursă instalată, cu corecția honeypot: commit5b46575b494b5ee0da7894e95beb2e2e39231d47 pe ramura design/m3-live-menu-editorial. Fiecare fișier a fost comparat cu SHA256 din pachetul aprobat. Copierea s-a făcut atomic dintr-un folder privat, numai în document root-ul Garden.

Verificarea Chrome a paginii instalate înainte de activare: https://github.com/teodorbadea-art/old-mike-pub-site/actions/runs/39407524959 — PASS. Rapoarte/capturi locale: output/menu-launch/live/. Metadatele adresei oficiale și evenimentele au fost verificate din nou după activare. Scorul Lighthouse din CI este pentru randarea de audit; nu este prezentat ca măsurătoare Lighthouse a hostingului live.

## Lista exactă a celor146fișiere instalate

- `meniu.php`
- `assets/brand/favicon-32.png`
- `assets/brand/oldmike-logo-146.webp`
- `assets/fonts/OFL-Cormorant.txt`
- `assets/fonts/OFL-Manrope.txt`
- `assets/fonts/cormorant-garamond-italic-ro.woff`
- `assets/fonts/cormorant-garamond-regular-ro.woff`
- `assets/fonts/manrope-ro-v1.woff`
- `assets/images/meniu/cat-bere-480.avif`
- `assets/images/meniu/cat-bere-480.webp`
- `assets/images/meniu/cat-bere-800.avif`
- `assets/images/meniu/cat-bere-800.webp`
- `assets/images/meniu/cat-bere.avif`
- `assets/images/meniu/cat-bere.webp`
- `assets/images/meniu/cat-burgeri-480.avif`
- `assets/images/meniu/cat-burgeri-480.webp`
- `assets/images/meniu/cat-burgeri-800.avif`
- `assets/images/meniu/cat-burgeri-800.webp`
- `assets/images/meniu/cat-burgeri.avif`
- `assets/images/meniu/cat-burgeri.webp`
- `assets/images/meniu/cat-cafea-480.avif`
- `assets/images/meniu/cat-cafea-480.webp`
- `assets/images/meniu/cat-cafea-800.avif`
- `assets/images/meniu/cat-cafea-800.webp`
- `assets/images/meniu/cat-cafea.avif`
- `assets/images/meniu/cat-cafea.webp`
- `assets/images/meniu/cat-ciorbe-480.avif`
- `assets/images/meniu/cat-ciorbe-480.webp`
- `assets/images/meniu/cat-ciorbe-800.avif`
- `assets/images/meniu/cat-ciorbe-800.webp`
- `assets/images/meniu/cat-ciorbe.avif`
- `assets/images/meniu/cat-ciorbe.webp`
- `assets/images/meniu/cat-cocktailuri-480.avif`
- `assets/images/meniu/cat-cocktailuri-480.webp`
- `assets/images/meniu/cat-cocktailuri-800.avif`
- `assets/images/meniu/cat-cocktailuri-800.webp`
- `assets/images/meniu/cat-cocktailuri.avif`
- `assets/images/meniu/cat-cocktailuri.webp`
- `assets/images/meniu/cat-deserturi-480.avif`
- `assets/images/meniu/cat-deserturi-480.webp`
- `assets/images/meniu/cat-deserturi-800.avif`
- `assets/images/meniu/cat-deserturi-800.webp`
- `assets/images/meniu/cat-deserturi.avif`
- `assets/images/meniu/cat-deserturi.webp`
- `assets/images/meniu/cat-gatite-480.avif`
- `assets/images/meniu/cat-gatite-480.webp`
- `assets/images/meniu/cat-gatite-800.avif`
- `assets/images/meniu/cat-gatite-800.webp`
- `assets/images/meniu/cat-gatite.avif`
- `assets/images/meniu/cat-gatite.webp`
- `assets/images/meniu/cat-gratar-480.avif`
- `assets/images/meniu/cat-gratar-480.webp`
- `assets/images/meniu/cat-gratar-800.avif`
- `assets/images/meniu/cat-gratar-800.webp`
- `assets/images/meniu/cat-gratar.avif`
- `assets/images/meniu/cat-gratar.webp`
- `assets/images/meniu/cat-pasare-480.avif`
- `assets/images/meniu/cat-pasare-480.webp`
- `assets/images/meniu/cat-pasare-800.avif`
- `assets/images/meniu/cat-pasare-800.webp`
- `assets/images/meniu/cat-pasare.avif`
- `assets/images/meniu/cat-pasare.webp`
- `assets/images/meniu/cat-paste-480.avif`
- `assets/images/meniu/cat-paste-480.webp`
- `assets/images/meniu/cat-paste-800.avif`
- `assets/images/meniu/cat-paste-800.webp`
- `assets/images/meniu/cat-paste.avif`
- `assets/images/meniu/cat-paste.webp`
- `assets/images/meniu/cat-peste-480.avif`
- `assets/images/meniu/cat-peste-480.webp`
- `assets/images/meniu/cat-peste-800.avif`
- `assets/images/meniu/cat-peste-800.webp`
- `assets/images/meniu/cat-peste.avif`
- `assets/images/meniu/cat-peste.webp`
- `assets/images/meniu/cat-pizza-480.avif`
- `assets/images/meniu/cat-pizza-480.webp`
- `assets/images/meniu/cat-pizza-800.avif`
- `assets/images/meniu/cat-pizza-800.webp`
- `assets/images/meniu/cat-pizza.avif`
- `assets/images/meniu/cat-pizza.webp`
- `assets/images/meniu/cat-platouri-480.avif`
- `assets/images/meniu/cat-platouri-480.webp`
- `assets/images/meniu/cat-platouri-800.avif`
- `assets/images/meniu/cat-platouri-800.webp`
- `assets/images/meniu/cat-platouri.avif`
- `assets/images/meniu/cat-platouri.webp`
- `assets/images/meniu/cat-porc-480.avif`
- `assets/images/meniu/cat-porc-480.webp`
- `assets/images/meniu/cat-porc-800.avif`
- `assets/images/meniu/cat-porc-800.webp`
- `assets/images/meniu/cat-porc.avif`
- `assets/images/meniu/cat-porc.webp`
- `assets/images/meniu/cat-racoritoare-480.avif`
- `assets/images/meniu/cat-racoritoare-480.webp`
- `assets/images/meniu/cat-racoritoare-800.avif`
- `assets/images/meniu/cat-racoritoare-800.webp`
- `assets/images/meniu/cat-racoritoare.avif`
- `assets/images/meniu/cat-racoritoare.webp`
- `assets/images/meniu/cat-salate-480.avif`
- `assets/images/meniu/cat-salate-480.webp`
- `assets/images/meniu/cat-salate-800.avif`
- `assets/images/meniu/cat-salate-800.webp`
- `assets/images/meniu/cat-salate.avif`
- `assets/images/meniu/cat-salate.webp`
- `assets/images/meniu/cat-spirtoase-480.avif`
- `assets/images/meniu/cat-spirtoase-480.webp`
- `assets/images/meniu/cat-spirtoase-800.avif`
- `assets/images/meniu/cat-spirtoase-800.webp`
- `assets/images/meniu/cat-spirtoase.avif`
- `assets/images/meniu/cat-spirtoase.webp`
- `assets/images/meniu/cat-starters-480.avif`
- `assets/images/meniu/cat-starters-480.webp`
- `assets/images/meniu/cat-starters-800.avif`
- `assets/images/meniu/cat-starters-800.webp`
- `assets/images/meniu/cat-starters.avif`
- `assets/images/meniu/cat-starters.webp`
- `assets/images/meniu/cat-tomahawk-480.avif`
- `assets/images/meniu/cat-tomahawk-480.webp`
- `assets/images/meniu/cat-tomahawk-800.avif`
- `assets/images/meniu/cat-tomahawk-800.webp`
- `assets/images/meniu/cat-tomahawk.avif`
- `assets/images/meniu/cat-tomahawk.webp`
- `assets/images/meniu/cat-vinuri-480.avif`
- `assets/images/meniu/cat-vinuri-480.webp`
- `assets/images/meniu/cat-vinuri-800.avif`
- `assets/images/meniu/cat-vinuri-800.webp`
- `assets/images/meniu/cat-vinuri.avif`
- `assets/images/meniu/cat-vinuri.webp`
- `assets/images/meniu/hero-meniu-480.avif`
- `assets/images/meniu/hero-meniu-480.webp`
- `assets/images/meniu/hero-meniu-800.avif`
- `assets/images/meniu/hero-meniu-800.webp`
- `assets/images/meniu/hero-meniu-mobile-480.avif`
- `assets/images/meniu/hero-meniu-mobile-480.webp`
- `assets/images/meniu/hero-meniu-mobile-800.avif`
- `assets/images/meniu/hero-meniu-mobile-800.webp`
- `assets/images/meniu/hero-meniu-mobile.avif`
- `assets/images/meniu/hero-meniu-mobile.webp`
- `assets/images/meniu/hero-meniu.avif`
- `assets/images/meniu/hero-meniu.webp`
- `assets/menu-editorial.js`
- `assets/oldmike-design.css`
- `assets/site.css`
- `assets/site.js`
- `cache/.htaccess`
- `cache/menu.json`
