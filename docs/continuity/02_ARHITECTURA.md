# Arhitectură și medii

## Existent, verificat

Site static: un `index.html` cu CSS și JS inline, cinci JPG în `img/`. Nu există `package.json`, lockfile, API, bază de date, autentificare sau framework în ramura hero v1. Nu introduce automat React/Next/Supabase doar pentru o pagină de prezentare.

Navigarea existentă oferă Acasă, Meniu, Old Mike Club, Contact și apel telefonic. Meniul mobil este un buton care comută vizibilitatea navigării. Integrarea cu celelalte produse este exclusiv prin link-uri externe.

Dependențe runtime în cod:

| Resursă | Sursă actuală | Implicație |
|---|---|---|
| Burger și bere | `raw.githubusercontent.com/.../old-mike-pub-site/main/img/` | Ocolește livrarea imaginilor din propriul deployment și depinde de `main` |
| Logo | `raw.githubusercontent.com/teodorbadea-art/Meniu-Electronic/main/assets/oldmike-logo.png` | Dependență runtime de alt repo; de copiat ulterior identic, fără a modifica sursa |
| Fonturi | Google Fonts: DM Sans 400–700, Outfit 400–900 | Cereri externe și multe greutăți; de evaluat înaintea optimizării |
| Animații | CSS: intrare, floating, marquee | `prefers-reduced-motion` există; costul pe mobil nu a fost măsurat |

## Vercel recuperat

- Proiect: `old-mike-pub-preview`, `prj_fTCbsQPfcvZ4pzONcBdnrMWqTSNI`.
- Deployment: `dpl_BGxkz6GWKq6XCh5fZgwoQQeFktnq`, READY, SHA `9a4ee466aec629cc6ae4c95e7817272b1eee6c8a`, ramura `feat/cinematic-hero-v1`.
- URL existent: https://old-mike-pub-preview-6k1jie18p-teodorbadea-6332.vercel.app
- Domenii listate de proiect: `old-mike-pub-preview.vercel.app`, `old-mike-pub-preview-teodorbadea-6332.vercel.app`, `old-mike-pub-preview-git-feat-cinematic-47e7a4-teodorbadea-6332.vercel.app`.
- API-ul marchează deployment-ul anterior `target: production`, deși proiectul se numește preview. Nu presupune că un nume de proiect înseamnă automat mediu Preview.
- SSO Protection: activ, `all_except_custom_domains`. Accesul anonim la URL nu este demonstrat în audit.
- În proiectul inspectat nu este listat `oldmike.ro`. Nu este o verificare DNS și nu dovedește starea site-ului oficial din altă găzduire.
- Planul, consumul și limitele efective ale contului nu au fost recuperate. Nu promite un număr de deploy-uri gratuite.

## Decizie tehnică pentru M0

Ramura de continuitate pornește din implementarea existentă. `vercel.json` dezactivează automatizarea doar pentru `docs/continuity-audit-20261018`, folosind `git.deploymentEnabled`. Nu modifică UI și nu afectează setările celorlalte ramuri.

Sursă oficială verificată în documentația Vercel: https://vercel.com/docs/project-configuration/git-configuration . Ramurile nenumite rămân activate implicit. Guard-ul nu reprezintă un deployment și nu împiedică un deploy manual; nu invoca deploy manual pentru M0.

## Propunere de continuare, încă neimplementată

Păstrează site-ul static cât timp cerințele nu cer backend. Livrează logo și imagini din același site, în formate și dimensiuni adecvate; restul integrării rămâne prin link-uri. Nu adăuga login, formular de rezervare, catalog duplicat, analytics/cookies sau CMS fără o cerință nouă.

Preview viitor: verifică explicit ținta, ramura și SHA înainte de creare. Un singur preview după verificări locale și după alinierea vizuală; nu modifica DNS și nu promova aliasul `oldmike.ro`. Producția este un milestone separat, după prezentare și autorizare explicită.
