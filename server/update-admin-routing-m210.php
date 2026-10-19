<?php
declare(strict_types=1);
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
$root='/home/oldmikero/public_html/garden.oldmike.ro';
$private='/home/oldmikero/oldmike-site-private';
function stop(string $why):never{fwrite(STDERR,"STOP $why\n");exit(1);}
if(realpath($root)!==$root||is_link($root)||realpath($private)!==$private||is_link($private))stop('invalid isolated directories');
$target=$root.'/.htaccess';
if(!is_file($target)||is_link($target))stop('missing or linked Garden rules');
$before=(string)file_get_contents($target);
if(!str_contains($before,'# BEGIN OLDMIKE GARDEN M29'))stop('unexpected Garden rules');
$old="\nDirectoryIndex index.html\n";$new="\nDirectoryIndex index.html index.php\n";
if(substr_count($before,$new)===1&&!str_contains($before,$old)){echo "UNCHANGED routing already corrected.\n";exit;}
if(substr_count($before,$old)!==1||str_contains($before,$new))stop('unexpected DirectoryIndex');
$after=str_replace($old,$new,$before);
if(($argv[1]??'')==='--check'){echo "READY only Garden DirectoryIndex: index.html index.php; no writes.\n";exit;}
if(($argv[1]??'')!=='--apply')stop('use --check or --apply');
$group=[];foreach(['index.php','.htaccess','php.ini'] as $name)if(is_file(dirname($root).'/'.$name))$group[$name]=hash_file('sha256',dirname($root).'/'.$name);
$backup=$private.'/garden-routing-'.gmdate('Ymd-His').'-'.bin2hex(random_bytes(3)).'.htaccess';
$f=fopen($backup,'x');if(!$f)stop('backup creation');chmod($backup,0600);
if(fwrite($f,$before)!==strlen($before)){fclose($f);stop('backup incomplete');}fclose($f);
if(!hash_equals(hash('sha256',$before),hash_file('sha256',$backup)))stop('backup verification');
$tmp=$target.'.m210-'.bin2hex(random_bytes(4));$switched=false;
try{
 $f=fopen($tmp,'x');if(!$f)throw new RuntimeException('temporary file');chmod($tmp,0644);
 if(fwrite($f,$after)!==strlen($after)){fclose($f);throw new RuntimeException('temporary write');}fclose($f);
 if(!hash_equals(hash('sha256',$before),hash_file('sha256',$target)))throw new RuntimeException('Garden rules changed concurrently');
 if(!rename($tmp,$target))throw new RuntimeException('atomic switch');$switched=true;
 if(!hash_equals(hash('sha256',$after),hash_file('sha256',$target)))throw new RuntimeException('installed rules mismatch');
 foreach($group as $name=>$hash)if(!hash_equals($hash,hash_file('sha256',dirname($root).'/'.$name)))throw new RuntimeException('group guard changed');
 echo 'OK M2.10 Garden admin routing fixed; only DirectoryIndex changed; group guards PASS; backup='.basename($backup)."\n";
}catch(Throwable $e){if(is_file($tmp))unlink($tmp);if($switched){copy($backup,$target);chmod($target,0644);}fwrite(STDERR,'STOP '.($switched?'Garden rules restored: ':'no switch: ').$e->getMessage()."\n");exit(1);}
