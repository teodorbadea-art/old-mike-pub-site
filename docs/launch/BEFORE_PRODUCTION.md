# Lansare: de completat și verificat, fără publicare în M1.7

## Confirmat

Program standard USER VALIDATED: luni–vineri10:00–24:00, sâmbătă–duminică12:00–24:00, Europe/Bucharest. Adresă Matei Voievod67, București; tel0787366166; office@oldmike.ro. Nu adăugăm program special pentru sărbători fără date.

## Amânat explicit de utilizator (nu blochează dezvoltarea vizuală)

Facebook/Instagram oficiale, denumire firmă, CUI, Registrul Comerțului: câmpuri null în data/site.json, omise din interfață până la confirmare. Trebuie completate înaintea verificării de lansare. Nu sunt inventate sau recuperate dintr-o firmă cu nume asemănător.

## De realizat înainte de activare/lansare

- Delimitare document root oldmike.ro în cPanel; PHP, baze de date, extensii, SMTP, limite upload, HTTPS și backup/rollback. Beraria exclus complet.
- Backend real pentru postere/cereri/email, autentificare administrare, control acces, protecții și teste server; transport email către office, fără a trimite mesaje de test neautorizate în această rundă.
- Politică de confidențialitate completă cu operatorul real, scopuri/temeiuri/păstrare și proces efectiv; pagina curentă descrie exclusiv comportamentul prototipului, nu certifică pregătirea juridică a viitoarei aplicații.
- Confirmare fidelitate preparate și imagini/spații de proprietar.
- Browser QA real pe375x812/390x844/1440x900, zoom200%, tastatură, meniu, dialog, formular, hartă la click; apoi telefon real și Lighthouse.
- Verificare Rich Results Test și URL Inspection după accesibilitatea autorizată a unei versiuni de test; Google decide afișarea și nu garantăm ranking/rich results.
- Site-config indexing_enabled rămâne false; robots.txt rămâne Disallow:/, fiecare pagină noindex. Doar la lansarea autorizată: eliminarea noindex pe pagini publice, robots.txt potrivit cu Sitemap:https://oldmike.ro/sitemap.xml; demo/admin/404 nu se indexează. Verifică să nu blochezi crawl-ul paginilor publice finale.
- Canonical/OG folosesc domeniul final planificat; share image1200x630 este pregătită local, nu disponibilă online până la upload. Verifică WhatsApp/Facebook după publicare, fără promisiuni că afișarea va fi instantanee.
- Configurația de cache/compresie/404/redirect HTTPS se aplică numai în folderul corect după audit. Vezi CPANEL_CONFIG_CANDIDATE.txt. Nu s-a instalat .htaccess.

Surse primare consultate18.10.2026:
- https://developers.google.com/search/docs/appearance/title-link
- https://developers.google.com/search/docs/appearance/snippet
- https://developers.google.com/search/docs/appearance/structured-data/local-business (inclusiv program până la miezul nopții)
- https://ogp.me/
- Font Cormorant Garamond/SIL OFL1.1: assets/fonts/OFL-Cormorant.txt; găzduit local, fără cont/abonament.
