"""Cross-page and delivery checks. This deliberately does not claim browser QA."""
from pathlib import Path
from html.parser import HTMLParser
from urllib.parse import urlsplit
from io import BytesIO
import sys,json,re,base64,subprocess,tempfile
from PIL import Image
from fontTools.ttLib import TTFont
ROOT=Path(__file__).resolve().parents[1]
sys.path.insert(0,str(ROOT/'scripts'))
from site_helpers import SITE,structured_data

class Page(HTMLParser):
    def __init__(self):super().__init__();self.ids=[];self.refs=[];self.images=[];self.meta={};self.title='';self.h1=0;self.tags=[];self.in_title=False;self.script_type=None;self.scripts=[];self.script=''
    def handle_starttag(self,tag,attrs):
        a=dict(attrs);self.tags.append(tag)
        if 'id' in a:self.ids.append(a['id'])
        if tag=='h1':self.h1+=1
        if tag=='title':self.in_title=True
        if tag=='meta':self.meta[a.get('name') or a.get('property')]=a.get('content')
        if tag=='img':assert 'alt' in a;self.images.append(a)
        for key in ['href','src','data-full']:
            if key in a:self.refs.append(a[key])
        if 'srcset' in a and not a['srcset'].startswith('data:'):
            parts=[p.strip().split() for p in a['srcset'].split(',')];assert len({p[1] for p in parts})==len(parts) if all(len(p)>1 for p in parts) else True
            self.refs.extend(p[0] for p in parts)
        if tag=='script' and 'src' not in a:self.script_type=a.get('type','javascript');self.script=''
    def handle_endtag(self,tag):
        if tag=='title':self.in_title=False
        if tag=='script' and self.script_type is not None:self.scripts.append((self.script_type,self.script));self.script_type=None
    def handle_data(self,data):
        if self.in_title:self.title+=data
        if self.script_type is not None:self.script+=data

def syntax(js):
    with tempfile.NamedTemporaryFile(suffix='.js',mode='w') as f:
        f.write(js);f.flush();subprocess.run(['node','--check',f.name],check=True,capture_output=True)

pages={}
for path in ROOT.glob('*.html'):
    page=Page();page.feed(path.read_text());pages[path.name]=page
    assert page.h1==1 and len(page.ids)==len(set(page.ids)),path
    assert page.meta['robots']=='noindex,nofollow',path
    assert len(page.meta['description'])>40 and page.meta['og:image'].startswith('https://oldmike.ro/'),path
    for kind,code in page.scripts:
        json.loads(code) if kind in ['application/json','application/ld+json'] else syntax(code)
    for im in page.images:
        if im.get('src'):
            actual=Image.open(ROOT/im['src']);actual.verify()
assert len({p.title for p in pages.values()})==len(pages)
for name,page in pages.items():
    for ref in page.refs:
        u=urlsplit(ref)
        if u.scheme:continue
        if u.path:assert (ROOT/u.path).is_file(),(name,ref)
        if u.fragment:assert u.fragment in pages[u.path or name].ids,(name,ref)
home=(ROOT/'index.html').read_text();menu=(ROOT/'meniu.html').read_text();contact=(ROOT/'contact.html').read_text();events=(ROOT/'evenimente.html').read_text()
assert len([i for i in pages['index.html'].images if i.get('src','').startswith('assets/images/')])==1
assert 'Pub &amp; terasă · Matei Voievod67' in home.replace('Voievod 67','Voievod67')
assert 'burger-single-chefs-v3.webp' in menu and 'pizza-oldmike-chefs-v3.webp' in menu and 'vita-chefs-fries-v2.webp' in menu
assert 'pizza-generic-v2.webp' not in menu and 'vita-chefs-v1.webp' not in menu
assert 'noroc' not in menu.lower() and 'diavola' not in menu.lower()
assert '<iframe' not in contact and 'data-load-map' in contact and 'confidentialitate.html' in contact
assert 'data-mode="preview"' in contact and 'type="email" autocomplete="email" maxlength="160" required' not in contact
assert 'data-filter' not in events and '>OM.<' not in events
assert not SITE['indexing_enabled']
assert SITE['operator_name']=='OLD MIKE PUB SRL' and SITE['operator_cui']=='RO40178199'
assert SITE['operator_trade_registry']=='J2018016609401'
for page in ['index.html','meniu.html','galerie.html','evenimente.html','contact.html']:
    html=(ROOT/page).read_text()
    assert all(SITE[k] in html for k in ['operator_name','operator_cui','operator_trade_registry'])
