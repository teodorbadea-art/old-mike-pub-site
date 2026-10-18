# Evaluare externă Claude — triere după M1.6

18.10.2026. Bază de cod: 64d162d7dfd8fde24ccfc6c7f94d3a38e79c870b. Utilizatorul a furnizat raportul extern în Pasted markdown(1).md. Acesta nu este o aprobare implicită de implementare a tuturor recomandărilor.

## Dovezi și verificare proprie

Raportul DECLARĂ teste browser desktop1440x900 și telefon emulat375x812, plus citirea repository-ului. Aceste rulări sunt dovezi raportate de evaluator, nu rulări reproduse de acest agent. Nu se transferă statutul browser QA în PASS propriu.

Confirmat prin citirea codului curent: header-cta este ascuns sub800px; numărul din footer moștenește9px pe mobil; menu-access este sticky și are layout vertical pe mobil; footer-ul nu include adresa/program/social; events.json nu este citit. Galeria curentă solicită fișierele active însumând2271530 bytes (aprox2,27MB), înainte de orice overhead; încărcarea lazy nu înseamnă că toate sunt descărcate imediat.

Fișierul desktop local actual are titlul M1.6, iar burgerii activi au fost corectați la un singur strat de carne. Raportul spune că atașamentul desktop primit era M1.3. Nu avem fișierul efectiv primit de evaluator pentru a demonstra originea diferenței; există o problemă de distribuție/identificare a versiunii, nu dovada că repository-ul actual a revenit la burger dublu. Preview-ul telefon este intenționat static/inactiv pentru evaluare vizuală și nu validează navigarea sau formularele site-ului real.

## Triere / propunere, nu aprobare

Prioritate înainte de lansare: acces clar la apel pe mobil și număr vizibil desktop; program/adresă/indicații/social în footer; lizibilitate; semnalistică/logo și fidelitate spații; meta/OG, imagini responsive, favicon și rută404; informare privind datele și comportamentul hărții; backend/admin/email și teste reale.

Noindex este intenționat corect în prototip; se schimbă numai la pregătirea unei lansări autorizate. Configurația HTTPS/cache/compresie se decide după audit cPanel, nu prin suprascriere automată de .htaccess. Bugetul de o zi din raport nu este estimare verificată. Lipsa OG nu demonstrează singură că o platformă va afișa o previzualizare complet goală. Alegerea fontului local poate îmbunătăți consistența, dar fallback-ul Georgia nu este în sine defect funcțional. Concluziile juridice menționate în raport nu sunt validate aici și necesită verificare separată înainte de decizii.

Nu adoptăm implicit: eliminarea Evenimente, reducerea Atmosferei la1-2imagini, schimbarea textului hero sau adăugarea bucătarilor în toate fotografiile. Acestea sunt preferințe/strategii de evaluat în contextul deciziilor utilizatorului. Patru scene generate au fost cerute explicit; nu se cer fotografii noi, există originale ale spațiilor. Nu promitem deep links de categorie fără verificarea suportului și nu modificăm Meniul Electronic pentru această corecție.

## Milestone propus

O rundă compactă de utilizabilitate/încredere: apel vizibil, footer practic, text mai lizibil, CTA de meniu mai compact și etichete oneste, filtre Evenimente când există date, semnalistică/fidelitate din originale. Separat, audit cPanel și pregătire reală de lansare/backend. Date de business încă necesare: program, linkuri sociale oficiale, identificare operator. Nu se inventează.

În această rundă s-a citit raportul și s-a verificat codul; nu s-au modificat site-ul, imaginile, preview-urile sau setările de hosting. Niciun deploy. Nu s-a accesat Berăria și nu s-au modificat Meniul Electronic ori Old Mike Community.
