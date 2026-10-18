# Probleme și pași următori

**Actualizare M0.1:** planul operațional curent este în `07_STRATEGIE_EXECUTIE.md`. Vercel rămâne doar istoric. Excludere completă Berăria. Conceptul recomandat pornește din M06 și exemplele culinare recuperate; nu se consideră încă aprobat individual.

| ID | Prioritate | Constatare | Pas următor |
|---|---|---|---|
| P01 | Blocant vizual | Aprobarea exactă M01–M07 / hero v1 nu a fost recuperată | Prezentare checkpoint; revalidare punctuală a compoziției |
| P02 | Ridicată | Codul v1 diferă vizibil de scenele ample din mock-up-uri | Alege referința dovedită/revalidată înainte de rescriere |
| P03 | Ridicată | AI assets existente nu demonstrează produsul efectiv servit | Compară cu pozele autentice din meniu; nu inventa promisiuni |
| P04 | Ridicată | Logo și imagini încărcate de pe raw GitHub | Livrează local din site, logo identic; fără mutații în repo-ul sursă |
| P05 | Ridicată | Adresa din cod „Matei Voievod 67” nu are aprobare recuperată; program/social lipsesc | Verifică sursă business înainte de text public |
| P06 | Medie | Mobil/performance necertificate | Test la 360/390/768/1440 px, imagini, fonturi, layout shift și Lighthouse local |
| P07 | Medie | Grain fix, blur, umbre mari și animații continue | Măsoară costul și păstrează mișcarea elegantă; reduced motion există deja |
| P08 | Medie | Buton meniu are aria, dar nu are închidere cu Escape / gestionare focus explicită | Verifică tastatura și corectează în M1 dacă necesar |
| P09 | Medie | Lipsesc width/height explicite la imaginile HTML | Stabilizează proporțiile; verifică CLS real |
| P10 | Ridicată operațional | Deployment anterior este `target: production` în proiect numit preview | Verifică explicit mediul înainte de orice deploy viitor |
| P11 | Medie | SSO activ pe proiectul Vercel | Testează accesul utilizatorului la preview; nu dezactiva protecția implicit |
| P12 | Ridicată continuitate | Înainte de M0 nu exista documentație în repo | Rezolvat documentar prin acest checkpoint; actualizare la milestone |

## Milestone-uri

### M0 — audit și checkpoint, această etapă

Recuperează sursele, distinge confirmările, documentează codul, resursele, mediul și limitele, salvează în GitHub și prezintă utilizatorului. Fără schimbări UI, publicare sau DNS.

### M1 — aliniere și pregătire locală

După prezentarea M0: revalidează doar vizualele nedovedite; păstrează implementarea ca punct de plecare. Rezolvă livrarea locală de asset-uri, logo-ul, navigarea și performanța. Nu promite ofertă, program sau adresă nevalidate. Documentează schimbările și salvează milestone-ul în GitHub.

### M2 — prezentare unică pentru pachetul verificat

Teste locale complete: layout mobil/desktop, lizibilitate, navigare, apel, meniu/club, mișcare redusă, tastatură, imagini și console. Prezintă o singură variantă coerentă și grupează feedback-ul. Dacă e nevoie de link de test, verifică întâi o zonă izolată în găzduirea existentă; nu crea deployment Vercel.

### M3 — lansare separată

Numai după evaluare și autorizare explicită: confirmă conținutul, domeniul oficial, DNS, rollback și costurile reale ale configurației. Prezintă concret rezultatul înainte de publicare. Nu muta și nu modifica aplicațiile meniu/club.

## Ce nu este o lipsă de decizie

Nu mai întreba dacă este nevoie de site premium, cinematic, cu hero dominant, mobil performant, meniu/club, rezervări la 0787 366 166 sau fără abonamente suplimentare. Acestea sunt stabilite. Întrebarea rămasă este selecția vizuală exactă și conținutul fără dovadă recuperată.