schema=structured_data();assert schema['openingHoursSpecification'][0]['opens']=='10:00';assert schema['openingHoursSpecification'][1]['opens']=='12:00';assert all(x['closes']=='00:00' for x in schema['openingHoursSpecification'])
assert (ROOT/'robots.txt').read_text().strip().endswith('Disallow: /')
from xml.etree import ElementTree
ElementTree.parse(ROOT/'sitemap.xml')
css=(ROOT/'assets/site.css').read_text()
for path in re.findall(r"url\(['\"]?([^)'\"]+)",css):assert (ROOT/'assets'/path).is_file(),path
for p in (ROOT/'assets/fonts').glob('*.woff'):
    font=TTFont(p);cmap=font.getBestCmap();assert all(ord(c) in cmap for c in 'ĂăÂâÎîȘșȚț'),p
assert subprocess.check_output(['git','hash-object','assets/brand/oldmike-logo.png'],cwd=ROOT,text=True).strip()=='5ce2fc5ac8c93fd2aff434c0e8eb617c5a3ed1dc'
for filename in ['old-mike-propunere-vizuala-m1.html','old-mike-previzualizare-telefon.html']:
    data=(ROOT/'output'/filename).read_text();p=Page();p.feed(data);assert SITE['version'] in p.title
    for kind,code in p.scripts:json.loads(code) if kind in ['application/json','application/ld+json'] else syntax(code)
    # Verify each distinct embedded image, including responsive variants and dialog sources.
    for uri in set(re.findall(r'data:image/[^;]+;base64,([A-Za-z0-9+/=]+)',data)):
        im=Image.open(BytesIO(base64.b64decode(uri)));im.verify()
    assert 'data:font/woff;base64,' in data
    if 'telefon' in filename:
        assert 'iframe' not in p.tags and 'script' not in p.tags
        assert len(p.ids)==len(set(p.ids))
        for ref in p.refs:
            if ref.startswith('#'):assert ref[1:] in p.ids,ref
        assert len(data.encode())<4_000_000
print(f'PASS: {len(pages)} pages, local resources/anchors, metadata/JSON-LD, fonts, official logo, current food assets, map opt-in and both self-contained reviews.')

# Preserve the explicitly requested cinematic venue selection across rebuilds.
venue_sources = ['terrace-wide-v2', 'covered-terrace-v2', 'interior-wide-v2', 'bar-wide-v2']
venue_markup = (ROOT/'galerie.html').read_text()
for source in venue_sources:
    assert f'data-full="assets/images/{source}.webp"' in venue_markup
    for width in [480, 800]:
        assert f'assets/images/{source}-{width}.webp {width}w' in venue_markup
assert '-photo-v3' not in venue_markup
print('PASS: all four cinematic venue masters and responsive variants retained.')

for page,stem in [('meniu.html','menu-table-cover-v1'),('galerie.html','atmosfera-gasca-v1'),('evenimente.html','atmosfera-meci-v1'),('contact.html','atmosfera-terasa-v1')]:
    markup=(ROOT/page).read_text()
    assert markup.count('class="page-intro page-cover ')==1,page
    assert f'assets/images/{stem}.webp' in markup,page
    assert 'id="page-cover-title"' in markup,page
    for width in [480,800]: assert f'assets/images/{stem}-{width}.webp {width}w' in markup,page
    assert 'fetchpriority="high"' in markup,page
assert 'page-cover-media' not in (ROOT/'index.html').read_text()
print('PASS: four photographic page introductions, eager responsive imagery and unchanged homepage composition.')
