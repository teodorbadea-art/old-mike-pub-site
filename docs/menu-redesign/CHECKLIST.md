# Verificare M3.3.1 — fără publicare

- [x]Public conține numai meniu.php/assets/cache; zero HTML-uri. Lista explicită de urcat este generată din fișierele efective.
- [x]Nicio modificare a HTML-urilor existente; hash-uri comparate înainte/după lucru.
- [x]Import design.css pe prima linie din site.css; familii de rezervă păstrate.
- [x]60probe PHP, inclusiv cache/invalidare individuală, avertisment cel mult o dată pe oră și lock concurent, lipsă master mobil fără avertismente.
- [x]132imagini separate WebP/AVIF, PHP autonom și previzualizare în afara public.
- [ ]Chrome1440/390/360: sticky mobil≤100px, ținte44px, navigare/taburi/fără JS/reduced motion.
- [ ]Fonturi prin import pe index/contact/evenimente/galerie/confidentialitate la1440și390px; fără modificarea HTML-urilor.
- [ ]PHP cURL real și Lighthouse mobil pe revizia curentă.
- [ ]Aprobarea utilizatorului, PHP8.1+cURL și verificare pe hosting înainte de lansare.
