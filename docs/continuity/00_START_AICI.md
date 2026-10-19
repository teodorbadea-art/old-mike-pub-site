# M3 — meniu editorial LIVE în previzualizare
Utilizatorul a cerut pagina completă cu prețuri sincronizate, dar vrea evaluare ÎNAINTE de publicare. Vezi [starea M3](34_M3_MENIU_EDITORIAL_PREVIEW.md) și [README](../menu-redesign/README.md). Verificat în CI, nepublicat; lipsește fotografia coastelor BBQ. Nu aplica rutarea sau pachetul pe producție.

# Actualizare M2.12 —20.10.2026
[Identitate comună Site/Meniu, fonturi și navigare](33_M212_IDENTITATE_COMUNA.md). Implementare locală verificată; cPanel cere login nou. Punctul3 (meniu complet/live în site) AMÂNAT explicit.

# M0 — Audit și checkpoint, 18.10.2026

**Audit conex Meniu Electronic, numai citire:** [producție, branding, SEO, alergeni și imagini](32_AUDIT_MENIU_ELECTRONIC_20261019.md). main în urmă cu1100commituri; 69liste alergeni stocate, toate257rețete draft;57poze Mâncare7,19MiB. Nicio corecție aplicată în meniu/DB/Vercel. M2.11 Garden rămâne de aplicat.

**M2.11 pregătit, neaplicat live:** [link către meniul public dedicat clienților](31_PUBLIC_MENU_LINK_M211.md). URL verificat în browser, corecție surse și updater izolat Garden. Necesită acces cPanel pentru aplicare.

**M2.10 aplicat manual de utilizator:** [Admin Forbidden corectat](30_ADMIN_ROUTING_M210.md). /admin/200,homepage200,API200,lib403. DNS/HTTPS public funcționează. Login real/POST încă de confirmat. Vezi rezultatul aplicării în document.

**M2.9 instalat pe server, lansare publică blocată DNS/HTTPS:** [Garden autorizat și DNS Cloudflare lipsă](29_GARDEN_LANSARE.md). Garden instalat izolat,65hash-uri și PHP41+26PASS. M2.8 aplicat și în staging. DNS Cloudflare lipsește, certificatul trebuie emis după DNS. Nu declara site-ul accesibil public.

**Checkpoint local M2.8:** [ciorne și promovare permanentă](28_CIORNE_PROMOVARE_PERMANENTA.md). Cod/pachet pregătite; instalarea și verificărilePHP blocate de eroarea conexiunii cPanel. Site-ul de test rămâne la implementarea precedentă.

**Actualizare locală:** [date firmă și fără probe reale de email](27_DATE_FIRMA_FARA_TEST_EMAIL.md). CUI furnizat de utilizator, date publice concordante; footer completat local. Nu este un deploy sau activare formular.

**PRIORITATE: lansarea oprită; pagina grupului se păstrează.** [Starea verificată și destinația de clarificat](26_LANSARE_OPRITA_PAGINA_GRUP.md). Site-ul nou NU este lansat pe domeniul principal; rămâne în staging. Nu utiliza candidatulM26 de instalare la rădăcină.

**Checkpoint tehnic M2.5:** [Postere până la8MB](25_M25_POSTERE_8MB.md), aplicat în staging. ConfigPHP doar pe directorul site-ului;41testeCLI PASS șiHTTP401. TestulHTTP cu posterul utilizatorului rămâne deschis. DesignM2.2.

**Decizie curentă: design M2.2 păstrat.** [Revenire surse și testarea posterelor](24_DECIZIE_PASTRARE_M22_TEST_POSTERE.md). M2.3/M2.4 respinse; stagingul rămâneM2.2, sursele sunt readuse la aceeași variantă. Urmează testul de upload/publicare prin administrare.

**Checkpoint local curent M2.4:** [Meniu în perechi compacte](23_M24_MENIU_MOZAIC_COMPACT.md). Încercare pregătită pentru evaluare; M2.3 respins din cauza golurilor. Stagingul rămâneM2.2, uploadul nu a fost reluat.

**Checkpoint local M2.3:** [Compoziție asimetrică Meniu și blocaj upload](22_M23_MENIU_RANDURI_COMPACTE.md). Corecție implementată și verificată local. Stagingul rămâne M2.2: încărcarea a fost refuzată de dialogul de permisiuni; reluarea necesită autorizare.

**Checkpoint curent M2.2:** [introduceri cinematice și actualizare în staging](21_M22_INTRODUCERI_CINEMATICE.md). Imagini în prima vedere la Meniu/Galerie/Evenimente/Contact; negru în stânga, fade spre fotografie în dreapta, text mic suprapus. Aplicat și verificat tehnic în oldmike-test; evaluarea vizuală M2.2 și uploadul posterelor rămân deschise.

**Checkpoint tehnic anterior: M2.1 — [backend și instalare de test blocată](20_M21_BACKEND_INSTALARE_TEST.md).**41teste PHP CLI și7fișiere PHP lint PASS pe găzduire. Staging instalat în oldmike-test; configurarea contului a fost încheiată de utilizator și accesul anonim401 este confirmat. Accesul utilizatorului la pagini și linkuri a fost confirmat; testele HTTP ale administrării rămân de făcut; vezi actualizarea de la finalul raportului M2.1. Nicio lansare pe pagina principală. **Design curent: M1.7.1.** Citește [restaurarea imaginilor cinematice ale spațiilor](18_M171_REVENIRE_SPATII_CINEMATICE.md). Cele patru imagini generate v2 sunt din nou active. Citește [corecțiile de utilizare, fidelitate și SEO](17_M17_CORECTII_UTILIZARE_SEO.md). Program confirmat, burger simplu/Pizza Old Mike/vită cu cartofi prăjiți; datele sociale și firma amânate. Fără publicare.

