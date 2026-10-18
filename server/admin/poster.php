<?php
declare(strict_types=1);
require __DIR__.'/../lib/app.php';
use function OldMike\{config,headers,session,admin,state};
use OldMike\Problem;
headers();
try {
    $c=config();session($c);admin();$id=$_GET['id']??'';
    if (!is_string($id) || !preg_match('/^[a-f0-9]{24}$/D',$id)) throw new Problem(404,'Poster indisponibil.');
    $e=state($c,fn(&$s)=>$s['events'][$id]??null);
    if (!$e || !preg_match('/^[a-f0-9]{32}\.jpg$/D',$e['poster'])) throw new Problem(404,'Poster indisponibil.');
    $file=$c['private_dir'].'/posters/'.$e['poster'];if (!is_file($file)) throw new Problem(404,'Poster indisponibil.');
    header('Content-Type: image/jpeg');readfile($file);
} catch (Problem $e) { http_response_code($e->status);echo 'Poster indisponibil.'; }
catch (Throwable $e) { http_response_code(503);echo 'Poster indisponibil.'; }
