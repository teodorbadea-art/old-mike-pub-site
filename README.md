# Old Mike Pub — site oficial

Repository dedicat site-ului de prezentare. Meniu Electronic și Old Mike Community sunt aplicații independente și nu se modifică aici.

## Începe aici

Checkpoint de recuperare: **18 octombrie 2026**, milestone **M0 — audit și continuitate**.

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
