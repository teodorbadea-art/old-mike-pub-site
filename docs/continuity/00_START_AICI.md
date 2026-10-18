# M0 — Audit și checkpoint, 18.10.2026

**Checkpoint curent: M1.7.1.** Citește [restaurarea imaginilor cinematice ale spațiilor](18_M171_REVENIRE_SPATII_CINEMATICE.md). Cele patru imagini generate v2 sunt din nou active. Citește [corecțiile de utilizare, fidelitate și SEO](17_M17_CORECTII_UTILIZARE_SEO.md). Program confirmat, burger simplu/Pizza Old Mike/vită cu cartofi prăjiți; datele sociale și firma amânate. Fără publicare.

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
