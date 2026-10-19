from pathlib import Path
import importlib.util,tempfile,json,hashlib
ROOT=Path(__file__).resolve().parents[1]
s=importlib.util.spec_from_file_location('brand_update',ROOT/'server/update-brand-m212.py');m=importlib.util.module_from_spec(s);s.loader.exec_module(m)
with tempfile.TemporaryDirectory() as t:
 base=Path(t).resolve();root=base/'public_html/garden.oldmike.ro';private=base/'private';payload=private/'brand';root.mkdir(parents=True);payload.mkdir(parents=True)
 group=root.parent/'index.html';group.write_text('GROUP UNCHANGED');(root/'.htaccess').write_text('# BEGIN OLDMIKE GARDEN M29\nDirectoryIndex index.html index.php\n')
 old={};manifest={}
 for name in m.NAMES:
  target=root/name;target.parent.mkdir(parents=True,exist_ok=True);source=payload/name;source.parent.mkdir(parents=True,exist_ok=True);source.write_bytes(('NEW '+name).encode());manifest[name]=m.digest(source.read_bytes())
  if 'manrope' not in name.lower():target.write_bytes(('OLD '+name).encode());old[name]=target.read_bytes()
 (payload/'MANIFEST.json').write_text(json.dumps(manifest))
 assert m.run(root,private,payload)['state']=='READY';assert (root/'index.html').read_bytes()==old['index.html']
 r=m.run(root,private,payload,True);assert r['state']=='APPLIED' and r['group_unchanged'] and r['access_rules_unchanged'];backup=Path(r['backup'])
 for name,data in old.items():assert (backup/name).read_bytes()==data
 assert m.run(root,private,payload,True)['state']=='UNCHANGED'
 (payload/'index.html').write_text('BAD CHECKSUM')
 try:m.run(root,private,payload,True);raise AssertionError('corrupt input accepted')
 except RuntimeError:pass
 assert (root/'index.html').read_text()=='NEW index.html';assert group.read_text()=='GROUP UNCHANGED'
 assert (root/'.htaccess').read_text().endswith('index.html index.php\n')
print('PASS Garden updater: read-only preflight, apply, verified backups, idempotence, corrupt-input rejection, group/access rules preserved.')
