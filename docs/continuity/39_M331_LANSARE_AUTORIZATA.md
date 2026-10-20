# M3.3.1 — lansare autorizată, oprită înainte de upload

USER VALIDATED20.10.2026 08:30București: pachet aprobat cu regula honeypot obligatorie. Ordine: verificarePHP, backupprivat, doar146fișiere listate, testmeniu.php, apoi rewrite+MIME, meniu.html, testpreț schimbat de utilizator, raport+rollback. Autorizare pentru Garden exclusiv; nu atinge grupul, Berăria, HTML-urile sau site-runtime.js.

FACT: .form-honeypot{position:absolute;left:-10000px;width:1px;height:1px;overflow:hidden} adăugată la finalul assets/site.css și în CSS-ul separat. Pachet refăcut și verificat; fărăHTMLînpublic. NoulZIPcu146fișiere publice pentru uploadprivat: output/menu-m3/oldmike-menu-m331-live-files.zip, SHA256df909d2ea5b2856add82075f2cce8a26a1be1377a6c4ec5c872394b8fd25ea94. Nu a fost încărcat.

FACT PAS1: autentificarecPanel reușită prin browserAuth securizat, MultiPHPManager arată garden.oldmike.ro PHP8.3 ea-php83, PHP-FPM activ. Terminal cPanel /opt/cpanel/ea-php83/root/usr/bin/php raportează8.3.35,curltrue,mbstringtrue. Nu s-a schimbat versiuneaPHP sau vreo setare.

FACT PAS2: backup în /home/oldmikero/oldmike-site-private/menu-m331-backup-20261020-053829, director0700. site.css și garden.htaccess0600, copiate din Garden și hashuri verificate. Starea privată menu-m331-launch-state.json0600 conține backup/root, hashurile7HTML-uri Garden și site-runtime.js, CSS și htaccess. Nu afișa conținuturi private/config/parole. Nu salva URL-uri/tokenuri de sesiune înGit.

BLOCKER PAS3: browsercredentialprotection a blocat listarea filelor după clickUpload în FileManager. Nu s-a selectat fișierul și nu s-a transmis niciun payload. Recuperare prin navigare explicită a tabuluiFileManager la originea cPanel: upstreamconnecterror/resetconnectiontermination. O singură reîncărcare a dus la credential_observation_restricted. Nu mai încerca automat login, nu reconstrui secrete, nu enumera file pentru ocolire. Este necesară preluare manuală a ferestrei conformbrowserAuth.

Nu s-a schimbat niciun fișier public. Nu s-a modificat htaccess/CSSlive, nu s-a activat rewrite, nu s-a modificat Supabase. ContinuăPAS3dupăacces, apoi confirmă fiecare pas în ordine. Nu trece laPAS5fărătestelePAS4. Testulprețului se face de utilizator, nu de agent.

Browserhandlepersistent: panelBrowserID1, panelTab TerminalID1, fileTabID3(recoveryorigin). Reia documentațiaCua dupăcompactare; stareaautentificării poate fi protejată. Nu presupune sesiunea reutilizabilă doar din aceastănotă.
