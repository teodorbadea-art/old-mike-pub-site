"""Package standalone live PHP + separate media; NEVER publishes or changes routing."""
from pathlib import Path
from html.parser import HTMLParser
from urllib.parse import urlsplit
import json,shutil,zipfile,re,hashlib
ROOT=Path(__file__).resolve().parents[1];OUT=ROOT/'output/menu-m3/package';PUBLIC=OUT/'public'
if OUT.exists():shutil.rmtree(OUT)
PUBLIC.mkdir(parents=True)
files=set()
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
html=(ROOT/'output/menu-m3/preview.html').read_text();assert 'data:image/' not in html
Links().feed(html)
for name in ['assets/site.css','assets/oldmike-design.css']:
 for ref in re.findall(r'url\([\'\"]?([^\)\'\"]+)',(ROOT/name).read_text()):
  if not ref.startswith(('data:','http:','https:')):add('assets/'+ref)
files.update(['assets/fonts/OFL-Cormorant.txt','assets/fonts/OFL-Manrope.txt'])
for name in sorted(files):
 dest=PUBLIC/name;dest.parent.mkdir(parents=True,exist_ok=True);shutil.copy2(ROOT/name,dest)
shutil.copy2(ROOT/'meniu.php',PUBLIC/'meniu.php')
# Preview uses the very same external CSS/JS/image files, without PHP installation.
preview=re.sub(r'href="([a-z-]+\.html(?:#[^"]*)?)"',lambda m:'href="'+('#continut' if m[1]=='meniu.html' else 'https://garden.oldmike.ro/'+m[1])+'"',html)
preview=preview.replace('assets/','public/assets/')
(OUT/'previzualizare.html').write_text(preview)
(OUT/'FISIERE_DE_URCAT.txt').write_text('Numai aceste fișiere se combină cu site-ul existent; nu înlocui folderele întregi. Nicio pagină HTML existentă nu se înlocuiește.\n\n'+'\n'.join(['meniu.php',*sorted(files),'cache/.htaccess','cache/menu.json'])+'\n')
(PUBLIC/'cache').mkdir();shutil.copy2(ROOT/'cache/.htaccess',PUBLIC/'cache/.htaccess');shutil.copy2(ROOT/'data/menu-redesign/menu-preview-snapshot.json',PUBLIC/'cache/menu.json')
shutil.copytree(ROOT/'docs/menu-redesign',OUT/'instructiuni-si-prompturi',ignore=shutil.ignore_patterns('m31-image-manifest.json'))
(OUT/'APACHE-routing-DOAR-DUPA-APROBARE.txt').write_text('# Only in Garden .htaccess, AFTER approval; do not replace existing rules.\n<IfModule mod_mime.c>\nAddType image/avif .avif\nAddType image/webp .webp\n</IfModule>\n<IfModule mod_deflate.c>\nAddOutputFilterByType DEFLATE text/html text/css application/javascript application/json\n</IfModule>\n<IfModule mod_rewrite.c>\nRewriteEngine On\nRewriteRule ^meniu\\.html$ meniu.php [END]\n</IfModule>\n')
for name in ['browser-checks.json','lighthouse-mobile.json','live-rpc-check.json','site-font-checks.json']:
 source=ROOT/'output/menu-m3'/name
 if source.is_file() and source.stat().st_mtime>(ROOT/'meniu.php').stat().st_mtime:
  (OUT/'verificari').mkdir(exist_ok=True);shutil.copy2(source,OUT/'verificari'/name)
for p in OUT.rglob('*'):p.chmod(0o755 if p.is_dir() else 0o644)
manifest={p.relative_to(OUT).as_posix():hashlib.sha256(p.read_bytes()).hexdigest() for p in sorted(OUT.rglob('*')) if p.is_file()}
(OUT/'MANIFEST_SHA256.json').write_text(json.dumps(manifest,indent=2)+'\n')
archive=ROOT/'output/menu-m3/Old-Mike-Meniu-M3-Pachet.zip'
with zipfile.ZipFile(archive,'w',zipfile.ZIP_DEFLATED)as z:
 for p in sorted(OUT.rglob('*')):
  if p.is_file():z.write(p,p.relative_to(OUT))
assert not list(PUBLIC.rglob('*.html')), 'No HTML page may be shipped in public/'
assert {p.name for p in PUBLIC.iterdir()}=={'meniu.php','assets','cache'}, 'Public payload allowlist'
assert not(PUBLIC/'.htaccess').exists()
assert (PUBLIC/'cache/menu.json').is_file()
assert 'require ' not in (PUBLIC/'meniu.php').read_text()
# 21 illustrations: native+480/800 ×2formats, plus6portrait mobile sources.
assert len(list((PUBLIC/'assets/images/meniu').glob('*.*')))==132
print('PASS package',len(manifest),'files,',archive.stat().st_size,'bytes; standalone PHP +132media files; preview outside public; routing unchanged')
