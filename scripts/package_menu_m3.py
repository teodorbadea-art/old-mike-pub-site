"""Package an inspectable PHP menu; NEVER installs it or changes Apache routing."""
from pathlib import Path
from html.parser import HTMLParser
from urllib.parse import urlsplit
import json,shutil,zipfile,re,hashlib
ROOT=Path(__file__).resolve().parents[1];OUT=ROOT/'output/menu-m3/package';PUBLIC=OUT/'public'
if OUT.exists():shutil.rmtree(OUT)
PUBLIC.mkdir(parents=True)
files={'assets/images/oldmike-share-v1.jpg'}
class Links(HTMLParser):
 def handle_starttag(self,tag,attrs):
  a=dict(attrs)
  for key in ['href','src']:
   if a.get(key):add(a[key])
  for item in a.get('srcset','').split(','):
   if item.strip():add(item.strip().split()[0])
def add(url):
 u=urlsplit(url)
 if u.scheme or not u.path or not u.path.startswith('assets/'):return
 path=(ROOT/u.path).resolve()
 assert path.is_relative_to(ROOT)and path.is_file(),url
 files.add(u.path)
Links().feed((ROOT/'output/menu-m3/preview.html').read_text())
for name in ['assets/site.css','assets/oldmike-design.css']:
 for ref in re.findall(r'url\([\'\"]?([^\)\'\"]+)',(ROOT/name).read_text()):
  if not ref.startswith(('data:','http:','https:')):add('assets/'+ref)
files.update(['assets/fonts/OFL-Cormorant.txt','assets/fonts/OFL-Manrope.txt'])
for name in sorted(files):
 dest=PUBLIC/name;dest.parent.mkdir(parents=True,exist_ok=True);shutil.copy2(ROOT/name,dest)
shutil.copy2(ROOT/'meniu.php',PUBLIC/'meniu.php');shutil.copytree(ROOT/'server/lib/menu',PUBLIC/'lib/menu')
(PUBLIC/'lib/.htaccess').write_text('Require all denied\n')
(PUBLIC/'cache').mkdir();shutil.copy2(ROOT/'cache/.htaccess',PUBLIC/'cache/.htaccess');shutil.copy2(ROOT/'data/menu-redesign/menu-preview-snapshot.json',PUBLIC/'cache/menu.json')
shutil.copytree(ROOT/'docs/menu-redesign',OUT/'instructiuni-si-prompturi')
shutil.copy2(ROOT/'output/menu-m3/Old-Mike-Meniu-Previzualizare.html',OUT/'Old-Mike-Meniu-Previzualizare.html')
(OUT/'APACHE-routing-DOAR-DUPA-APROBARE.txt').write_text('# Only in Garden .htaccess, AFTER approval; do not replace existing rules.\n<IfModule mod_deflate.c>\nAddOutputFilterByType DEFLATE text/html text/css application/javascript application/json\n</IfModule>\n<IfModule mod_rewrite.c>\nRewriteEngine On\nRewriteRule ^meniu\\.html$ meniu.php [END]\n</IfModule>\n')
for name in ['browser-checks.json','lighthouse-mobile.json','live-rpc-check.json']:
 source=ROOT/'output/menu-m3'/name
 if source.is_file():
  (OUT/'verificari').mkdir(exist_ok=True);shutil.copy2(source,OUT/'verificari'/name)
for p in OUT.rglob('*'):
 p.chmod(0o755 if p.is_dir() else 0o644)
manifest={p.relative_to(OUT).as_posix():hashlib.sha256(p.read_bytes()).hexdigest() for p in sorted(OUT.rglob('*')) if p.is_file()}
(OUT/'MANIFEST_SHA256.json').write_text(json.dumps(manifest,indent=2)+'\n')
archive=ROOT/'output/menu-m3/Old-Mike-Meniu-M3-Pachet.zip'
with zipfile.ZipFile(archive,'w',zipfile.ZIP_DEFLATED)as z:
 for p in sorted(OUT.rglob('*')):
  if p.is_file():z.write(p,p.relative_to(OUT))
assert not(PUBLIC/'meniu.html').exists()and not(PUBLIC/'.htaccess').exists()
assert (PUBLIC/'cache/menu.json').is_file()and (PUBLIC/'lib/menu/config.php').is_file()
print('PASS package',len(manifest),'files,',archive.stat().st_size,'bytes; existingHTML/routing untouched')
