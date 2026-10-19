# M2.8 — Ciorne și promovare permanentă pentru meciuri, 19.10.2026

## Scop autorizat
USER VALIDATED: editarea/ștergerea ciornelor, înlocuirea posterului; adăugarea promovării permanente a meciurilor. Site-ul rămâne în staging; pagina grupului oldmike.ro este exclusă. Fără probe reale de email.

## Implementat local
server/lib/drafts.php: actualizare ciornă cu validare titlu/categorie/date și poster nou opțional; fără fișier nou se păstrează cel actual. Ciorna nu se publică prin editare. Ștergere ciornă cu confirmare explicită server-side; eliminare fișier poster după salvarea metadatelor. Curățarea verifică nume strict, refuzăsymlink și păstrează orice fișier încă referit de alt eveniment. Erorile de curățare sunt raportate și nu anulează logic metadatele deja salvate.

Acțiunile de editare/ștergere refuză evenimentele publicate: trebuie întâi ascunse. Token de revizie calculat din eveniment, verificat sub lock, previne suprascrierea/ștergerea după o modificare concurentă. CSRF și autentificarea existente protejează POST-urile. Auditul stochează numai acțiune/ID/timp. Nu este operată nicio ciornă reală în timpul implementării.

Admin: sub fiecare ciornă apar «Editează ciorna» și «Șterge ciorna», deschise la click. Editarea completează câmpurile curente; înlocuirea posterului e opțională, maximum8MB. Ștergerea cere bifă și buton distinct. După validare eșuată se păstrează câmpurile text; fișierul trebuie reselectat. Evenimentele publicate oferă în continuare «Ascunde».

Promovare permanentă: secțiune separată de înregistrările cu date, vizibilă sub evenimente inclusiv când sunt zeroevenimente. Titlu «Meciurile importante. Cu gașca ta.», descriere și CTA0787366166, adresă, poster complet cu titlu/logo/adresă/telefon pe imagine, cerut ulterior de utilizator după exemplul încărcat. Generat cu logo-ul oficial ca referință; antetul site-ului păstrează fișierul oficial intact. Poster nou imagegen,3varianteWebP responsive, scenă ilustrativă generată cu adulți,meci,bere,platou/cartofi prăjiți. Proveniență în docs/design/ASSET_SPORT_PERMANENT_M28.json. Nu are start/end și nu e afectată de expirarea evenimentelor. Nu este un eveniment/documentar real și nu promite transmisia tuturor meciurilor.

Idee propusă utilizatorului: «Aniversări și seri cu gașca», legată de Contact; încă nu s-a adăugat un al doilea poster sau eveniment permanent.

## Verificări și limite
PASS local:validate_site.py(8pagini,resurse/ancore/logo/metadate/cover-uri/imagini/review-uri); package_cpanel.py și tests/package_cpanel.py(checksum-uri,arhivă,resurse/configurare); hours-and-form.cjs(10cazuri program și regresii formular); node--check site-runtime.js; git diff--check. Probă structurală PASS: exactosecțiune permanentă în afara regiunii de evenimente înlocuite de runtime; câmp confirmare și înlocuireposteropțional în admin.

Teste PHP pregătite în tests/php/drafts.php: editare, păstrare/înlocuireposter, validare date, revizie veche, confirmare lipsă, refuz publicate, ștergere și izolare fișiere, traversare și audit; exclusiv fixtures sintetice. Ele NU au fost rulate: PHP lipsește local, iar accesul cloud browser la https://server.affixhost.co.uk:2083/ răspunde «upstream connect error or disconnect/reset before headers. reset reason: connection termination», inclusiv după unica reîncărcare. Sesiunea de noapte/filele nu mai erau disponibile. Nu declara lintPHP sau noile teste PASS și nu presupune că eroarea cere o nouă parolă.

## Stare instalare
NEAPLICAT în staging. Pachet local output/old-mike-drafts-m28.zip,13fișiere strict pentru oldmike-test, hash-uri before/after și backup privat. Include și actualizarea locală a datelor firmei dinM2.7 în footer; ținta reală are încă vechiul footer. Noua lib/drafts.php se copiază înainte de admin pentru a evita un require lipsă. Nu include config/parole/date/.htaccess/email și nu șterge ciorne existente.

Reluare: acces cPanelfuncțional; încărcare/extragere în director privat; lint public/admin/index.php,public/lib/drafts.php,updater; rulat tests/php/backend.php și tests/php/drafts.php pe găzduire. Numai după PASS se rulează updaterul. Apoi verificareHTTP401staging și probă autentificată pe o ciornă sintetică, păstrând posterele utilizatorului. Accesul și parola nu se cer în chat.

Restanțe: curățare automată a evenimentelor/posterelor după expirare nu este implementată în această rundă; propunerea de7zile nu este încă job instalat. Formularul și notificările rămân inactive. Lansare publică numai la destinație separată confirmată; vechiul instalatorM26 la rădăcină rămâne blocat.
