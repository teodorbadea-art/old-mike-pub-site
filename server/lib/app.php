<?php
declare(strict_types=1);
namespace OldMike;

final class Problem extends \RuntimeException {
    public function __construct(public int $status, string $message) { parent::__construct($message); }
}
function esc(mixed $value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function config(): array {
    $file = getenv('OLDMIKE_SITE_CONFIG') ?: '/home/oldmikero/oldmike-site-private/config.php';
    if (!is_file($file)) throw new Problem(503, 'Serviciul online nu este încă activ. Sună la 0787 366 166.');
    $c = require $file;
    if (!is_array($c) || !($c['enabled'] ?? false)) throw new Problem(503, 'Serviciul online nu este încă activ. Sună la 0787 366 166.');
    $private = realpath($c['private_dir'] ?? '');
    $public = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
    if (!$private || !$public || $private === $public || str_starts_with($private, $public . DIRECTORY_SEPARATOR)) throw new Problem(503, 'Configurația serviciului trebuie verificată.');
    if (!preg_match('~^https://[a-z0-9.-]+(?::[0-9]+)?$~D', $c['origin'] ?? '') || !preg_match('~^(?:/[a-zA-Z0-9_-]+)*$~D', $c['base_path'] ?? '')) throw new Problem(503, 'Configurația adresei trebuie verificată.');
    if (strlen($c['rate_secret'] ?? '') < 32) throw new Problem(503, 'Configurația serviciului trebuie verificată.');
    $c['private_dir'] = $private;
    return $c;
}
function path(array $c, string $suffix): string { return $c['base_path'] . '/' . ltrim($suffix, '/'); }
function headers(bool $json = false): void {
    header('Cache-Control: no-store'); header('X-Content-Type-Options: nosniff'); header('X-Frame-Options: DENY');
    header('Referrer-Policy: same-origin'); header('X-Robots-Tag: noindex, nofollow');
    header("Content-Security-Policy: default-src 'none'; style-src 'self' 'unsafe-inline'; img-src 'self'; form-action 'self'; base-uri 'none'; frame-ancestors 'none'");
    if ($json) header('Content-Type: application/json; charset=utf-8');
}
function session(array $c): void {
    if (PHP_SAPI !== 'cli' && ($_SERVER['HTTPS'] ?? '') !== 'on') throw new Problem(400, 'Folosește conexiunea HTTPS.');
    ini_set('session.use_strict_mode', '1'); ini_set('session.use_only_cookies', '1'); ini_set('session.use_trans_sid', '0');
    session_name('OLDMIKESITE');
    session_set_cookie_params(['lifetime'=>0, 'path'=>path($c,''), 'secure'=>true, 'httponly'=>true, 'samesite'=>'Strict']);
    if (!session_start()) throw new Problem(503, 'Sesiunea nu poate fi pornită.');
    if (!isset($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
function csrf(): void {
    $supplied = $_POST['csrf'] ?? '';
    if (!is_string($supplied) || !hash_equals($_SESSION['csrf'] ?? '', $supplied)) throw new Problem(403, 'Pagina a expirat. Reîncarcă și încearcă din nou.');
}
function admin(): void {
    if (empty($_SESSION['admin_at']) || time() - $_SESSION['admin_at'] > 1800 || time() - ($_SESSION['admin_login'] ?? 0) > 28800) { unset($_SESSION['admin_at']); throw new Problem(401, 'Conectează-te pentru administrare.'); }
    $_SESSION['admin_at'] = time();
}
function initial_state(): array { return ['events'=>[], 'requests'=>[], 'rates'=>[], 'audit'=>[]]; }
function state(array $c, callable $fn): mixed {
    // A separate stable lock protects the atomic rename, including concurrent readers.
    $lock = fopen($c['private_dir'].'/state.lock', 'c');
    if (!$lock || !flock($lock, LOCK_EX)) throw new Problem(503, 'Serviciul este ocupat. Încearcă din nou.');
    chmod($c['private_dir'].'/state.lock',0600);
    $temp = null;
    try {
        $file = $c['private_dir'].'/state.json';
        $s = is_file($file) ? json_decode((string)file_get_contents($file),true,512,JSON_THROW_ON_ERROR) : initial_state();
        foreach (['events','requests','rates','audit'] as $key) if (!isset($s[$key]) || !is_array($s[$key])) throw new \RuntimeException('Invalid state');
        $result = $fn($s);
        $json = json_encode($s, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        $temp = tempnam($c['private_dir'], 'state-');
        if (!$temp || file_put_contents($temp,$json) !== strlen($json)) throw new \RuntimeException('Storage failed');
        chmod($temp,0600);
        if (!rename($temp,$file)) throw new \RuntimeException('Atomic write failed');
        $temp = null;
        return $result;
    } finally { if ($temp && is_file($temp)) unlink($temp); flock($lock,LOCK_UN); fclose($lock); }
}
function audit(array &$s, string $action, string $id): void {
    $s['audit'][] = ['at'=>gmdate('c'),'action'=>$action,'id'=>$id];
    $s['audit'] = array_slice($s['audit'],-1000); // No names, phones, email bodies or IP addresses.
}
function rate(array $c, string $bucket, int $limit, int $window): void {
    $now = time(); $key = hash_hmac('sha256',$bucket.'|'.($_SERVER['REMOTE_ADDR'] ?? 'unknown'),$c['rate_secret']);
    $allowed = state($c,function (&$s) use ($now,$key,$limit,$window) {
        $s['rates'] = array_filter($s['rates'],fn($r)=>$r['until']>$now);
        if (!isset($s['rates'][$key])) {
            if (count($s['rates']) >= 5000) return false;
            $s['rates'][$key] = ['count'=>0,'until'=>$now+$window];
        }
        $s['rates'][$key]['count']++;
        return $s['rates'][$key]['count'] <= $limit;
    });
    if (!$allowed) throw new Problem(429, 'Prea multe încercări. Revino puțin mai târziu.');
}
function text(array $v, string $key, int $max, bool $required = false): string {
    $value = $v[$key] ?? '';
    if (!is_string($value) || !preg_match('//u',$value)) throw new Problem(422, 'Verifică datele completate.');
    $value = trim($value);
    if (strlen($value)>$max*4 || preg_match_all('/./us',$value)>$max || ($required && $value==='') || preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/',$value)) throw new Problem(422, 'Verifică lungimea și conținutul câmpurilor.');
    return $value;
}
function date_local(string $value, bool $time = false): \DateTimeImmutable {
    $fmt = $time ? 'Y-m-d\TH:i' : 'Y-m-d';
    $dt = \DateTimeImmutable::createFromFormat('!'.$fmt,$value,new \DateTimeZone('Europe/Bucharest'));
    if (!$dt || $dt->format($fmt)!==$value) throw new Problem(422,'Verifică data și ora.');
    return $dt;
}
function request_values(array $v, ?string $today = null): array {
    $r=[];
    foreach (['name'=>100,'phone'=>30,'email'=>160,'date'=>10,'time'=>5,'occasion'=>50,'area'=>50,'details'=>2000] as $k=>$max) $r[$k]=text($v,$k,$max,in_array($k,['name','phone','date']));
    if (strlen(preg_replace('/\D/','',$r['phone']))<7 || preg_match('/[\r\n]/',$r['phone'])) throw new Problem(422,'Verifică numărul de telefon.');
    if ($r['email']!=='' && !filter_var($r['email'],FILTER_VALIDATE_EMAIL)) throw new Problem(422,'Verifică adresa de email.');
    date_local($r['date']); $today ??= (new \DateTimeImmutable('now',new \DateTimeZone('Europe/Bucharest')))->format('Y-m-d');
    if ($r['date']<$today) throw new Problem(422,'Alege data de astăzi sau o dată viitoare.');
    if ($r['time']!=='' && !preg_match('/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/D',$r['time'])) throw new Problem(422,'Verifică ora.');
    $guests = $v['guests'] ?? '';
    if (!is_scalar($guests) || !preg_match('/^[0-9]{1,4}$/D',(string)$guests) || (int)$guests<2 || (int)$guests>1000) throw new Problem(422,'Verifică numărul de persoane.');
    $r['guests']=(int)$guests;
    if (!in_array($r['occasion'],['Ieșire în grup','Aniversare','Eveniment de firmă','Altă ocazie'],true) || !in_array($r['area'],['Fără preferință','Terasa exterioară','Terasa acoperită','Interior'],true)) throw new Problem(422,'Alege una dintre opțiunile disponibile.');
    if (($v['contact_permission'] ?? '')!=='on') throw new Problem(422,'Bifează acordul de a fi contactat pentru solicitare.');
    return $r;
}
function save_request(array $c, array $values, string $key): array {
    return state($c,function (&$s) use ($values,$key) {
        foreach ($s['requests'] as $r) if (hash_equals($r['submission_key'],$key)) return ['id'=>$r['id'],'duplicate'=>true];
        if (count($s['requests'])>=2000) throw new Problem(503,'Nu putem înregistra cererea acum. Sună la 0787 366 166.');
        $id=bin2hex(random_bytes(12));
        $s['requests'][$id]=$values+['id'=>$id,'created_at'=>gmdate('c'),'status'=>'new','email_state'=>'pending','submission_key'=>$key,'contact_permission_at'=>gmdate('c')];
        audit($s,'request_created',$id); return ['id'=>$id,'duplicate'=>false];
    });
}
function event_values(array $v): array {
    $title=text($v,'title',120,true);$category=text($v,'category',10,true);
    if (!in_array($category,['sport','other'],true)) throw new Problem(422,'Alege categoria.');
    $start=date_local(text($v,'starts',16,true),true);$end=date_local(text($v,'ends',16,true),true);
    if ($end<=$start) throw new Problem(422,'Încheierea trebuie să fie după începere.');
    return ['title'=>$title,'category'=>$category,'starts'=>$start->format(DATE_ATOM),'ends'=>$end->format(DATE_ATOM)];
}
function poster(array $file, array $c): string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE)!==UPLOAD_ERR_OK || ($file['size'] ?? 0)>1900000 || !is_uploaded_file($file['tmp_name'] ?? '')) throw new Problem(422,'Alege un poster JPG, PNG sau WebP de maximum 1,8 MB.');
    if (!function_exists('imagecreatefromstring')) throw new Problem(503,'Prelucrarea imaginilor trebuie activată pe găzduire.');
    $info=@getimagesize($file['tmp_name']);
    if (!$info || !in_array($info['mime'],['image/jpeg','image/png','image/webp'],true) || $info[0]<100 || $info[1]<100 || $info[0]>6000 || $info[1]>6000 || $info[0]*$info[1]>12000000) throw new Problem(422,'Posterul trebuie să fie o imagine validă, între100px și12megapixeli.');
    $im=@imagecreatefromstring((string)file_get_contents($file['tmp_name']));
    if (!$im) throw new Problem(422,'Imaginea nu poate fi citită.');
    $scale=min(1,1600/max($info[0],$info[1]));$w=(int)round($info[0]*$scale);$h=(int)round($info[1]*$scale);
    $out=imagecreatetruecolor($w,$h);imagefill($out,0,0,imagecolorallocate($out,255,255,255));
    imagecopyresampled($out,$im,0,0,0,0,$w,$h,$info[0],$info[1]);
    $name=bin2hex(random_bytes(16)).'.jpg';$dir=$c['private_dir'].'/posters';
    if (!is_dir($dir) && !mkdir($dir,0700)) throw new Problem(503,'Posterul nu poate fi salvat.');
    if (!imagejpeg($out,$dir.'/'.$name,85)) throw new Problem(503,'Posterul nu poate fi salvat.');
    chmod($dir.'/'.$name,0600);imagedestroy($out);imagedestroy($im);
    return $name;
}
function active_events(array $events, ?int $now=null): array {
    $now ??= time();$events=array_filter($events,fn($e)=>($e['published']??false) && strtotime($e['ends'])>$now);
    usort($events,fn($a,$b)=>strcmp($a['starts'],$b['starts'])); return $events;
}
function public_events(array $c): array {
    $events=state($c,fn(&$s)=>active_events($s['events']));
    return array_map(fn($e)=>['id'=>$e['id'],'title'=>$e['title'],'category'=>$e['category'],'starts'=>$e['starts'],'ends'=>$e['ends'],'poster'=>path($c,'api/index.php?action=poster&id='.$e['id'])],$events);
}
function notify(array $c, string $id): string {
    if (($c['mail_transport']??'disabled')!=='mail') return 'disabled';
    if (($c['notify_to']??'')!=='office@oldmike.ro' || !filter_var($c['mail_from']??'',FILTER_VALIDATE_EMAIL) || preg_match('/[\r\n]/',$c['mail_from'])) throw new Problem(503,'Configurația notificărilor trebuie verificată.');
    $claimed=state($c,function (&$s) use ($id) {
        if (!isset($s['requests'][$id])) throw new Problem(404,'Cererea nu există.');
        if ($s['requests'][$id]['email_state']!=='pending') return false;
        $s['requests'][$id]['email_state']='sending'; audit($s,'notification_claimed',$id);return true;
    });
    if (!$claimed) return 'already_attempted';
    // Only an opaque reference is emailed. Personal details stay in protected administration.
    $body="O noua cerere pentru grupuri a fost inregistrata. Referinta: $id\n".$c['origin'].path($c,'admin/')."\nRezervarea nu este confirmata automat.\n";
    try { $accepted=mail($c['notify_to'],'Old Mike - cerere pentru grup',$body,['From'=>$c['mail_from'],'Content-Type'=>'text/plain; charset=UTF-8']); }
    catch (\Throwable $e) { $accepted=null; }
    $status=$accepted===true?'accepted':($accepted===false?'failed':'unknown');
    state($c,function (&$s) use ($id,$status) { $s['requests'][$id]['email_state']=$status;audit($s,'notification_'.$status,$id); });
    return $status; // accepted != delivered; ambiguous attempts are never retried automatically.
}
