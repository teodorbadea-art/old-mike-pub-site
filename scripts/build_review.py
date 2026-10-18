"""Make one offline review file from the five real HTML pages and local assets."""
from pathlib import Path
import base64
import json
import mimetypes
import re

ROOT = Path(__file__).resolve().parents[1]
OUT = ROOT / 'output'
OUT.mkdir(exist_ok=True)
css = (ROOT / 'assets/site.css').read_text()
js = (ROOT / 'assets/site.js').read_text()
pages = {}
asset_data = {}
for f in ['index.html','meniu.html','evenimente.html','galerie.html','contact.html','administrare-demo.html']:
    text = (ROOT/f).read_text()
    text = text.replace('<link rel="stylesheet" href="assets/site.css">', '<style>'+css+'</style>')
    text = text.replace('<script src="assets/site.js" defer></script>', '<script>'+js+'</script>')
    for path in set(re.findall(r'(?:src|srcset|href)="(assets/[^\"]+)"',text)):
        if path not in asset_data:
            asset_data[path]='data:'+mimetypes.guess_type(path)[0]+';base64,'+base64.b64encode((ROOT/path).read_bytes()).decode()
        text=text.replace('"'+path+'"','"'+asset_data[path]+'"')
    intercept = '''<script>document.querySelectorAll('a[href]').forEach(a=>a.addEventListener('click',e=>{const h=a.getAttribute('href');if(/^(index|meniu|evenimente|galerie|contact)\\.html(?:#.*)?$/.test(h)){e.preventDefault();parent.postMessage({oldMikePage:h},'*')}}));</script>'''
    pages[f]=text.replace('</body>',intercept+'</body>')
shell='''<!doctype html><html lang="ro"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Propunere vizuală Old Mike Pub · M1.1</title><style>*{box-sizing:border-box}body{margin:0;background:#292722;color:#f7f1e7;font:13px Arial}header{min-height:68px;display:flex;align-items:center;gap:15px;padding:12px 20px;border-bottom:1px solid #ffffff2a;flex-wrap:wrap}header strong{margin-right:auto}select,button{background:#12100c;color:#e9c58c;border:1px solid #7e6b4a;padding:9px;min-height:36px}button[aria-pressed=true]{background:#dfb574;color:#17110b}main{height:calc(100svh - 68px);overflow:auto;display:flex;justify-content:center;align-items:flex-start}iframe{display:block;border:0;width:100%;height:100%;background:#100e0b}iframe.phone{width:390px;min-width:320px;max-width:100%;height:844px;max-height:100%;box-shadow:0 0 0 1px #ffffff33}small{color:#bfb5a6}button:focus-visible,select:focus-visible{outline:2px solid white}</style></head><body><header><strong>OLD MIKE · Propunere vizuală M1.1</strong><label for="page">Pagina</label><select id="page"><option value="index.html">Acasă</option><option value="meniu.html">Meniu</option><option value="evenimente.html">Evenimente</option><option value="galerie.html">Galerie</option><option value="contact.html">Contact</option><option value="administrare-demo.html">Administrare (demo)</option></select><button id="desktop" aria-pressed="true">Desktop</button><button id="phone" aria-pressed="false">Telefon</button><small>Concept local · fără publicare</small></header><main><iframe id="view" title="Site Old Mike Pub — propunere vizuală"></iframe></main><script>const pages=__PAGES__;const frame=document.getElementById('view'),select=document.getElementById('page');function show(p){const [file,anchor]=p.split('#');if(!pages[file])return;select.value=file;frame.srcdoc=pages[file];frame.onload=()=>{if(anchor)frame.contentDocument.getElementById(anchor)?.scrollIntoView()};}select.addEventListener('change',()=>show(select.value));window.addEventListener('message',e=>{if(e.source===frame.contentWindow&&e.data&&typeof e.data.oldMikePage==='string'&&pages[e.data.oldMikePage.split('#')[0]])show(e.data.oldMikePage)});for(const mode of ['desktop','phone'])document.getElementById(mode).addEventListener('click',()=>{frame.classList.toggle('phone',mode==='phone');for(const m of ['desktop','phone'])document.getElementById(m).setAttribute('aria-pressed',String(m===mode))});show('index.html');</script></body></html>'''
payload=json.dumps(pages,ensure_ascii=False).replace('</script>','<\\/script>')
target=OUT/'old-mike-propunere-vizuala-m1.html'
target.write_text(shell.replace('__PAGES__',payload),encoding='utf-8')
print(str(target))
print('Bytes:',target.stat().st_size)
