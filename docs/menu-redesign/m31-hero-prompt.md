# M3.1 — Hero de meniu: masa Old Mike

## Rezultat și verificare

Generat cu instrumentul integrat `image_gen`, folosind trei imagini de categorie deja aprobate. Nu s-a folosit API/CLI separat. Inspectat vizual: burger cu o singură chiftea, pizza în stilul imaginii aprobate, platou mixt cu cartofi și sosuri, două halbe generice, cinci bucătari în miniatură. Scenă pe masă, fără straturi plutitoare și fără text.

Imagine ilustrativă compozită; generarea reconstruiește preparatele după referințe și nu reprezintă o fotografie documentară a unei mese servite ori o promisiune de gramaje. Dishes and presentation remain recognizable from approved visual references.

Dimensiune nativă: **1672 × 941 px**. Raport nativ aproximativ 16:9. Exporturile păstrează cadrul întreg; adaptarea strictă 16:9 este o redimensionare infimă, fără crop și fără generare suplimentară.
- 800 × 450: micșorat.
- 1200 × 675: micșorat.
- 2000 × 1125: **mărit din nativ**, nu generat nativ la 2K.

Alt: „Masă Old Mike cu burger, pizza, platou mixt și bere, pregătite în joacă de cinci bucătari în miniatură.”

Original arhivat local: `work/menu-m31-generated/hero-meniu-original.png`.
Fișiere web: `assets/images/meniu/hero-meniu-{800,1200,2000}.{webp,avif}`.
Poziționare recomandată: imagine întreagă deasupra textului pe telefon; nu decupa bucătarii.

## Referințe

- `assets/images/burger-single-chefs-v3.webp`
- `assets/images/pizza-oldmike-chefs-v3.webp`
- `assets/images/platouri-chefs-v1.webp`

## Prompt exact

```text
Use case: compositing / ads-marketing.
Asset: Old Mike Pub menu editorial hero, a NEW scene distinct from a homepage exploded or floating burger.
Use the three approved category photographs as visual food/presentation references: image1 single-patty burger, image2 Old Mike-style mixed-topping pizza on wooden board, image3 abundant mixed grill platter with fries and small sauce bowls.
Create ONE coherent premium hyperrealistic restaurant advertising photograph of an abundant pub table with these THREE dishes together. A grounded complete single-patty burger on dark board at front-left; the recognizable pizza on round wooden board at rear-center/right; the actual-style mixed grill platter with fries, grilled sausages/meat and sauce bowls at front-right. Two generic transparent beer mugs of golden beer with foam in rear, no labels. All food rests on table, no exploded layers or floating food. Retain recognizable ingredients and serving boards from references, no extra dishes or invented products. Burger has exactly ONE visible beef patty, no second patty.
Five clearly visible miniature adult chefs in white uniforms and tall white toques work humorously across the dishes: one on a short ladder adjusts burger pickles, one holds a pizza wheel, one lifts a pizza slice, one uses tongs at grill platter, one carries a single fry like a plank. Chefs should be large enough to read clearly in the full image displayed 390px wide, approximately 20–24% of frame height including toque; keep their faces/hands plausible. Photoreal humans in miniature, not dolls or cartoons.
Composition: wide 16:9 landscape, requested resolution 2048x1152 or higher in exact16:9. Complete abundant table scene and all dishes within frame; chefs entirely inside central safe area. Low three-quarter tabletop view with cinematic depth but all three dishes readable. Dark warm near-black #100e0b studio surroundings, textured dark brown table, warm lateral studio light, realistic golden highlights, appetizing textures and subtle steam. Little empty space; hero is an image displayed WHOLE above the copy on mobile, so distribute focal dishes across the width and do not reserve a text-overlay area. No text, no logos, no price tags, no watermark, no readable brand labels.
```

