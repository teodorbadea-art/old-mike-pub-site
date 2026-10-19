# Imagini editoriale de mâncare — Old Mike

Instrument: `image_gen` integrat, editare/compoziție pornind de la fotografiile reale. Data: 20 octombrie 2026. Fără API plătit separat.

## Limite și verificare

Toate cele opt rezultate au fost inspectate vizual. Sunt ilustrații de categorie, marcate în pagină „Imagine ilustrativă”. Generarea păstrează vizual tipul preparatului și vesela, dar reconstruiește fotografia: nu poate promite identitate pixel cu pixel, gramaje exacte sau inventar de ingrediente. Originalele rămân neatinse în `work/menu-reference-photos/`; PNG-urile generate rămân în folderul local `generated_images/`. WebP-urile au master 1536 × 1024 și variante de 800/480 px, fără crop.

Discrepanțe între metadatele catalogului și pozele sursă: `OM-0040 / PENNE Carbonara Original` arată paste lungi tip panglică; imaginea generată păstrează fotografia, nu inventează penne. `OM-0066 / Dorada la Gratar cu Orez Salbatic` arată pește, legume și midii, fără orez vizibil; nu s-a adăugat orez. Pentru produsul exact este necesară confirmarea restaurantului. Imaginea de categorie nu pretinde că arată porția exactă a fiecărui produs.

Coaste BBQ: fotografie reală indisponibilă; nu s-a generat un substitut.

Prima variantă pentru ciorbă a interpretat șervețelul ca pâine; a fost respinsă și regenerată cu interdicție explicită. Rezultatul ales folosește șervețel alb.

## tomahawk

Sursă catalog: **OM-0004 — Tomahwk de Porc si Cartofi cu Parmezan**.
Fotografie: `/workspace/scratch/52be6ffe8fd8/old-mike-pub-site/work/menu-reference-photos/tomahawk.jpg`.
Fișier: `assets/images/menu-editorial/cat-tomahawk.webp` (+ `-800.webp`, `-480.webp`).
Alt: Doi bucătari în miniatură ridică un tomahawk de porc cu un scripete, lângă cartofi și sos.

Prompt exact:

```text
Use case: compositing. Asset type: premium restaurant category editorial photograph. Edit the supplied real dish photograph. Preserve the exact food, ingredients, portion, plating and serving vessel visible in reference; do not substitute or add foods. Replace only surroundings with a warm dark charcoal studio/table background, warm lateral studio light, subtle natural steam. Add exactly two hyperrealistic tiny chefs wearing white uniforms and tall white toques, sufficiently large and sharply detailed to read on a phone (roughly 15% image height). Photograph quality, not toy/cartoon, witty miniature world. Landscape 3:2, at least 1536x1024. Complete serving vessel and both chefs safely within central 85% frame, no cropping. No text, price, logos, watermark or readable brands. Preserve the grilled PORK tomahawk with long bone on the white rectangular platter, fries, garnish, and black handled sauce pot exactly. One tiny chef operates a small pulley whose rope supports the end of the pork bone very slightly, while second steadies rope beside platter. Keep steak on platter. Not beef.
```

## ciorbe

Sursă catalog: **OM-0034 — Ciorba de Vacuta**.
Fotografie: `/workspace/scratch/52be6ffe8fd8/old-mike-pub-site/work/menu-reference-photos/ciorbe.jpg`.
Fișier: `assets/images/menu-editorial/cat-ciorbe.webp` (+ `-800.webp`, `-480.webp`).
Alt: Bucătari în miniatură gustă ciorba din vasul portocaliu, alături de un ardei iute.

Prompt exact:

```text
Use case: compositing. Asset type: premium restaurant category editorial photograph. Edit the supplied real dish photograph. Preserve the exact food, ingredients, portion, plating and serving vessel visible in reference; do not substitute or add foods. Replace only surroundings with a warm dark charcoal studio/table background, warm lateral studio light, subtle natural steam. Add exactly two hyperrealistic tiny chefs wearing white uniforms and tall white toques, sufficiently large and sharply detailed to read on a phone (roughly 15% image height). Photograph quality, not toy/cartoon, witty miniature world. Landscape 3:2, at least 1536x1024. Complete serving vessel and both chefs safely within central 85% frame, no cropping. No text, price, logos, watermark or readable brands. Preserve orange handled soup pot of beef soup with chopped green herbs, white patterned round underplate, napkin and long red chili exactly. One tiny chef uses a ladle as large as himself to taste soup from edge; second assists beside pot. Preserve soup and its crockery. CRITICAL: the white folded material to the right of soup bowl is a WHITE PAPER NAPKIN, NOT BREAD. Keep it as clearly folded thin white paper napkin, with the red chili lying on paper. Absolutely NO bread, pita, flatbread or pastry. Complete circular plate fully inside frame. All other invariants unchanged.
```

