<?php
// Run from the extracted visual-m25 patch outside public_html. Only poster size limit and per-directory PHP upload settings; no credentials.
declare(strict_types=1);
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
$root='/home/oldmikero/public_html/oldmike-test';
if(realpath($root)!==$root || is_link($root))exit("STOP: destinatie nevalida.\n");
$allowed=['lib/app.php','admin/index.php','.user.ini'];
$patch=json_decode((string)file_get_contents(__DIR__.'/PATCH.json'),true,512,JSON_THROW_ON_ERROR);
foreach($patch as $name=>$hashes){
 if(!in_array($name,$allowed,true) || is_link(__DIR__.'/public/'.$name) || !is_file(__DIR__.'/public/'.$name) || !hash_equals($hashes['after'],hash_file('sha256',__DIR__.'/public/'.$name)))exit("STOP: pachet nevalid.\n");
 $target=$root.'/'.$name;$parent=realpath(dirname($target));
 if(!$parent || !str_starts_with($parent.'/', $root.'/') || is_link($target))exit("STOP: cale nevalida.\n");
 if(is_file($target) && hash_equals($hashes['after'],hash_file('sha256',$target)))continue;
 if($hashes['before']===null ? file_exists($target) : (!is_file($target) || !hash_equals($hashes['before'],hash_file('sha256',$target))))exit("STOP: versiunea existenta difera; nu suprascriem.\n");
}
$backup=__DIR__.'/backup-'.gmdate('Ymd-His').'-'.bin2hex(random_bytes(3));mkdir($backup,0700);
$changed=[];
try{
 foreach($patch as $name=>$hashes){
  $target=$root.'/'.$name;
  if(is_file($target) && hash_equals($hashes['after'],hash_file('sha256',$target)))continue;
  if(is_file($target)){
   $old=$backup.'/'.$name;if(!is_dir(dirname($old)))mkdir(dirname($old),0700,true);
   if(!copy($target,$old))throw new RuntimeException('Backup failed');chmod($old,0600);
  }
  $temporary=$target.'.m25-'.bin2hex(random_bytes(4));
  if(!copy(__DIR__.'/public/'.$name,$temporary))throw new RuntimeException('Copy failed');chmod($temporary,0644);
  if(!rename($temporary,$target))throw new RuntimeException('Atomic replace failed');$changed[]=$name;
  if(!hash_equals($hashes['after'],hash_file('sha256',$target)))throw new RuntimeException('Verification failed');
 }
 echo 'OK M2.5: '.count($changed)." fisiere pentru limita posterelor actualizate, hash-uri verificate; backup privat creat. Protectia si backend-ul sunt pastrate.\n";
}catch(Throwable $e){
 foreach(array_reverse($changed) as $name)if(is_file($backup.'/'.$name)){copy($backup.'/'.$name,$root.'/'.$name);chmod($root.'/'.$name,0644);}
 echo "STOP: actualizare incompleta; fisierele existente au fost restaurate din backup. Verifica zona de test.\n";exit(1);
}
