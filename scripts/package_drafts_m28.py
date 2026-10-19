"""Package a narrowly scoped staging update; does not execute deployment."""
from pathlib import Path
import json,hashlib,zipfile
ROOT=Path(__file__).resolve().parents[1];public=ROOT/'output/cpanel-m21/public'
m=json.loads((ROOT/'docs/launch/M28_PATCH.json').read_text())
for n,h in m.items():assert hashlib.sha256((public/n).read_bytes()).hexdigest()==h['after'],n
out=ROOT/'output/old-mike-drafts-m28.zip'
with zipfile.ZipFile(out,'w',zipfile.ZIP_DEFLATED) as z:
 for n in m:z.write(public/n,'public/'+n)
 z.write(ROOT/'docs/launch/M28_PATCH.json','PATCH.json');z.write(ROOT/'server/update-drafts-m28.php','update-drafts-m28.php')
 for n in ['server/lib/app.php','server/lib/drafts.php','tests/php/backend.php','tests/php/drafts.php']:z.write(ROOT/n,n)
print(out)
