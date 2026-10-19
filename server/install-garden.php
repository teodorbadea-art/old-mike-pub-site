<?php
declare(strict_types=1);
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
$root='/home/oldmikero/public_html/garden.oldmike.ro';
$private='/home/oldmikero/oldmike-site-private';
function stop(string $why):never{fwrite(STDERR,"STOP $why\n");exit(1);}
if(realpath($root)!==$root||is_link($root)||realpath($private)!==$private||is_link($private))stop('invalid isolated directories');
$manifest=json_decode((string)file_get_contents(__DIR__.'/MANIFEST.json'),true,512,JSON_THROW_ON_ERROR);
if(!is_array($manifest)||count($manifest)<30)stop('invalid manifest');
foreach($manifest as $name=>$hash){
 if(str_contains($name,'..')||str_starts_with($name,'/')||!preg_match('~^(assets/|api/|admin/|lib/|(?:index|meniu|galerie|evenimente|contact|confidentialitate|404)\.html$|robots\.txt$|sitemap\.xml$)~D',$name))stop('invalid package path');
 $source=__DIR__.'/public/'.$name;
 if(!is_file($source)||is_link($source)||!hash_equals($hash,hash_file('sha256',$source)))stop('package hash mismatch');
 if(file_exists($root.'/'.$name)||is_link($root.'/'.$name))stop('target already contains site files');
}
foreach(scandir($root) as $name)if(!in_array($name,['.','..','.htaccess','.user.ini','php.ini','.well-known'],true))stop('unexpected pre-existing target content');
foreach(['.htaccess','.user.ini','php.ini','.well-known'] as $name)if(is_link($root.'/'.$name))stop('target symlink');
$config=$private.'/garden-config.php';
if(file_exists($config)||is_link($config))stop('Garden config already exists');
$c=require $private.'/config.php';
if(!is_array($c)||!($c['enabled']??false))stop('existing private configuration unavailable');
$c['origin']='https://garden.oldmike.ro';$c['base_path']='';$c['requests_enabled']=false;$c['mail_transport']='disabled';
$rules=(string)file_get_contents(__DIR__.'/garden.rules');
$original=is_file($root.'/.htaccess')?(string)file_get_contents($root.'/.htaccess'):'';
if(str_contains($original,'OLDMIKE GARDEN')||!str_starts_with($rules,'# BEGIN OLDMIKE GARDEN M29'))stop('unexpected rules');
$group=[];foreach(['index.php','.htaccess','php.ini'] as $name)if(is_file(dirname($root).'/'.$name))$group[$name]=hash_file('sha256',dirname($root).'/'.$name);
if(($argv[1]??'')==='--check'){echo 'READY Garden only; '.count($manifest)." hashes verified; group files unchanged; private config not written.\n";exit;}
if(($argv[1]??'')!=='--apply')stop('use --check or --apply');
// Run synthetic tests and lint again immediately before publishing any file.
foreach(['backend.php','drafts.php'] as $name){passthru(escapeshellarg(PHP_BINARY).' '.escapeshellarg(__DIR__.'/checks/tests/php/'.$name),$code);if($code!==0)stop('synthetic PHP checks failed');}
foreach(array_merge(glob(__DIR__.'/public/admin/*.php'),glob(__DIR__.'/public/api/*.php'),glob(__DIR__.'/public/lib/*.php'),[__FILE__]) as $file){exec(escapeshellarg(PHP_BINARY).' -l '.escapeshellarg($file).' 2>&1',$lines,$code);if($code!==0)stop('PHP lint failed');}
$backup=$private.'/garden-backup-'.gmdate('Ymd-His').'-'.bin2hex(random_bytes(3));
if(!mkdir($backup,0700))stop('backup directory');
if(file_put_contents($backup.'/garden.htaccess',$original)===false)stop('backup rules');
chmod($backup.'/garden.htaccess',0600);
file_put_contents($backup.'/group-hashes.json',json_encode($group,JSON_THROW_ON_ERROR));chmod($backup.'/group-hashes.json',0600);
$written=[];$configWritten=false;$rulesWritten=false;
try{
 $f=fopen($config,'x');if(!$f)throw new RuntimeException('config exists');$configWritten=true;chmod($config,0600);$body="<?php\nreturn ".var_export($c,true).";\n";if(fwrite($f,$body)!==strlen($body))throw new RuntimeException('config write');fclose($f);unset($c,$body);
 $names=array_keys($manifest);$names=array_values(array_diff($names,['index.html']));$names[]='index.html';
 foreach($names as $name){$target=$root.'/'.$name;if(!is_dir(dirname($target))&&!mkdir(dirname($target),0755,true))throw new RuntimeException('mkdir');$f=fopen($target,'x');if(!$f)throw new RuntimeException('target changed');$written[]=$target;chmod($target,0644);$bytes=file_get_contents(__DIR__.'/public/'.$name);if(fwrite($f,$bytes)!==strlen($bytes))throw new RuntimeException('write');fclose($f);if(!hash_equals($manifest[$name],hash_file('sha256',$target)))throw new RuntimeException('hash');}
 if(!hash_equals(hash('sha256',$original),hash('sha256',is_file($root.'/.htaccess')?(string)file_get_contents($root.'/.htaccess'):'')))throw new RuntimeException('rules changed');
 $tmp=$root.'/.htaccess.m29';if(file_put_contents($tmp,$rules."\n".$original)===false)throw new RuntimeException('rules prepare');chmod($tmp,0644);if(!rename($tmp,$root.'/.htaccess'))throw new RuntimeException('rules switch');$rulesWritten=true;
 foreach($group as $name=>$hash)if(!hash_equals($hash,hash_file('sha256',dirname($root).'/'.$name)))throw new RuntimeException('group guard changed');
 file_put_contents($backup.'/installed-manifest.json',json_encode($manifest,JSON_THROW_ON_ERROR));chmod($backup.'/installed-manifest.json',0600);
 echo 'OK M2.9 installed ONLY garden.oldmike.ro; '.count($manifest).' files verified; group guards PASS; no email sent; staging untouched; backup='.basename($backup)."\n";
}catch(Throwable $e){foreach(array_reverse($written) as $target)if(is_file($target))unlink($target);if($rulesWritten)file_put_contents($root.'/.htaccess',$original);if($configWritten&&is_file($config))unlink($config);fwrite(STDERR,"STOP installation rolled back: ".$e->getMessage()."\n");exit(1);}
