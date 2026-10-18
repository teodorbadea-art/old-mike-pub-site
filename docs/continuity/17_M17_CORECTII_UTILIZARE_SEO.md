# M1.7 — utilizare, fidelitate culinară și pregătire pentru căutare

18.10.2026. Bază `4f185c934e7c4377f70de63e2689a0eca70cde3e`, ramura `design/m1-static-cinematic`. Fără publicare.

## USER VALIDATED în această rundă

- Implementarea corecțiilor prioritizate după raportul Claude, cu păstrarea direcției vizuale. Evaluarea/adoptarea sugestiilor suplimentare pentru Acasă.
- Firma/CUI/Registrul Comerțului și linkurile sociale pot fi completate mai târziu; nu blochează dezvoltarea. Trebuie urmărite pentru lansare.
- Program confirmat ulterior: luni–vineri10:00–24:00, sâmbătă–duminică12:00–24:00. Europe/Bucharest.
- Înlocuirea imaginii Pizza Margherita cu una inspirată de Pizza Old Mike din meniul existent. Categoria rămâne generic „Pizza”.
- Bucătari miniaturali și gesturi comice în celelalte imagini culinare din Meniu. Burgerii rămân cu o singură bucată de carne.
- Corecție suplimentară: nu se servesc cartofi la cuptor; imaginea grătarului de vită trebuie să aibă cartofi prăjiți sau piure. S-au ales cartofi prăjiți.

## Implementat

### Acasă și apel

Un singur hero artistic, neschimbat ca sursă, fără alte fotografii pe homepage. Identitate explicită „Pub & terasă · Matei Voievod67”, burgeri/pizza/bere, terasă acoperită/meciuri/București în text. Păstrat H1 „Poftă de ceva bun.” Banda practică are adresă, program, telefon și indicații; trei ieșiri text discrete duc la Spații/Evenimente/Cerere grup. Nu am adăugat cartierul Obor, neconfirmat pentru acest mesaj SEO.

Header cu „Sună” pe telefon și număr vizibil desktop. Mobil: imagine de aproximativ38svh, text compact și două CTA alăturate, Sună principal, Meniu secundar; nu se acoperă produsul cu titlul. Intenția este accesul la ambele în primul ecran; **nu s-a măsurat în browser**. Nu pretinde aceeași încadrare pentru orice dispozitiv, dimensiune de text/zoom.

Starea Deschis/Închis se calculează din programul confirmat în Europe/Bucharest, actualizare la60sec și revenirea în pagină. Fallback fără JS: programul scris. Configurație centrală în `data/site.json`; footer, program Contact și JSON-LD sunt generate din aceleași intervale. Închiderea24:00 este reprezentată00:00 în schema pentru căutare; în interfață rămâne24:00. Program standard, fără excepții de sărbători inventate.

### Meniu și imagini

Pizza Old Mike a fost verificată prin SELECT-uri în catalog/media: mozzarella Fior di Latte, șuncă cotto, chorizo, ciuperci, măsline, ardei, ceapă. Media este verified/published/available, iar fișierul asociat a fost inspectat. Nicio schimbare la Meniul Electronic/Supabase.

Imagine nouă de pizza cu doi bucătari: tăietor mare + spatulă care ridică felia. Burgerul asamblat simplu are acum doi bucătari cu râșniță și clește. Platouri și papanași păstrează imaginile cu bucătari existente. Vita are cartofi prăjiți, nu cartofi la cuptor, cu bucătarul/solnița păstrate. Cele cinci repere de mâncare au același concept; berea și scenele cu oameni nu primesc bucătari miniaturali.

Prompturi și dovada sursei pizza în `../design/ASSET_PROMPTS_M17.json`; conversii/crop în `scripts/prepare_m17_assets.py`. Nu se certifică identitatea gramajelor/platingului cu porțiile reale; proprietarul mai validează fidelitatea înainte de publicare.

Bara sticky Meniu a devenit compactă (~62px prin regulile CSS, fără măsurătoare browser), un singur CTA vizibil. Textele de categorie sunt oneste „Deschide meniul complet”; nu pretind filtrare sau deep links pe care aplicația nu le-a confirmat.

### Galerie și lizibilitate

Spațiile folosesc din nou **fotografiile originale**, cu crop-uri documentate și ajustări deterministe de lumină/culoare/claritate. Nu sunt regenerate obiectele, arhitectura sau literele/logo-urile; astfel au fost eliminate textele artificiale din aceste patru imagini. Reperele pot fi diferite de cadrele AI vechi fiindcă sursa este cea reală. Originalele nu sunt suprascrise. `../design/VENUE_PHOTO_CORRECTIONS_M17.json` descrie exact sursele/hashurile/crop-urile. Cadrele cu oameni rămân cele patru scene generate cerute anterior; nota de ilustrare este mai explicită/lizibilă.

