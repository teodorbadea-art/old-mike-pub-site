<?php
/** OLD MIKE M3.3 — COMPLETE STANDALONE PHP. Public RPC + 600s cache + last-good fallback.
 * Images/CSS/JS are separate files. No runtime PHP includes or secret keys.
 * Standalone server-side menu; keep the external assets and writable cache beside this file.
 */
declare(strict_types=1);
namespace OldMikeMenu;

const ENDPOINT='https://kghkbfzggfughelmzchl.supabase.co/rest/v1/rpc/get_public_menu?select=group_code,category_name,name_ro,price,portion_display,description_ro,featured,serving_weight_g,kcal_100,fat_g_100,carbs_g_100,sugars_g_100,protein_g_100,salt_g_100';
const PUBLIC_KEY='sb_publishable_rI7-1Mnf22VHw3G3ZZOpcw_q84eQXUO';
const TTL=600;
function invalid_row_reason(mixed $row):?string{
    if(!is_array($row))return 'row_type';
    foreach(['group_code','category_name','name_ro']as $key)if(!isset($row[$key])||!is_string($row[$key])||trim($row[$key])===''||strlen($row[$key])>1000)return $key;
    if(!isset($row['price'])||!is_numeric($row['price'])||!is_finite((float)$row['price'])||(float)$row['price']<0||(float)$row['price']>1000000)return 'price';
    foreach(['description_ro','portion_display']as $key)if(isset($row[$key])&&!is_string($row[$key]))return $key;
    if(isset($row['featured'])&&!is_bool($row['featured']))return 'featured';
    return null;
}
/** Invalid products do not prevent the valid products from refreshing. */
function valid_rows(mixed $rows,string $source='menu'):?array{
    if(!is_array($rows)||!array_is_list($rows)||count($rows)>10000){error_log('[Old Mike menu] Invalid response envelope; source='.$source);return null;}
    $valid=[];
    foreach($rows as $index=>$row){$reason=invalid_row_reason($row);if($reason!==null){error_log('[Old Mike menu] Skipped invalid product; source='.$source.'; row='.$index.'; field='.$reason);continue;}$valid[]=$row;}
    // Distinguish a genuinely empty published menu from a wholly broken payload.
    if($rows&&!$valid){error_log('[Old Mike menu] No valid products in nonempty response; keeping last good cache');return null;}
    return $valid;
}
function fetch_live():?array {
    if(!function_exists('curl_init')){error_log('[Old Mike menu] cURL unavailable');return null;}
    $ch=curl_init(ENDPOINT);
    curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>json_encode(['restaurant_slug'=>'old-mike','requested_location_code'=>'loc-oldmike-matei']),CURLOPT_HTTPHEADER=>['apikey: '.PUBLIC_KEY,'Content-Type: application/json','Accept: application/json'],CURLOPT_RETURNTRANSFER=>true,CURLOPT_MAXFILESIZE=>2097152,CURLOPT_CONNECTTIMEOUT=>5,CURLOPT_TIMEOUT=>8,CURLOPT_FOLLOWLOCATION=>false,CURLOPT_SSL_VERIFYPEER=>true,CURLOPT_SSL_VERIFYHOST=>2]);
    $body=curl_exec($ch);$status=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);
    if(!is_string($body)||$status!==200||strlen($body)>2097152){error_log('[Old Mike menu] Public RPC request failed; HTTP='.$status);return null;}
    $rows=json_decode($body,true);
    return valid_rows($rows,'supabase');
}
function read_cache(string $file):?array {
    if(!is_file($file)||filesize($file)>3145728)return null;
    $value=json_decode((string)@file_get_contents($file),true);
    if(!is_array($value)||!isset($value['at'])||!is_numeric($value['at'])||(int)$value['at']>time()+60)return null;
    $rows=valid_rows($value['rows']??null,'cache');if($rows===null)return null;
    return ['at'=>(int)$value['at'],'rows'=>$rows];
}
/** Shared timestamp + independent lock: at most one stale warning per hour. */
function warn_old_cache(string $directory,int $at,int $now):void{
    if($now-$at<=3600)return;
    $logLock=@fopen($directory.'/menu.stale-warning','c+');
    if(!$logLock)return;
    if(!flock($logLock,LOCK_EX|LOCK_NB)){fclose($logLock);return;}
    try{
        $last=(int)stream_get_contents($logLock);
        if($last>0&&$now-$last<3600)return;
        rewind($logLock);
        if(!ftruncate($logLock,0)||fwrite($logLock,(string)$now)===false||!fflush($logLock))return;
        error_log('[Old Mike menu] Serving cache older than 60 minutes; age_minutes='.(int)floor(($now-$at)/60));
    }finally{flock($logLock,LOCK_UN);fclose($logLock);}
}
/** Cache is always last-known-good. Concurrent requests never clobber it. */
function menu(string $directory,?callable $fetcher=null,?int $now=null):array {
    $now??=time();$file=$directory.'/menu.json';$cached=read_cache($file);
    $result=static function(?array $value,string $source)use($now,$directory):array{
        if($value&&$source==='stale')warn_old_cache($directory,$value['at'],$now);
        return ['rows'=>$value['rows']??[],'at'=>$value['at']??0,'source'=>$source,'stale'=>$source!=='live'&&$source!=='cache'];
    };
    if($cached&&$now-$cached['at']<TTL)return $result($cached,'cache');
    if($cached&&is_file($directory.'/menu.retry')&&$now-(int)@file_get_contents($directory.'/menu.retry')<60)return $result($cached,'stale');
    if(!is_dir($directory))@mkdir($directory,0755,true);
    $lock=@fopen($directory.'/menu.lock','c');
    if($lock&&!flock($lock,LOCK_EX|LOCK_NB)){
        fclose($lock);
        // Another visitor is refreshing. Serve existing HTML immediately.
        return $result($cached,'stale');
    }
    try{
        $fresh=read_cache($file);
        if($fresh&&$now-$fresh['at']<TTL)return $result($fresh,'cache');
        try{$rows=($fetcher??__NAMESPACE__.'\\fetch_live')();}catch(\Throwable $e){$rows=null;}
        if($rows!==null)$rows=valid_rows($rows,'refresh');
        if($rows!==null){
            $value=['at'=>$now,'rows'=>$rows];
            $json=json_encode($value,JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_SUBSTITUTE);
            $temp=@tempnam($directory,'.menu-');
            if($temp!==false){
                if(@file_put_contents($temp,$json,LOCK_EX)!==false){@chmod($temp,0644);if(!@rename($temp,$file))@unlink($temp);}else @unlink($temp);
            }
            @unlink($directory.'/menu.retry');
            return $result($value,'live');
        }
        @file_put_contents($directory.'/menu.retry',(string)$now,LOCK_EX);
        return $result($cached,'stale');
    }finally{if($lock){flock($lock,LOCK_UN);fclose($lock);}}
}
function e(mixed $v):string{return htmlspecialchars((string)$v,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
function lower(string $s):string{return function_exists('mb_strtolower')?mb_strtolower($s,'UTF-8'):strtr(strtolower($s),['Ă'=>'ă','Â'=>'â','Î'=>'î','Ș'=>'ș','Ț'=>'ț','Ş'=>'ş','Ţ'=>'ţ']);}
function upper(string $s):string{return function_exists('mb_strtoupper')?mb_strtoupper($s,'UTF-8'):strtr(strtoupper($s),['ă'=>'Ă','â'=>'Â','î'=>'Î','ș'=>'Ș','ț'=>'Ț','ş'=>'Ş','ţ'=>'Ţ']);}
function ucfirst_ro(string $s):string{return preg_replace_callback('/^./us',static fn($m)=>upper($m[0]),$s)??$s;}
function name(string $s):string {
    $s=trim(preg_replace('/\s+/u',' ',$s)??$s);
    return preg_replace_callback('/\b[\p{Lu}][\p{Lu}\d-]+\b/u',static function($m){return in_array($m[0],['BBQ','IPA','XL','XXL','AOP','A.O.P','VS','VSOP','XO'],true)?$m[0]:ucfirst_ro(lower($m[0]));},$s)??$s;
}
function portion(?string $s):string {
    $s=trim((string)$s);
    $s=preg_replace('/(\d)\s*(?:gr|g)\b\.?/iu','$1 g',$s)??$s;
    $s=preg_replace('/(\d)\s*ml\b/iu','$1 ml',$s)??$s;
    return trim(preg_replace('/\s+/u',' ',$s)??$s);
}
function description(?string $s):string{return ucfirst_ro(trim((string)$s));}
function price(mixed $value):string{$n=(float)$value;return number_format($n,abs($n-round($n))<0.00001?0:2,',','.').' lei';}
/** Display only alcohol percentages actually supplied in the public text. */
function drink_details(array $row):array {
    $quantity=portion($row['portion_display']??'');$text=trim((string)($row['description_ro']??''));$abv='';
    // An explicit alcohol marker prevents interpreting fruit percentages as ABV.
    $pattern='/(?<![\d.,])(100|\d{1,2}(?:[.,]\d{1,2})?)\s*%\s*(?:alcool|alc\.?|vol\.?)(?!\p{L})/iu';
    if(preg_match($pattern,$text,$match)||preg_match($pattern,(string)$row['name_ro'],$match)){
        $n=(float)str_replace(',','.',$match[1]);
        if($n>=0&&$n<=100){$abv=rtrim(rtrim(number_format($n,2,',',''),'0'),',').'%';$text=trim(preg_replace($pattern,'',$text)??$text," \t\n\r\0\x0B·,;–-");}
    }
    return ['name'=>$abv!==''?trim(preg_replace($pattern,'',(string)$row['name_ro'])??$row['name_ro']):$row['name_ro'],'quantity'=>$quantity,'abv'=>$abv,'inline'=>implode(' · ',array_filter([$quantity,$abv],static fn($v)=>$v!=='')),'description'=>description($text)];
}

// CATEGORY → IMAGE + EDITORIAL CONFIGURATION (one place).
function configuration():array {
/** The ONLY category -> illustration/editorial mapping. New categories need no edits. */
return [
 'hero'=>['image'=>'meniu/hero-meniu','alt'=>'Masă Old Mike cu burger, pizza, platou cu grătare și cartofi prăjiți, halbe și bucătari în miniatură la lucru.'],
 'spirit_tabs'=>[
 ['id'=>'whisky','label'=>'Whisky','categories'=>['Whisky']],
 ['id'=>'rom','label'=>'Rom','categories'=>['Rom']],
 ['id'=>'vodka','label'=>'Vodka','categories'=>['Vodka']],
 ['id'=>'gin','label'=>'Gin','categories'=>['Lichior/vermuth/gin'],'match_name'=>'~\\bgin\\b|beefeater|beffeater~iu'],
 ['id'=>'tequila','label'=>'Tequila','categories'=>['Tequilla']],
 ['id'=>'coniac','label'=>'Coniac','categories'=>['Coniac']],
 ['id'=>'shoturi','label'=>'Shot-uri','categories'=>['Shots 25 Ml']],
 ['id'=>'lichior','label'=>'Lichior și vermut','categories'=>['Lichior/vermuth/gin'],'exclude_name'=>'~\\bgin\\b|beefeater|beffeater~iu'],
 ],
 'lunch'=>['title'=>'Marea Prânzeală','price'=>33.99,'parts'=>'Ciorbă + fel de bază + garnitură','hours'=>'Luni–vineri · 12:00–16:00'],
 'labels'=>[
 'Preparate Noi la Old Mike'=>'Noutăți la Old Mike','Starters'=>'Aperitive','Ciorbe si Supe'=>'Ciorbe și supe','Peste si Fructe de Mare'=>'Pește și fructe de mare','Mancare Gatita Old Mike'=>'Mâncare gătită','Preparate Pasare'=>'Preparate din pasăre','Preparate cu Vita si Oaie'=>'Vită și oaie','Preparate cu Porc'=>'Preparate din porc','Preparate Vegetariene'=>'Vegetarian','Garnituri si Salate Acompaniament'=>'Garnituri și salate de însoțire','Paine si Sosuri'=>'Pâine și sosuri','Desert'=>'Deserturi','Promotie Bere'=>'Promoție bere','Pachete Bauturi'=>'Pachete de băuturi','Cafea si Bauturi Calde'=>'Cafea și băuturi calde','Vinuri Albe'=>'Vinuri albe','Vinuri Roze'=>'Vinuri roze','Vinuri Rosii'=>'Vinuri roșii','Vinuri Spumante / Sampanie'=>'Vinuri spumante și șampanie','Tequilla'=>'Tequila','Soft Drinks'=>'Băuturi răcoritoare','Lichior/vermuth/gin'=>'Lichior, vermut și gin','Cocktail cu Alcool'=>'Cocktailuri cu alcool','Cocktail Fara Alcool'=>'Cocktailuri fără alcool','Shots 25 Ml'=>'Shot-uri','Long Drinks'=>'Long drinks'],
 'sections'=>[
 ['id'=>'burgeri','group'=>'Mancare','categories'=>['Burgeri'],'title'=>'Burgeri.','italic'=>'Serios de buni.','kicker'=>'De poftă','image'=>'meniu/cat-burgeri','alt'=>'Burger cu o singură bucată de carne și bucătari miniaturali la lucru.'],
 ['id'=>'pizza','group'=>'Mancare','categories'=>['Pizza'],'title'=>'Pizza.','italic'=>'Se împarte. Sau nu.','kicker'=>'De împărțit','image'=>'meniu/cat-pizza','alt'=>'Pizza Old Mike cu bucătari miniaturali, tăietor uriaș și o felie ridicată.'],
 ['id'=>'platouri','group'=>'Mancare','categories'=>['Platouri'],'title'=>'Platouri.','italic'=>'Mai cheamă un prieten.','kicker'=>'Pentru toată gașca','image'=>'meniu/cat-platouri','alt'=>'Platou cu grătare și cartofi prăjiți, pregătit de bucătari miniaturali.'],
 ['id'=>'noutati','group'=>'Mancare','categories'=>['Preparate Noi la Old Mike'],'title'=>'Noutăți.','italic'=>'Intră tare în meniu.','kicker'=>'Micul șantier de gusturi','image'=>'meniu/cat-tomahawk','alt'=>'Tomahawk de porc și cartofi din fotografia Old Mike, cu bucătari miniaturali și scripete.'],
 ['id'=>'aperitive','group'=>'Mancare','categories'=>['Starters'],'title'=>'Aperitive.','italic'=>'Începem cu dreptul.','kicker'=>'Înainte de toate','image'=>'meniu/cat-starters','alt'=>'Bruschete cu roșii și busuioc pe farfuria de referință Old Mike, cu bucătari miniaturali.'],
 ['id'=>'ciorbe','group'=>'Mancare','categories'=>['Ciorbe si Supe'],'title'=>'Ciorbe și supe.','italic'=>'Polonicul are planuri.','kicker'=>'Cald, cum trebuie','image'=>'meniu/cat-ciorbe','alt'=>'Ciorbă în vasul portocaliu Old Mike și un bucătar miniatural cu polonic mare.'],
 ['id'=>'salate','group'=>'Mancare','categories'=>['Salate'],'title'=>'Salate.','italic'=>'Frunzele fac spectacol.','kicker'=>'Verde la poftă','image'=>'meniu/cat-salate','alt'=>'Salata Old Mike în platoul original negru, cu bucătari miniaturali care aranjează frunzele.'],
 ['id'=>'paste','group'=>'Mancare','categories'=>['Paste'],'title'=>'Paste.','italic'=>'Ne țin la aceeași masă.','kicker'=>'Cu fir de poveste','image'=>'meniu/cat-paste','alt'=>'Paste lungi cu sos și parmezan în farfuria galbenă din fotografia de referință, cu bucătari miniaturali.'],
 ['id'=>'peste','group'=>'Mancare','categories'=>['Peste si Fructe de Mare'],'title'=>'Pește și fructe de mare.','italic'=>'Captură de poftă.','kicker'=>'Mare atenție la gust','image'=>'meniu/cat-peste','alt'=>'Dorada cu legume și midii pe farfuria pătrată din fotografia de referință, măsurată de un bucătar miniatural.'],
 ['id'=>'gatite','group'=>'Mancare','categories'=>['Mancare Gatita Old Mike'],'title'=>'Mâncare gătită.','italic'=>'Mai stăm o porție.','kicker'=>'Fără grabă','image'=>'meniu/cat-gatite','alt'=>'Ciolan cu fasole în farfuria pătrată albă Old Mike, cu bucătar miniatural care îl escaladează.'],
 ['id'=>'pasare','group'=>'Mancare','categories'=>['Preparate Pasare'],'title'=>'Pasăre.','italic'=>'Rumeneală cu echipă.','kicker'=>'Din ceaun, cu drag','image'=>'meniu/cat-pasare','alt'=>'Coquelet cu mămăligă și mujdei în vasul metalic original, cu bucătari miniaturali.'],
 ['id'=>'gratar','group'=>'Mancare','categories'=>['Preparate cu Vita si Oaie'],'title'=>'Vită și oaie.','italic'=>'Foc mic. Poftă mare.','kicker'=>'Rumenit cum trebuie','image'=>'meniu/cat-gratar','alt'=>'Grătar de vită și cartofi prăjiți cu bucătar miniatural și solniță uriașă.'],
 ['id'=>'porc','group'=>'Mancare','categories'=>['Preparate cu Porc'],'title'=>'Porc.','italic'=>'Glazură cu aplauze.','kicker'=>'De lins pe degete','image'=>'meniu/cat-porc','standalone'=>true,'alt'=>'Ilustrație cu coaste BBQ și cartofi prăjiți pe farfurie dreptunghiulară, glazurate de bucătari miniaturali.'],
 ['id'=>'deserturi','group'=>'Mancare','categories'=>['Desert'],'title'=>'Deserturi.','italic'=>'Mai este loc. Știm noi.','kicker'=>'Final cu zâmbet','image'=>'meniu/cat-deserturi','alt'=>'Papanași cu smântână și dulceață, în scena existentă cu bucătari miniaturali.'],
 ['id'=>'bere','group'=>'Bauturi','categories'=>['Bere','Promotie Bere'],'title'=>'Bere.','italic'=>'Rece. Restul mai vedem.','kicker'=>'La o vorbă','image'=>'meniu/cat-bere','alt'=>'Două halbe fără etichete, un bucătar miniatural la dozator și altul care transportă spuma pe o lopățică.'],
 ['id'=>'vinuri','group'=>'Bauturi','categories'=>['Vinuri Albe','Vinuri Roze','Vinuri Rosii','Vinuri Spumante / Sampanie'],'title'=>'Vinuri.','italic'=>'Poveștile curg.','kicker'=>'Cu un strop de răgaz','image'=>'meniu/cat-vinuri','alt'=>'Bucătar-somelier miniatural turnând vin dintr-o sticlă mare fără etichetă lizibilă.'],
 ['id'=>'cocktailuri','group'=>'Bauturi','categories'=>['Cocktail cu Alcool','Cocktail Fara Alcool','Long Drinks'],'title'=>'Cocktailuri.','italic'=>'Lime-ul pune umărul.','kicker'=>'Cu sau fără alcool','image'=>'meniu/cat-cocktailuri','alt'=>'Bucătari miniaturali decorează cocktailuri cu o felie uriașă de lime.'],
 ['id'=>'spirtoase','group'=>'Bauturi','categories'=>['Whisky','Rom','Vodka','Tequilla','Coniac','Lichior/vermuth/gin','Shots 25 Ml'],'title'=>'Spirtoase.','italic'=>'Gheața face echipă.','kicker'=>'Pentru gusturi hotărâte','image'=>'meniu/cat-spirtoase','alt'=>'Bucătar miniatural transportând un cub mare de gheață lângă pahare și sticle fără etichete lizibile.'],
 ['id'=>'cafea','group'=>'Bauturi','categories'=>['Cafea si Bauturi Calde'],'title'=>'Cafea.','italic'=>'Lucruri mari, în ceașcă.','kicker'=>'Pauza care ne adună','image'=>'meniu/cat-cafea','alt'=>'Bucătar miniatural desenând latte art într-o ceașcă uriașă.'],
 ['id'=>'racoritoare','group'=>'Bauturi','categories'=>['Soft Drinks'],'title'=>'Răcoritoare.','italic'=>'Stoarcem ziua de tot.','kicker'=>'Un strop de prospețime','image'=>'meniu/cat-racoritoare','alt'=>'Bucătari miniaturali storc o lămâie uriașă pentru limonadă.'],
 ]
];

}
function header_html():string {return <<<'OLDMIKE_HEADER_HTML'
<a class="skip" href="#continut">Sari la conținut</a><header class="om-header"><a class="om-brand" href="index.html" aria-label="Old Mike Pub · București, Acasă"><img src="assets/brand/oldmike-logo-146.webp" width="73" height="73" alt="Logo oficial Old Mike"><span class="om-brand-name">OLD MIKE<small>PUB · BUCUREȘTI</small></span></a><nav class="om-desktop-nav" aria-label="Navigare principală"><a href="index.html">Acasă</a><a href="meniu.html" aria-current="page">Meniu</a><a href="evenimente.html">Evenimente</a><a href="galerie.html">Galerie</a><a href="contact.html">Contact</a><a href="https://club.oldmike.ro/">Old Mike Club ↗</a></nav><a class="om-call" href="tel:+40787366166">Sună<span class="om-call-number"> · 0787 366 166</span></a><details class="om-mobile-nav"><summary aria-label="Meniu: navigarea Old Mike">Meniu ☰</summary><nav aria-label="Navigare Old Mike"><a href="index.html">Acasă</a><a href="meniu.html" aria-current="page">Meniu</a><a href="evenimente.html">Evenimente</a><a href="galerie.html">Galerie</a><a href="contact.html">Contact</a><a href="https://club.oldmike.ro/">Old Mike Club ↗</a></nav></details></header>
OLDMIKE_HEADER_HTML;
}
function footer_html():string {return <<<'OLDMIKE_FOOTER_HTML'
<footer class="om-footer"><div class="om-footer-grid"><div><strong>OLD MIKE PUB</strong><p>Pub și terasă în București.<br>Strada Matei Voievod 67, București</p><a href="https://www.google.com/maps/dir/?api=1&amp;destination=Strada+Matei+Voievod+67+Bucuresti" target="_blank" rel="noopener noreferrer">Indicații de orientare ↗</a></div><div><strong>Program</strong><p>Luni–vineri 10:00–24:00 · Sâmbătă–duminică 12:00–24:00</p><a class="om-call" href="tel:+40787366166">Sună · Rezervă</a><p><a href="tel:+40787366166">0787 366 166</a><br><a href="mailto:office@oldmike.ro">office@oldmike.ro</a></p></div><nav aria-label="Legături utile"><a href="index.html">Înapoi la site-ul Old Mike →</a><a href="meniu.html">Descoperă meniul pe site →</a><a href="https://club.oldmike.ro/">Old Mike Club ↗</a><a href="confidentialitate.html">Confidențialitate →</a></nav></div><p class="om-footer-note">Mâncare bună. Oameni faini.<br>OLD MIKE PUB SRL · RO40178199 · J2018016609401</p></footer>
OLDMIKE_FOOTER_HTML;
}

function label(string $category,array $config):string{return $config['labels'][$category]??name($category);}
function model(array $rows,array $config,string $root):array{
    $groups=[];foreach($rows as $r)$groups[$r['group_code']][$r['category_name']][]=$r;
    $ordered=[];foreach(['Mancare','Bauturi','Narghilea'] as $key)if(isset($groups[$key]))$ordered[$key]=$groups[$key];
    $groups=$ordered+array_diff_key($groups,$ordered);
    $sections=[];$used=[];
    foreach($config['sections'] as $s){
        $s['lists']=[];
        foreach($s['categories'] as $category)if(isset($groups[$s['group']][$category]))$s['lists'][$category]=$groups[$s['group']][$category];
        $s['has_image']=$s['image']&&is_file($root.'/assets/images/'.$s['image'].'.webp');
        if(!$s['lists']||(!$s['has_image']&&!($s['standalone']??false)))continue;
        foreach($s['lists'] as $category=>$list)$used[$s['group']][$category]=true;
        $sections[$s['group']][]=$s;
    }
    $compact=[];foreach($groups as $group=>$categories)foreach($categories as $category=>$list)if(!isset($used[$group][$category]))$compact[$group][$category]=$list;
    return ['groups'=>$groups,'sections'=>$sections,'compact'=>$compact];
}
function picture(string $stem,string $alt,string $root,bool $hero=false):string{
    $base='assets/images/'.$stem;$path=$base.'.webp';$size=@getimagesize($root.'/'.$path);if(!$size)return '';
    $sets=[];foreach(['avif','webp'] as $format){$items=[];foreach([480,800]as $width)$items[]=$base.'-'.$width.'.'.$format.' '.$width.'w';$items[]=$base.'.'.$format.' '.$size[0].'w';$sets[$format]=implode(', ',$items);}
    $sizes=$hero?'100vw':'(max-width:800px) 100vw, (max-width:1500px) 55vw, 800px';$mobile='';
    // Mobile master is optional: keep working 480/800 sources if it is absent.
    if($hero&&is_file($root.'/'.$base.'-mobile-800.webp')){
        $master=$root.'/'.$base.'-mobile.webp';
        $mobileSize=is_file($master)?@getimagesize($master):false;
        foreach(['avif','webp']as $format){$items=[];
            foreach([480,800]as $width)if(is_file($root.'/'.$base.'-mobile-'.$width.'.'.$format))$items[]=$base.'-mobile-'.$width.'.'.$format.' '.$width.'w';
            if($mobileSize&&is_file($root.'/'.$base.'-mobile.'.$format))$items[]=$base.'-mobile.'.$format.' '.$mobileSize[0].'w';
            if($items)$mobile.='<source media="(max-width:800px)" type="image/'.$format.'" srcset="'.e(implode(', ',$items)).'" sizes="100vw">';
        }
    }
    return '<picture>'.$mobile.'<source type="image/avif" srcset="'.e($sets['avif']).'" sizes="'.e($sizes).'"><img src="'.e($path).'" srcset="'.e($sets['webp']).'" sizes="'.e($sizes).'" width="'.$size[0].'" height="'.$size[1].'" alt="'.e($alt).'" '.($hero?'fetchpriority="high" loading="eager"':'loading="lazy"').' decoding="async"></picture>';
}
function nutrition_data(array $row):?array{
    $weight=$row['serving_weight_g']??null;$energy=$row['kcal_100']??null;
    if(!is_numeric($weight)||!is_finite((float)$weight)||(float)$weight<=0||!is_numeric($energy)||!is_finite((float)$energy)||(float)$energy<0)return null;
    return ['weight'=>(float)$weight,'kcal'=>(float)$energy*(float)$weight/100];
}
function nutrition_html(array $row):string{
    $n=nutrition_data($row);if(!$n)return '';
    $fields=['kcal_100'=>'Energie (kcal)','fat_g_100'=>'Grăsimi (g)','carbs_g_100'=>'Glucide (g)','sugars_g_100'=>'din care zaharuri (g)','protein_g_100'=>'Proteine (g)','salt_g_100'=>'Sare (g)'];
    $html='<details class="om-nutrition"><summary>Valori nutriționale<span class="sr-only"> · '.e(name($row['name_ro'])).'</span></summary><table><thead><tr><th scope="col">Valoare</th><th scope="col">Pe porție<br>'.e(number_format($n['weight'],0,',','.')).' g</th><th scope="col">100 g</th></tr></thead><tbody>';
    foreach($fields as $key=>$label){$v=$row[$key]??null;if(!is_numeric($v)||!is_finite((float)$v)||(float)$v<0)continue;$html.='<tr><th scope="row">'.e($label).'</th><td>'.e(number_format((float)$v*$n['weight']/100,1,',','.')).'</td><td>'.e(number_format((float)$v,1,',','.')).'</td></tr>';}
    return $html.'</tbody></table><p>Valori estimate, calculate din rețetă.</p></details>';
}
function rows_html(array $rows):string{
    $html='';foreach($rows as $r){
        $displayName=$r['name_ro'];$isDrink=$r['group_code']==='Bauturi';$portion=portion($r['portion_display']??'');$desc=description($r['description_ro']??'');$inline='';
        if($isDrink){$drink=drink_details($r);$inline=$drink['inline'];$desc=$drink['description'];$displayName=$drink['name'];}
        $name='<span class="om-dish-name">'.e(name($displayName)).'</span>';
        if($isDrink)$name='<span class="om-drink-heading">'.$name.($inline!==''?'<span class="om-drink-measure">'.e($inline).'</span>':'').'</span>';
        $meta=(!empty($r['featured'])?'<span class="om-recommended">Recomandat</span>':'').(!$isDrink&&$portion!==''?'<span class="om-dish-portion">'.e($portion).'</span>':'');
        $n=nutrition_data($r);if($n)$meta.='<span class="om-dish-energy">~'.e(number_format($n['kcal'],0,',','.')).' kcal / porție</span>';
        $html.='<li class="om-dish'.($isDrink?' om-dish-drink':'').'" data-menu-item><div class="om-dish-line">'.$name.'<span class="om-dish-dots" aria-hidden="true"></span><strong class="om-dish-price">'.e(price($r['price'])).'</strong></div>'.($meta!==''?'<div class="om-dish-meta">'.$meta.'</div>':'').($desc!==''?'<p class="om-dish-description">'.e($desc).'</p>':'').nutrition_html($r).'</li>';
    }return $html;
}
/** Presentation grouping only; published category and product data stay intact. */
function spirit_panels(array $lists,array $config):array{
    $panels=[];$used=[];
    foreach($config['spirit_tabs']as $tab){$rows=[];
        foreach($tab['categories']as $category)foreach($lists[$category]??[]as $index=>$row){
            if(isset($used[$category][$index]))continue;
            if(isset($tab['match_name'])&&!preg_match($tab['match_name'],$row['name_ro']))continue;
            if(isset($tab['exclude_name'])&&preg_match($tab['exclude_name'],$row['name_ro']))continue;
            $used[$category][$index]=true;$rows[]=$row;
        }
        if($rows)$panels[]=['id'=>'spirit-'.$tab['id'],'label'=>$tab['label'],'rows'=>$rows];
    }
    foreach($lists as $category=>$rows){$remaining=[];foreach($rows as $index=>$row)if(!isset($used[$category][$index]))$remaining[]=$row;
        if($remaining)$panels[]=['id'=>'spirit-'.substr(hash('sha256',$category),0,10),'label'=>label($category,$config),'rows'=>$remaining];
    }return $panels;
}
function spirits_html(array $lists,array $config):string{
    $panels=spirit_panels($lists,$config);$html='<div class="om-spirits" data-spirit-tabs><nav class="om-spirit-tabs" aria-label="Tipuri de spirtoase">';
    foreach($panels as $p)$html.='<a id="tab-'.e($p['id']).'" href="#'.e($p['id']).'">'.e($p['label']).'</a>';
    $html.='</nav>';
    foreach($panels as $p)$html.='<section class="om-spirit-panel" id="'.e($p['id']).'" aria-labelledby="title-'.e($p['id']).'"><h3 id="title-'.e($p['id']).'" class="om-subcategory-title">'.e($p['label']).'</h3>'.dishes($p['rows'],$p['label']).'</section>';
    return $html.'</div>';
}
function dishes(array $rows,string $category):string{
    $first=count($rows)<=8?count($rows):6;
    $html='<ul class="om-dishes">'.rows_html(array_slice($rows,0,$first)).'</ul>';
    if($first<count($rows))$html.='<details class="om-more"><summary>Încă '.(count($rows)-$first).' '.(in_array($rows[0]['group_code']??'',['Bauturi','Narghilea'],true)?'opțiuni':'preparate').'<span class="sr-only"> · '.e($category).'</span><span aria-hidden="true">＋</span></summary><ul class="om-dishes">'.rows_html(array_slice($rows,$first)).'</ul></details>';
    return $html;
}
function item_schema(array $row):array{
    $displayName=name($row['name_ro']);$desc=description($row['description_ro']??'');
    if($row['group_code']==='Bauturi'){$details=drink_details($row);$displayName=name($details['name']);$desc=implode(' · ',array_filter([$details['inline'],$details['description']],static fn($v)=>$v!==''));}
    return ['@type'=>'MenuItem','name'=>$displayName,'description'=>$desc,'offers'=>['@type'=>'Offer','price'=>number_format((float)$row['price'],2,'.',''),'priceCurrency'=>'RON']];
}
function json_ld(array $model):array{
    $sections=[];$config=configuration();
    foreach($model['groups'] as $group=>$categories)foreach($categories as $category=>$rows){
        $sections[]=['@type'=>'MenuSection','name'=>label($category,$config),'hasMenuItem'=>array_map(__NAMESPACE__.'\\item_schema',$rows)];
    }
    return ['@context'=>'https://schema.org','@type'=>'Restaurant','@id'=>'https://garden.oldmike.ro/#restaurant','name'=>'Old Mike Pub','url'=>'https://garden.oldmike.ro/','telephone'=>'+40787366166','address'=>['@type'=>'PostalAddress','streetAddress'=>'Strada Matei Voievod 67','addressLocality'=>'București','addressCountry'=>'RO'],'hasMenu'=>['@type'=>'Menu','@id'=>'https://garden.oldmike.ro/meniu.html#meniu','name'=>'Meniu Old Mike Pub','url'=>'https://garden.oldmike.ro/meniu.html','hasMenuSection'=>$sections]];
}
function render(array $menu,string $root,bool $preview=false):string{
    $config=configuration();$m=model($menu['rows'],$config,$root);$lunch=$config['lunch'];
    $groupNames=['Mancare'=>'Mâncare','Bauturi'=>'Băuturi','Narghilea'=>'Narghilea'];
    $groupIds=['Mancare'=>'mancare','Bauturi'=>'bauturi','Narghilea'=>'narghilea'];
    $total=count($menu['rows']);$description='Meniul Old Mike Pub: preparate și băuturi, cu prețuri actualizate. Strada Matei Voievod 67, București. Rezervări: 0787 366 166.';
    ob_start();
    ?>
<!doctype html><html lang="ro"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover"><meta name="robots" content="<?= $preview?'noindex,nofollow':'index,follow' ?>"><meta name="theme-color" content="#100e0b"><title>Meniu Old Mike Pub București | Preparate și prețuri actualizate</title><meta name="description" content="<?= e($description) ?>"><link rel="canonical" href="https://garden.oldmike.ro/meniu.html"><meta property="og:type" content="website"><meta property="og:locale" content="ro_RO"><meta property="og:site_name" content="Old Mike Pub"><meta property="og:title" content="Meniu Old Mike Pub · Gusturi pentru seri bune"><meta property="og:description" content="<?= e($description) ?>"><meta property="og:url" content="https://garden.oldmike.ro/meniu.html"><meta property="og:image" content="https://garden.oldmike.ro/assets/images/meniu/hero-meniu.webp"><meta property="og:image:width" content="1536"><meta property="og:image:height" content="864"><meta name="twitter:card" content="summary_large_image"><link rel="icon" href="assets/brand/favicon-32.png"><link rel="stylesheet" href="assets/oldmike-design.css?v=m331"><link rel="stylesheet" href="assets/site.css?v=m331"><script type="application/ld+json"><?= json_encode(json_ld($m),JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_INVALID_UTF8_SUBSTITUTE) ?></script></head>
<body class="menu-editorial">
<?php if($preview): ?><aside class="om-preview-notice">Previzualizare · <?= $total ?> produse și prețuri reale<?= $menu['at']?' · '.e((new \DateTimeImmutable('@'.$menu['at']))->setTimezone(new \DateTimeZone('Europe/Bucharest'))->format('d.m.Y H:i')):'' ?> · Site-ul oficial nu a fost modificat.</aside><?php endif ?>
<?= header_html() ?>
<main id="continut">
<section class="om-menu-hero" aria-labelledby="menu-title"><figure class="om-menu-hero-image"><?= picture($config['hero']['image'],$config['hero']['alt'],$root,true) ?></figure><div class="om-menu-hero-copy"><p class="eyebrow">La ce ți-e poftă?</p><h1 id="menu-title">Gusturi pentru<br><em>seri bune.</em></h1><p>Burgeri, pizza, un prânz bun.<br>Și încă un motiv să mai stai.</p><div class="om-hero-actions"><a class="button" href="#pranz">Vezi meniul de prânz <span aria-hidden="true">↓</span></a><a class="button ghost" href="tel:+40787366166">Sună · Rezervă</a></div></div></section>
<div class="om-menu-wrap">
<section class="om-lunch" id="pranz" aria-labelledby="lunch-title"><div><p class="om-kicker">Pauza cu gust bun</p><h2 id="lunch-title">Marea <em>Prânzeală.</em></h2><p class="om-lunch-parts"><?= e($lunch['parts']) ?></p><p class="om-lunch-hours"><?= e($lunch['hours']) ?></p></div><div class="om-lunch-price"><strong><?= e(str_replace(' lei','',price($lunch['price']))) ?><small>lei</small></strong><a class="om-text-link" href="https://meniu.oldmike.ro/m/old-mike/loc-oldmike-matei">Deschide meniul electronic <span aria-hidden="true">↗</span></a></div></section>
</div>
<nav class="om-menu-nav" aria-label="Categoriile meniului"><div class="om-menu-nav-layout"><div class="om-menu-group-switch" aria-label="Grupele meniului">
<?php foreach($m['groups'] as $g=>$categories):$gid=$groupIds[$g]??('grup-'.substr(hash('sha256',$g),0,8)); ?><a href="#<?= e($gid) ?>" data-menu-group="<?= e($gid) ?>"><?= e($groupNames[$g]??name($g)) ?></a><?php endforeach ?>
</div><div class="om-menu-nav-inner">
<?php foreach($m['groups'] as $g=>$categories):$gid=$groupIds[$g]??('grup-'.substr(hash('sha256',$g),0,8)); ?><div class="om-menu-category-links" data-menu-nav-group="<?= e($gid) ?>"><?php foreach($m['sections'][$g]??[] as $s): ?><a href="#<?= e($s['id']) ?>"><?= e(rtrim($s['title'],'.')) ?></a><?php endforeach ?><?php if(!empty($m['compact'][$g])): ?><a href="#altele-<?= e($gid) ?>">Și altele<span class="sr-only"> · <?= e($groupNames[$g]??$g) ?></span></a><?php endif ?></div><?php endforeach ?>
</div></div></nav>
<div class="om-menu-wrap om-menu-body">
<?php if(!$menu['rows']): ?><section class="om-menu-fallback"><h2>Pofta rămâne.<br><em>Ne auzim la telefon.</em></h2><p>Pentru meniul complet și detalii, deschide meniul electronic sau sună-ne.</p><a class="button" href="https://meniu.oldmike.ro/m/old-mike/loc-oldmike-matei">Deschide meniul electronic ↗</a></section><?php endif ?>
<?php $number=0;foreach($m['groups'] as $g=>$categories):$gid=$groupIds[$g]??('grup-'.substr(hash('sha256',$g),0,8)); ?>
<section class="om-menu-group" id="<?= e($gid) ?>" aria-labelledby="title-<?= e($gid) ?>"><div class="om-group-heading"><span class="om-kicker">Pe îndelete</span><h2 id="title-<?= e($gid) ?>"><?= e($groupNames[$g]??name($g)) ?><span aria-hidden="true">.</span></h2></div>
<?php foreach($m['sections'][$g]??[] as $s):$number++; ?>
<section class="om-category <?= !$s['has_image']?'om-category-text':($number%2===0?'om-category-reverse':'') ?>" id="<?= e($s['id']) ?>" aria-labelledby="title-<?= e($s['id']) ?>"><?php if($s['has_image']): ?><figure class="om-category-figure"><div class="om-category-image"><?= picture($s['image'],$s['alt'],$root) ?></div></figure><?php endif ?><div class="om-category-copy"><p class="om-kicker"><?= str_pad((string)$number,2,'0',STR_PAD_LEFT) ?> / <?= e($s['kicker']) ?></p><h2 id="title-<?= e($s['id']) ?>"><?= e($s['title']) ?><br><em><?= e($s['italic']) ?></em></h2>
<?php if($s['id']==='spirtoase'): ?><?= spirits_html($s['lists'],$config) ?><?php else: ?><?php foreach($s['lists'] as $c=>$rows):if(count($s['lists'])>1&&lower(rtrim(label($c,$config),'. '))!==lower(rtrim($s['title'],'. '))): ?><h3 class="om-subcategory-title"><?= e(label($c,$config)) ?></h3><?php endif ?><?= dishes($rows,label($c,$config)) ?><?php endforeach ?><?php endif ?></div></section>
<?php endforeach ?>
<?php if(!empty($m['compact'][$g])): ?><section class="om-compact" id="altele-<?= e($gid) ?>" aria-labelledby="title-altele-<?= e($gid) ?>"><div class="om-compact-heading"><p class="om-kicker">Și încă ceva bun</p><h2 id="title-altele-<?= e($gid) ?>">Și <em>altele.</em></h2></div><div class="om-compact-grid">
<?php foreach($m['compact'][$g] as $c=>$rows): ?><section class="om-compact-category" aria-labelledby="compact-<?= substr(hash('sha256',$g.'/'.$c),0,12) ?>"><h3 id="compact-<?= substr(hash('sha256',$g.'/'.$c),0,12) ?>"><?= e(label($c,$config)) ?></h3><?= dishes($rows,label($c,$config)) ?></section><?php endforeach ?>
</div></section><?php endif ?></section><?php endforeach ?>
<section class="om-menu-end" aria-label="Mai multe informații despre meniu"><p class="om-kicker">Ne vedem la masă</p><h2>Ai ales?<br><em>Noi am pus masa.</em></h2><a class="button" href="https://meniu.oldmike.ro/m/old-mike/loc-oldmike-matei">Deschide meniul electronic <span aria-hidden="true">↗</span></a><p>Poze de produs și detalii în meniul electronic.</p><p class="om-menu-fineprint">Prețurile se actualizează automat din meniul restaurantului.<br>Informații despre alergeni: întreabă ospătarul.<br>Imagine ilustrativă: scenele de categorie sunt ilustrative; fotografiile produselor sunt în meniul electronic.</p></section>
</div></main>
<?= footer_html() ?>
<script src="assets/site.js" defer></script><script src="assets/menu-editorial.js" defer></script>
</body></html>
<?php return (string)ob_get_clean();
}

// Runtime entry: public RPC, never a browser-side Supabase request.
ini_set('display_errors','0');
if(PHP_SAPI!=='cli'&&!ini_get('zlib.output_compression')&&function_exists('ob_gzhandler'))ob_start('ob_gzhandler');
$preview=PHP_SAPI==='cli'&&getenv('OM_MENU_SNAPSHOT')!==false;
if($preview){
    $loaded=read_cache((string)getenv('OM_MENU_SNAPSHOT'));
    if(!$loaded){fwrite(STDERR,"Snapshot invalid\n");exit(1);}
    $loaded+=['source'=>'snapshot','stale'=>false];
}else{$loaded=menu(__DIR__.'/cache');}
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: public, max-age=0, must-revalidate');
header('X-Content-Type-Options: nosniff');
echo render($loaded,__DIR__,$preview);
