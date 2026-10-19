# M2.9 — Destinație Garden autorizată, 19.10.2026

USER VALIDATED: utilizatorul a cerut lansarea și a confirmat explicit `garden.oldmike.ro`. oldmike.ro rămâne pagina grupului; Berăria, Meniu și Club sunt excluse.

FACT: conectare securizată cPanel reușită. Domeniul Garden lipsea; creat prin Domains cu document root separat `/home/oldmikero/public_html/garden.oldmike.ro`, confirmat de mesajul Success. Directorul nou conține doar scheletul cPanel: .htaccess/.user.ini/php.ini/.well-known. PHP CLI 8.3.35.

FACT DNS: de pe găzduire, NS oldmike.ro sunt connie.ns.cloudflare.com și aldo.ns.cloudflare.com. `dig @aldo.ns.cloudflare.com garden.oldmike.ro A +noall +answer +comments` returnează NOERROR, autoritativ, ANSWER:0. `curl -I https://garden.oldmike.ro/` nu rezolvă numele. Nu este doar propagarea unei modificări făcute aici: cPanel nu administrează zona autoritativă Cloudflare. Înregistrarea necesară: A, nume garden, IP46.32.229.176 (IP shared afișat de cPanel). Inițial DNS only pentru validarea certificatului; nu schimba NS/MX/apex sau alte subdomenii. HTTPS/AutoSSL trebuie verificate după DNS. Nu s-au modificat setări Cloudflare.

Pachet separat `scripts/package_garden.py` + `server/install-garden.php`; vechiul installerM26 rămâne retras. Pachetul folosește designM2.2 + M2.7 firmă + M2.8 ciorne/poster permanent. Canonical/OG/schema/sitemap în copia de producție se referă la Garden. Formularul public inactiv este înlocuit în această copie cu telefon/email; fără probe reale email, fără notificări active. Nu declara formularul operațional.

Installerul acceptă numai folderul Garden nou, fără site existent; verifică65hash-uri și lipsa symlink-urilor, rulează2suite sintetice și lint înainte de scriere, folosește config privat separat garden-config.php derivat intern din staging (fără a afișa secretele), păstrează aceleași date evenimente/admin. Backup privat pentru regulile Garden și hash-uri ale index.php/.htaccess/php.ini ale grupului. Nu modifică stagingul, runtime state, configul inițial, rădăcina grupului sau alte aplicații. Regulile Garden se adaugă păstrând blocurile PHP generate de cPanel. Stagingul păstrează Basic Auth. Admin Garden păstrează autentificarea aplicației/CSRF.

PASS local: construirea pachetului Garden,65fișiere publice,6.249.417bytes; aserțiuni adresă/formular/poster/deny lib; git diff --check.

## Rezultat efectiv
FACT: sursa preluată direct de pe GitHub, commit b57ee80864b71b366b7391cb7a7d89fac91d79cc, prin HTTPS în folderul privat /home/oldmikero/oldmike-garden-m29; fără credențiale în comandă. Pachetele au fost reconstruite acolo. Inițial numărul local68 includea3imagini dintr-un pachet intermediar; reconstruirea curată a eliminat copiile nefolosite. Manifestul final local/server este identic SHA256087ba786556ef68a777df4862944abbc5afd43a876dd0e07fc80bd7e92cdc074,65fișiere. Copie în docs/launch/M29_MANIFEST.json.

PASS PHP pe găzduire:41probe backend +26probe ciorne, fixtures sintetice, fără email sau date reale. Lint installer și toate cele5fișiere PHP publice verificat înainte de instalare. Comanda --check a trecut; --apply a repetat testele/lint și a instalat65fișiere exclusiv Garden. Mesaj: OK M2.9 installed ONLY garden.oldmike.ro;65files verified;group guards PASS;noemail sent. Backup privat garden-backup-20261019-050103-ce36c1; config separat creat intern, fără secrete afișate. State/postere/admin existente păstrate în dosarul privat comun.

M2.8 aplicat și în staging după PASS:13fișiere actualizate și verificate, backup privat; .htaccess/config/auth nemodificate. Test anon HTTP staging=401. Ciornele reale și posterele utilizatorului nu au fost editate/șterse.

HTTP la origin cu --resolve garden.oldmike.ro:80:46.32.229.176:301 spre https://garden.oldmike.ro/. HTTPS la origin cu --resolve, verificarea certificatului ACTIVĂ:curl60,self-signed certificate. Nu s-a folosit -k și nu s-a ocolit avertizarea. Certificatul trebuie emis/validat prin cPanel după A Cloudflare; nu declara HTTPS valid sau lansare publică.

BLOCKED extern: A garden lipsește în zona autoritativă Cloudflare. Administratorul trebuie să adauge DOAR A garden →46.32.229.176,TTLAuto,DNSonly inițial. Nu modifica NS/MX/apex/alte înregistrări. După aceasta:verificareDNS,AutoSSLpentruGarden,HTTPS200,pagini/resurse/admin/APIși probeHTTP ciorne autentificate. Nu sunt necesare servicii Vercel sau abonamente noi.

Restanțe separate: QA vizual public/telefon după DNS+HTTPS, QA HTTP ciorne autentificate, curățare automată postere expirate, formular/notificări, sociale. Formularul public rămâne înlocuit cu telefon/email în producție; nu este o funcție activată sau testată. Protecția stagingului rămâne activă.
