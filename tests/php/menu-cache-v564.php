<?php
declare(strict_types=1);
require __DIR__.'/../../server/lib/menu/data.php';
use function OldMikeMenu\{menu,read_cache};
$dir=sys_get_temp_dir().'/om-v564-cache-'.bin2hex(random_bytes(6));mkdir($dir,0700);
$row=['group_code'=>'Mancare','category_name'=>'Test','name_ro'=>'Test','price'=>10,'serving_weight_g'=>250,'kcal_100'=>200];$calls=0;$now=time();
$fetch=static function()use(&$calls,$row){$calls++;return [$row];};
function check_cache(bool $ok,string $label):void{if(!$ok)throw new RuntimeException($label);}
try{
 $a=menu($dir,$fetch,$now);check_cache($a['source']==='live'&&$calls===1,'Cold refresh');
 $b=menu($dir,$fetch,$now+599);check_cache($b['source']==='cache'&&$calls===1,'600-second TTL');
 $c=menu($dir,static fn()=>null,$now+601);check_cache($c['stale']&&$c['rows'][0]['kcal_100']===200,'Last good nutrition survives outage');
 check_cache(read_cache($dir.'/menu.json')['rows'][0]['price']===10,'Outage does not overwrite cache');
 $d=menu($dir,static fn()=>[['bad'=>true]],$now+662);check_cache($d['stale']&&count($d['rows'])===1,'Invalid response retains last copy');
 $e=menu($dir,static fn()=>[array_replace($row,['kcal_100'=>220])],$now+723);check_cache($e['source']==='live'&&$e['rows'][0]['kcal_100']===220,'Successful refresh updates nutrition');
 echo "PASS Garden cache: cold/warm, 10-minute TTL, outage, invalid response and nutrition refresh\n";
}finally{foreach(new DirectoryIterator($dir)as $f)if(!$f->isDot())unlink($f->getPathname());rmdir($dir);}
