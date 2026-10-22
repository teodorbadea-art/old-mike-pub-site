<?php
declare(strict_types=1);
require __DIR__.'/../../server/lib/menu/data.php';
require __DIR__.'/../../server/lib/menu/render.php';
$p=['name_ro'=>'Test','group_code'=>'Mancare','price'=>10,'kcal_100'=>200,'serving_weight_g'=>250,'fat_g_100'=>10,'carbs_g_100'=>20,'sugars_g_100'=>5,'protein_g_100'=>6,'salt_g_100'=>1];
function verify(bool $ok,string $label):void{if(!$ok)throw new RuntimeException($label);}
verify(OldMikeMenu\nutrition_data($p)['kcal']===500.0,'Portion energy');
$html=OldMikeMenu\rows_html([$p]);
verify(str_contains($html,'~500 kcal / porție'),'Compact summary');
verify(str_contains($html,'<details class="om-nutrition">')&&str_contains($html,'Pe porție')&&str_contains($html,'100 g'),'Accessible detail');
verify(str_contains($html,'Valori estimate, calculate din rețetă.'),'Estimate note');
foreach([null,'',-1,'broken',INF]as $value){$p['kcal_100']=$value;verify(OldMikeMenu\nutrition_data($p)===null,'Missing/invalid energy hidden');verify(!str_contains(OldMikeMenu\rows_html([$p]),'kcal / porție'),'No false zero');}
verify(str_contains(file_get_contents(__DIR__.'/../../assets/site.css'),'.form-honeypot{position:absolute;left:-10000px;width:1px;height:1px;overflow:hidden}'),'Honeypot preserved');
echo "PASS Garden nutrition summaries, detail, invalid values and honeypot\n";
