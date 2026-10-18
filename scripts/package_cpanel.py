"""Package only referenced site assets and PHP application, never unrelated apps or secrets."""
from pathlib import Path
from html.parser import HTMLParser
from urllib.parse import urlsplit,unquote
import re,json,shutil,zipfile,hashlib
ROOT=Path(__file__).resolve().parents[1]
OUT=ROOT/'output/cpanel-m21'
PUBLIC=OUT/'public'
if OUT.exists():shutil.rmtree(OUT)
PUBLIC.mkdir(parents=True)
files=set()
class Links(HTMLParser):
 def handle_starttag(self,tag,attrs):
  a=dict(attrs)
  for k in ['src','href','data-full']:
   if a.get(k):add(a[k])
  for item in a.get('srcset','').split(','):
   if item.strip():add(item.strip().split()[0])
def add(url):
 parsed=urlsplit(url)
 if parsed.scheme or parsed.netloc or not parsed.path:return
 name=unquote(parsed.path)
 path=(ROOT/name).resolve()
 if not path.is_relative_to(ROOT) or not path.is_file():raise ValueError('Invalid asset: '+url)
 if path.suffix=='.html':return
 files.add(path.relative_to(ROOT).as_posix())
pages=['index.html','meniu.html','evenimente.html','galerie.html','contact.html','confidentialitate.html','404.html']
for name in pages:
 source=(ROOT/name).read_text()
 Links().feed(source)
 if name=='contact.html':
  source=source.replace('data-mode="preview"','data-mode="server"')
  source=source.replace('Previzualizează cererea','Trimite cererea')
  source=source.replace('În această propunere poți verifica formularul. Trimiterea către restaurant nu este încă activă.','Rezervarea se confirmă numai după discuția cu echipa Old Mike.')
  source=source.replace('<p id="request-status"','<label class="form-honeypot" aria-hidden="true">Website<input name="website" tabindex="-1" autocomplete="off"></label><p id="request-status"')
 source=source.replace('</body>','<script src="assets/site-runtime.js" defer></script><noscript><p class="form-note">Pentru rezervări și informații despre evenimente, sună la 0787 366 166.</p></noscript></body>')
 (PUBLIC/name).write_text(source)
# CSS font/image references are relative to their stylesheet, not the HTML root.
for match in re.finditer(r'url\([\'"]?([^\)\'\"]+)',(ROOT/'assets/site.css').read_text()):
 ref=match.group(1)
 if not ref.startswith('data:'):add('assets/'+ref)
files.update(['assets/site-runtime.js','assets/images/oldmike-share-v1.jpg','assets/fonts/OFL-Cormorant.txt'])
for name in sorted(files):
 target=PUBLIC/name;target.parent.mkdir(parents=True,exist_ok=True);shutil.copy2(ROOT/name,target)
with (PUBLIC/'assets/site.css').open('a') as f:f.write('\n.form-honeypot{position:absolute;left:-10000px;width:1px;height:1px;overflow:hidden}\n')
for folder in ['api','admin','lib']:shutil.copytree(ROOT/'server'/folder,PUBLIC/folder)
(PUBLIC/'robots.txt').write_text('User-agent: *\nDisallow: /\n')
(PUBLIC/'.htaccess').write_text('Require all denied\nOptions -Indexes\nDirectoryIndex index.html index.php\n<IfModule mod_headers.c>\nHeader always set X-Content-Type-Options "nosniff"\nHeader always set X-Robots-Tag "noindex, nofollow"\n</IfModule>\n')
shutil.copy2(ROOT/'server/staging.user.ini',PUBLIC/'.user.ini')
# Configuration example belongs OUTSIDE the public directory, and remains disabled.
shutil.copy2(ROOT/'server/config/config.example.php',OUT/'config.example.php')
shutil.copy2(ROOT/'server/setup.php',OUT/'setup.php')
shutil.copy2(ROOT/'server/install-test.php',OUT/'install-test.php')
manifest={p.relative_to(OUT).as_posix():hashlib.sha256(p.read_bytes()).hexdigest() for p in sorted(OUT.rglob('*')) if p.is_file()}
(OUT/'MANIFEST_SHA256.json').write_text(json.dumps(manifest,indent=2)+'\n')
archive=ROOT/'output/old-mike-cpanel-m21-test.zip'
with zipfile.ZipFile(archive,'w',zipfile.ZIP_DEFLATED) as z:
 for p in sorted(OUT.rglob('*')):
  if p.is_file():z.write(p,p.relative_to(OUT))
print(json.dumps({'package':str(archive),'files':len(manifest),'bytes':archive.stat().st_size,'public_bytes':sum(p.stat().st_size for p in PUBLIC.rglob('*') if p.is_file())}))
