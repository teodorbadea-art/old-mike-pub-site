# M1.3 — categorii vizuale în Meniu

18.10.2026. Bază: `61ac48bb69bdbc235d3eed4bfb90d5bdad6ceffb`, ramura `design/m1-static-cinematic`.

## USER VALIDATED

Utilizatorul a evaluat favorabil M1.2 („arată bine acum”). Cere extinderea paginii Meniu cu încă trei imagini reprezentative: Platouri (grătare, cârnați, mici), Grătar de vită și Desert/papanași. Cere păstrarea conceptului cu bucătari mici realiști în câteva imagini, cu umor de scară (furculiță/solniță mare), și fotografii asemănătoare preparatelor din meniul existent. Nu este aprobare de publicare.

## FACT — verificarea meniului, exclusiv citire

Au fost consultate categories, products și media_assets în proiectul existent prin SELECT-uri. În catalog există Burgeri (6 produse), Platouri (3), Pizza (16), Preparate cu Vită și Oaie (3), Desert (13), precum și alte categorii de mâncare/băuturi. Aceste numere descriu înregistrările ne-arhivate găsite, nu certifică disponibilitatea fiecărui produs sau un snapshot publicat.

Media asociată platoului Butcher și papanașilor este marcată verified. Fotografia de platou găsită are un platou mixt, cartofi, carne la grătar și cârnați; este referință de stil/servire, nu dovada exactă a compoziției Butcher. Cele două denumiri de platou au returnat aceeași imagine. Pentru mușchiul de vită, imaginea găsită în UAT este **archived**; folosită doar ca reper artistic, nu ca fotografie actuală sau recomandare de produs. Categoria și preparatele cu vită există și în catalogul LIVE. Nu s-a reactivat, încărcat sau modificat nicio imagine și nu s-au modificat produse, date ori alte repository-uri.

Surse, statusuri, SHA256 și prompturi exacte: `../design/ASSET_PROMPTS_M13.json`. Fișierele derivate sunt autonome în site, fără dependență runtime de Supabase.

## DECISION / implementare

Pagina de prezentare are cinci repere culinare și o încheiere pentru băuturi: Burgeri, Pizza, Platouri, Grătar de vită, Deserturi (imagine papanași), Băuturi. Este o selecție de intrări vizuale, nu reproducerea exhaustivă a tuturor categoriilor meniului. Restul preparatelor/prețurilor rămân în meniul complet, accesibil sus și jos.

- Burgerul asamblat distinct rămâne secțiunea de deschidere în Meniu; hero-ul Acasă aprobat rămâne neschimbat.
- Pizza generică rămâne fără recomandarea unei rețete anume.
- Trei imagini noi statice: platou cu doi bucătari/furculiță mare, vită cu un bucătar/solniță mare, papanași cu doi bucătari/lingură de dulceață. Aspect fotografic cinematic comun, fără animații.
- Pizza/Platouri/Grătar/Deserturi: două coloane desktop, una mobil; imagini 3:2 integrale, fără crop în CSS. Estompare discretă la margini, nu peste bucătari. Băuturile încheie separat pagina.
- Navigare locală către cele șase secțiuni; legăturile de categorie duc la meniul complet existent. Nu inventăm deep links sau filtre nesuportate de aplicația meniului.
- Active noi optimizate 1200×800 WebP, încărcare lazy/async. Nicio dependență/serviciu/abonament nou. Eliminat și un caracter „+” rătăcit înaintea benzii CTA.

Imaginile cu bucătari sunt compoziții artistice de categorie, fără prețuri, gramaje sau promisiunea unei porții exacte. Marcarea media verified nu dovedește separat că sursa este o fotografie documentară; fidelitatea finală se verifică de proprietar înainte de lansare.

## Verificări

Imaginile au fost inspectate vizual: preparatele și personajele sunt vizibile, fără text sau branding generat. Verificări statice: generarea celor șase pagini și pachetului autonom, resurse/ancore locale, un H1/pagină, alternative text, sintaxa JS comun/inline/review, structura și dimensiunile celor trei imagini și `git diff --check`. Prețurile nu sunt duplicate, nicio referință Diavola/Noroc în pagina Meniu. Acasă, Contact, Galerie, Evenimente și demo administrare sunt identice la nivel HTML față de M1.2; CSS adăugat vizează pagina Meniu.

Browser QA, aspectul efectiv pe dispozitive, Lighthouse și harta live rămân neexecutate: control-browser nu este disponibil în mediul managed. Verificarea statică nu demonstrează aspectul randat. Fișierul autonom permite evaluarea de către utilizator, inclusiv modul Telefon.

## Rămâne

Evaluarea vizuală M1.3 și confirmarea reprezentativității produselor; verificarea cPanel; backend administrare evenimente/cereri și livrarea emailului office@oldmike.ro; program/social media. Formularul rămâne doar preview. Niciun deploy/build Vercel, modificare live sau acces la Berăria. Lansarea se face separat după autorizare.
