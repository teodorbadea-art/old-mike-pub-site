"""M3.1: full-frame 800/1200/2000 WebP+AVIF derivatives; existing masters unchanged."""
from pathlib import Path
from PIL import Image
import os,json
ROOT=Path(__file__).resolve().parents[1];OUT=ROOT/'assets/images/meniu';OUT.mkdir(exist_ok=True)
sources={
 'cat-burgeri':'burger-single-chefs-v3','cat-pizza':'pizza-oldmike-chefs-v3',
 'cat-platouri':'platouri-chefs-v1','cat-gratar':'vita-chefs-fries-v2','cat-deserturi':'papanasi-chefs-v1',
 **{f'cat-{x}':f'menu-editorial/cat-{x}' for x in ['tomahawk','ciorbe','salate','paste','peste','gatite','pasare','starters','vinuri','cocktailuri','spirtoase','cafea','racoritoare']}}
manifest={}
for name,stem in sources.items():
 source=ROOT/'assets/images'/f'{stem}.webp'
 with Image.open(source)as original:
  im=original.convert('RGB');manifest[name]={'source':str(source.relative_to(ROOT)),'native':list(im.size),'upscaled_2000':im.width<2000,'variants':[]}
  for width in [800,1200,2000]:
   height=round(im.height*width/im.width);resized=im.resize((width,height),Image.Resampling.LANCZOS)
   for extension,options in [('webp',{'quality':82,'method':6}),('avif',{'quality':65,'speed':6})]:
    path=OUT/f'{name}-{width}.{extension}'
    if not path.is_file():
     temp=path.with_suffix('.tmp');resized.save(temp,extension.upper(),**options);os.replace(temp,path)
    with Image.open(path)as check:assert check.size==(width,height);check.verify()
    manifest[name]['variants'].append({'path':str(path.relative_to(ROOT)),'width':width,'height':height,'bytes':path.stat().st_size})
for name,sourcefile in [('cat-bere','cat-bere-source.png'),('hero-meniu','hero-meniu-original.png')]:
 source=ROOT/'work/menu-m31-generated'/sourcefile
 with Image.open(source)as im:manifest[name]={'source':str(source.relative_to(ROOT)),'native':list(im.size),'upscaled_2000':im.width<2000,'variants':[]}
 for width in [800,1200,2000]:
  for extension in ['webp','avif']:
   path=OUT/f'{name}-{width}.{extension}'
   with Image.open(path)as check:
    assert check.width==width;size=check.size;check.verify()
   manifest[name]['variants'].append({'path':str(path.relative_to(ROOT)),'width':size[0],'height':size[1],'bytes':path.stat().st_size})
(ROOT/'docs/menu-redesign/m31-image-manifest.json').write_text(json.dumps(manifest,ensure_ascii=False,indent=2)+'\n')
print('PASS',len(manifest),'illustrations × 3 widths × 2 formats; originals retained')
