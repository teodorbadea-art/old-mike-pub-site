# Verificări și jurnal de milestone

## M0 — 18.10.2026

### Citiri și recuperări efectuate

- Metadate repository, toate cele două ramuri existente la început, cele șapte commit-uri accesibile, tree-uri și codul complet `index.html`.
- Clone local read-only pentru cod; inspecție Git a blob-urilor și a arborelui real de commit.
- Colecții GitHub PR și issues: goale. Actions: 0 runs.
- Vercel: proiectul `old-mike-pub-preview` și un deployment anterior READY, asociat exact cu SHA hero v1.
- Inventar al imaginilor recente din proiect; recuperare locală a 42 de fișiere relevante, fără modificarea originalelor.
- Inspecție vizuală a șapte mock-up-uri și a planșei cu fotografii/capturi. Lectură text a mock-up-urilor unde a fost disponibilă.
- Logo: existență și blob verificate prin tree-ul Meniu Electronic, read-only.
- Două căutări țintite ale istoricului: direcție recuperată, feedback individual complet nerecuperat.
- Documentație Vercel consultată pentru guard-ul `git.deploymentEnabled`.

### Modificări M0

README de orientare, AGENTS pentru continuitate, șapte documente tematice, inventar CSV cu hash-uri și dimensiuni, catalog documentar M01–M07. Guard Vercel doar pentru ramura `docs/continuity-audit-20261018`. Niciun asset runtime și niciun HTML/CSS/JS modificat.

### Verificare locală înainte de salvare

Control obligatoriu: `git diff --check`; JSON valid; toate link-urile Markdown locale există; toate cele șase fișiere runtime (`index.html` + cinci JPG) sunt identice byte-cu-byte cu `9a4ee466aec629cc6ae4c95e7817272b1eee6c8a`; inventarul și catalogul au aceleași șapte referințe M01–M07; lipsa materialelor de inspirație din `img/`.

Rezultat executat: **PASS** — `git diff --check`, parsare JSON, 6 fișiere runtime identice byte-cu-byte, 12 link-uri locale rezolvate, 47 intrări în inventar (42 materiale recuperate + 5 JPG existente), 7 referințe unice M01–M07 și 10 fotografii reale ale localului. Catalogul documentar are 342013 bytes și nu este folosit în site.

Commit-ul checkpoint-ului se verifică în GitHub; commit-ul nu trebuie să se autociteze în propriul conținut. Ramura este punctul de intrare, părintele este SHA hero v1.

### Limite explicite

Nu au fost rulate teste browser, Lighthouse sau teste end-to-end și nu se declară PASS pentru funcționalitatea site-ului. Nu au fost testate actual programul, adresa, DNS, link-urile externe, accesul anonim la preview sau limitele financiare ale conturilor. Nu s-au citit secrete și nu s-au făcut mutații în celelalte aplicații.

Endpoint-ul GitHub deployments nu a fost acceptat de suprafața de citire; starea deployment-ului a fost recuperată direct din Vercel. Pentru tree SHA s-a folosit obiectul real de commit (`60704f1e0ba29709afa8697982e28f973d5a9588`), confirmat și local, nu câmpul sintetic din răspunsul recursive-tree.

Nu s-a invocat niciun build/deploy, nu s-au schimbat setări Vercel, domenii, DNS sau producția. Nu s-a făcut merge în `main` sau în ramura hero-ului.

## Șablon pentru următorul milestone

- Scop și bază SHA:
- Decizii aplicate / revalidate:
- Fișiere și comportament schimbat:
- Verificări executate și rezultat:
- Ce nu a fost verificat:
- Commit salvat în GitHub:
- Preview: SHA, mediu, URL; număr build-uri/deploy-uri:
- Probleme rămase și următorul pas:
