# Continuitate Old Mike Pub

Înainte de lucru citește `docs/continuity/00_START_AICI.md`, apoi documentele legate de task. Nu reconstrui istoricul din ultimul mesaj și nu considera o propunere drept aprobare.

M1.1: citește `10_M11_CORECTII_VIZUALE_CONTACT.md`. Acasă are burger descompus static, Meniu alt burger asamblat; Pizza generic, niciun „Noroc” la bere; Galerie patru zone recognoscibile fără crop arbitrar. Adresă USER VALIDATED: Matei Voievod 67, București. Cererile pentru grupuri trebuie primite în administrare + email, dar în concept sunt doar previzualizate. Nu activa trimiteri până la configurarea/testarea backend-ului și confirmarea adresei destinatare.

Prioritate de design: `08_DIRECTIE_VALIDATA_SI_NAVIGARE.md`. Homepage cu un singur produs hero static; fără plutire/parallax/zoom al pozei și fără alte fotografii cu mâncare pe Acasă. Meniu este pagină vizuală proprie cu recomandări și băuturi + link la meniul complet. Galerie: originale editate cinematic, arhitectură recognoscibilă. Evenimente: sport și altele, upload simplu de postere; automatizarea nu este cerută acum.

- Scope exclusiv: `teodorbadea-art/old-mike-pub-site`.
- Nu modifica Meniu Electronic sau Old Mike Community. Integrarea curentă: link-uri externe, fără DB comună.
- Nu utiliza Lovable sau Canva. Nu adăuga servicii/abonamente plătite.
- Păstrează logo-ul oficial integral, fără reinterpretare. Logo-urile din imagini AI nu sunt sursă oficială.
- Nu publica, nu promova deployment-uri și nu modifica domenii/DNS înainte de prezentarea rezultatului și autorizarea utilizatorului pentru pasul respectiv.
- Salvează fiecare milestone în GitHub; descrie modificări, verificări efectuate, limite și rest de lucru în jurnalul de continuitate.
- Citește mai întâi `07_STRATEGIE_EXECUTIE.md`: găzduire finală pe cPanel, fără dependență Vercel. Nu crea preview-uri Vercel în fluxul curent; deployment-ul existent rămâne istoric. Grupează schimbările și verifică local.
- `beraria.oldmike.ro` este exclus complet: nu accesa și nu modifica site-ul, fișierele, DNS-ul sau configurația lui. Delimitează document root-ul `oldmike.ro` prin configurația domeniului, fără a explora Berăria.
- Utilizatorul nu are experiență web: explică scopul și consecința fiecărui pas, în limbaj simplu; cere intervenție doar când accesul sau o informație de business o impune. Prezintă o singură propunere coerentă desktop/mobil/mișcare, nu serii de variante fără direcție.
- Pentru M0: nicio schimbare la `index.html` sau `img/*`; numai documentație, catalog de referințe și guard de non-deploy al ramurii.
- Nu relua proiectul de la zero. Designul exact al hero-ului și mock-up-ul final au încă aprobare nerecuperată; vezi `03_DESIGN.md`.
- Stări obligatorii: FACT (verificat), USER VALIDATED (cerință explicită), DECISION (alegere documentată), INFERENCE (interpretare), UNKNOWN (nedovedit).
