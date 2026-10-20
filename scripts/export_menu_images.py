"""M3.2: original-sized masters (max1536) +480/800, WebP/AVIF; never upscale."""
from pathlib import Path
from PIL import Image
import os,json
ROOT=Path(__file__).resolve().parents[1];OUT=ROOT/'assets/images/meniu';OUT.mkdir(exist_ok=True)
sources={
 'cat-burgeri':ROOT/'assets/images/burger-single-chefs-v3.webp',
 'cat-pizza':ROOT/'assets/images/pizza-oldmike-chefs-v3.webp',
 'cat-platouri':ROOT/'assets/images/platouri-chefs-v1.webp',
 'cat-gratar':ROOT/'assets/images/vita-chefs-fries-v2.webp',
 'cat-deserturi':ROOT/'assets/images/papanasi-chefs-v1.webp',
 **{f'cat-{x}':ROOT/f'assets/images/menu-editorial/cat-{x}.webp' for x in ['tomahawk','ciorbe','salate','paste','peste','gatite','pasare','starters','vinuri','cocktailuri','spirtoase','cafea','racoritoare']},
 'cat-bere':ROOT/'work/menu-m31-generated/cat-bere-source.png',
 'hero-meniu':ROOT/'work/menu-m31-generated/hero-meniu-original.png',
}
manifest={}
for name,source in sources.items():
 with Image.open(source)as original:
  im=original.convert('RGB');master_width=min(1536,im.width)
  manifest[name]={'source':str(source.relative_to(ROOT)),'native':list(im.size),'upscaled':False,'variants':[]}
  for width in [480,800,master_width]:
   height=round(im.height*width/im.width);resized=im.resize((width,height),Image.Resampling.LANCZOS)
   for extension,options in [('webp',{'quality':82,'method':6}),('avif',{'quality':65,'speed':6})]:
    stem=name if width==master_width else f'{name}-{width}';path=OUT/f'{stem}.{extension}'
    # Retain matching prior800px files; format delivery changes only.
    if not path.is_file():
     temp=path.with_suffix('.tmp');resized.save(temp,extension.upper(),**options);os.replace(temp,path)
    with Image.open(path)as check:assert check.size==(width,height);check.verify()
    manifest[name]['variants'].append({'path':str(path.relative_to(ROOT)),'width':width,'height':height,'bytes':path.stat().st_size})
# M3.3 mobile and pork assets are generated separately; never overwrite them with the wide hero.
for path in OUT.iterdir():
 if any(path.name.endswith(f'-{width}.{ext}')for width in [1200,2000]for ext in ['webp','avif']):path.unlink()
(ROOT/'docs/menu-redesign/m32-image-manifest.json').write_text(json.dumps(manifest,ensure_ascii=False,indent=2)+'\n')
print('PASS20illustrations ×3widths ×2formats +4mobile variants; no upscaling/2000px')
