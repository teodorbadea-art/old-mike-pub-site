"""Private, two-file Garden update. No HTML, routing or cache replacement."""
from pathlib import Path
import hashlib,json,zipfile
ROOT=Path(__file__).resolve().parents[1]
OUT=ROOT/'output/menu-v564';OUT.mkdir(parents=True,exist_ok=True)
files=['meniu.php','assets/site.css']
before={'meniu.php':'15efe8f22d6fc08051146b849faaed7d6109cc5f7d6ff398670532f6a26c1b24','assets/site.css':'19df37d0d9d585f4da1af09877525b784a97ee7a8b160bc02ca9ceecc2882ed3'}
manifest={name:{'before':before[name],'after':hashlib.sha256((ROOT/name).read_bytes()).hexdigest()} for name in files}
(OUT/'manifest.json').write_text(json.dumps(manifest,indent=2)+'\n')
sources=['server/lib/menu/'+name for name in ['data.php','render.php','config.php','header.html','footer.html']]
sources+=['tests/php/menu.php','tests/php/menu-nutrition.php','data/menu-redesign/menu-preview-snapshot.json']
with zipfile.ZipFile(OUT/'Garden-V564-private.zip','w',zipfile.ZIP_DEFLATED)as z:
 for name in files+sources:z.write(ROOT/name,'candidate/'+name)
 z.write(OUT/'manifest.json','manifest.json')
with zipfile.ZipFile(OUT/'Garden-V564-DOAR-FISIERE-PUBLICE.zip','w',zipfile.ZIP_DEFLATED)as z:
 for name in files:z.write(ROOT/name,name)
print('Prepared private Garden ZIP: two public files; tests and sources stay outside public')
