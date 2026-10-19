"""Build Garden-only brand patch from verified Garden package; no server operations."""
from pathlib import Path
import hashlib,json,shutil,zipfile
ROOT=Path(__file__).resolve().parents[1];SOURCE=ROOT/'output/garden-m29/public';OUT=ROOT/'output/brand-m212'
NAMES=('index.html','meniu.html','evenimente.html','galerie.html','contact.html','confidentialitate.html','404.html','assets/site.css','assets/oldmike-design.css','assets/fonts/manrope-ro-v1.woff','assets/fonts/OFL-Manrope.txt')
if OUT.exists():shutil.rmtree(OUT)
OUT.mkdir()
for name in NAMES:
 target=OUT/name;target.parent.mkdir(parents=True,exist_ok=True);shutil.copy2(SOURCE/name,target)
manifest={name:hashlib.sha256((OUT/name).read_bytes()).hexdigest() for name in NAMES}
(OUT/'MANIFEST.json').write_text(json.dumps(manifest,indent=2)+'\n')
archive=ROOT/'output/old-mike-garden-brand-m212.zip'
with zipfile.ZipFile(archive,'w',zipfile.ZIP_DEFLATED) as z:
 for p in sorted(OUT.rglob('*')):
  if p.is_file():z.write(p,p.relative_to(OUT))
print('PASS Garden brand patch:',len(manifest),'files;',archive.stat().st_size,'bytes; no admin, lib, config or access-rule changes.')