## salate

Sursă catalog: **OM-0038 — Salata Old Mike**.
Fotografie: `/workspace/scratch/52be6ffe8fd8/old-mike-pub-site/work/menu-reference-photos/salate.jpg`.
Fișier: `assets/images/menu-editorial/cat-salate.webp` (+ `-800.webp`, `-480.webp`).
Alt: Bucătari în miniatură aranjează salata cu fâșii crocante și brânză rasă, pe farfuria neagră.

Prompt exact:

```text
Use case: compositing. Asset type: premium restaurant category editorial photograph. Edit the supplied real dish photograph. Preserve the exact food, ingredients, portion, plating and serving vessel visible in reference; do not substitute or add foods. Replace only surroundings with a warm dark charcoal studio/table background, warm lateral studio light, subtle natural steam. Add exactly two hyperrealistic tiny chefs wearing white uniforms and tall white toques, sufficiently large and sharply detailed to read on a phone (roughly 15% image height). Photograph quality, not toy/cartoon, witty miniature world. Landscape 3:2, at least 1536x1024. Complete serving vessel and both chefs safely within central 85% frame, no cropping. No text, price, logos, watermark or readable brands. Preserve the actual Old Mike salad photo: black hammered wide round platter; central mound of lettuce topped with breaded golden strips, abundant finely grated pale cheese, egg half and lemon half. Remove only background and the turquoise text overlay; keep photographed food and plate. One tiny chef arranges an existing lettuce leaf like festive confetti; second gathers existing grated cheese beside the mound. Do not add ingredients.
```

## paste

Sursă catalog: **OM-0040 — PENNE Carbonara Original**.
Fotografie: `/workspace/scratch/52be6ffe8fd8/old-mike-pub-site/work/menu-reference-photos/paste.jpg`.
Fișier: `assets/images/menu-editorial/cat-paste.webp` (+ `-800.webp`, `-480.webp`).
Alt: Doi bucătari în miniatură trag o panglică de paste, pe farfuria galbenă cu paste și brânză rasă.

Prompt exact:

```text
Use case: compositing. Asset type: premium restaurant category editorial photograph. Edit the supplied real dish photograph. Preserve the exact food, ingredients, portion, plating and serving vessel visible in reference; do not substitute or add foods. Replace only surroundings with a warm dark charcoal studio/table background, warm lateral studio light, subtle natural steam. Add exactly two hyperrealistic tiny chefs wearing white uniforms and tall white toques, sufficiently large and sharply detailed to read on a phone (roughly 15% image height). Photograph quality, not toy/cartoon, witty miniature world. Landscape 3:2, at least 1536x1024. Complete serving vessel and both chefs safely within central 85% frame, no cropping. No text, price, logos, watermark or readable brands. Preserve EXACTLY the photographed long yellow ribbon pasta with creamy sauce, pieces of meat and wide grated cheese shavings in the large yellow ridged wide-rim bowl. Despite source menu label, do NOT replace actual photographed ribbon pasta with penne. One tiny chef gently hauls a single existing long ribbon pasta strand like a rope; second assists beside the bowl. No added spaghetti; preserve photographed pasta shape and plating.
```

## peste

Sursă catalog: **OM-0066 — Dorada la Gratar cu Orez Salbatic**.
Fotografie: `/workspace/scratch/52be6ffe8fd8/old-mike-pub-site/work/menu-reference-photos/peste.jpg`.
Fișier: `assets/images/menu-editorial/cat-peste.webp` (+ `-800.webp`, `-480.webp`).
Alt: Bucătari în miniatură măsoară o doradă la grătar, alături de legume și midii, pe platou dreptunghiular.

Prompt exact:

```text
Use case: compositing. Asset type: premium restaurant category editorial photograph. Edit the supplied real dish photograph. Preserve the exact food, ingredients, portion, plating and serving vessel visible in reference; do not substitute or add foods. Replace only surroundings with a warm dark charcoal studio/table background, warm lateral studio light, subtle natural steam. Add exactly two hyperrealistic tiny chefs wearing white uniforms and tall white toques, sufficiently large and sharply detailed to read on a phone (roughly 15% image height). Photograph quality, not toy/cartoon, witty miniature world. Landscape 3:2, at least 1536x1024. Complete serving vessel and both chefs safely within central 85% frame, no cropping. No text, price, logos, watermark or readable brands. Preserve exact photographed whole grilled dorada with head and tail, grilled vegetables and mussels, on the dark rectangular serving platter. Do NOT add wild rice: none is visible in actual reference. Remove background cutlery only if outside platter. One tiny chef unrolls a miniature measuring tape along fish like a record catch; second holds other end beside head. Fish, food and rectangular plate remain exact reference.
```

