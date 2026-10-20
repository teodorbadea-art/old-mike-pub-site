<?php
/** The ONLY category -> illustration/editorial mapping. New categories need no edits. */
return [
 'hero'=>['image'=>'meniu/hero-meniu','alt'=>'Masă Old Mike cu burger, pizza, platou cu grătare și cartofi prăjiți, halbe și cinci bucătari în miniatură la lucru.'],
 'spirit_tabs'=>[
 ['id'=>'whisky','label'=>'Whisky','categories'=>['Whisky']],
 ['id'=>'rom','label'=>'Rom','categories'=>['Rom']],
 ['id'=>'vodka','label'=>'Vodka','categories'=>['Vodka']],
 ['id'=>'gin','label'=>'Gin','categories'=>['Lichior/vermuth/gin'],'match_name'=>'~\\bgin\\b|beefeater|beffeater~iu'],
 ['id'=>'tequila','label'=>'Tequila','categories'=>['Tequilla']],
 ['id'=>'coniac','label'=>'Coniac','categories'=>['Coniac']],
 ['id'=>'shoturi','label'=>'Shot-uri','categories'=>['Shots 25 Ml']],
 ['id'=>'lichior','label'=>'Lichior și vermut','categories'=>['Lichior/vermuth/gin'],'exclude_name'=>'~\\bgin\\b|beefeater|beffeater~iu'],
 ],
 'lunch'=>['title'=>'Marea Prânzeală','price'=>33.99,'parts'=>'Ciorbă + fel de bază + garnitură','hours'=>'Luni–vineri · 12:00–16:00'],
 'labels'=>[
 'Preparate Noi la Old Mike'=>'Noutăți la Old Mike','Starters'=>'Aperitive','Ciorbe si Supe'=>'Ciorbe și supe','Peste si Fructe de Mare'=>'Pește și fructe de mare','Mancare Gatita Old Mike'=>'Mâncare gătită','Preparate Pasare'=>'Preparate din pasăre','Preparate cu Vita si Oaie'=>'Vită și oaie','Preparate cu Porc'=>'Preparate din porc','Preparate Vegetariene'=>'Vegetarian','Garnituri si Salate Acompaniament'=>'Garnituri și salate de însoțire','Paine si Sosuri'=>'Pâine și sosuri','Desert'=>'Deserturi','Promotie Bere'=>'Promoție bere','Pachete Bauturi'=>'Pachete de băuturi','Cafea si Bauturi Calde'=>'Cafea și băuturi calde','Vinuri Albe'=>'Vinuri albe','Vinuri Roze'=>'Vinuri roze','Vinuri Rosii'=>'Vinuri roșii','Vinuri Spumante / Sampanie'=>'Vinuri spumante și șampanie','Tequilla'=>'Tequila','Soft Drinks'=>'Băuturi răcoritoare','Lichior/vermuth/gin'=>'Lichior, vermut și gin','Cocktail cu Alcool'=>'Cocktailuri cu alcool','Cocktail Fara Alcool'=>'Cocktailuri fără alcool','Shots 25 Ml'=>'Shot-uri','Long Drinks'=>'Long drinks'],
 'sections'=>[
 ['id'=>'burgeri','group'=>'Mancare','categories'=>['Burgeri'],'title'=>'Burgeri.','italic'=>'Serios de buni.','kicker'=>'De poftă','image'=>'meniu/cat-burgeri','alt'=>'Burger cu o singură bucată de carne și bucătari miniaturali la lucru.'],
 ['id'=>'pizza','group'=>'Mancare','categories'=>['Pizza'],'title'=>'Pizza.','italic'=>'Se împarte. Sau nu.','kicker'=>'De împărțit','image'=>'meniu/cat-pizza','alt'=>'Pizza Old Mike cu bucătari miniaturali, tăietor uriaș și o felie ridicată.'],
 ['id'=>'platouri','group'=>'Mancare','categories'=>['Platouri'],'title'=>'Platouri.','italic'=>'Mai cheamă un prieten.','kicker'=>'Pentru toată gașca','image'=>'meniu/cat-platouri','alt'=>'Platou cu grătare și cartofi prăjiți, pregătit de bucătari miniaturali.'],
 ['id'=>'noutati','group'=>'Mancare','categories'=>['Preparate Noi la Old Mike'],'title'=>'Noutăți.','italic'=>'Intră tare în meniu.','kicker'=>'Micul șantier de gusturi','image'=>'meniu/cat-tomahawk','alt'=>'Tomahawk de porc și cartofi din fotografia Old Mike, cu bucătari miniaturali și scripete.'],
 ['id'=>'aperitive','group'=>'Mancare','categories'=>['Starters'],'title'=>'Aperitive.','italic'=>'Începem cu dreptul.','kicker'=>'Înainte de toate','image'=>'meniu/cat-starters','alt'=>'Bruschete cu roșii și busuioc pe farfuria de referință Old Mike, cu bucătari miniaturali.'],
 ['id'=>'ciorbe','group'=>'Mancare','categories'=>['Ciorbe si Supe'],'title'=>'Ciorbe și supe.','italic'=>'Polonicul are planuri.','kicker'=>'Cald, cum trebuie','image'=>'meniu/cat-ciorbe','alt'=>'Ciorbă în vasul portocaliu Old Mike și un bucătar miniatural cu polonic mare.'],
 ['id'=>'salate','group'=>'Mancare','categories'=>['Salate'],'title'=>'Salate.','italic'=>'Frunzele fac spectacol.','kicker'=>'Verde la poftă','image'=>'meniu/cat-salate','alt'=>'Salata Old Mike în platoul original negru, cu bucătari miniaturali care aranjează frunzele.'],
 ['id'=>'paste','group'=>'Mancare','categories'=>['Paste'],'title'=>'Paste.','italic'=>'Ne țin la aceeași masă.','kicker'=>'Cu fir de poveste','image'=>'meniu/cat-paste','alt'=>'Paste lungi cu sos și parmezan în farfuria galbenă din fotografia de referință, cu bucătari miniaturali.'],
 ['id'=>'peste','group'=>'Mancare','categories'=>['Peste si Fructe de Mare'],'title'=>'Pește și fructe de mare.','italic'=>'Captură de poftă.','kicker'=>'Mare atenție la gust','image'=>'meniu/cat-peste','alt'=>'Dorada cu legume și midii pe farfuria pătrată din fotografia de referință, măsurată de un bucătar miniatural.'],
 ['id'=>'gatite','group'=>'Mancare','categories'=>['Mancare Gatita Old Mike'],'title'=>'Mâncare gătită.','italic'=>'Mai stăm o porție.','kicker'=>'Fără grabă','image'=>'meniu/cat-gatite','alt'=>'Ciolan cu fasole în farfuria pătrată albă Old Mike, cu bucătar miniatural care îl escaladează.'],
 ['id'=>'pasare','group'=>'Mancare','categories'=>['Preparate Pasare'],'title'=>'Pasăre.','italic'=>'Rumeneală cu echipă.','kicker'=>'Din ceaun, cu drag','image'=>'meniu/cat-pasare','alt'=>'Coquelet cu mămăligă și mujdei în vasul metalic original, cu bucătari miniaturali.'],
 ['id'=>'gratar','group'=>'Mancare','categories'=>['Preparate cu Vita si Oaie'],'title'=>'Vită și oaie.','italic'=>'Foc mic. Poftă mare.','kicker'=>'Rumenit cum trebuie','image'=>'meniu/cat-gratar','alt'=>'Grătar de vită și cartofi prăjiți cu bucătar miniatural și solniță uriașă.'],
 ['id'=>'porc','group'=>'Mancare','categories'=>['Preparate cu Porc'],'title'=>'Porc.','italic'=>'Glazură cu aplauze.','kicker'=>'De lins pe degete','image'=>'meniu/cat-porc','standalone'=>true,'pending_image'=>'meniu/cat-porc','alt'=>'Coaste BBQ Old Mike cu cartofi prăjiți, glazurate de un bucătar miniatural.'],
 ['id'=>'deserturi','group'=>'Mancare','categories'=>['Desert'],'title'=>'Deserturi.','italic'=>'Mai este loc. Știm noi.','kicker'=>'Final cu zâmbet','image'=>'meniu/cat-deserturi','alt'=>'Papanași cu smântână și dulceață, în scena existentă cu bucătari miniaturali.'],
 ['id'=>'bere','group'=>'Bauturi','categories'=>['Bere','Promotie Bere'],'title'=>'Bere.','italic'=>'Rece. Restul mai vedem.','kicker'=>'La o vorbă','image'=>'meniu/cat-bere','alt'=>'Două halbe fără etichete, un bucătar miniatural la dozator și altul care transportă spuma pe o lopățică.'],
 ['id'=>'vinuri','group'=>'Bauturi','categories'=>['Vinuri Albe','Vinuri Roze','Vinuri Rosii','Vinuri Spumante / Sampanie'],'title'=>'Vinuri.','italic'=>'Poveștile curg.','kicker'=>'Cu un strop de răgaz','image'=>'meniu/cat-vinuri','alt'=>'Bucătar-somelier miniatural turnând vin dintr-o sticlă mare fără etichetă lizibilă.'],
 ['id'=>'cocktailuri','group'=>'Bauturi','categories'=>['Cocktail cu Alcool','Cocktail Fara Alcool','Long Drinks'],'title'=>'Cocktailuri.','italic'=>'Lime-ul pune umărul.','kicker'=>'Cu sau fără alcool','image'=>'meniu/cat-cocktailuri','alt'=>'Bucătari miniaturali decorează cocktailuri cu o felie uriașă de lime.'],
 ['id'=>'spirtoase','group'=>'Bauturi','categories'=>['Whisky','Rom','Vodka','Tequilla','Coniac','Lichior/vermuth/gin','Shots 25 Ml'],'title'=>'Spirtoase.','italic'=>'Gheața face echipă.','kicker'=>'Pentru gusturi hotărâte','image'=>'meniu/cat-spirtoase','alt'=>'Bucătar miniatural transportând un cub mare de gheață lângă pahare și sticle fără etichete lizibile.'],
 ['id'=>'cafea','group'=>'Bauturi','categories'=>['Cafea si Bauturi Calde'],'title'=>'Cafea.','italic'=>'Lucruri mari, în ceașcă.','kicker'=>'Pauza care ne adună','image'=>'meniu/cat-cafea','alt'=>'Bucătar miniatural desenând latte art într-o ceașcă uriașă.'],
 ['id'=>'racoritoare','group'=>'Bauturi','categories'=>['Soft Drinks'],'title'=>'Răcoritoare.','italic'=>'Stoarcem ziua de tot.','kicker'=>'Un strop de prospețime','image'=>'meniu/cat-racoritoare','alt'=>'Bucătari miniaturali storc o lămâie uriașă pentru limonadă.'],
 ]
];
