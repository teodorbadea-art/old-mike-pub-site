<?php
/** Old Mike menu: complete server HTML, public RPC, 10-minute last-known-good file cache. */
declare(strict_types=1);
ini_set('display_errors','0');
if(PHP_SAPI!=='cli'&&!ini_get('zlib.output_compression')&&function_exists('ob_gzhandler'))ob_start('ob_gzhandler');
$library=__DIR__.'/lib/menu';
if(!is_file($library.'/data.php'))$library=__DIR__.'/server/lib/menu';
require_once $library.'/data.php';
require_once $library.'/render.php';
$preview=PHP_SAPI==='cli'&&getenv('OM_MENU_SNAPSHOT')!==false;
if($preview){
    $menu=OldMikeMenu\read_cache((string)getenv('OM_MENU_SNAPSHOT'));
    if(!$menu){fwrite(STDERR,"Snapshot invalid\n");exit(1);}
    $menu+=['source'=>'snapshot','stale'=>false];
}else{$menu=OldMikeMenu\menu(__DIR__.'/cache');}
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: public, max-age=0, must-revalidate');
header('X-Content-Type-Options: nosniff');
echo OldMikeMenu\render($menu,__DIR__,$preview);