## gatite

Sursă catalog: **OM-0068 — Ciolan Afumat cu Iahnie de Fasole**.
Fotografie: `/workspace/scratch/52be6ffe8fd8/old-mike-pub-site/work/menu-reference-photos/gatite.jpg`.
Fișier: `assets/images/menu-editorial/cat-gatite.webp` (+ `-800.webp`, `-480.webp`).
Alt: Un bucătar în miniatură escaladează ciolanul pe pat de fasole, ajutat de un coleg.

Prompt exact:

```text
Use case: compositing. Asset type: premium restaurant category editorial photograph. Edit the supplied real dish photograph. Preserve the exact food, ingredients, portion, plating and serving vessel visible in reference; do not substitute or add foods. Replace only surroundings with a warm dark charcoal studio/table background, warm lateral studio light, subtle natural steam. Add exactly two hyperrealistic tiny chefs wearing white uniforms and tall white toques, sufficiently large and sharply detailed to read on a phone (roughly 15% image height). Photograph quality, not toy/cartoon, witty miniature world. Landscape 3:2, at least 1536x1024. Complete serving vessel and both chefs safely within central 85% frame, no cropping. No text, price, logos, watermark or readable brands. Preserve actual photographed smoked pork knuckle resting on orange sauced white beans, dill garnish, white square plate with its original arrangement. One tiny chef carefully climbs the knuckle as if a little mountain using a tiny rope anchored beside plate; second chef belays from plate edge. Same pork knuckle, beans, garnish, plate. Do not add food.
```

## pasare

Sursă catalog: **OM-0067 — COQUELET la Ceaun cu Mamaliga si Mujdei de Usturoi**.
Fotografie: `/workspace/scratch/52be6ffe8fd8/old-mike-pub-site/work/menu-reference-photos/pasare.jpg`.
Fișier: `assets/images/menu-editorial/cat-pasare.webp` (+ `-800.webp`, `-480.webp`).
Alt: Bucătari în miniatură ung cocoșelul rumenit, alături de mămăligă și mujdei, în vasul metalic.

Prompt exact:

```text
Use case: compositing. Asset type: premium restaurant category editorial photograph. Edit the supplied real dish photograph. Preserve the exact food, ingredients, portion, plating and serving vessel visible in reference; do not substitute or add foods. Replace only surroundings with a warm dark charcoal studio/table background, warm lateral studio light, subtle natural steam. Add exactly two hyperrealistic tiny chefs wearing white uniforms and tall white toques, sufficiently large and sharply detailed to read on a phone (roughly 15% image height). Photograph quality, not toy/cartoon, witty miniature world. Landscape 3:2, at least 1536x1024. Complete serving vessel and both chefs safely within central 85% frame, no cropping. No text, price, logos, watermark or readable brands. Preserve the exact actual photographed golden whole cooked coquelet, two wedges yellow polenta, paper lining inside silver metal round handled serving pan, silver lid tilted behind, and separate little metal mujdei garlic sauce cup. Original paper lining may retain texture but remove legible lettering. One tiny chef on a small ladder brushes the actual coquelet skin with a large cooking brush; second holds brush handle steady beside pan. Do not replace serving pan or food.
```

## starters

Sursă catalog: **OM-0026 — Bruschetta cu Rosii si Busuioc**.
Fotografie: `/workspace/scratch/52be6ffe8fd8/old-mike-pub-site/work/menu-reference-photos/starters.jpg`.
Fișier: `assets/images/menu-editorial/cat-starters.webp` (+ `-800.webp`, `-480.webp`).
Alt: Bucătari în miniatură aranjează roșii și busuioc pe bruschete, folosind o mistrie mică.

Prompt exact:

```text
Use case: compositing. Asset type: premium restaurant category editorial photograph. Edit the supplied real dish photograph. Preserve the exact food, ingredients, portion, plating and serving vessel visible in reference; do not substitute or add foods. Replace only surroundings with a warm dark charcoal studio/table background, warm lateral studio light, subtle natural steam. Add exactly two hyperrealistic tiny chefs wearing white uniforms and tall white toques, sufficiently large and sharply detailed to read on a phone (roughly 15% image height). Photograph quality, not toy/cartoon, witty miniature world. Landscape 3:2, at least 1536x1024. Complete serving vessel and both chefs safely within central 85% frame, no cropping. No text, price, logos, watermark or readable brands. Preserve actual photographed bruschetta slices topped with chopped red tomatoes and green basil on the plain white round plate. Keep bread, tomato, herbs, same serving and vessel; widen framing enough to show complete plate without inventing additional foods. One tiny chef gently spreads existing tomato topping with a tiny trowel as if masonry; second assists with existing basil leaf. No added toppings.
```
