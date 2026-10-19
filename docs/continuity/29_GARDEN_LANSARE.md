# M2.9 — Destinație Garden autorizată, 19.10.2026

USER VALIDATED: utilizatorul a cerut lansarea și a confirmat explicit `garden.oldmike.ro`. oldmike.ro rămâne pagina grupului; Berăria, Meniu și Club sunt excluse.

FACT: conectare securizată cPanel reușită. Domeniul Garden lipsea; creat prin Domains cu document root separat `/home/oldmikero/public_html/garden.oldmike.ro`, confirmat de mesajul Success. Directorul nou conține doar scheletul cPanel: .htaccess/.user.ini/php.ini/.well-known. PHP CLI 8.3.35.

FACT DNS: de pe găzduire, NS oldmike.ro sunt connie.ns.cloudflare.com și aldo.ns.cloudflare.com. `dig @aldo.ns.cloudflare.com garden.oldmike.ro A +noall +answer +comments` returnează NOERROR, autoritativ, ANSWER:0. `curl -I https://garden.oldmike.ro/` nu rezolvă numele. Nu este doar propagarea unei modificări făcute aici: cPanel nu administrează zona autoritativă Cloudflare. Înregistrarea necesară: A, nume garden, IP46.32.229.176 (IP shared afișat de cPanel). Inițial DNS only pentru validarea certificatului; nu schimba NS/MX/apex sau alte subdomenii. HTTPS/AutoSSL trebuie verificate după DNS. Nu s-au modificat setări Cloudflare.

Pachet separat `scripts/package_garden.py` + `server/install-garden.php`; vechiul installerM26 rămâne retras. Pachetul folosește designM2.2 + M2.7 firmă + M2.8 ciorne/poster permanent. Canonical/OG/schema/sitemap în copia de producție se referă la Garden. Formularul public inactiv este înlocuit în această copie cu telefon/email; fără probe reale email, fără notificări active. Nu declara formularul operațional.

Installerul acceptă numai folderul Garden nou, fără site existent; verifică68hash-uri și lipsa symlink-urilor, rulează2suite sintetice și lint înainte de scriere, folosește config privat separat garden-config.php derivat intern din staging (fără a afișa secretele), păstrează aceleași date evenimente/admin. Backup privat pentru regulile Garden și hash-uri ale index.php/.htaccess/php.ini ale grupului. Nu modifică stagingul, runtime state, configul inițial, rădăcina grupului sau alte aplicații. Regulile Garden se adaugă păstrând blocurile PHP generate de cPanel. Stagingul păstrează Basic Auth. Admin Garden păstrează autentificarea aplicației/CSRF.

PASS local: construirea pachetului Garden,68fișiere publice,6.565.011bytes; aserțiuni adresă/formular/poster/deny lib; git diff --check.

ÎN CURS: transfer privat, testePHP, instalare. Acest checkpoint este pregătirea, NU dovada unei lansări. Actualizează aici rezultatele reale înainte de încheiere. DNS/HTTPS public rămân blocate de lipsa înregistrării Cloudflare. Restante anterioare: QA HTTP ciorne cu utilizator autentificat, curățare automată postere expirate, formular/notificări, sociale.
