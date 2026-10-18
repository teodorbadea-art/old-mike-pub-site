# M1 — imagini, surse și prompturi

Mod folosit: built-in `image_gen`, fără CLI/API sau cont nou. Derivatele WebP sunt în `assets/images/`. Originalele generate sunt păstrate automat; variantele de site sunt copii optimizate. Conversiile WebP și crop-ul responsive sunt derivate tehnice, nu regenerări.

## Hero: `assets/images/hero-burger-v1.webp`

Sursă de referință: `img/burger.jpg`, imagine AI existentă în repo, nu fotografie autentificată a produsului servit. **Concept vizual**, fără nume de produs, ingrediente sau prețuri declarate în pagină. Înainte de producție trebuie comparat cu produsul real din Meniul Electronic și fotografia sa. Aceasta este o limită explicită, nu o verificare deja efectuată.

Prompt efectiv:

> Use case: ads-marketing. Asset type: hyperrealistic static website hero photograph for Old Mike Pub, landscape 1536x1024 or wider. Reference image 1 is the existing concept burger, use its double beef patty, cheddar, lettuce, red onion, pickles, bacon and sesame brioche appearance as product reference, not its book, words or framing. Create ONE arresting exquisitely photographed gourmet pub burger, natural believable food texture, juicy seared beef, melted cheddar, crisp lettuce, subtle realistic imperfections, close-up 3/4 eye-level food advertising photography with natural macro depth, warm amber rim light, almost black chocolate studio background, very tasteful cinematic color, not oversaturated. Composition for a minimalist full-height desktop website: single complete burger on low dark stone slab occupies RIGHT 55 percent, left 42 percent is clean dark negative space for HTML headline. Entire bun and burger visible, grounded on slab, no floating ingredients. Tight sophisticated spotlight, faint warm background haze, absolutely no text, no logo, no watermark, no menu book, no beer, no fries, no second product, no sparks, no decorative plants. It should feel like a real premium food campaign shot not 3D CGI. This is a design concept image, not documentation of the actually served recipe.

Rezultat inspectat vizual: un singur burger, fără text/logo, spațiu în stânga, slab/table recognoscibil, fără ingrediente suspendate. Master 1536×1024. Derivat mobil 900×900, crop spre produs, fără animație.

## Terasă: `assets/images/terrace-cinematic-v1.webp`

Țintă editată: `IMG_6692.jpeg` din materialele proiectului. Prompt efectiv:

> Use case: lighting-weather. Edit target: supplied real photograph of Old Mike Pub terrace. Create a refined cinematic editorial COLOR GRADE of this EXACT photograph for a premium restaurant gallery. Preserve the precise architecture and geometry, tables and chairs and their count/positions, pergola metal beams, roof, neighboring building shape, pavement, plants, signage and all letters OLD MIKE, windows, outdoor equipment. Keep original camera perspective and recognizable real venue. Change ONLY color/light/exposure: gently warmer wood highlights, restrained olive greens, soft charcoal shadows, richer but natural contrast, subtle warm existing string lights, slightly subdued sky, natural premium editorial finish. This remains an honest daylight/late-afternoon view, NOT a transformed night scene. No new furniture, no people added, no fake crowds, no architectural redesign, no extra signs, no invented text, no painting effect, no extreme orange/teal. Keep image portrait, photorealism, realistic materials.

Rezultat inspectat alături de original: reperele majore ale pergolei, clădirii, firmei, pavimentului și meselor rămân recognoscibile. Lumina este mai caldă, cerul este modificat. Editarea generativă nu este o simplă corecție pixel-cu-pixel și nu se declară identitate geometrică perfectă; validarea proprietarului rămâne necesară înainte de publicare.

## Bar: `assets/images/bar-cinematic-v1.webp`

Țintă editată: `IMG_6689.jpeg`. Prompt efectiv:

> Use case: lighting-weather. Edit target: actual Old Mike Pub interior/bar photograph. Apply a sophisticated premium cinematic editorial COLOR GRADE ONLY. Preserve exact physical layout, bar shape and black panels, wooden counter, ceiling structure, equipment, bottle placement, refrigerator, ALL readable signage including OLD MIKE and the worker unchanged, no face changes. Preserve perspective and recognizability. Warm existing practical pendant light, amber wood highlights, deeper clean charcoal shadows, slightly muted greens, balanced natural contrast, gently control bright windows, preserve realistic daylight. No new people or objects, no architectural changes, no added decorations, no fictitious expensive bar, no orange cast, no excessive bloom. It must look like a professionally graded photo of the same actual location, not a new scene. Portrait photo.

Rezultat inspectat alături de original: barul curbat, panourile, grinzile, firma, frigiderul și reperele principale sunt păstrate vizual; culoare și lumină mai calde. Nu s-a făcut verificare de identitate facială ori comparație geometrică exhaustivă. Nu se afirmă conservare perfectă a tuturor detaliilor.

## Resurse existente reutilizate

- `pizza-concept.webp` și `bere-concept.webp`: copii WebP ale `img/pizza.jpg` și `img/bere.jpg`, fără editare creativă nouă. Sunt concepte AI istorice, nu fotografii reale validate. Folosite numai în pagina Meniu.
- Logo: copie exactă din sursa `Meniu-Electronic/assets/oldmike-logo.png`, blob Git `5ce2fc5ac8c93fd2aff434c0e8eb617c5a3ed1dc`, verificat prin hash. Nicio modificare a repo-ului sursă.
- Nu s-au utilizat alte imagini culinare pe homepage. Fără foto brute ale localului în noua Galerie.
