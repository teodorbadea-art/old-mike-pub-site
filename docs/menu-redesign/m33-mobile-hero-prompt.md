# M3.3 — Hero mobil vertical

Instrument: `image_gen` integrat, referință `work/menu-m31-generated/hero-meniu-original.png`. Două iterații; prima a fost respinsă pentru felia de pizza fără suport și apropierea excesivă de margine. Cea selectată arată pizza întreagă și trei bucătari mari, cu corpul întreg vizibil, burger cu o singură chiftea, parte din platou/cartofi și o halbă generică.

Dimensiuni returnate de instrument: **1122 × 1402 px**, foarte aproape de 4:5. Derivata principală **1120 × 1400 px** folosește o redimensionare infimă pentru raport exact 4:5, fără crop, fără compoziție prin cod și fără upscaling. Variante responsive: **800 × 1000** și **480 × 600**. Format WebP și AVIF.

Fișiere: `assets/images/meniu/hero-meniu-mobile.{webp,avif}`, `hero-meniu-mobile-800.{webp,avif}`, `hero-meniu-mobile-480.{webp,avif}`. Original neschimbat: `work/menu-m33-generated/hero-mobile-original.png`.

Alt: „Trei bucătari în miniatură pregătesc un burger, pizza și un platou cu cartofi, lângă o halbă de bere.”

Control vizual: bucătarii ocupă aproximativ 25–40% din înălțime; la 390 px lățime sunt vizibili aproximativ 120–190 px. Se afișează cadrul întreg deasupra textului pe telefon. Ilustrație publicitară, nu fotografie documentară a porțiilor servite. Nu s-a generat categorie de porc sau produs nou.

## Prompt exact selectat

```text
Use case: compositing; asset: premium Old Mike Pub MENU mobile hero.
Input: approved wide Old Mike pub-table advertisement is reference for actual-style food, recognizable serving/presentation, lighting and photographic identity. Recompose into a NEW PORTRAIT 4:5 photograph, 1200x1500 (or other exact4:5 with width<=1536), designed to be viewed whole 390px wide above page text. This is NOT a crop of the landscape image.
A dense abundant pub table, warm dark near-black #100e0b studio background and warm lateral light. Retain recognizable single-patty burger on dark board, the Old Mike-style mixed topping pizza on its wooden board, a modest visible part of existing mixed grill/fries on wooden platter, one generic beer mug without branding. Keep ingredients/presentation recognizable from reference; do not invent other products. Exactly ONE visible burger patty. Food grounded on table, no floating exploded layers.
CRITICAL visual priority: exactly THREE realistic miniature chefs in clean white uniforms and white tall toques, each FULL BODY visible and LARGE—each occupies 25–30% of portrait image height (at least 375px of1500px high image). At phone display each chef must be roughly120px tall, emphatically NOT distant25px figurines. Chef1 foreground-left works at burger with tongs; chef2 mid-right holds a pizza wheel beside pizza; chef3 foreground-right carries one fry as a large plank. Arrange the burger front-left, pizza mid/rear with a recognizable lifted slice or whole pizza, modest grill/fries lower-right, mug upper-left. Chefs and their equipment entirely inside frame with comfortable margins. Food itself also visibly large, filling remaining space. Do not overload tiny props. Coherent scale and photographic perspective, human faces/hands plausible, no cartoons/toys.
Portrait photograph with appetizing ultra-real texture, shallow natural depth but all3chefs readable, golden light and gentle steam; preserve same visual family as approved image. No text or overlay space, no logos, no watermark, no readable labels. Exact4:5 fullframe composition.
Final correction priorities: keep ALL THREE chefs fully inside image with at least6% side margin; no chef body, arm, foot or toque touching edge. Pizza stays whole and flat on board—NO raised slice, NO floating pizza. Chef2 holds pizza wheel on flat pizza. Use coherent human miniature scale. Exact portrait4:5 ratio. Preserve large chefs and single burger patty.
```

