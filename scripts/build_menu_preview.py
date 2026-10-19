"""Render real PHP through CLI; then create an offline review with the real menu snapshot.
No browser server is started and no production file is replaced.
"""
from pathlib import Path
import subprocess,os,re,base64,mimetypes
ROOT=Path(__file__).resolve().parents[1];OUT=ROOT/'output/menu-m3';OUT.mkdir(parents=True,exist_ok=True)
env={**os.environ,'OM_MENU_SNAPSHOT':str(ROOT/'data/menu-redesign/menu-preview-snapshot.json')}
html=subprocess.check_output([os.environ.get('OM_PHP_BIN','php'),str(ROOT/'meniu.php')],env=env).decode()
(OUT/'preview.html').write_text(html)
# Exact production renderer with fixture data: no publication and no preview noindex.
code="require 'server/lib/menu/data.php';require 'server/lib/menu/render.php';$m=OldMikeMenu\\read_cache('data/menu-redesign/menu-preview-snapshot.json');echo OldMikeMenu\\render($m,getcwd(),false);"
production=subprocess.check_output([os.environ.get('OM_PHP_BIN','php'),'-r',code],env=env,cwd=ROOT).decode()
(OUT/'audit.html').write_text(production)
def uri(path):
 data=path.read_bytes();mime=mimetypes.guess_type(str(path))[0] or 'application/octet-stream';return 'data:'+mime+';base64,'+base64.b64encode(data).decode()
def css_link(match):
 path=ROOT/match[1].split('?')[0];css=path.read_text()
 css=re.sub(r'url\([\'\"]?([^\)\'\"]+)[\'\"]?\)',lambda m:'url("'+uri(path.parent/m[1])+'")' if not m[1].startswith(('data:','http:','https:')) else m[0],css)
 return '<style>'+css+'</style>'
review=re.sub(r'<link rel="stylesheet" href="([^"]+)">',css_link,html)
review=re.sub(r'<script src="([^"]+)" defer></script>',lambda m:'<script>'+(ROOT/m[1].split('?')[0]).read_text()+'</script>',review)
# One appropriate image per picture keeps the offline file compact.
review=re.sub(r'<source[^>]+>','@@HERO_MOBILE@@',review)
review=re.sub(r'\s(?:srcset|sizes)="[^"]*"','',review)
def image_src(m):
 path=ROOT/m[1]
 if '/images/' in str(path) and path.stem not in ['oldmike-share-v1']:
  variant=path.with_name(path.stem+'-800.webp')
  if variant.is_file():path=variant
 return 'src="'+uri(path)+'"'
review=re.sub(r'src="(assets/[^"?]+)"',image_src,review)
review=review.replace('@@HERO_MOBILE@@','<source media="(max-width:600px)" srcset="'+uri(ROOT/'assets/images/hero-single-chefs-v4-mobile-800.webp')+'">')
review=re.sub(r'href="(assets/brand/[^"?]+)"',lambda m:'href="'+uri(ROOT/m[1])+'"',review)
(OUT/'Old-Mike-Meniu-Previzualizare.html').write_text(review)
print('Preview rendered from PHP:',len(html),'bytes; offline review:',len(review),'characters')
