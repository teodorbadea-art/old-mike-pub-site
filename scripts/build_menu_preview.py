"""Render complete PHP for review and audit; keep all media as separate files."""
from pathlib import Path
import subprocess,os
ROOT=Path(__file__).resolve().parents[1];OUT=ROOT/'output/menu-m3';OUT.mkdir(parents=True,exist_ok=True)
env={**os.environ,'OM_MENU_SNAPSHOT':str(ROOT/'data/menu-redesign/menu-preview-snapshot.json')}
php=os.environ.get('OM_PHP_BIN','php')
html=subprocess.check_output([php,str(ROOT/'meniu.php')],env=env,cwd=ROOT).decode()
assert 'data:image/' not in html and ';base64,' not in html
(OUT/'preview.html').write_text(html)
code="require 'server/lib/menu/data.php';require 'server/lib/menu/render.php';$m=OldMikeMenu\\read_cache('data/menu-redesign/menu-preview-snapshot.json');echo OldMikeMenu\\render($m,getcwd(),false);"
production=subprocess.check_output([php,'-r',code],env=env,cwd=ROOT).decode()
(OUT/'audit.html').write_text(production)
assert html.replace('noindex,nofollow','index,follow').split('<main',1)[1]==production.split('<main',1)[1], 'standalone renderer mismatch'
print('PASS standalone PHP and maintained renderer; images separate:',len(html.encode()),'HTML bytes')