**Evaluare externă după M1.6:** citește [trierea critică a raportului Claude](16_REVIEW_EXTERN_M16.md). Recomandările sunt propuse, nu implementate sau aprobate implicit.

**M1.6 (istoric).** Vezi [Galerie: Atmosfera și Spațiile](15_M16_GALERIE_ATMOSFERA.md). Cerință clarificată: oameni generați fotorealist, cu scene spontane bazate pe spațiile existente.

**M1.5 (istoric).** [Burger simplu în Acasă și Meniu](14_M15_BURGER_SIMPLU.md), fără dublă carne; bucătarii din hero păstrați.

**M1.4 (istoric).** Vezi [previzualizarea pentru telefon](13_M14_PREVIEW_TELEFON.md). Designul site-ului rămâne M1.3.

**Design M1.3.** Citește [categoriile vizuale din Meniu](12_M13_CATEGORII_MENIU.md). Hero-ul Acasă M1.2 a fost evaluat favorabil și rămâne neschimbat.

**M1.2 (istoric).** Citește [hero-ul cu doi bucătari și emailul confirmat](11_M12_HERO_BUCATARI_EMAIL.md). Contact: office@oldmike.ro, notificări încă inactive.

**M1.1 (istoric).** Citește [corecțiile și starea Contact](10_M11_CORECTII_VIZUALE_CONTACT.md). Datele de la M1 sunt istorice; adresa a fost între timp confirmată, Galeria are patru zone, hero-ul este diferit, iar formularul este numai preview local.

**Progres M1:** conceptul multipagină este implementat local pe `design/m1-static-cinematic`; citește [checkpoint-ul M1](09_M1_PROPUNERE_VIZUALA.md) pentru ce este gata, ce nu a fost verificat și ce urmează. Nu s-a făcut deploy. Direcția de design M0.2 rămâne autoritatea cerințelor.

**Direcția curentă M0.2:** citește întâi [clarificările vizuale și navigarea](08_DIRECTIE_VALIDATA_SI_NAVIGARE.md). Acasă aerisit cu un singur hero static; fără mișcarea fotografiei sau alte imagini culinare. Pagină Meniu vizuală, Galerie editată cinematic și Evenimente cu upload de postere.

**Actualizare ulterioară M0.1, aceeași zi:** utilizatorul a stabilit găzduirea finală pe cPanel și excluderea Berăriei. Citește [strategia curentă](07_STRATEGIE_EXECUTIE.md) înaintea restului planului; referințele M0 la viitoare preview-uri Vercel sunt depășite.

## Rezultatul recuperării

Proiectul există și nu se reîncepe. Au fost recuperate codul hero-ului, șapte mock-up-uri, fotografii ale localului, capturi de inspirație, cele cinci imagini existente și un deployment Vercel anterior. Nu a fost recuperată corespondența completă dintre fiecare variantă vizuală și aprobările/corecțiile utilizatorului.

| Element | Stare verificată |
|---|---|
| Repository | Public, `teodorbadea-art/old-mike-pub-site`, default `main` |
| `main` înainte de audit | `3b166cae6cf71831fa4a55f88a9606fcfcebe13e`; README + 5 JPG; fără pagină HTML |
| Implementare existentă | `feat/cinematic-hero-v1`, `9a4ee466aec629cc6ae4c95e7817272b1eee6c8a`, 18.10.2026 15:19:32 Europe/Bucharest |
| Cod | `index.html`, 12.430 bytes; HTML/CSS/JS inline, fără package manager/framework |
| Istoric recuperat | 7 commit-uri: inițializare, 5 imagini, hero v1 |
| PR / issue / Actions | Niciun PR, niciun issue, 0 workflow runs la audit |
| Documentație anterioară | README de 54 bytes; niciun AGENTS, checkpoint sau document de design în cele două ramuri |
| Vercel | Proiect existent `old-mike-pub-preview`; un deployment găsit, READY |
| Implementare aprobată final | UNKNOWN; existența codului/deployment-ului nu dovedește aprobarea |
| Site oficial / DNS curent | Nu s-au inspectat sau modificat în acest audit; nu deduce starea lor din proiectul preview |

## Contractul acestei etape

USER VALIDATED: audit, recuperare, checkpoint în GitHub, apoi prezentarea rezultatelor înainte de modificări importante/publicare. Nu se modifică site-ul live, nu se face deploy în producție și nu se modifică celelalte două aplicații.

Checkpoint-ul se salvează pe `docs/continuity-audit-20261018`, pornind din hero v1. `index.html` și `img/*` rămân identice. Singura configurație nouă este `vercel.json`, care blochează deployment-urile automate pentru ramura documentară; celelalte ramuri nu sunt dezactivate. Nu se schimbă setări în Vercel.

## Cum continuă următoarea sesiune

1. Verifică HEAD și eventualele schimbări ulterioare în GitHub. Nu suprascrie muncă nouă.
2. Citește [deciziile](01_DECIZII.md), [designul](03_DESIGN.md) și [problemele](05_PROBLEME_SI_PASI.md).
3. Folosește catalogul M01–M07 și inventarul de resurse; nu cere încă o dată toate materialele.
4. Prezintă diferența dintre hero v1 și referințele recuperate. Revalidează numai elementele fără dovadă de aprobare, fără a redeschide direcția cinematică deja stabilită.
5. Implementează M1 conform strategiei M0.1; verifică local, salvează milestone-ul și pregătește o singură prezentare completă desktop/mobil/mișcare. Nu crea deployment Vercel.

Auditul nu certifică funcționalitatea în browser, performanța, fidelitatea produselor AI sau pregătirea de producție. Vezi jurnalul pentru ce a fost verificat efectiv.
