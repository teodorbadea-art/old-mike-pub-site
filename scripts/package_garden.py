"""Production package for the explicitly approved, isolated Garden domain only."""
from pathlib import Path
import hashlib,json,re,shutil,zipfile

ROOT=Path(__file__).resolve().parents[1]
OUT=ROOT/'output/garden-m29'
if OUT.exists(): shutil.rmtree(OUT)
PUBLIC=OUT/'public'
shutil.copytree(ROOT/'output/cpanel-m21/public',PUBLIC)
for name in ['.htaccess','.user.ini']: (PUBLIC/name).unlink()
shutil.copy2(ROOT/'server/staging.user.ini',PUBLIC/'admin/.user.ini')
app=PUBLIC/'lib/app.php'
app.write_text(app.read_text().replace("'/home/oldmikero/oldmike-site-private/config.php'","'/home/oldmikero/oldmike-site-private/garden-config.php'"))
for p in PUBLIC.glob('*.html'):
    s=p.read_text().replace('https://oldmike.ro/','https://garden.oldmike.ro/')
    if p.name!='404.html': s=s.replace('content="noindex,nofollow"','content="index,follow"')
    if p.name=='contact.html':
        s,n=re.subn(r'<form class="group-form".*?</form>','<div class="group-contact"><h3>Pregătim împreună evenimentul tău.</h3><p>Pentru grupuri și evenimente, sună la <a href="tel:+40787366166">0787 366 166</a> sau scrie la <a href="mailto:office@oldmike.ro">office@oldmike.ro</a>.</p><p>Spune-ne data, numărul aproximativ de persoane și ce ai în plan. Echipa Old Mike îți confirmă disponibilitatea.</p><a class="button" href="tel:+40787366166">Sună · Rezervă</a></div>',s,flags=re.S)
        assert n==1
        s=s.replace('povestește-ne ce pregătești în formularul de mai jos.','contactează-ne telefonic sau prin email.').replace('Cerere pentru grupuri și evenimente','Grupuri și evenimente')
    if p.name=='confidentialitate.html':
        s,n=re.subn(r'<section><h2>Formularul pentru grupuri</h2>.*?</section>','<section><h2>Contact pentru grupuri</h2><p>Pentru rezervări și solicitări, sună la <a href="tel:+40787366166">0787 366 166</a> sau scrie la <a href="mailto:office@oldmike.ro">office@oldmike.ro</a>. Site-ul nu are un formular public activ de colectare a solicitărilor și nu te înscrie automat la mesaje promoționale.</p></section>',s,flags=re.S)
        assert n==1
    p.write_text(s)
(PUBLIC/'sitemap.xml').write_text((ROOT/'sitemap.xml').read_text().replace('https://oldmike.ro/','https://garden.oldmike.ro/'))
(PUBLIC/'robots.txt').write_text('User-agent: *\nDisallow: /admin/\nDisallow: /api/\nDisallow: /lib/\nSitemap: https://garden.oldmike.ro/sitemap.xml\n')
rules='''# BEGIN OLDMIKE GARDEN M29
Options -Indexes
DirectoryIndex index.html
ErrorDocument 404 /404.html
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{HTTP_HOST} ^garden\\.oldmike\\.ro$ [NC]
RewriteCond %{HTTPS} !=on
RewriteRule ^ https://garden.oldmike.ro%{REQUEST_URI} [R=301,L]
</IfModule>
<IfModule mod_headers.c>
Header always set X-Content-Type-Options "nosniff"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>
# END OLDMIKE GARDEN M29
'''
(OUT/'garden.rules').write_text(rules)
for name in ['backend.php','drafts.php']:
    target=OUT/'checks/tests/php'/name;target.parent.mkdir(parents=True,exist_ok=True);shutil.copy2(ROOT/'tests/php'/name,target)
for name in ['app.php','drafts.php']:
    target=OUT/'checks/server/lib'/name;target.parent.mkdir(parents=True,exist_ok=True);shutil.copy2(ROOT/'server/lib'/name,target)
shutil.copy2(ROOT/'server/install-garden.php',OUT/'install-garden.php')
manifest={p.relative_to(PUBLIC).as_posix():hashlib.sha256(p.read_bytes()).hexdigest() for p in sorted(PUBLIC.rglob('*')) if p.is_file()}
(OUT/'MANIFEST.json').write_text(json.dumps(manifest,indent=2)+'\n')
assert not (PUBLIC/'index.php').exists()
assert 'group-request-form' not in (PUBLIC/'contact.html').read_text()
assert 'poster-sport-permanent' in (PUBLIC/'evenimente.html').read_text()
assert (PUBLIC/'lib/.htaccess').read_text().strip()=='Require all denied'
assert not any('https://oldmike.ro/' in p.read_text() for p in PUBLIC.glob('*.html'))
archive=ROOT/'output/old-mike-garden-m29.zip'
with zipfile.ZipFile(archive,'w',zipfile.ZIP_DEFLATED) as z:
    for p in sorted(OUT.rglob('*')):
        if p.is_file(): z.write(p,p.relative_to(OUT))
print('PASS Garden package:',len(manifest),'public files;',archive.stat().st_size,'bytes; phone/email reservations; isolated config; no secrets.')
