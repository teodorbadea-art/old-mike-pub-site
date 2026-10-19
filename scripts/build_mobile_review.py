"""Static phone review: real HTML and embedded images, no iframe or JavaScript.

This is a separate visual fallback, not a replacement for site functionality.
Forms are disabled; original site pages and desktop review are untouched.
"""
from pathlib import Path
from html import escape
from io import BytesIO
import base64
import re
from PIL import Image
from review_assets import inline_css, inline_html, write_review

ROOT = Path(__file__).resolve().parents[1]
PAGES = [('index.html', 'acasa', 'Acasă'), ('meniu.html', 'meniu', 'Meniu'),
         ('galerie.html', 'galerie', 'Galerie'), ('evenimente.html', 'evenimente', 'Evenimente'),
         ('contact.html', 'contact', 'Contact'), ('confidentialitate.html', 'confidentialitate', 'Confidențialitate')]
PAGE_IDS = {name: key for name, key, title in PAGES}
assets = {}

def embed(path):
    if path not in assets:
        data = (ROOT/path).read_bytes()
        mime = 'image/png'
        # Keep the official logo byte-for-byte. Resize/compress review photos only.
        if not path.startswith('assets/brand/'):
            im = Image.open(BytesIO(data)).convert('RGB')
            im.thumbnail((760, 760), Image.Resampling.LANCZOS)
            output = BytesIO()
            im.save(output, format='JPEG', quality=82, optimize=True)
            data, mime = output.getvalue(), 'image/jpeg'
        assets[path] = 'data:' + mime + ';base64,' + base64.b64encode(data).decode()
    return assets[path]

parts = []
for filename, key, title in PAGES:
    source = (ROOT/filename).read_text()
    classes, body = re.search(r'<body class="([^"]*)">(.*?)</body>', source, re.S).groups()
    body = re.sub(r'<script\b[^>]*>.*?</script>', '', body, flags=re.S)
    body = re.sub(r'<dialog\b[^>]*>.*?</dialog>', '', body, flags=re.S)
    body = re.sub(r'<iframe\b[^>]*>.*?</iframe>', '<p class="static-map">Harta se deschide prin link-urile de orientare de mai jos.</p>', body, flags=re.S)
    # Without scripting, native forms must never submit/reload with entered values.
    body = re.sub(r'(<form\b[^>]*>)', r'\1<fieldset disabled class="static-disabled-form">', body)
    body = body.replace('</form>', '</fieldset></form>')
    body = re.sub(r'<button([^>]*\bdata-gallery\b[^>]*)>', r'<div\1>', body)
    body = re.sub(r'(</img>|<img[^>]*>)(</button>)', r'\1</div>', body)
    # Review photographs are already bounded JPEGs; remove duplicate full/srcset payloads.
    body = re.sub(r'(<img\b[^>]*?)\s+srcset="[^"]*"', r'\1', body)
    body = re.sub(r'\s+data-full="[^"]*"', '', body)
    # Prefix IDs and all local references, avoiding collisions between pages.
    body = re.sub(r'\bid="([^"]+)"', lambda m: f'id="{key}-{m[1]}"', body)
    for attr in ['aria-labelledby', 'aria-controls', 'for']:
        body = re.sub(r'\b'+attr+r'="([^"]+)"', lambda m: attr+'="'+' '.join(key+'-'+v for v in m[1].split())+'"', body)
    def link(m):
        href = m[1]
        if href.startswith('#'):
            return 'href="#'+key+'-'+href[1:]+'"'
        match = re.fullmatch(r'([^#]+\.html)(?:#(.+))?', href)
        if match and match[1] in PAGE_IDS:
            target = PAGE_IDS[match[1]]
            return 'href="#'+target+('-'+match[2] if match[2] else '')+'"'
        return m[0]
    body = re.sub(r'href="([^"]+)"', link, body)
    body = inline_html(body, embed)
    parts.append(f'<section id="{key}" class="static-page {classes}" aria-label="Pagina {title}"><div class="static-page-label">{title}</div>{body}</section>')

css = inline_css((ROOT/'assets/site.css').read_text())
css += '''
.static-toolbar{position:sticky;top:0;z-index:100;background:#211b13;border-bottom:1px solid #d8ad6955;padding:10px 16px;font:12px Arial;color:#f5f0e7}.static-toolbar strong{display:block;font-size:12px;margin:0 0 8px}.static-toolbar nav{display:flex;flex-wrap:wrap;gap:5px 20px}.static-toolbar a{display:inline-block;padding:9px 0;color:#e8c590;min-height:36px}.static-note{font:12px/1.7 Arial;color:#c9beae;padding:16px;margin:0;background:#18140f}.static-page{scroll-margin-top:110px}.static-page [id]{scroll-margin-top:130px}.static-page-label{font:12px Arial;letter-spacing:.18em;text-transform:uppercase;padding:14px 20px;background:#302619;color:#e8c590}.static-page .site-nav,.static-page .nav-toggle,.static-page .header-cta{display:none}.static-page .mobile-call{display:inline-flex;margin-left:auto}.static-page .map-load{display:none}.static-page .site-header{justify-content:flex-start}.static-page .menu-access{position:relative;top:auto}.static-page .map-canvas{height:auto;padding:25px}.static-map{font:14px/1.7 Arial;color:#c9beae}.static-page .venue-photo img{aspect-ratio:auto!important}.static-disabled-form{border:0;margin:0;padding:0;min-width:0;display:contents}.static-page .gallery-button{cursor:default}.static-page .venue-enlarge{display:none}.static-page .site-footer{margin-top:20px}
'''
nav = ''.join(f'<a href="#{key}">{title}</a>' for _,key,title in PAGES)
html = '<!doctype html><html lang="ro"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Old Mike · M2.8 · previzualizare pentru telefon</title><style>'+css+'</style></head><body><div class="static-toolbar"><strong>OLD MIKE · M2.8 · Evaluare vizuală</strong><nav aria-label="Pagini">'+nav+'</nav></div><p class="static-note">M2.8 · Toate paginile sunt mai jos. Versiune statică, pentru evaluarea vizuală. Apasă un nume pentru a ajunge direct la pagina dorită. Această versiune este pentru evaluarea designului; formularele sunt inactive.</p>'+''.join(parts)+'</body></html>'
out = ROOT/'output/old-mike-previzualizare-telefon.html'
out.parent.mkdir(exist_ok=True)
write_review(out,html)
print(out)
print('Bytes:', out.stat().st_size, 'Images:', len(assets))
