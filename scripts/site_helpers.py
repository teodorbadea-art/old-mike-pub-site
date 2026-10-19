"""Shared semantic markup, confirmed business data and preview-safe metadata."""
from pathlib import Path
from html import escape
import json
from PIL import Image
ROOT=Path(__file__).resolve().parents[1]
SITE=json.loads((ROOT/'data/site.json').read_text())
PAGES=[('index.html','Acasă'),('meniu.html','Meniu'),('evenimente.html','Evenimente'),('galerie.html','Galerie'),('contact.html','Contact')]
MAP_LINK='https://www.google.com/maps/dir/?api=1&destination=Strada+Matei+Voievod+67+Bucuresti'
DAY_NAMES={1:'Luni',2:'Marți',3:'Miercuri',4:'Joi',5:'Vineri',6:'Sâmbătă',7:'Duminică'}
DAY_SCHEMA={1:'Monday',2:'Tuesday',3:'Wednesday',4:'Thursday',5:'Friday',6:'Saturday',7:'Sunday'}
def clock(n,schema=False):
    if n==1440:return '00:00' if schema else '24:00'
    return f'{n//60:02d}:{n%60:02d}'
def hour_groups():
    groups={}
    for key,range_ in sorted(SITE['hours'].items(),key=lambda pair:int(pair[0])):groups.setdefault(tuple(range_),[]).append(int(key))
    return groups.items()
def hours_label(days):
    return 'Luni–vineri' if days==[1,2,3,4,5] else 'Sâmbătă–duminică' if days==[6,7] else ', '.join(DAY_NAMES[d] for d in days)
HOURS=' · '.join(hours_label(days)+' '+clock(start)+'–'+clock(end) for (start,end),days in hour_groups())
HOURS_HTML='<br>'.join(hours_label(days)+' · <strong>'+clock(start)+'–'+clock(end)+'</strong>' for (start,end),days in hour_groups())


def intro(kicker,title,text):
    return f'<div class="page-intro"><div><div class="eyebrow">{kicker}</div><h1>{title}</h1></div><p class="intro">{text}</p></div>'


def page_cover(kicker,title,text,theme):
    sources={
        'menu':('menu-table-cover-v1','Masă de pub cu burger simplu, pizza, platou cu grătare, cartofi prăjiți și bere; compoziție generată'),
        'gallery':('atmosfera-gasca-v1','Scenă de socializare generată, inspirată de spațiile Old Mike'),
        'events':('atmosfera-meci-v1','Scenă generată cu oaspeți care urmăresc un meci în terasa acoperită Old Mike'),
        'contact':('atmosfera-terasa-v1','Scenă generată de seară pe terasa Old Mike')
    }
    stem,alt=sources[theme]
    sizes='(max-width:800px) 100vw, (max-width:1440px) 76vw, 1000px'
    srcset=', '.join(f'assets/images/{stem}-{w}.webp {w}w' for w in [480,800])+f', assets/images/{stem}.webp 1200w'
    cta=button('tel:'+SITE['phone'],'Sună · '+SITE['phone_display'],True) if theme=='contact' else ''
    return f'<section class="page-intro page-cover cover-{theme}" aria-labelledby="page-cover-title"><picture class="page-cover-media"><img src="assets/images/{stem}.webp" srcset="{srcset}" sizes="{sizes}" width="1200" height="800" alt="{escape(alt,quote=True)}" loading="eager" fetchpriority="high" decoding="async"></picture><div class="page-cover-title"><div class="eyebrow">{kicker}</div><h1 id="page-cover-title">{title}</h1>{cta}</div><p class="intro">{text}</p></section>'

def button(href,text,ghost=False):
    arrow='↗' if href.startswith('https://') else '→' if not href.startswith(('tel:','mailto:')) else ''
    icon=f'<span class="arrow" aria-hidden="true">{arrow}</span>' if arrow else ''
    return f'<a class="button{" ghost" if ghost else ""}" href="{escape(href,quote=True)}">{text}{icon}</a>'

def brand_shell(kind,current=''):
    # Build-time rendering from the same dependency-free module used by the app.
    import subprocess
    facts={k:SITE[k] for k in ['name','phone','phone_display','email','address','hours_display','menu_url','club_url']}
    facts['website']='';facts['home']='index.html'
    facts['logo']='assets/brand/oldmike-logo.png'
    facts['operator']=' · '.join(SITE[k] for k in ['operator_name','operator_cui','operator_trade_registry'])
    program="const shell=require(process.argv[1]);console.log(shell[process.argv[2]](JSON.parse(process.argv[3]),process.argv[4]));"
    return subprocess.check_output(['node','-e',program,str(ROOT/'assets/oldmike-shell.js'),kind,json.dumps(facts),current],text=True).strip()

def header(current):
    current='' if current=='index.html' else current
    return '<a class="skip" href="#continut">Sari la conținut</a>'+brand_shell('header',current)

def footer():
    return brand_shell('footer')

