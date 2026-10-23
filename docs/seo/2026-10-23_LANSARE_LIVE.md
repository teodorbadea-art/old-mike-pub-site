# Old Mike Garden — SEO PUBLICAT LIVE

FACT: publicare autorizată explicit de utilizator prin „ok, confirm”. Instalarea s-a încheiat la `2026-10-23T17:22:23+00:00`, conform raportului de pe hosting. Au fost înlocuite exact șase fișiere în `/home/oldmikero/public_html/garden.oldmike.ro`:

1. index.html
2. contact.html
3. evenimente.html
4. galerie.html
5. confidentialitate.html
6. meniu.php

Numele principal este Old Mike Garden, iar Old Mike Pub rămâne doar denumire alternativă în schema. Au fost păstrate designul, H1-urile editoriale, imaginile, formularele și scripturile runtime. Operatorul OLD MIKE PUB SRL nu a fost redenumit.

## Backup și integritate

- Backup verificat înainte de orice înlocuire: `/home/oldmikero/oldmike-site-private/seo-garden-backup-20261023-172223`.
- Backup-ul conține cele șase fișiere și copii pentru `.htaccess`, `assets/site.css`, `assets/site-runtime.js`, `robots.txt`, `sitemap.xml`, plus `before-sha256.json` și `deployment.json`.
- Pachet verificat în folder privat: `/home/oldmikero/oldmike-site-private/seo-garden-release-20261023`.
- SHA-256 ZIP instalat: `66a5fa21cdb2d745c82eeb7cdbafc8eaae9962bbca9fb8e7f534d46b9f46c23e`.
- Installer CLI: `scripts/deploy_garden_seo.php`. Verifică toate hash-urile live și pachet înainte de backup, verifică backup-ul, pregătește fișierele, reverifică eventuale editări concurente, instalează și verifică din nou. Nu se încarcă în public_html și nu se accesează prin web.
- Fișierele protejate de mai sus sunt identice înainte/după. Nicio modificare DNS, Supabase, Meniu Electronic, Community, oldmike.ro sau Berăria/Parc. Permisiuni fișiere publice 0644; folder backup privat 0700.

## Verificări live efectuate

| Verificare | Rezultat |
|---|---|
| PHP hosting 8.3.36, cURL, mbstring | PASS |
| Toate cele șase fișiere corespund exact hash-urilor aprobate pe server | PASS |
| `/`, `/index.html`, contact, evenimente, galerie, confidențialitate | HTTP 200; HTML identic pachetului aprobat |
| `/meniu.php` și `/meniu.html` | HTTP 200, canonical `https://garden.oldmike.ro/meniu.html` |
| Produse și nutriție | 257 produse, 148 tabele nutriționale pe ambele rute de meniu |
| Cache | 257 produse, 89 secunde vechime la verificarea în terminal, permisiuni 0644 |
| `/cache/menu.json` | HTTP 403 |
| Title, canonical, robots, schema | Garden, URL corect, index,follow, un H1 pe fiecare pagină; Pub alternateName în Restaurant |
| Homepage WebSite | Old Mike Garden; url Garden; alternateName Old Mike Pub |
| `robots.txt`, `sitemap.xml`, CSS și runtime | HTTP 200, nemodificate; `.form-honeypot` păstrat |
| API public evenimente | HTTP 200; lista validă, 0 evenimente publicate la verificare |
| Browser 390 px și 1440 px | Toate cele șase pagini PASS: fonturi, logo, hero unde există, un H1, fără scroll orizontal; navigare mobilă statică funcțională |

Verificările HTTP au folosit un parametru de control și Cache-Control:no-cache pentru a evita răspunsuri intermediare vechi. Homepage-ul fără parametri a fost deschis separat în browser și arată identitatea nouă. Testul Chromium live a vizitat URL-urile obișnuite, fără parametri. Nu au fost trimise cereri de rezervare sau formulare și nu a fost activat formularul de grupuri.

Dovezi: [HTTP](releases/20261023/live-http-results.json), [Chromium 390/1440](releases/20261023/browser-results-live.json), [API evenimente](releases/20261023/live-events-api.json), [arhivă cu 12 capturi LIVE și testul de browser](https://backend.composio.dev/api/v3/sl/x5zQGZEcHJ). Captură separată a paginii principale salvată și afișată utilizatorului la livrare.

Nu declarăm un nou scor Lighthouse, o nouă poziție Google sau indexarea efectivă a meniului. GSC înainte de lansare: proprietate Domain verificată, sitemap Success, homepage indexată; solicitarea indexării meniului confirmată de utilizator. Nu s-au operat modificări în contul GSC sau în Google Business Profile în această lansare.

## Revenire exactă

Din cPanel, restaurează **numai cele șase fișiere enumerate** din backup-ul `seo-garden-backup-20261023-172223` în folderul Garden, cu 0644. Verifică hash-urile lor față de `before-sha256.json`. CSS, runtime, cache, robots, sitemap și `.htaccess` nu necesită restaurare: nu au fost modificate în acest lot.

Repetă HTTP 200, rutarea meniului, cele 257 produse, API evenimente și cache 403. Nu elimina blocul rewrite al meniului și nu folosi pachete HTML preview istorice. Nu rerula `--apply`: guard-ul se oprește după instalare deoarece fișierele live au deja hash-urile noi.

## Pasul următor

Verificarea și completarea profilului Google Maps existent necesită acces de Manager acordat de asociat. Website-ul țintă este `https://garden.oldmike.ro/`; meniul este `https://garden.oldmike.ro/meniu.html`. Nu se creează alt profil. Planul gratuit de conținut, verificările GBP și indicatorii sunt în [auditul SEO](2026-10-23_SEO_GARDEN.md).
