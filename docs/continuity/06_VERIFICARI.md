# Verificări și jurnal de milestone

## M1.3 — categorii vizuale Meniu, 18.10.2026

Vezi `12_M13_CATEGORII_MENIU.md`. Trei imagini noi de categorie cu bucătari miniaturali; catalog/media citite read-only; pagina Meniu extinsă și navigare locală. Verificări statice și inspecție imagini; browser QA neexecutat. Celelalte pagini și backend-ul sunt neschimbate. Fără deploy.

## M1.2 — hero artistic și email, 18.10.2026

Vezi `11_M12_HERO_BUCATARI_EMAIL.md`. Doi bucătari miniaturali în hero static; office@oldmike.ro confirmat și afișat în Contact. Verificări statice și inspecție imagine desktop/derivat mobil PASS. Formularul rămâne local, fără trimiteri. Browser QA neexecutat. Fără deploy.

## M1.1 — corecții cerute și Contact, 18.10.2026

Vezi `10_M11_CORECTII_VIZUALE_CONTACT.md`. Cerințe și adresa confirmate, hero descompus, CTA de meniu accesibil, Pizza/Bere generice, Galerie cu patru zone, hartă și formular local de grup. Fotografii de meniu recuperate prin citiri read-only; fără mutații Supabase sau în repo-ul meniului. Verificări statice și validare formular PASS; browser, hartă live și trimitere email neexecutate. Cererile nu sunt salvate sau trimise. Niciun deploy sau acces la Berăria.

## M1 — concept multipagină, 18.10.2026

Implementare și verificări în `09_M1_PROPUNERE_VIZUALA.md`. Surse și prompturi în `../design/ASSET_PROMPTS_M1.md`. Verificări statice PASS; browser QA și Lighthouse neexecutate deoarece control-browser nu este disponibil în mediul managed. Administrare doar demo local; contact incomplet; imagini de produs de concept. Nicio publicare sau mutație în cPanel/celelalte aplicații. Ramură de design cu guard Vercel de non-deploy.

## M0.2 — clarificare vizuală și structură, 18.10.2026

- Bază remote: `d7dcf7c43d4968ee279ed3b497d12ba5856d8b6b`.
- Înregistrate cerințele explicite: static hiperrealist preferat animației aproximative; homepage aerisit cu un singur produs; editare cinematică a localului recognoscibil; încărcare săptămânală de postere.
- Două clarificări primite: Meniu vizual propriu cu mâncare/băuturi și link către meniul complet; Evenimente pentru sport și alte evenimente.
- Creat `08_DIRECTIE_VALIDATA_SI_NAVIGARE.md`, actualizate punctele de intrare. Propus modul local PHP de administrare, dependent de verificarea hostingului; nu a fost implementat.
- Nicio schimbare HTML, imagini runtime sau configurare. Fără generare/editare de imagini, acces cPanel sau deploy. Verificări documentare și integritate runtime, nu teste UI.

## M0.1 — strategie și corecția destinației de hosting, 18.10.2026

- Bază: checkpoint `d9daedf45d308466390132e785a23a41c6159380`; HEAD remote reverificat înainte de scriere.
- Cerințe consemnate: cPanel final fără Vercel/costuri noi; Berăria exclusă complet; ghidare pentru începător; execuție eficientă într-o direcție coerentă.
- Creat `07_STRATEGIE_EXECUTIE.md`: strategie comercială, propunere M06, storyboard de mișcare, necesar, evaluare instrumente și plan până la lansare.
- Actualizate punctele de intrare, arhitectura, deciziile și planul pentru a marca Vercel drept istoric. README și AGENTS trimit la strategia curentă.
- Verificare documentară: diff fără erori de whitespace, toate link-urile locale rezolvate, cele șase fișiere runtime identice cu hero v1; nicio modificare la `vercel.json`. Nu au fost rulate teste UI.
- Nu s-a folosit Canva/Lovable, nu s-a conectat Flair, nu s-au generat active noi și nu s-a accesat cPanel/Berăria. Nu s-a făcut deploy.
- Rămâne: prezentarea strategiei, apoi pregătirea unui singur concept local desktop/mobil/mișcare și confirmarea vizuală. M06 rămâne recomandare a asistentului.

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
