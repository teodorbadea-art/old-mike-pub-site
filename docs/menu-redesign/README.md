# Old Mike — meniu editorial M3 (previzualizare, fără publicare)

## Ce este pregătit

- `meniu.php` + `server/lib/menu/`: randare PHP completă, fără dependență de JavaScript pentru produse/prețuri.
- Funcția publică existentă `get_public_menu`, strict restaurantul `old-mike`, locația `loc-oldmike-matei`; numai cele7câmpuri necesare. Nu scrie nimic în Supabase și nu utilizează chei secrete.
- Cache `cache/menu.json`,600secunde, actualizare atomică/lock, ultima copie validă la eroare. Un răspuns HTTP200cu listă goală este tratat ca meniu gol legitim. JSONinvalid sau rânduri invalide nu înlocuiesc copia bună. La prima pornire fără cache și fără conexiune rămâne o invitație utilă spre meniul electronic/telefon, fără erori tehnice.
- `server/lib/menu/config.php`: singurul loc pentru categorie→imagine, texte editoriale și oferta de prânz. Necunoscutele apar automat în „Și altele”. Toate257produsele din snapshot apar o singură dată în HTML și JSON-LD; filtrele clientului din aplicație nu sunt copiate aici.
- Oferta transmisă de utilizator:33,99lei, ciorbă+fel de bază+garnitură. Interval USERVALIDATED20.10.2026:luni–vineri12:00–16:00. Oferta este configurată separat, deoarece RPC-ul produselor nu o expune; schimbarea acestei oferte se face aici, nu pretindem sincronizare automată pentru ea.
- Meniu/site folosesc aceleași fonturi locale Manrope+Cormorant și shell-ul comun M2.12. CSS-ul nou este blocul comentat „OLDMIKEM3 ·MENIU EDITORIAL LIVE” de la finalul `assets/site.css`; este izolat pe `.menu-editorial`.
-13imagini noi:8mâncare+5băuturi;39WebP-uri (master1536×1024,480/800). Imaginile existente sunt păstrate. Fiecare are „Imagine ilustrativă” sub ea. Prompturi exacte și limite: `food-image-prompts.md`, `drink-image-prompts.md`.

## Fotografii și limite

Fotografiile-sursă provin din imaginile publicate în Meniul Electronic, nu din imagini de mâncare inventate. Tomahawk-ul este de PORC. Fișierul asociat produsului „PENNE Carbonara Original” arată paste panglică; dorada asociată cu orez în denumire apare în fotografie cu legume/midii. Generarea a urmat fotografia, fără să inventeze penne sau orez. Aceste nepotriviri sunt de revizuit separat în catalog; nu s-au schimbat datele.

Lipsește fotografia reală pentru coastele BBQ cu cartofi prăjiți. Nu s-a generat o imagine pentru porc; categoria apare temporar ca listă fără fotografie, fără spațiu gol sau imagine greșită. Este necesară fotografia utilizatorului pentru a închide această ilustrație. Generarea AI poate modifica detalii fine: imaginile sunt de evaluat vizual înainte de aprobare; nu se declară păstrare pixel-cu-pixel a farfuriei.

## Previzualizare

`output/menu-m3/Old-Mike-Meniu-Previzualizare.html` este o copie autonomă, cu toate produsele/prețurile reale la momentul indicat în banner. Se poate deschide pe telefon sau calculator, fără instalare; acesta este un snapshot pentru evaluare, nu un meniu live. Versiunea PHP din pachet citește live și reîmprospătează cache-ul la primul acces după10minute.

Nu este necesar și nu este autorizat acum un upload în pagina oficială. Nu s-a schimbat `meniu.html`, nu s-a modificat rutarea Apache, DNS sau baza de date. Nicio publicare pe Vercel; ramura este exclusă explicit de la build automat. Sursa nouă este salvată pe `design/m3-live-menu-editorial`.

## Verificări

LocalPHP8.3:31probePASS(cache rece/cald/întrerupere/JSONinvalid/date noi/categorie nouă/formatare/XSS/257produseși257elementeJSON-LD). Request-ul public a fost verificat în timp real prin HTTPS și a returnat257produse. PHPcURL pe runtime-ul local a întâmpinat timeoutla conexiune; comportamentul de fallback a fost verificat. Testul real cPanel nu este efectuat în această etapă.

Workflow-ul `Review menu editorial (no deploy)` verifică Chrome1440/390/360, overflow, fonturi, imagini, details,navigareactivă,motionredus și produce capturi + raport Lighthouse. Rezultatele se completează după rulare; nu declara scorLighthouse≥90fără raport măsurat.
