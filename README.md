# Old Mike Pub — site oficial

Repository dedicat site-ului de prezentare. Meniu Electronic și Old Mike Community sunt aplicații independente și nu se modifică aici.

## Începe aici

**M1.1 — versiunea curentă:** [corecții vizuale, hartă și cerere pentru grupuri](docs/continuity/10_M11_CORECTII_VIZUALE_CONTACT.md). Hero artistic descompus pe Acasă, Meniu generic, Galerie cu patru zone, CTA mai accesibil și Contact extins. Cererile sunt încă în mod de previzualizare, fără trimitere.

**M1 — concept implementat local:** [stare, verificări și limitări](docs/continuity/09_M1_PROPUNERE_VIZUALA.md). Pagini: `index.html`, `meniu.html`, `evenimente.html`, `galerie.html`, `contact.html`. Pentru fișierul autonom de evaluare: `python3 scripts/build_pages.py`, apoi `python3 scripts/build_review.py`. Nu este publicat; administrarea evenimentelor este doar o demonstrație locală.

**Direcția curentă M0.2:** [Homepage static aerisit, pagini și administrare Evenimente](docs/continuity/08_DIRECTIE_VALIDATA_SI_NAVIGARE.md). Clarificările utilizatorului înlocuiesc propunerea anterioară de homepage lung și mișcarea imaginilor pe straturi.

Checkpoint de recuperare: **18 octombrie 2026**, milestone **M0 — audit și continuitate**.

**Actualizare M0.1:** [Strategia de execuție și găzduirea cPanel](docs/continuity/07_STRATEGIE_EXECUTIE.md). Acest document înlocuiește planul anterior cu Vercel: găzduire finală pe cPanel, fără dependență Vercel sau costuri noi. `beraria.oldmike.ro` este exclus complet din intervenție.

1. [Stare actuală și limitele recuperării](docs/continuity/00_START_AICI.md)
2. [Decizii și dovezi](docs/continuity/01_DECIZII.md)
3. [Arhitectură și publicare](docs/continuity/02_ARHITECTURA.md)
4. [Design și mock-up-uri recuperate](docs/continuity/03_DESIGN.md)
5. [Resurse și proveniență](docs/continuity/04_RESURSE.md)
6. [Probleme și pași următori](docs/continuity/05_PROBLEME_SI_PASI.md)
7. [Verificări și milestone-uri](docs/continuity/06_VERIFICARI.md)

Implementarea existentă este în `feat/cinematic-hero-v1`, commit `9a4ee466aec629cc6ae4c95e7817272b1eee6c8a`. Acest checkpoint pornește din ea și îi păstrează `index.html` și cele cinci imagini identice.

Direcție aprobată: premium, minimalist, cinematic, hero dominant, animații fluide, mobil performant, logo oficial intact, meniu + club + rezervări la **0787 366 166**, fără abonamente suplimentare. Alegerea exactă a mock-up-ului final nu a putut fi dovedită din istoricul recuperat.

**Nu se publică în producție în etapa de audit.** Ramura `docs/continuity-audit-20261018` conține un guard Vercel care dezactivează deployment-urile automate doar pentru această ramură. Nu modifica DNS, domenii sau celelalte aplicații.
