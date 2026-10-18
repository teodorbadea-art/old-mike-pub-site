<?php
// CLI only, executed from an extracted, non-public release directory.
declare(strict_types=1);
if (PHP_SAPI!=='cli') {http_response_code(404);exit;}
$target='/home/oldmikero/public_html/oldmike-test';
if (file_exists($target) || is_link($target)) exit("STOP: zona de test exista deja; nu suprascriem.\n");
$base=__DIR__; $manifest=json_decode((string)file_get_contents($base.'/MANIFEST_SHA256.json'),true,512,JSON_THROW_ON_ERROR);
foreach ($manifest as $name=>$hash) {
    if (str_contains($name,'..') || str_starts_with($name,'/') || !is_file($base.'/'.$name) || is_link($base.'/'.$name) || !hash_equals($hash,hash_file('sha256',$base.'/'.$name))) exit("STOP: manifest nevalid.\n");
}
$access=(string)file_get_contents($base.'/public/.htaccess');
if (!str_starts_with($access,"Require all denied\n")) exit("STOP: protectia initiala lipseste.\n");
if (!mkdir($target,0755)) exit("STOP: zona nu poate fi creata.\n");
if (file_put_contents($target.'/.htaccess',$access)!==strlen($access)) exit("STOP: protectia initiala nu poate fi scrisa.\n");
foreach ($manifest as $name=>$hash) {
    if (!str_starts_with($name,'public/') || $name==='public/.htaccess') continue;
    $relative=substr($name,7);$destination=$target.'/'.$relative;
    if (!is_dir(dirname($destination)) && !mkdir(dirname($destination),0755,true)) exit("STOP: instalare incompleta, accesul ramane blocat.\n");
    if (!copy($base.'/'.$name,$destination) || !hash_equals($hash,hash_file('sha256',$destination))) exit("STOP: instalare incompleta, accesul ramane blocat.\n");
    chmod($destination,0644);
}
echo "OK: site de test copiat numai in public_html/oldmike-test; Require all denied activ. Site-ul principal nu a fost modificat.\n";
