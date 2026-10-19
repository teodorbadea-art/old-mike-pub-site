<?php
declare(strict_types=1);
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
exit("RETIRED: oldmike.ro hosts the group landing page. Deployment to public_html root is forbidden. Choose and approve a separate restaurant target first.\n");
$root='/home/oldmikero/public_html';$private='/home/oldmikero/oldmike-site-private';
$manifest=json_decode((string)file_get_contents(__DIR__.'/MANIFEST.json'),true,512,JSON_THROW_ON_ERROR);
if(realpath($root)!==$root || is_link($root) || !is_file($root.'/index.php') || is_link($root.'/index.php'))exit("STOP invalid root/index.\n");
foreach(['assets','api','admin','lib'] as $dir)if(file_exists($root.'/'.$dir)||is_link($root.'/'.$dir))exit("STOP existing target directory: $dir\n");
foreach($manifest as $name=>$hash){
 if(str_contains($name,'..') || str_starts_with($name,'/') || !preg_match('~^(assets/|api/|admin/|lib/|(?:index|meniu|galerie|evenimente|contact|confidentialitate|404)\.html$|index\.php$|robots\.txt$|sitemap\.xml$)~D',$name))exit("STOP invalid path.\n");
 if(!is_file(__DIR__.'/public/'.$name)||is_link(__DIR__.'/public/'.$name)||!hash_equals($hash,hash_file('sha256',__DIR__.'/public/'.$name)))exit("STOP invalid package.\n");
 if($name!=='index.php' && (file_exists($root.'/'.$name)||is_link($root.'/'.$name)))exit("STOP existing file: $name\n");
}
$hash=hash_file('sha256',$root.'/index.php');
if(($argv[1]??'')==='--check'){echo "READY production candidate; files=".count($manifest)."; existing_index_sha256=$hash; existing_root_htaccess=".(is_file($root.'/.htaccess')?'yes':'no')."; no writes.\n";exit;}
if(!isset($argv[1]) || !hash_equals($hash,$argv[1]))exit("STOP original index changed or approval hash missing.\n");
$c=require $private.'/config.php';
if(!is_array($c)||!($c['enabled']??false))exit("STOP config unavailable.\n");
$c['base_path']='';$c['requests_enabled']=false;$c['mail_transport']='disabled';
$production=$private.'/production-config.php';if(file_exists($production)||is_link($production))exit("STOP production config already exists.\n");
$backup=$private.'/production-backup-'.gmdate('Ymd-His').'-'.bin2hex(random_bytes(3));mkdir($backup,0700);
if(!copy($root.'/index.php',$backup.'/index.php') || !hash_equals($hash,hash_file('sha256',$backup.'/index.php')))exit("STOP backup failed.\n");chmod($backup.'/index.php',0600);
$written=[];
try{
 if(file_put_contents($production,"<?php\nreturn ".var_export($c,true).";\n")===false)throw new RuntimeException('config write');chmod($production,0600);
 foreach($manifest as $name=>$digest){
  if($name==='index.php')continue;$target=$root.'/'.$name;
  if(!is_dir(dirname($target))&&!mkdir(dirname($target),0755,true))throw new RuntimeException('mkdir');
  if(!copy(__DIR__.'/public/'.$name,$target))throw new RuntimeException('copy');$written[]=$target;chmod($target,0644);
  if(!hash_equals($digest,hash_file('sha256',$target)))throw new RuntimeException('hash');
 }
 $temporary=$root.'/index.php.m26-new';if(!copy(__DIR__.'/public/index.php',$temporary))throw new RuntimeException('switch prepare');chmod($temporary,0644);
 if(!rename($temporary,$root.'/index.php'))throw new RuntimeException('switch');
 echo 'OK M2.6 production installed; '.count($manifest).' files verified; original index backed up privately; phone/email only; root .htaccess/php.ini and unrelated apps unchanged. Backup: '.basename($backup)."\n";
}catch(Throwable $e){copy($backup.'/index.php',$root.'/index.php');foreach(array_reverse($written) as $target)unlink($target);if(is_file($production))unlink($production);echo "STOP rolled back copied files and original index.\n";exit(1);}