Galeria oferă480/800/master cu srcset/sizes, păstrează rapoartele native și deschide masterul în dialog. Buget verificat pe fișierele aferente celor8imagini:288918bytes la480px,632298bytes la800px,1275618bytes master. Alegerea efectivă depinde de viewport/DPR/browser, iar masterul poate fi descărcat la mărire. Nu sunt măsurători de rețea sau Lighthouse.

Textele de bază sunt mărite spre15–16px, linkuri14px, etichete12px, footer14px și telefon18px mobil. Marca mică din header are excepții deliberate10–12px. Titlurile folosesc Cormorant Garamond local regular/italic400 cu caractere românești, font-display:swap; WOFF ales pentru a evita dependența Brotli în build, licența SIL OFL inclusă. Nu există cont/abonament/cereri Google Fonts la runtime. Logo-ul oficial din header rămâne byte-identic.

### Contact / evenimente / tehnic

Footer comun cu adresă, program, tel/email, explicația Old Mike Club, navigare și informații privind datele. Câmpurile sociale/operator rămân null și omise, nu apar placeholder-e fictive.

Email opțional în formular, validat dacă este completat; telefon/nume/data/grup/acord rămân. Formularul este tot preview-only, fără POST/email/persistență. Harta nu are iframe/src la încărcare; iframe Google este creat doar după alegerea utilizatorului, iar linkurile de indicații rămân funcționale fără JS.

Pagina Confidențialitate descrie numai comportamentul actual al prototipului. **Nu este politica finală și nu certifică conformitatea viitorului backend.** Operatorul, procedurile, temeiurile și păstrarea trebuie completate înaintea activării cererilor. Vezi `../launch/BEFORE_PRODUCTION.md`.

Evenimente rămâne în meniu. Starea goală nu are filtre fără efect și nu mai are marca inventată OM. Generatorul citește events.json și poate reda evenimente valide publicate/neîncheiate cu filtre numai când există categorii diferite; nu am inventat evenimente. Administrarea reală și actualizarea automată pe hosting rămân de implementat. Iconița meniului se schimbă☰/×; săgeata↗ este păstrată pentru destinații externe, nu toate apelurile interne.

Titluri/descrieri distincte, canonical, OG/Twitter și imagine share1200x630, JSON-LD Restaurant cu date confirmate, favicon derivat mic, sitemap.xml și404.html. Toate sunt pregătite local. **Noindex și robots Disallow:/ rămân intenționat active**, fără submit Google sau publicare. Configurația .htaccess este doar un candidat în docs/launch, nu este instalată. HTTPS/cache/compresie/rutare404 se decid după audit cPanel.

## Verificări efectuate

PASS: generator pagini8HTML, desktop review și telefon static; resurse/ancore locale/ID-uri; titluri distincte și JSON/JSON-LD valid; fonturi locale decodabile și glyphuri românești; logo oficial hash5ce2fc5ac8c93fd2aff434c0e8eb617c5a3ed1dc; imaginile active decodabile și noile referințe; opt imagini în Galerie și variante responsive. `tests/validate_site.py` verifică și imaginile embedded în ambele preview-uri.

PASS `tests/hours-and-form.cjs`:10cazuri orar/închidere/weekend/DST, email opțional și regresii formular. Verificări sintetice evenimente: publicat/nepublicat, expirare, timezone, respingere poster în afara folderului și escape HTML. Nicio trimitere sau date de client reale. Sintaxă JS și Python, git diff --check.

Inspecție vizuală: noile imagini pizza/burger/vită și fotografiile spațiilor comparate cu sursele, decupaj burger mobil. **Nu sunt capturi ale site-ului.** Browser QA, comportamentul CSS, primul ecran, tastatură/zoom pe dispozitiv, harta live și Lighthouse nu sunt executate: control-browser indisponibil în mediul managed. Nu se preiau testele declarate de Claude ca PASS propriu.

## Livrare și rest de lucru

Preview-urile afișează vizibil M1.7; telefonul este în continuare o evaluare statică fără JS/iframe/formulare active, aproximativ3,1MB. Desktop este interactiv, aproximativ10,9MB fiindcă include imaginile/fonturile/variantele inline. Niciunul nu este deploymentul real. Nu evalua comportamentul meniului mobil după ambalajul static.

Rămân: evaluare vizuală M1.7 și browser QA real, cPanel/document root/backup, administrare reală/postere/cereri/SMTP și teste, completări sociale/firmă/juridice, activare indexare numai la lansare autorizată. Fără deploy Vercel/production, fără alte plăți. Berăria exclusă; Meniul Electronic și Old Mike Community neatinse.
