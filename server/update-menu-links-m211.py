"""CLI-only, isolated Garden URL replacement. No application or data changes."""
from pathlib import Path
import datetime, hashlib, os, sys

ROOT = Path('/home/oldmikero/public_html/garden.oldmike.ro')
PRIVATE = Path('/home/oldmikero/oldmike-site-private')
OLD = b'https://meniu.oldmike.ro/'
NEW = OLD + b'm/old-mike/loc-oldmike-matei'
NAMES = ('index.html', 'meniu.html', 'contact.html')

def require(ok, message):
    if not ok:
        raise SystemExit('STOP ' + message)

require(len(sys.argv) == 2 and sys.argv[1] in ('--check', '--apply'), 'use --check or --apply')
require(ROOT.resolve() == ROOT and PRIVATE.resolve() == PRIVATE, 'unexpected directories')
require(ROOT.is_dir() and PRIVATE.is_dir(), 'missing directories')
require('# BEGIN OLDMIKE GARDEN M29' in (ROOT / '.htaccess').read_text(), 'missing Garden marker')
before, after = {}, {}
for name in NAMES:
    path = ROOT / name
    require(path.is_file() and not path.is_symlink(), 'unexpected file ' + name)
    data = path.read_bytes()
    # Exclude already-correct URLs from the legacy count; reruns are safe.
    legacy = data.replace(NEW, b'').count(OLD)
    require(legacy == 0 or legacy == (8 if name == 'meniu.html' else 1), 'unexpected URL count ' + name)
    before[name] = data
    after[name] = data.replace(NEW, OLD).replace(OLD, NEW)
require(all(NEW in data for data in after.values()), 'missing public menu URLs')
changed = [name for name in NAMES if before[name] != after[name]]
if sys.argv[1] == '--check' or not changed:
    print('READY' if changed else 'UNCHANGED', 'Garden public menu links:', ', '.join(changed))
    raise SystemExit(0)
backup = PRIVATE / ('garden-menu-m211-' + datetime.datetime.now(datetime.timezone.utc).strftime('%Y%m%dT%H%M%SZ') + '-' + os.urandom(3).hex())
backup.mkdir(mode=0o700)
for name in changed:
    dest = backup / name
    with dest.open('xb') as stream:
        os.chmod(dest, 0o600)
        stream.write(before[name])
    require(dest.read_bytes() == before[name], 'backup verification ' + name)
installed = []
try:
    for name in changed:
        target = ROOT / name
        require(target.read_bytes() == before[name], 'concurrent change ' + name)
        temp = ROOT / ('.m211-' + os.urandom(8).hex())
        try:
            with temp.open('xb') as stream:
                os.chmod(temp, target.stat().st_mode & 0o777)
                stream.write(after[name])
            require(temp.read_bytes() == after[name], 'temporary verification ' + name)
            require(target.read_bytes() == before[name], 'concurrent change ' + name)
            os.replace(temp, target)
            installed.append(name)
            require(target.read_bytes() == after[name], 'installed verification ' + name)
        finally:
            if temp.exists():
                temp.unlink()
except BaseException:
    for name in reversed(installed):
        target = ROOT / name
        if target.read_bytes() == after[name]:
            target.write_bytes(before[name])
    raise
print('OK M2.11 Garden links updated; backup:', backup.name)
for name in changed:
    print(name, hashlib.sha256(after[name]).hexdigest())
