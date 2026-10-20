<?php
declare(strict_types=1);
namespace OldMikeMenu;
const ENDPOINT='https://kghkbfzggfughelmzchl.supabase.co/rest/v1/rpc/get_public_menu?select=group_code,category_name,name_ro,price,portion_display,description_ro,featured';
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
/** Cache is always last-known-good. Concurrent requests never clobber it. */
function menu(string $directory,?callable $fetcher=null,?int $now=null):array {
    $now??=time();$file=$directory.'/menu.json';$cached=read_cache($file);
    $result=static function(?array $value,string $source)use($now):array{
        if($value&&$source==='stale'&&$now-$value['at']>3600)error_log('[Old Mike menu] Serving cache older than60minutes; age_minutes='.(int)floor(($now-$value['at'])/60));
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
