# M3.3.1 — corecție blocantă: pachet fără HTML

Utilizatorul a auditat M3.3: cele4HTML-uri incluse erau surse nelive, cu noindex, domeniuoldmike.ro și fără site-runtime.js/JSON-LDindex. Nu trebuie urcate. Instrucțiunea actuală prevalează față de M3.3: nicio pagină HTML existentă nu se editează/înlocuiește.

Packagerul permite în public doar meniu.php/assets/cache, blochează oriceHTML și nu mai colectează activele celorlalte4pagini. Previziunarea HTML rămâne numai la rădăcinaZIP, în afara public. FISIERE_DE_URCAT.txt este lista exactă generată din payload. Instalarea cere combinarea fișierelor, nu înlocuirea folderelor; cache/menu.json doar la prima instalare. HTML-urile live și site-runtime.js rămân păstrate.

Importul oldmike-design.css este prima linie din site.css. Chrome verifică importul cu linkurile directe design.css cu query blocate: index/contact/evenimente/galerie/confidentialitate la1440și390(10probe). Nu s-au schimbat HTML-urile; SHA256 comparate înainte/după. Fișierele locale HTML istorice nu sunt dovadă că funcțiile live au fost testate; verificarea de acum confirmă fonturile și payloadul.

picture verifică existența și dimensiunile masterului mobil înainte de utilizare; fără master păstrează variantele480/800, fără avertismente. Log cache>60min limitat la o dată/oră prin timestamp persistent și flock separat (menu.stale-warning), inclusiv cereri concurente. Sticky mobil96px, ținte44px.60testePHP, Chrome1440/390/360/taburi/fărăJS/reducedmotion și RPCPHP257PASS. Lighthouse94/100/100/100.

Revizie runtime verificată d24c35e52d43dd2906a52018a3135d3002145031; Actions39374449320, artifact12242457433. Nu publica. Sursele salvate pe design/m3-live-menu-editorial, fără deploy. Pachet output/menu-m3/Old-Mike-Meniu-M3-Pachet.zip înlocuieșteM3.3; păstrează identitateaLibrary libfile_bb489d3e261c8191b24fbb13da01f2ac, versiunea anterioară3.
