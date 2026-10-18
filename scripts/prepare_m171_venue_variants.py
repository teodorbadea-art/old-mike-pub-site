"""Technical responsive derivatives of approved existing cinematic venue assets."""
from pathlib import Path
from io import BytesIO
from PIL import Image
OUT=Path(__file__).resolve().parents[1]/'assets/images'
for key in ['terrace-wide-v2','covered-terrace-v2','interior-wide-v2','bar-wide-v2']:
    with Image.open(OUT/(key+'.webp')) as source:
        image=source.convert('RGB')
        for width in [480,800]:
            size=(width,round(image.height*width/image.width))
            buffer=BytesIO()
            image.resize(size,Image.Resampling.LANCZOS).save(buffer,format='WEBP',quality=80,method=6)
            target=OUT/f'{key}-{width}.webp'
            temporary=target.with_suffix('.webp.tmp')
            temporary.write_bytes(buffer.getvalue())
            temporary.replace(target)
print('Prepared eight responsive derivatives; generated masters unchanged.')
