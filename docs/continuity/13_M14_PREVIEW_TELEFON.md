# M1.4 — previzualizare statică pentru telefon

18.10.2026. Bază `4c99368e4e14ab68f7acee53c56be2e48c10cda4`.

USER REPORTED: fișierul de evaluare lasă pagina goală pe telefon. Dispozitivul/aplicația de deschidere nu sunt cunoscute.

FACT: fișierul desktop construiește conținutul unui iframe din JavaScript; fără executarea acelui script, iframe-ul este gol. INFERENCE: viewer-ul telefonului poate bloca scriptul/srcdoc sau fișierul local. Cauza pe dispozitiv nu este reprodusă și nu este demonstrată.

DECISION: livrare separată pentru evaluare pe telefon, fără publicare: `scripts/build_mobile_review.py` produce `output/old-mike-previzualizare-telefon.html`. Cinci pagini sunt prezente direct în HTML, una sub alta, cu ancore native și navigare sus. Fără JavaScript sau iframe; harta devine link-urile existente, formularele sunt dezactivate explicit, Galeria se vede integral fără lightbox. Nu este un test funcțional al site-ului și nu înlocuiește administrarea/formularele reale. Desktop review existent rămâne disponibil.

Fotografiile sunt redimensionate la maximum 900px și convertite JPEG pentru fișierul separat; logo-ul oficial păstrează bytes originali. Aproximativ 3,1 MB față de 6,3 MB pachet desktop. Modificările de structură/navigare sunt numai în acest ambalaj de evaluare. Site-ul și imaginile sale rămân neschimbate.

PASS verificări statice: cinci pagini/conținut HTML, lipsa script/iframe/dialog, imagini embedded decodabile, ID-uri unice și ancore valide, formulare disabled, dimensiune sub 3,5MB, pagini/CSS originale identice cu HEAD, git diff --check.

UNKNOWN: deschiderea efectivă în viewer-ul utilizatorului. Browser QA/dispozitiv real neexecutate (control-browser indisponibil în mediul managed). Un viewer care nu redă deloc HTML poate cere descărcare și deschidere în browser sau un preview găzduit autorizat separat. Nu promitem că eliminarea iframe-ului rezolvă orice viewer.

Fără deploy, modificări Vercel/cPanel/DNS sau acces la Berăria. Rămâne evaluarea pe telefon și restul M1.3.
