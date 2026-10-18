"""Shared embedding helpers for review files, including responsive srcsets/fonts."""
from pathlib import Path
import base64,mimetypes,re
ROOT=Path(__file__).resolve().parents[1]

def data_uri(path):
    return 'data:'+(mimetypes.guess_type(str(path))[0] or 'application/octet-stream')+';base64,'+base64.b64encode(Path(path).read_bytes()).decode()

def inline_css(css):
    def replace(m):
        relative=m[1].strip('\"\'')
        if relative.startswith(('data:','https:','http:')):return m[0]
        return 'url("'+data_uri(ROOT/'assets'/relative)+'")'
    return re.sub(r'url\(([^)]+)\)',replace,css)

def inline_html(text,embed):
    def replace(m):
        attr,value=m[1],m[2]
        if attr=='srcset':
            value=re.sub(r'assets/[A-Za-z0-9_./-]+',lambda x:embed(x[0]),value)
        elif value.startswith('assets/'):
            value=embed(value)
        return attr+'="'+value+'"'
    return re.sub(r'\b(src|srcset|href|data-full)="([^\"]*)"',replace,text)


def write_review(path,content):
    path=Path(path)
    temporary=path.with_suffix(path.suffix+'.tmp')
    temporary.write_text(content,encoding='utf-8')
    temporary.replace(path)
