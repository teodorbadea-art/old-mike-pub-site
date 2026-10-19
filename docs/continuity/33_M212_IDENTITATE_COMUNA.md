# M2.12 — aceeași casă: Site + Meniu Electronic

USER VALIDATED20.10.2026: font de text ales pentru ambele; antet cu logo oficial, Sună și navigare; subsol cu adresă, program și legături; variabile CSS comune. Clarificare prioritară: punctul3 (meniul complet/prețurile live în site, sincronizate Supabase/SEO) este AMÂNAT. Nu implementa meniu-nou.php în această etapă. URL-ul returnează404, nu există în sursele recuperate.

DECISION: Manrope400–700 pentru text/prețuri/butoane/navigare, Cormorant400 regular/italic pentru titluri. Manrope Latin+Română WOFF34,068bytes, verificat cmapĂăÂâÎîȘșȚț; SIL OFL inclusă, sursă Google Fonts oficială. Fără fonturi sau servicii externe la runtime.

FACT LOCAL: assets/oldmike-design.css este sursa canonică pentru culori, fonturi și spațieri. assets/oldmike-shell.js este sursa comună pentru antet/subsol. Python generează HTML-ul site-ului din același modul JS care rulează în meniu, fără dependență Node pe cPanel. scripts/sync_menu_brand.py sincronizează9resurse în checkout-ul existent Meniu Electronic; --check verifică identitatea byte-cu-byte. Copii locale în ambele aplicații pentru încărcare fără dependențe între domenii. Datele publice sunt generate din data/site.json; nu există DB comună nouă.

Meniu Electronic: shell numai pe restaurantulold-mike/locațialoc-oldmike-matei; lista și detaliul produsului au identitatea comună și legături de întoarcere. Alte restaurante și rolurile administrative rămân izolate. Contextul public SSR este păstrat la bootstrap pentru intrarea generală, fără revenire la meniul demo. Miniaturile/PDF și gate-urile alergenilor rămân cele dinV571.

Verificări efectuate:28suite Node PASS în meniul electronic;8pagini site și resurse/ancore/fonturi/logo/metadate PASS; sincronizare9resurse PASS; pachete cPanel/Garden + manifest/arhivă PASS. Test Chrome nou desktop390px/mobil, fonturi, navigare, detaliu/înapoi este pregătit pentru CI; nu este încă un rezultat. CI precedent2d5acc93 a fost verificat completed/success (39322121026).

LIVE NEAPLICAT: cPanel răspunde la pagina de login, sesiunea invalidă; nu s-a autentificat și nu s-au modificat fișiere pe server. Nu există deploy nou Vercel sau migrare hosted executată în această etapă. Pagina grupului, Berăria, Community, DNS și email sunt excluse.

Pachet incremental Garden:output/old-mike-garden-brand-m212.zip,11fișiere,65,780bytes. Updater CLI server/update-brand-m212.py pregătit, testat pe fixture temporar (preflight fără scriere, apply, backup, idempotent, corupție refuzată, pagina grupului/.htaccess păstrate). NEEXECUTAT pe server.

Rezultat Chrome pe commitul Meniuc4e14288:frontendSUCCESS,4probe (941navigare, câmpuri opționale, alergeni/preț/publicare, brand desktop/mobil),28suite Node. Baza de date rulează separat; niciun deploy. Conectarea securizată cPanel a reușit; terminal disponibil. Instalarea încă de executat.

## Rezultat final sesiune — acces cPanel întrerupt
FACT: autentificarea securizată a reușit și a fost observat pozitiv cPanelTools, utilizatoroldmikero. Terminalul afișaPython3.9.25. Comanda lungă de staging s-a oprit prin deadline în timpul tastării, înainte deEnter. Nu a descărcat/extras pachetul și nu a lansat updaterul. O încercare de anulare a tastării și lipire a fost blocată de protecția datelor de browser; revenirea la originea canonică, apoi verificarea într-o filă nouă au afișat upstream connect error / connection termination. STOP acces cPanel; niciun fișier public modificat. Nu relua logarea în buclă.

Garden:M2.12 și M2.11 încă NEAPLICATE. Meniu:c4e14288, frontend/ChromePASS, DB încă rulează (la ultima verificare probaUIV5.0.26), migrarea hosted/deploy neschimbate. RepoSite:9ec81268 conține arhiva deploy/garden-brand-m212.zip și updaterul; SHA256arhivă478f0b1e4779aa8768afd4052ca471e5c1c12431dab773410c7ef6cf34a8b24e. Preflight/apply server încă NECONFIRMATE.