META={
'index.html':('Old Mike Pub București | Burgeri, pizza și terasă','Old Mike Pub, pub și terasă pe Strada Matei Voievod 67, București. Burgeri, pizza și meciuri pe ecran. Rezervări: 0787 366 166.'),
'meniu.html':('Meniu Old Mike Pub | Burgeri, pizza, platouri și deserturi','Descoperă burgeri, Pizza Old Mike, platouri, grătar de vită și deserturi. Preparatele și prețurile actualizate sunt în Meniul Electronic Old Mike.'),
'galerie.html':('Galerie Old Mike Pub | Atmosfera și spațiile restaurantului','Explorează atmosfera și spațiile Old Mike: terasa exterioară, terasa acoperită, interiorul și barul de pe Matei Voievod 67, București.'),
'evenimente.html':('Evenimente Old Mike Pub | Meciuri și seri cu gașca','Vezi evenimentele publicate la Old Mike Pub București. Pentru detalii despre meciuri și rezervări, sună la 0787 366 166.'),
'contact.html':('Contact Old Mike Pub | Rezervări, program și adresă','Rezervări Old Mike Pub: 0787 366 166, office@oldmike.ro. Matei Voievod 67, București. L–V 10–24, S–D 12–24. Cereri pentru grupuri și evenimente.'),
'confidentialitate.html':('Confidențialitate | Old Mike Pub','Informații despre formularul de contact, harta opțională și datele din versiunea de evaluare Old Mike Pub.'),
'404.html':('Pagina nu a fost găsită | Old Mike Pub','Revino la Old Mike Pub: meniu, galerie, evenimente și rezervări.'),
'administrare-demo.html':('Administrare evenimente — demonstrație | Old Mike Pub','Demonstrație locală de încărcare și previzualizare postere, fără publicare.')}

def structured_data():
    return {'@context':'https://schema.org','@type':'Restaurant','@id':SITE['base_url']+'#restaurant','name':SITE['name'],'url':SITE['base_url'],'telephone':SITE['phone'],'email':SITE['email'],'address':{'@type':'PostalAddress','streetAddress':'Strada Matei Voievod 67','addressLocality':'București','addressCountry':'RO'},'menu':SITE['menu_url'],'image':SITE['base_url']+'assets/images/oldmike-share-v1.jpg','openingHoursSpecification':[{'@type':'OpeningHoursSpecification','dayOfWeek':[DAY_SCHEMA[d] for d in days],'opens':clock(start,True),'closes':clock(end,True)} for (start,end),days in hour_groups()]}

def head(filename):
    title,description=META[filename];url=SITE['base_url']+('' if filename=='index.html' else filename)
    # Indexing stays disabled in this milestone. Launch requires an explicit later review.
    robots='index,follow' if SITE['indexing_enabled'] and filename not in ['administrare-demo.html','404.html'] else 'noindex,nofollow'
    ld=json.dumps(structured_data(),ensure_ascii=False).replace('</','<\\/') if filename in ['index.html','contact.html'] else ''
    return f'''<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="{robots}"><meta name="theme-color" content="#100e0b"><title>{escape(title)}</title><meta name="description" content="{escape(description,quote=True)}"><link rel="canonical" href="{url}"><meta property="og:type" content="website"><meta property="og:locale" content="ro_RO"><meta property="og:site_name" content="Old Mike Pub"><meta property="og:title" content="{escape(title,quote=True)}"><meta property="og:description" content="{escape(description,quote=True)}"><meta property="og:url" content="{url}"><meta property="og:image" content="{SITE['base_url']}assets/images/oldmike-share-v1.jpg"><meta property="og:image:width" content="1200"><meta property="og:image:height" content="630"><meta property="og:image:alt" content="Old Mike Pub, burger cu bucătari miniaturali"><meta name="twitter:card" content="summary_large_image"><link rel="icon" type="image/png" sizes="32x32" href="assets/brand/favicon-32.png"><link rel="apple-touch-icon" sizes="180x180" href="assets/brand/favicon-180.png"><link rel="stylesheet" href="assets/oldmike-design.css?v=1"><link rel="stylesheet" href="assets/site.css?v=m212">{f'<script type="application/ld+json">{ld}</script>' if ld else ''}'''

def config_tag():
    public={k:SITE[k] for k in ['version','timezone','hours']}
    return '<script id="site-config" type="application/json">'+json.dumps(public,ensure_ascii=False)+'</script>'

def photo_markup(source,alt):
    path=ROOT/'assets/images'/source;w,h=Image.open(path).size
    stem=path.stem;variants=[]
    for size in [480,800]:
        candidate=path.with_name(f'{stem}-{size}.webp')
        if candidate.exists() and size<w:variants.append(f'assets/images/{candidate.name} {size}w')
    variants.append(f'assets/images/{source} {w}w')
    # Do not duplicate an existing800px master in srcset.
    variants=list(dict.fromkeys(variants))
    return f'<img src="assets/images/{source}" srcset="{", ".join(variants)}" sizes="(max-width:800px) calc(100vw - 40px), (max-width:1440px) 44vw, 620px" width="{w}" height="{h}" alt="{escape(alt,quote=True)}" loading="lazy" decoding="async" data-full="assets/images/{source}">'
