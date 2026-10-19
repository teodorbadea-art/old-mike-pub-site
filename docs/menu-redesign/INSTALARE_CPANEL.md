# Instalare — numai după aprobarea previzualizării

Acum nu trebuie schimbat nimic online.

1. În cPanel → File Manager intră exclusiv în `public_html/garden.oldmike.ro`. Fă o copie de siguranță a `meniu.html`, `assets/site.css` și `.htaccess` în afara folderului public. Nu lucra direct în `public_html` și nu modifica pagina grupului/Berăria.
2. Pachetul are un folder `public`. Urcă CONȚINUTUL acestuia în folderul Garden: `meniu.php`, `lib/menu/`, `cache/`, fonturi, CSS/JS și imaginile indicate. Păstrează structura; nu urca folderul `public` ca nivel suplimentar.
3. Pentru `cache`,0755este suficient dacă PHP rulează cu utilizatorul contului, așa cum este uzual în cPanel. Dacă găzduirea cere scriere prin grup, folosește0775doar cu grupul corect. Nu folosi0777. Fișierele obișnuite:0644. `cache/.htaccess` trebuie păstrat: blochează citirea cache-ului din browser. `cache/menu.json` din pachet este copia publică bună de pornire, cu timestamp-ul original, nu o copie declarată fals ca fiind proaspătă.
4. Verifică în cPanel că versiunea PHP este8.1sau mai nouă și extensia cURL este activă. mbstring este recomandată; există fallback pentru diacriticele românești.
5. Mai întâi verifică `https://garden.oldmike.ro/meniu.php`:produse,prețuri,imagini,telefon,navigare. Restul site-ului rămâne neschimbat.
6. Doar după verificarea paginii și acordul de lansare, adaugă în `.htaccess` DIN GARDEN, după regulile de HTTPS:

```apache
# BEGIN OLDMIKE MENU M3
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule ^meniu\.html$ meniu.php [END]
</IfModule>
# END OLDMIKE MENU M3
```

Adresa publică rămâne `garden.oldmike.ro/meniu.html`; produsele se randează intern prinPHP. Nu schimba linia `DirectoryIndex index.html index.php`. Nu înlocui întregul `.htaccess` și nu activa execuția PHP pentru toate fișiereleHTML.

7. Verifică apoi `meniu.html`,paginaAcasă,Evenimente,Contactși/admin/. Pentru cache, modifică PUBLICAT un preț în aplicație: pagina îl preia la primul acces după expirarea celor10minute. Nu este un pushinstantaneu. Dacă Supabase este indisponibil, se afișează ultima copie bună, care poate avea prețuri mai vechi până la revenirea conexiunii.
8. Revenire: scoate DOAR blocul `OLDMIKE MENU M3` și restaureazăCSSdinbackupdacăeste necesar. PaginaHTMLprecedentă este păstrată. Datele din Supabase nu au fost schimbate de această instalare.

## Unde se modifică pe viitor

- Produs/preț/disponibilitate:aplicațiaMeniuElectronic, urmat dePublică.
- Categorie nouă:apareautomatca listăfărăimagine. Pentru ilustrație:adaugămaparea în`lib/menu/config.php`și cele3imaginiWebP.
- Oferta de prânz/orele:secțiunea`lunch`dinacelașifișier; aceasta este o configurare separată, confirmată de utilizator.
- Aspect:bloculCSScomentatla sfârșitul`assets/site.css`.
