# Verificare M3.3.1 — fără publicare

- [x]Public conține numai meniu.php/assets/cache; zero HTML-uri. Lista explicită de urcat este generată din fișierele efective.
- [x]Nicio modificare a HTML-urilor existente; hash-uri comparate înainte/după lucru.
- [x]Import design.css pe prima linie din site.css; familii de rezervă păstrate.
- [x]60probe PHP, inclusiv cache/invalidare individuală, avertisment cel mult o dată pe oră și lock concurent, lipsă master mobil fără avertismente.
- [x]132imagini separate WebP/AVIF, PHP autonom și previzualizare în afara public.
- [x]Chrome1440/390/360: sticky mobil≤100px, ținte44px, navigare/taburi/fără JS/reduced motion.
- [x]Fonturi prin import pe index/contact/evenimente/galerie/confidentialitate la1440și390px; fără modificarea HTML-urilor.
- [x]PHP cURL real:257produse. Lighthouse mobil:94performanță /100accesibilitate /100bune practici /100SEO.
- [ ]Aprobarea utilizatorului, PHP8.1+cURL și verificare pe hosting înainte de lansare.

Revizie verificată: `d24c35e52d43dd2906a52018a3135d3002145031`.
Rulare: https://github.com/teodorbadea-art/old-mike-pub-site/actions/runs/39374449320
Bara mobilă măsurată: **96px** la390și360px.10verificări fonturi (5pagini×2lățimi) cu linkurile directe design.css blocate și importul activ. Pachetul este verificat automat în CI pentru zeroHTML-uri în public.
