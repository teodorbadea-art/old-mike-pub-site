# M1.5 — burger cu o singură bucată de carne

18.10.2026. Bază `92d4d47fbbe9a9fd56cc1adc965325aa3b437e7c`, ramura design/m1-static-cinematic.

USER VALIDATED: Old Mike nu servește burger dublu; reprezentarea cu două bucăți de carne este necorespunzătoare. Cerința se aplică atât hero-ului Acasă, cât și fotografiei din Meniu. Bucătarii miniaturali se păstrează.

Implementat: două editări imagegen punctuale. Acasă: burger descompus, o singură bucată de carne și un strat cheddar, aceiași doi bucătari. Meniu: burger asamblat cu o singură bucată de carne/un strat cheddar. Aspectul cinematic, amplasarea în dreapta și spațiul pentru text se păstrează. Desktop și mobil au active noi versionate; versiunile duble rămân doar istorice și nu sunt referite în paginile actuale.

Active: `hero-single-chefs-v4.webp`, `hero-single-chefs-v4-mobile.webp`, `burger-single-v2.webp`, `burger-single-v2-mobile.webp`. Prompturile exacte și sursele sunt în `../design/ASSET_PROMPTS_M15.json`. Conversii WebP quality86; mobil crop (512,0,1536,1024), 900×900. Ambele fișiere de evaluare sunt regenerate.

Verificări: inspecție vizuală a celor două editări și a derivatelor mobile — o singură bucată de carne în fiecare, ambii bucătari ai hero-ului vizibili. Verificări statice: generator pagini/desktop review/mobile review, resurse locale, noile referințe, eliminarea referințelor active la burgerii dubli, alternative text, decodarea/dimensiunile imaginilor, sintaxa JS, git diff --check. Celelalte pagini, CSS și imaginile de categorie rămân neschimbate.

Limită: nu s-a identificat și validat separat o fotografie actuală a burgerului servit; corecția confirmă numărul bucăților de carne, nu certifică gramajul, toate ingredientele sau identitatea cu un produs anume. Fotografiile rămân concepte pentru evaluare. Înainte de lansare se compară cu produsul real. Browser QA, Lighthouse și confirmarea deschiderii pe telefonul utilizatorului rămân neexecutate.

Nicio publicare, modificare cPanel/Vercel/DNS sau intervenție asupra Meniului Electronic, Old Mike Community ori Berăriei. Backend-ul pentru cereri/evenimente rămâne de realizat; formularul nu trimite. Rămâne evaluarea vizuală și restul checkpoint-ului M1.4.
