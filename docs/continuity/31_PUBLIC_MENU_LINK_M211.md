# M2.11 — Link către meniul public

19.10.2026. USER VALIDATED: deschiderea meniului din site afișează utilizatorului butoanele Restaurant Admin și Platform Admin.

FACT: toate cele opt linkuri din Garden /meniu.html duc la https://meniu.oldmike.ro/. Într-un browser fără autentificare, pagina generală nu afișează butoanele. Sursa JavaScript live app.js?v=5070-domain-ready arată că shell() afișează comenzile când există sesiune administrativă; pe ruta publică shell() returnează direct conținutul clientului. Aceasta explică diferența raportată, fără a demonstra o vulnerabilitate de autorizare.

DECISION: folosește https://meniu.oldmike.ro/m/old-mike/loc-oldmike-matei pentru toate legăturile și datele structurate ale site-ului Garden. Nu modifica aplicația Meniu Electronic, autentificarea sau datele ei.

FACT: ruta dedicată a fost verificată în browser: logo Old Mike PUB, categorii, RO/EN, produse și prețuri încărcate; fără bara aplicației și fără butoane administrative. Sesiunea administrativă a utilizatorului nu a fost folosită în test; comportamentul în acea sesiune este susținut de cod, nu de un test autentificat.

Implementare locală: data/site.json este sursa unică a adresei; scripts/build_pages.py o folosește inclusiv în cele opt CTA-uri. Regenerate index.html și contact.html (doar URL în JSON-LD) și meniu.html (opt linkuri). Designul M2.2 rămâne identic. python tests/validate_site.py PASS și git diff --check PASS.

Livrare pregătită: server/update-menu-links-m211.py modifică exclusiv URL-ul în cele trei fișiere HTML Garden deja instalate; păstrează transformările M2.9, formularul dezactivat și metadatele Garden. --check verifică markerul și numărul exact de URL-uri; --apply face copii private și verifică rezultatul, cu revenire la eroare. Nu accesează pagina grupului, celelalte aplicații sau datele backendului. Scriptul nu a fost executat pe găzduire.

STARE: pregătit local, NEAPLICAT LIVE. cPanel solicită autentificare. După aplicare: verifică cele opt URL-uri din /meniu.html și fluxul real până la meniul public; utilizatorul confirmă și în sesiunea sa. Nu declara corectat live doar din salvarea GitHub.

Actualizare acces: cererea securizată browserAuth a returnat submitted, dar inspecția imediată a paginii cPanel a arătat «A network error occurred during your login request». Nu există autentificare reușită și nu s-au efectuat scrieri pe găzduire. Nu repeta automat credentialele. Sintaxa updaterului Python verificată local prin py_compile PASS; updater NEEXECUTAT pe server. Checkpoint de implementare: ecc30ea82a90528fe31f667f3e8105a73f681fb5.
