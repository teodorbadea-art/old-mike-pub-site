from pathlib import Path
from html.parser import HTMLParser
import json,hashlib,zipfile
ROOT=Path(__file__).resolve().parents[1];OUT=ROOT/'output/cpanel-m21';PUBLIC=OUT/'public'
manifest=json.loads((OUT/'MANIFEST_SHA256.json').read_text())
for name,digest in manifest.items():assert hashlib.sha256((OUT/name).read_bytes()).hexdigest()==digest,name
assert (PUBLIC/'assets/images/oldmike-share-v1.jpg').is_file()
assert (PUBLIC/'assets/fonts/OFL-Cormorant.txt').is_file()
assert not (PUBLIC/'setup.php').exists()
assert (PUBLIC/'.htaccess').read_text().startswith('Require all denied\n')
assert not (PUBLIC/'config.example.php').exists()
assert not (PUBLIC/'administrare-demo.html').exists()
assert not (PUBLIC/'data').exists()
assert (PUBLIC/'lib/.htaccess').read_text().strip()=='Require all denied'
assert "'enabled' => false" in (OUT/'config.example.php').read_text()
assert "'requests_enabled' => false" in (OUT/'config.example.php').read_text()
contact=(PUBLIC/'contact.html').read_text()
assert 'data-mode="server"' in contact and 'name="website"' in contact
assert 'site-runtime.js' in contact
assert 'data-mode="preview"' in (ROOT/'contact.html').read_text()
assert '-photo-v3' not in (PUBLIC/'galerie.html').read_text()
for name in manifest:assert not any(part in name for part in ['.git/','node_modules/','meniu.oldmike.ro','club.oldmike.ro','state.json','config.php'])
with zipfile.ZipFile(ROOT/'output/old-mike-cpanel-m21-test.zip') as z:
 assert set(z.namelist())==set(manifest)|{'MANIFEST_SHA256.json'}
 assert z.testzip() is None
print('PASS: package checksums, archive, selected assets, inert local reviews, isolated configuration and runtime integration.')
