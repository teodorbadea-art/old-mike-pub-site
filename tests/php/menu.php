<?php
declare(strict_types=1);
require __DIR__.'/../../server/lib/menu/data.php';require __DIR__.'/../../server/lib/menu/render.php';
use function OldMikeMenu\{menu,valid_rows,name,portion,price,description,render,read_cache};
$count=0;function check(bool $ok,string $label):void{global $count;if(!$ok)throw new RuntimeException('FAIL '.$label);$count++;}
$root=dirname(__DIR__,2);$base=sys_get_temp_dir().'/om-menu-'.bin2hex(random_bytes(6));mkdir($base,0700);$now=time();
$rows=[['group_code'=>'Mancare','category_name'=>'Categorie nouă','name_ro'=>'PENNE Carbonara','price'=>33.99,'portion_display'=>'300 GR','description_ro'=>'sos și parmezan','featured'=>true]];
try{
 check(valid_rows($rows),'valid rows');check(!valid_rows([['price'=>12]]),'malformed row');check(!valid_rows('bad'),'bad JSON');check(!valid_rows([array_replace($rows[0],['price'=>null])]),'missing price');check(!valid_rows([array_replace($rows[0],['price'=>-1])]),'negative price');
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
 $live=json_decode(file_get_contents($root.'/data/menu-redesign/menu-preview-snapshot.json'),true);check(valid_rows($live['rows']),'real snapshot valid');$page=render($live,$root,true);check(substr_count($page,'data-menu-item')===257,'257 products rendered exactly once');
 preg_match('~<script type="application/ld\+json">(.*?)</script>~s',$page,$match);$ld=json_decode($match[1],true,512,JSON_THROW_ON_ERROR);$schemaCount=0;foreach($ld['hasMenu']['hasMenuSection'] as $section)$schemaCount+=count($section['hasMenuItem']);check($schemaCount===257,'257 schema items');check(strpos($page,'href="#mancare"')<strpos($page,'href="#bauturi"'),'food before drinks');
 echo 'PASS '.$count.' menu checks: cache/outage/formatting/new category/escaping/257 complete real items'.PHP_EOL;
}finally{
 foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base,FilesystemIterator::SKIP_DOTS),RecursiveIteratorIterator::CHILD_FIRST)as $p){$p->isDir()?rmdir($p->getPathname()):unlink($p->getPathname());}rmdir($base);
}
