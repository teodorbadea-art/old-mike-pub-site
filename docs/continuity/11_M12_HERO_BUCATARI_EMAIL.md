# M1.2 — doi bucătari miniaturali și email confirmat

18.10.2026. Bază: `ea9cf69ca8d48eb2d55a38e40caaebbc8bd92b24`, ramura `design/m1-static-cinematic`.

## USER VALIDATED

- Email de contact: **office@oldmike.ro**. În contextul cerinței existente de notificări pentru cererile de grup, acesta este destinatarul de implementat.
- Hero artistic: doi bucătari mici care lucrează la un burger imens, hiperrealist, cu umor discret. Compoziție artistică, fără a reprezenta explicit un produs din meniu.

## Implementat / FACT

- Editare cu imagegen integrat a hero-ului descompus v2: exact doi bucătari adulți miniaturali, unul la bază și unul pe scară, iluminare cinematică. Fără animație sau mișcarea fotografiei.
- Active noi versionate `assets/images/hero-chefs-v3.webp` și `hero-chefs-mobile-v3.webp`. Versiunile vechi se păstrează. Meniu păstrează burgerul asamblat distinct.
- Contact afișează emailul cu legătură `mailto:`. Aceasta deschide aplicația de email a vizitatorului; nu trimite automat mesaje.
- Generatorul paginilor și pachetul autonom de evaluare sunt actualizate la M1.2. Promptul exact, sursa și conversiile sunt în `../design/ASSET_PROMPTS_M12.json`.
- Logo-ul oficial, meniul existent, Old Mike Community, Berăria și configurațiile de găzduire nu au fost modificate. Fără deploy.

## Verificări

PASS: generarea paginilor și pachetului; sintaxa JS comun, inline și review; resurse locale; un H1/pagină și alt pentru imagini; referințe către noul hero desktop/mobil; email/mailto corect; formular explicit preview-only; `git diff --check`. Imaginea completă și derivatul mobil au fost inspectate vizual, ambii bucătari sunt vizibili.

Browser QA, comportamentul CSS pe dispozitive, Lighthouse și harta live rămân neexecutate: control-browser nu este disponibil în mediul managed. Nu afirmăm testare în browser. Emailul nu a fost testat prin trimitere și livrabilitatea inboxului nu este verificată.

## Rămâne

Evaluarea vizuală a propunerii M1.2 de utilizator; verificarea accesului și capabilităților cPanel; backend pentru administrarea posterelor/cererilor și livrarea emailului. Formularul NU salvează și NU trimite încă. Destinatarul este acum confirmat, dar activarea cere implementare și teste reale înainte de lansare. Programul și link-urile sociale rămân de confirmat. Publicarea necesită autorizarea utilizatorului.
