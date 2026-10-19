"""Generate responsive size variants; never change any existing illustration master."""
from pathlib import Path
from PIL import Image
import os
ROOT=Path(__file__).resolve().parents[1]
for stem in ['burger-single-chefs-v3','pizza-oldmike-chefs-v3','platouri-chefs-v1','vita-chefs-fries-v2','papanasi-chefs-v1','bere-menu-source-v2','hero-single-chefs-v4']:
 source=ROOT/'assets/images'/f'{stem}.webp'
 with Image.open(source)as im:
  for size in [480,800,1200]:
   if size>=im.width:continue
   dest=source.with_name(f'{stem}-{size}.webp')
   if dest.is_file():continue
   image=im.convert('RGB');image.thumbnail((size,size),Image.Resampling.LANCZOS);temp=dest.with_suffix('.tmp');image.save(temp,'WEBP',quality=83,method=6);os.replace(temp,dest)
print('Responsive variants prepared; original masters retained')
