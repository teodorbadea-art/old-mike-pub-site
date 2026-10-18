# M1.7.1 — Revenire la spațiile cinematice

Data: 18.10.2026. Bază: d7396fe896c628672b34721d28d11664a3015b77, ramura design/m1-static-cinematic.

USER VALIDATED: utilizatorul a semnalat revenirea nedorită la fotografiile originale. Se restaurează selecția generată cinematic existentă, fără regenerare și fără revenirea întregului site la o versiune veche.

| Spațiu | Imagine restaurată |
| --- | --- |
| Terasa exterioară | terrace-wide-v2.webp |
| Terasa acoperită | covered-terrace-v2.webp |
| Interior | interior-wide-v2.webp |
| Barul | bar-wide-v2.webp |

Masterele existente nu au fost modificate. Opt derivate WebP la480/800px păstrează raportul de aspect; script reproductibil: scripts/prepare_m171_venue_variants.py. Generatorul paginii referă imaginile cinematice, cu descrieri alternative corecte. Lightbox-ul păstrează masterele; secțiunea Atmosfera și corecțiile culinare/SEO/contact/program din M1.7 rămân active. Fișierele originale retușate sunt păstrate ca istoric, fără referințe în Galerie. Scriptul istoric prepare_m17_assets.py nu selectează imaginile afișate; selecția aparține build_pages.py.

Verificat: generarea celor8pagini și ambelor fișiere de evaluare; validate_site.py PASS (resurse, imagini decodabile, metadate, logo oficial, hartă la cerere, imagini culinare curente); regresie nouă PASS pentru toate cele4mastere cinematice și variantele responsive; hours-and-form.cjs PASS (10cazuri program/DST/miezul nopții și formular); git diff --check PASS. Fișier telefon3.18MB; desktop15.13MB, ambele autonome. Diferențele din celelalte pagini sunt actualizarea versiunii în configurația publică.

Limite: fără testare vizuală în browser/dispozitiv, fără Lighthouse; această restaurare nu corectează eventualele inscripții deformate din imaginile AI. Acestea rămân de evaluat/corectat înainte de lansare, fără înlocuirea tacită a imaginilor aprobate. Nu se prezintă imaginile generate ca fotografii documentare.

Salvare: milestone GitHub pe ramura de design; commitul cu acest document este checkpoint-ul. Niciun deploy/build Vercel, nicio publicare, nicio schimbare cPanel/DNS/main. Berăria și celelalte aplicații nu au fost accesate sau modificate.

Următorul pas: evaluarea acestei versiuni; apoi continuarea listei de lansare existente (backend cereri/admin, date sociale și firmă, verificări browser/mobil, configurare cPanel autorizată).
