# M3.1 — Bere cu bucătari în miniatură

Generare nouă cu instrumentul built-in `image_gen`; fără CLI/API sau fallback plătit. Imaginea de bere existentă nu a fost modificată.

Alt: „Un bucătar în miniatură trage bere la dozator, iar altul transportă spuma cu o lopățică lângă o halbă rece.”

## Master și rezoluție

- Sursă furnizată de instrument: `generated_images/exec-6942c384-06b1-4189-b670-892552a7d9d7.png`.
- Copie de lucru în proiect: `work/menu-m31-generated/cat-bere-source.png`.
- Rezoluție nativă efectivă: **1536×1024**, raport 3:2. Promptul a cerut minimum 2000 px dacă este posibil, însă instrumentul a livrat 1536 px.
- Exportul de 2000×1333 este **mărit din masterul nativ**, cu Lanczos; nu reprezintă detaliu nativ 2K. Exporturile păstrează întreaga compoziție, fără crop.
- Derivate: redimensionare/conversie Pillow, WebP quality 84; AVIF quality 64.
- Inspecție vizuală: halbe generice fără marcă, ambii bucătari și dozatorul întregi, spumă pe lopățică, fundal cald întunecat și lumină laterală. Fără text sau logo. Scenă ilustrativă, nu fotografie a prezentării exacte din restaurant.

| Fișier | Dimensiuni | Octeți |
|---|---|---|
| `assets/images/meniu/cat-bere-800.webp` | 800×533 | 88,612 |
| `assets/images/meniu/cat-bere-800.avif` | 800×533 | 57,155 |
| `assets/images/meniu/cat-bere-1200.webp` | 1200×800 | 171,038 |
| `assets/images/meniu/cat-bere-1200.avif` | 1200×800 | 109,947 |
| `assets/images/meniu/cat-bere-2000.webp` | 2000×1333 | 341,382 |
| `assets/images/meniu/cat-bere-2000.avif` | 2000×1333 | 211,837 |

## Prompt exact

Use case: ads-marketing. Asset type: editorial beverage category photograph for Old Mike Pub menu. Create one premium hyperrealistic advertising photograph in landscape 3:2, preferably native 2304x1536 or wider than 2000 pixels. A warmly lit miniature-chef brewery scene on a dark rustic pub counter against a warm nearly black #100e0b background. A beautiful generic unbranded clear glass beer mug with luminous golden beer, fine bubbles, realistic condensation and a creamy white foam head is the central hero. Next to it is a classic polished brass beer tap with a blank handle: one miniature adult chef in a white uniform and tall white toque stands on a short safe wooden platform, visibly pulling the tap lever to pour a narrow stream of golden beer into a second smaller clear unbranded mug underneath the spout. Another miniature chef in white uniform and tall white toque is carrying a small fluffy mound of beer foam on a tiny shovel beside the main mug, like an amusing careful craftsman. Keep the foam unmistakably beer foam, not snow or whipped cream. The chefs should be large enough to read on mobile, about a quarter of the height of the hero mug. Include the entire beer tap, both full mugs, both complete chefs and tools within the central 80 percent safe frame, no cut-off heads or vessels. Quiet warm dark upper background, lateral golden studio lighting, rich amber reflections, tactile wood, cinematic food advertising photography, believable miniature physical materials. No text, no prices, no logos, no watermarks, no labels, no readable branding anywhere. Not a cartoon, not a 3D illustration. No food, no extra people, no scattered props. Composition will be preserved entirely in responsive exports, not cropped.
