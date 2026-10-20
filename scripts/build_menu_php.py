"""Build one standalone meniu.php from maintained modules (no runtime includes)."""
from pathlib import Path
import re,sys
ROOT=Path(__file__).resolve().parents[1];LIB=ROOT/'server/lib/menu'
def body(name):
 text=(LIB/name).read_text();return re.sub(r'^<\?php\s*(?:declare\(strict_types=1\);\s*)?(?:namespace OldMikeMenu;\s*)?','',text,count=1)
data=body('data.php')
config=body('config.php')
renderer=body('render.php').replace("require __DIR__.'/config.php'",'configuration()').replace("file_get_contents(__DIR__.'/header.html')",'header_html()').replace("file_get_contents(__DIR__.'/footer.html')",'footer_html()')
shell=''
for part in ['header','footer']:
 html=(LIB/f'{part}.html').read_text();tag='OLDMIKE_'+part.upper()+'_HTML'
 assert tag not in html
 shell+=f"function {part}_html():string {{return <<<'{tag}'\n{html.rstrip()}\n{tag};\n}}\n"
entry=r'''
// Runtime entry: public RPC, never a browser-side Supabase request.
ini_set('display_errors','0');
if(PHP_SAPI!=='cli'&&!ini_get('zlib.output_compression')&&function_exists('ob_gzhandler'))ob_start('ob_gzhandler');
$preview=PHP_SAPI==='cli'&&getenv('OM_MENU_SNAPSHOT')!==false;
if($preview){
    $loaded=read_cache((string)getenv('OM_MENU_SNAPSHOT'));
    if(!$loaded){fwrite(STDERR,"Snapshot invalid\n");exit(1);}
    $loaded+=['source'=>'snapshot','stale'=>false];
}else{$loaded=menu(__DIR__.'/cache');}
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: public, max-age=0, must-revalidate');
header('X-Content-Type-Options: nosniff');
echo render($loaded,__DIR__,$preview);
'''
output="<?php\n/** OLD MIKE M3.1 — COMPLETE STANDALONE PHP. Public RPC + 600s cache + last-good fallback.\n * Images/CSS/JS are separate files. No runtime PHP includes or secret keys.\n * Maintained source: server/lib/menu; rebuild: python3 scripts/build_menu_php.py.\n */\ndeclare(strict_types=1);\nnamespace OldMikeMenu;\n\n"+data+'\n// CATEGORY → IMAGE + EDITORIAL CONFIGURATION (one place).\nfunction configuration():array {\n'+config+'\n}\n'+shell+'\n'+renderer+entry
path=ROOT/'meniu.php'
if '--check' in sys.argv:
 assert path.read_text()==output,'meniu.php needs regeneration'
 print('PASS standalone PHP matches maintained source')
else:
 path.write_text(output);print('Standalone meniu.php:',len(output.encode()),'bytes')
