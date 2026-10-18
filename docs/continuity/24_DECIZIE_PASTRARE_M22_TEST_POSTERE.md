# Decizie: păstrăm M2.2 și testăm posterele — 19.10.2026

USER VALIDATED: «rămânem la varianta inițială cea urcată pe oldmike.ro»; în context este variantaM2.2 din zona protejată /oldmike-test/, nu o autorizare de lansare la rădăcina domeniului. ÎncercărileM2.3/M2.4 sunt respinse; rămân în istoricul Git pentru trasabilitate.

FACT: site-ul de test nu a primitM2.3/M2.4. Sursele vizuale, versiunea și generatoarele au fost readuse la commit30fc88be061c92a0e06025b3681cfc534fa2c245 (M2.2), iar cele8pagini și ambele fișiere autonome au fost regenerate local. tests/validate_site.py PASS. Nu este necesar un upload pentru revenire, întrucât stagingul este dejaM2.2. Nicio operație cPanel, producție, Meniu Electronic, Club sau Berăria.

DECISION: nu rula updaterele/pacheteleM23/M24. Fișierele și notele lor sunt istorice. Fișierul autonom salvat anterior în Library ca versiunea9 arată încă încercareaM2.4; nu este reprezentativ pentru decizia curentă. Sursele Git și site-ul de testM2.2 sunt referința curentă.

## Testarea posterelor — în curs, fără PASS declarat
Administrarea instalată: https://oldmike.ro/oldmike-test/admin/. Se utilizează contul configurat de utilizator; parola nu se cere în chat. Pot exista autentificarea Basic pentru întreaga zonă și formularul propriu de conectare la administrare.

Flux verificat din sursa server/admin/index.php: Adaugă un eveniment → Titlu → Categorie(Sport/Alte evenimente) → Începe și Se încheie, ora Bucureștiului → PosterJPG/PNG/WebP maximum1,8MB → Salvează ciorna → verifică imagine/date → Publică. Evenimentul se verifică în /oldmike-test/evenimente.html. Ascunde îl scoate din afișare; evenimentele încheiate sunt filtrate public. Pentru test, încheierea trebuie să fie în viitor.

Încă neconfirmate prin interfață: conectare appadmin, upload, persistența ciornei, publicare/afișare poster, ascundere. Utilizatorul face proba ghidată; orice eroare se investighează înainte de declararea PASS. Formularul pentru grupuri și notificările sunt încă inactive.
