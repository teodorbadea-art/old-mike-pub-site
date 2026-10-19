"""Prepare the approved M2.2 site for cPanel production, phone/email reservations only."""
from pathlib import Path
import shutil,re,json,hashlib,zipfile
raise SystemExit("RETIRED: root oldmike.ro is the group site and must remain untouched. Configure a separate approved target first.")
ROOT=Path(__file__).resolve().parents[1]
OUT=ROOT/'output/production-m26';PUBLIC=OUT/'public'
if OUT.exists():shutil.rmtree(OUT)
shutil.copytree(ROOT/'output/cpanel-m21/public',PUBLIC)
(PUBLIC/'.htaccess').unlink();(PUBLIC/'.user.ini').unlink()
shutil.copy2(ROOT/'server/staging.user.ini',PUBLIC/'admin/.user.ini')
app=PUBLIC/'lib/app.php';app.write_text(app.read_text().replace("'/home/oldmikero/oldmike-site-private/config.php'","'/home/oldmikero/oldmike-site-private/production-config.php'"))
for p in PUBLIC.glob('*.html'):
 s=p.read_text()
 if p.name!='404.html':s=s.replace('content="noindex,nofollow"','content="index,follow"')
 if p.name=='contact.html':
  s,n=re.subn(r'<form class="group-form".*?</form>','<div class="group-contact"><h3>Pregătim împreună evenimentul tău.</h3><p>Pentru grupuri și evenimente, sună la <a href="tel:+40787366166">0787 366 166</a> sau scrie la <a href="mailto:office@oldmike.ro">office@oldmike.ro</a>.</p><p>Spune-ne data, numărul aproximativ de persoane și ce ai în plan. Echipa Old Mike îți confirmă disponibilitatea.</p><a class="button" href="tel:+40787366166">Sună · Rezervă</a></div>',s,flags=re.S);assert n==1
  s=s.replace('povestește-ne ce pregătești în formularul de mai jos.','contactează-ne telefonic sau prin email.').replace('Cerere pentru grupuri și evenimente','Grupuri și evenimente')
 if p.name=='confidentialitate.html':
  s,n=re.subn(r'<main.*?</main>','<main id="continut" class="page-main text-page"><div class="eyebrow">Informații despre date</div><h1>Confidențialitate<br><em>și contact.</em></h1><section><h2>Rezervări și contact</h2><p>Pentru rezervări și solicitări, sună la <a href="tel:+40787366166">0787 366 166</a> sau scrie la <a href="mailto:office@oldmike.ro">office@oldmike.ro</a>. Site-ul nu are un formular public activ de colectare a solicitărilor și nu te înscrie automat la mesaje promoționale.</p></section><section><h2>Harta și legăturile externe</h2><p>Harta Google se încarcă numai când alegi să o deschizi. Meniul Electronic și Old Mike Club sunt servicii separate, accesibile prin legăturile din site.</p></section><section><h2>Fișiere și sesiune</h2><p>Fonturile și imaginile sunt găzduite împreună cu site-ul. Site-ul de prezentare nu include instrumente de analiză a vizitatorilor sau publicitate. Zona de administrare folosește o sesiune necesară conectării echipei.</p></section></main>',s,flags=re.S);assert n==1
 p.write_text(s)
(PUBLIC/'index.php').write_text('<?php\ndeclare(strict_types=1);\nheader("Content-Type: text/html; charset=utf-8");\nreadfile(__DIR__."/index.html");\n')
shutil.copy2(ROOT/'sitemap.xml',PUBLIC/'sitemap.xml')
(PUBLIC/'robots.txt').write_text('User-agent: *\nDisallow: /admin/\nDisallow: /api/\nDisallow: /lib/\nDisallow: /oldmike-test/\nSitemap: https://oldmike.ro/sitemap.xml\n')
manifest={p.relative_to(PUBLIC).as_posix():hashlib.sha256(p.read_bytes()).hexdigest() for p in sorted(PUBLIC.rglob('*')) if p.is_file()}
(OUT/'MANIFEST.json').write_text(json.dumps(manifest,indent=2)+'\n')
shutil.copy2(ROOT/'server/install-production.php',OUT/'install-production.php')
with zipfile.ZipFile(ROOT/'output/old-mike-production-m26.zip','w',zipfile.ZIP_DEFLATED) as z:
 for p in sorted(OUT.rglob('*')):
  if p.is_file():z.write(p,p.relative_to(OUT))
assert not (PUBLIC/'.htaccess').exists() and not (PUBLIC/'.user.ini').exists()
assert 'group-request-form' not in (PUBLIC/'contact.html').read_text()
assert 'production-config.php' in app.read_text()
assert not any('meniu.oldmike.ro/' in p or 'club.oldmike.ro/' in p for p in manifest)
print('PASS production candidate:',len(manifest),'files, no public request form; root php.ini/.htaccess and other apps excluded.')
