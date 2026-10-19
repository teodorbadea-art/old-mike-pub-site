"""CLI-only Garden brand update; exact allowlist, backup, hash checks and rollback."""
from pathlib import Path
import argparse,datetime,hashlib,json,os,tempfile
NAMES=('index.html','meniu.html','evenimente.html','galerie.html','contact.html','confidentialitate.html','404.html','assets/site.css','assets/oldmike-design.css','assets/fonts/manrope-ro-v1.woff','assets/fonts/OFL-Manrope.txt')
def require(ok,message):
    if not ok:raise RuntimeError('STOP '+message)
def digest(data):return hashlib.sha256(data).hexdigest()
def safe_file(root,name):
    path=root/name
    require(path.resolve().is_relative_to(root.resolve()),'path outside target '+name)
    for part in [path,*list(path.parents)[:len(Path(name).parts)-1]]:require(not part.is_symlink(),'symlink '+name)
    require(not path.exists() or path.is_file(),'unexpected file '+name)
    return path

def run(root,private,payload,apply=False):
    require(root.is_dir() and private.is_dir() and payload.is_dir(),'missing directories')
    require(root.resolve()==root and private.resolve()==private and payload.resolve()==payload,'symlinked root')
    require(not private.is_relative_to(root) and not payload.is_relative_to(root),'payload/backup must be outside Garden')
    access=(root/'.htaccess').read_bytes()
    require(b'# BEGIN OLDMIKE GARDEN M29' in access,'Garden marker missing')
    group=root.parent/'index.html';group_before=group.read_bytes() if group.is_file() else None
    manifest=json.loads((payload/'MANIFEST.json').read_text())
    require(set(manifest)==set(NAMES),'unexpected manifest scope')
    before={};after={}
    for name in NAMES:
        source=safe_file(payload,name);require(source.is_file(),'missing source '+name)
        data=source.read_bytes();require(digest(data)==manifest[name],'source checksum '+name)
        target=safe_file(root,name);require(target.parent.is_dir(),'missing target parent '+name)
        before[name]=target.read_bytes() if target.exists() else None;after[name]=data
    changed=[name for name in NAMES if before[name]!=after[name]]
    if not apply or not changed:return {'state':'READY' if changed else 'UNCHANGED','files':len(changed)}
    backup=private/('garden-brand-m212-'+datetime.datetime.now(datetime.timezone.utc).strftime('%Y%m%dT%H%M%SZ')+'-'+os.urandom(3).hex());backup.mkdir(mode=0o700)
    (backup/'before.json').write_text(json.dumps({n:digest(before[n]) if before[n] is not None else None for n in changed},indent=2))
    os.chmod(backup/'before.json',0o600)
    for name in changed:
        if before[name] is None:continue
        dest=backup/name;dest.parent.mkdir(parents=True,exist_ok=True)
        with dest.open('xb') as f:os.chmod(dest,0o600);f.write(before[name])
        require(dest.read_bytes()==before[name],'backup verification '+name)
    installed=[]
    try:
        for name in changed:
            dest=safe_file(root,name)
            require((dest.read_bytes() if dest.exists() else None)==before[name],'concurrent change '+name)
            fd,temp=tempfile.mkstemp(prefix='.m212-',dir=dest.parent)
            try:
                with os.fdopen(fd,'wb') as f:f.write(after[name])
                os.chmod(temp,dest.stat().st_mode&0o777 if dest.exists() else 0o644)
                require(digest(Path(temp).read_bytes())==manifest[name],'temporary checksum '+name)
                require((dest.read_bytes() if dest.exists() else None)==before[name],'concurrent change '+name)
                os.replace(temp,dest);installed.append(name)
            finally:
                if Path(temp).exists():Path(temp).unlink()
        require((root/'.htaccess').read_bytes()==access,'Garden access rules changed')
        require((group.read_bytes() if group.is_file() else None)==group_before,'group page changed')
        for name in NAMES:require(digest((root/name).read_bytes())==manifest[name],'installed checksum '+name)
    except BaseException:
        for name in reversed(installed):
            dest=root/name
            if dest.read_bytes()!=after[name]:continue
            if before[name] is None:dest.unlink()
            else:dest.write_bytes(before[name])
        raise
    return {'state':'APPLIED','files':len(changed),'backup':str(backup),'group_unchanged':True,'access_rules_unchanged':True}

if __name__=='__main__':
    p=argparse.ArgumentParser();p.add_argument('--apply',action='store_true');p.add_argument('payload',type=Path);a=p.parse_args()
    root=Path('/home/oldmikero/public_html/garden.oldmike.ro');private=Path('/home/oldmikero/oldmike-site-private')
    require(a.payload.resolve().is_relative_to(private),'payload must be staged in private directory')
    print(json.dumps(run(root,private,a.payload.resolve(),a.apply)))
