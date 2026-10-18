"""Reproduce the eight-file M2.2 -> M2.3 staging patch; does not deploy."""
from pathlib import Path
import json,hashlib,zipfile
ROOT=Path(__file__).resolve().parents[1]
manifest=json.loads((ROOT/'docs/launch/M23_PATCH.json').read_text())
public=ROOT/'output/cpanel-m21/public'
for name,hashes in manifest.items():
    assert hashlib.sha256((public/name).read_bytes()).hexdigest()==hashes['after'], name
archive=ROOT/'output/old-mike-visual-m23.zip'
with zipfile.ZipFile(archive,'w',zipfile.ZIP_DEFLATED) as z:
    for name in manifest:z.write(public/name,'public/'+name)
    z.write(ROOT/'docs/launch/M23_PATCH.json','PATCH.json')
    z.write(ROOT/'server/update-visual-m23.php','update-visual-m23.php')
print(archive)
