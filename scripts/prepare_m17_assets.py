"""Versioned M1.7 derivatives; no generative alteration of venue originals."""
from pathlib import Path
from PIL import Image,ImageEnhance,ImageOps
import json,hashlib
from io import BytesIO
ROOT=Path(__file__).resolve().parents[1]
BASE=ROOT.parent
OUT=ROOT/'assets/images'
GENERATED=BASE/'generated_images'
def save_asset(im,path,**options):
    path=Path(path);buffer=BytesIO();fmt={'.webp':'WEBP','.png':'PNG','.jpg':'JPEG'}[path.suffix]
    im.save(buffer,format=fmt,**options);data=buffer.getvalue()
    if not data:raise ValueError('Empty image output: '+str(path))
    temp=path.with_suffix(path.suffix+'.tmp');temp.write_bytes(data);temp.replace(path)

for key,source in [('pizza-oldmike-chefs-v3','exec-93745ebe-ea4f-4265-a024-05b8b44b3ed4.png'),('burger-single-chefs-v3','exec-36815102-7e1b-4ab6-a00b-e072934ed296.png'),('vita-chefs-fries-v2','exec-644ad801-c03b-4c74-9ae6-d93ec4567a90.png')]:
 im=Image.open(GENERATED/source).convert('RGB')
 save_asset(im.resize((1200,800),Image.Resampling.LANCZOS),OUT/(key+'.webp'),quality=84,method=6)
 if key.startswith('burger'):
  save_asset(im.crop((512,0,1536,1024)).resize((900,900),Image.Resampling.LANCZOS),OUT/(key+'-mobile.webp'),quality=85,method=6)
# Authorized conservative photo correction: crops + tone/color only, no object edits.
photos=[('terrace-photo-v3','IMG_6692.jpeg',(0,340,1152,1204)),('covered-terrace-photo-v3','IMG_6685.jpeg',(0,280,1000,1030)),('interior-photo-v3','IMG_6690.jpeg',(0,360,1152,1224)),('bar-photo-v3','IMG_6689.jpeg',(0,500,800,1100))]
records=[]
for key,source,box in photos:
 src=BASE/'audit-materials/Meniu Electronic'/source
 im=ImageOps.exif_transpose(Image.open(src)).convert('RGB').crop(box)
 im=ImageOps.autocontrast(im,cutoff=.35)
 im=ImageEnhance.Color(im).enhance(.94)
 im=ImageEnhance.Contrast(im).enhance(1.06)
 r,g,b=im.split();r=r.point(lambda x:min(255,round(x*1.025)));b=b.point(lambda x:round(x*.96));im=Image.merge('RGB',(r,g,b))
 im=ImageEnhance.Sharpness(im).enhance(1.08)
 save_asset(im,OUT/(key+'.webp'),quality=85,method=6)
 records.append({'asset':key+'.webp','source':source,'source_sha256':hashlib.sha256(src.read_bytes()).hexdigest(),'crop':box,'size':im.size,'processing':'autocontrast cutoff0.35,color0.94,contrast1.06,red1.025,blue0.96,sharpness1.08; no generated pixels/objects/signage'})
(ROOT/'docs/design/VENUE_PHOTO_CORRECTIONS_M17.json').write_text(json.dumps(records,ensure_ascii=False,indent=2)+'\n')
# Responsive variants for all gallery sources; original large assets retained for lightbox.
keys=[k for k,_,_ in photos]+['atmosfera-'+s+'-v1' for s in ['meci','gasca','terasa','impreuna']]
for key in keys:
 im=Image.open(OUT/(key+'.webp')).convert('RGB')
 for w in [480,800]:
  if w>im.width:continue
  h=round(im.height*w/im.width)
  save_asset(im.resize((w,h),Image.Resampling.LANCZOS),OUT/(key+f'-{w}.webp'),quality=80,method=6)
# Small derivative favicons preserve the source identity; official header logo untouched.
brand=Image.open(ROOT/'assets/brand/oldmike-logo.png').convert('RGBA')
for size in [32,180]:save_asset(brand.resize((size,size),Image.Resampling.LANCZOS),ROOT/f'assets/brand/favicon-{size}.png')
# Social image: square hero fitted in 1200x630, with official logo, no generated text.
hero=Image.open(OUT/'hero-single-chefs-v4-mobile.webp').convert('RGB').resize((630,630),Image.Resampling.LANCZOS)
social=Image.new('RGB',(1200,630),'#100e0b');social.paste(hero,(570,0))
logo=brand.resize((240,240),Image.Resampling.LANCZOS);social.paste(logo,(165,190),logo)
save_asset(social,OUT/'oldmike-share-v1.jpg',quality=88,optimize=True)
print('Created M1.7 food, conservative venue derivatives, responsive photos, icons and social image.')
