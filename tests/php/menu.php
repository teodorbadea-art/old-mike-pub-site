<?php
declare(strict_types=1);
require __DIR__.'/../../server/lib/menu/data.php';require __DIR__.'/../../server/lib/menu/render.php';
use function OldMikeMenu\{menu,valid_rows,name,portion,price,description,render,read_cache};
$count=0;function check(bool $ok,string $label):void{global $count;if(!$ok)throw new RuntimeException('FAIL '.$label);$count++;}
$root=dirname(__DIR__,2);$base=sys_get_temp_dir().'/om-menu-'.bin2hex(random_bytes(6));mkdir($base,0700);$now=time();
$rows=[['group_code'=>'Mancare','category_name'=>'Categorie nouă','name_ro'=>'PENNE Carbonara','price'=>33.99,'portion_display'=>'300 GR','description_ro'=>'sos și parmezan','featured'=>true]];
try{
 check(valid_rows($rows)!==null,'valid rows');check(!valid_rows([['price'=>12]]),'malformed row');check(!valid_rows('bad'),'bad JSON');check(!valid_rows([array_replace($rows[0],['price'=>null])]),'missing price');check(!valid_rows([array_replace($rows[0],['price'=>-1])]),'negative price');
 check(name('PENNE Carbonara')==='Penne Carbonara','mixed uppercase name');check(name('SALATĂ GRECEASCĂ')==='Salată Grecească','Romanian uppercase');check(name('Coaste BBQ')==='Coaste BBQ','acronym preserved');check(portion('500 GR / 300 gr')==='500 g / 300 g','grams');check(portion('250 ML')==='250 ml','millilitres');check(price(54)==='54 lei','whole price');check(price(33.99)==='33,99 lei','decimal price');check(description('sos de roșii')==='Sos de roșii','sentence');
 $calls=0;$fetch=static function()use(&$calls,$rows){$calls++;return $rows;};
 $first=menu($base,$fetch,$now);check($first['source']==='live'&&$calls===1,'cold fetch');check(is_file($base.'/menu.json'),'cache created');
 $cached=menu($base,$fetch,$now+100);check($cached['source']==='cache'&&$calls===1,'warm no request');
 file_put_contents($base.'/menu.json',json_encode(['at'=>$now-700,'rows'=>$rows]));
 $stale=menu($base,static fn()=>throw new RuntimeException('offline'),$now);check($stale['rows']===$rows&&$stale['stale'],'outage last-known-good');
 $before=file_get_contents($base.'/menu.json');$bad=menu($base,static fn()=>[['bad'=>1]],$now+61);check(file_get_contents($base.'/menu.json')===$before,'invalid refresh does not overwrite');
 unlink($base.'/menu.retry');$refreshed=menu($base,static fn()=>[array_replace($rows[0],['price'=>54])],$now);check($refreshed['rows'][0]['price']===54,'published price refresh');
 $html=render(['rows'=>$rows,'at'=>$now],$root,true);check(str_contains($html,'Categorie nouă'),'new category appears');check(substr_count($html,'data-menu-item')===1,'no duplicated rows');check(str_contains($html,'Recomandat'),'featured marker');check(str_contains($html,'33,99 lei'),'real price HTML');check(str_contains($html,'hasMenuSection'),'structured menu');check(str_contains($html,'12:00–16:00'),'confirmed lunch hours');
 $malicious=[array_replace($rows[0],['name_ro'=>'<script>alert(1)</script>','description_ro'=>'</script><script>alert(2)</script>'])];$safe=render(['rows'=>$malicious,'at'=>$now],$root);check(!str_contains($safe,'<script>alert('),'HTML/LD injection escaped');
 $emptyDir=$base.'/empty';mkdir($emptyDir);$fallback=menu($emptyDir,static fn()=>null,$now);$fallbackHtml=render($fallback,$root);check(str_contains($fallbackHtml,'Deschide meniul electronic')&&!str_contains($fallbackHtml,'Exception'),'cold outage useful fallback');
 $empty=menu($base,static fn()=>[],$now+700);check($empty['rows']===[],'legitimate empty menu accepted');
 $live=json_decode(file_get_contents($root.'/data/menu-redesign/menu-preview-snapshot.json'),true);check(valid_rows($live['rows'])!==null,'real snapshot valid');$page=render($live,$root,true);check(substr_count($page,'data-menu-item')===257,'257 products rendered exactly once');
 preg_match('~<script type="application/ld\+json">(.*?)</script>~s',$page,$match);$ld=json_decode($match[1],true,512,JSON_THROW_ON_ERROR);$schemaCount=0;foreach($ld['hasMenu']['hasMenuSection'] as $section)$schemaCount+=count($section['hasMenuItem']);check($schemaCount===257,'257 schema items');check(strpos($page,'href="#mancare"')<strpos($page,'href="#bauturi"'),'food before drinks');
 $drink=OldMikeMenu\drink_details(['name_ro'=>'Chivas Regal','portion_display'=>'40 ML','description_ro'=>'40% alcool']);
 check($drink['inline']==='40 ml · 40%'&&$drink['description']==='','drink quantity+ABV inline, no duplicate description');
 $drink=OldMikeMenu\drink_details(['name_ro'=>'Wyborowa','portion_display'=>'40 ML','description_ro'=>'37,5% alc']);check($drink['inline']==='40 ml · 37,5%','decimal ABV');
 $drink=OldMikeMenu\drink_details(['name_ro'=>'Whisky','portion_display'=>'40 ML','description_ro'=>'single malt']);check($drink['inline']==='40 ml'&&$drink['abv']==='','unknown ABV never guessed');
 $drink=OldMikeMenu\drink_details(['name_ro'=>'Palinca 50% Alcool','portion_display'=>'30 ML','description_ro'=>null]);check($drink['inline']==='30 ml · 50%'&&$drink['name']==='Palinca','explicit name ABV moved to measure');
 $drink=OldMikeMenu\drink_details(['name_ro'=>'Suc','portion_display'=>'250 ML','description_ro'=>'100% fructe']);check($drink['abv']==='','fruit percentage is not alcohol');
 $config=require $root.'/server/lib/menu/config.php';$model=OldMikeMenu\model($live['rows'],$config,$root);$spirits=array_values(array_filter($model['sections']['Bauturi'],static fn($s)=>$s['id']==='spirtoase'))[0];$panels=OldMikeMenu\spirit_panels($spirits['lists'],$config);
 check(count($panels)===8,'eight spirit tabs including gin and liqueurs');
 $panelCount=array_sum(array_map(static fn($p)=>count($p['rows']),$panels));check($panelCount===array_sum(array_map('count',$spirits['lists'])),'tabs retain every spirit once');
 $gin=array_values(array_filter($panels,static fn($p)=>$p['label']==='Gin'))[0];check(count($gin['rows'])===3,'three published gins separated from mixed source category');
 check(str_contains($page,'type="image/avif"')&&str_contains($page,'-480.avif 480w')&&str_contains($page,'-800.webp 800w')&&str_contains($page,'.webp 1536w'),'native+480/800formats/widths');
 check(str_contains($page,'fetchpriority="high" loading="eager"'),'hero priority');
 check(!str_contains($page,'data:image/')&&!str_contains($page,';base64,'),'no embedded image data');
 check(str_contains($page,'assets/images/meniu/hero-meniu.webp')&&!str_contains($page,'hero-single-chefs'),'menu hero distinct from homepage');
 check(is_file($root.'/assets/images/meniu/hero-meniu-mobile.webp')&&is_file($root.'/assets/images/meniu/hero-meniu-mobile-800.webp')&&str_contains($page,'hero-meniu-mobile-800.webp 800w'),'mobile source generated from shipped800file, no mobile master needed');
 check(str_contains($page,'id="porc"')&&!str_contains($page,'pending_image'),'pork is a standalone section, no placeholder text');
 check(str_contains($page,'assets/images/meniu/hero-meniu-mobile-480.avif'),'mobile AVIF source');
 check(!str_contains($page,'-2000.')&&!str_contains($page,'-1200.'),'no2000/1200media requests');
 $css=file_get_contents($root.'/assets/site.css');check(str_starts_with($css,"@import url('oldmike-design.css');\n"),'shared typography import is first line');
 check(!preg_match('/\\d+\\s*%\\s*(?:alcool|alc\\b)/iu',$page),'ABV suffix removed from customer HTML and JSON-LD');
 check(substr_count($page,'Imagine ilustrativă')===1,'one illustration note');
 check(!str_contains($page,'<h3 class="om-subcategory-title">Bere</h3>'),'no duplicate beer subtitle');
 check(str_contains($page,'assets/images/meniu/cat-porc.webp'),'pork editorial image');
 $log=$base.'/errors.log';ini_set('error_log',$log);
 check(count(valid_rows([$rows[0],['price'=>null]],'test'))===1,'skip only malformed row');
 file_put_contents($base.'/menu.json',json_encode(['at'=>$now-700,'rows'=>$rows]));@unlink($base.'/menu.retry');
 $partial=menu($base,static fn()=>[['bad'=>true],array_replace($rows[0],['price'=>61])],$now);
 check(count($partial['rows'])===1&&$partial['rows'][0]['price']===61&&read_cache($base.'/menu.json')['rows'][0]['price']===61,'partial refresh updates and caches valid products');
 file_put_contents($base.'/menu.json',json_encode(['at'=>$now-3700,'rows'=>$rows]));menu($base,static fn()=>null,$now);
 check(str_contains(file_get_contents($log),'Skipped invalid product')&&str_contains(file_get_contents($log),'older than 60 minutes'),'invalid rows and old cache logged');
 $warnings=static fn()=>substr_count(file_get_contents($log),'Serving cache older than 60 minutes');
 $initialWarnings=$warnings();menu($base,static fn()=>null,$now+10);menu($base,static fn()=>null,$now+3599);
 check($initialWarnings===1&&$warnings()===1,'old cache warning suppressed within hour');
 menu($base,static fn()=>null,$now+3600);check($warnings()===2,'old cache warning resumes after hour');
 $held=fopen($base.'/menu.stale-warning','c+');flock($held,LOCK_EX);
 OldMikeMenu\warn_old_cache($base,$now-3700,$now+7200);check($warnings()===2,'concurrent stale log lock suppresses duplicate');flock($held,LOCK_UN);fclose($held);
 $mediaRoot=$base.'/media';mkdir($mediaRoot.'/assets/images/test',0755,true);
 foreach(['webp','avif']as $format)foreach(['','-480','-800','-mobile-480','-mobile-800']as $suffix)copy($root.'/assets/images/meniu/hero-meniu'.($suffix===''?'':(str_starts_with($suffix,'-mobile')?$suffix:$suffix)).'.'.$format,$mediaRoot.'/assets/images/test/hero'.$suffix.'.'.$format);
 set_error_handler(static function($severity,$message){throw new RuntimeException($message);});
 try{$missing=OldMikeMenu\picture('test/hero','Test',$mediaRoot,true);}finally{restore_error_handler();}
 check(str_contains($missing,'-mobile-800.webp 800w')&&!str_contains($missing,'hero-mobile.webp'),'absent optional mobile master causes no warning; variants retained');
 echo 'PASS '
.$count.' menu checks: cache/outage/formatting/new category/escaping/257 complete real items'.PHP_EOL;
}finally{
 foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base,FilesystemIterator::SKIP_DOTS),RecursiveIteratorIterator::CHILD_FIRST)as $p){$p->isDir()?rmdir($p->getPathname()):unlink($p->getPathname());}rmdir($base);
}
