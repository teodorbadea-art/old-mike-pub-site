<?php
declare(strict_types=1);
require __DIR__.'/../lib/app.php';
use function OldMike\{config,headers,session,csrf,rate,admin,state,audit,text,esc,path,event_values,poster,notify};
use OldMike\Problem;
headers();header('Content-Type: text/html; charset=utf-8');
$error='';$message='';$c=null;$logged=false;$snapshot=['events'=>[],'requests'=>[]];
try {
    $c=config();session($c);$hash=$c['admin_password_hash']??'';
    if (empty($c['admin_user']) || !is_string($hash) || empty(password_get_info($hash)['algo'])) throw new Problem(503,'Accesul de administrare trebuie configurat.');
    if ($_SERVER['REQUEST_METHOD']==='POST') {
        csrf();$action=text($_POST,'action',30,true);
        if ($action==='login') {
            rate($c,'login',8,900);$user=text($_POST,'username',100,true);$password=$_POST['password']??'';
            $valid=is_string($password) && strlen($password)<=1024 && password_verify($password,$hash);
            if (!$valid || !hash_equals($c['admin_user'],$user)) throw new Problem(401,'Datele de conectare nu sunt corecte.');
            session_regenerate_id(true);$_SESSION=['csrf'=>bin2hex(random_bytes(32)),'admin_at'=>time(),'admin_login'=>time()];
            header('Location: '.path($c,'admin/'),true,303);exit;
        }
        admin();
        if ($action==='logout') {
            $_SESSION=[];session_destroy();setcookie('OLDMIKESITE','',['expires'=>1,'path'=>path($c,''),'secure'=>true,'httponly'=>true,'samesite'=>'Strict']);
            header('Location: '.path($c,'admin/'),true,303);exit;
        }
        if ($action==='event_create') {
            $values=event_values($_POST);$name=poster($_FILES['poster']??[],$c);$id=bin2hex(random_bytes(12));
            try { state($c,function (&$s) use ($id,$values,$name) {
                if (count($s['events'])>=500) throw new Problem(422,'Limita de evenimente a fost atinsă. Contactează administratorul pentru arhivare.');
                $s['events'][$id]=$values+['id'=>$id,'poster'=>$name,'published'=>false,'created_at'=>gmdate('c')];audit($s,'event_created',$id);
            }); } catch (\Throwable $e) { unlink($c['private_dir'].'/posters/'.$name);throw $e; }
            $message='Poster salvat ca ciornă. Verifică detaliile, apoi apasă Publică.';
        } elseif ($action==='event_publish' || $action==='event_hide') {
            $id=text($_POST,'id',24,true);state($c,function (&$s) use ($id,$action) {
                if (!isset($s['events'][$id])) throw new Problem(404,'Evenimentul nu există.');
                if ($action==='event_publish' && strtotime($s['events'][$id]['ends'])<=time()) throw new Problem(422,'Un eveniment încheiat nu poate fi publicat.');
                $s['events'][$id]['published']=$action==='event_publish';audit($s,$action,$id);
            });$message=$action==='event_publish'?'Eveniment publicat.':'Eveniment ascuns. Poate fi publicat din nou.';
        } elseif ($action==='request_status') {
            $id=text($_POST,'id',24,true);$status=text($_POST,'status',20,true);
            if (!in_array($status,['new','contacted','closed'],true)) throw new Problem(422,'Alege starea cererii.');
            state($c,function (&$s) use ($id,$status) {
                if (!isset($s['requests'][$id])) throw new Problem(404,'Cererea nu există.');
                $s['requests'][$id]['status']=$status;audit($s,'request_'.$status,$id);
            });$message='Starea a fost salvată. Aceasta nu confirmă automat rezervarea.';
        } elseif ($action==='notify') {
            $status=notify($c,text($_POST,'id',24,true));
            $message=['accepted'=>'Serverul de email a acceptat notificarea. Primirea trebuie verificată în căsuța office.','disabled'=>'Trimiterea emailului este încă dezactivată.','failed'=>'Serverul nu a acceptat notificarea. Cererea este salvată aici.','unknown'=>'Rezultatul trimiterii este necunoscut. Nu retrimitem automat.','already_attempted'=>'Notificarea a fost deja încercată; nu am trimis un duplicat.'][$status];
        } else throw new Problem(400,'Acțiune indisponibilă.');
        $_SESSION['flash']=$message;header('Location: '.path($c,'admin/'),true,303);exit;
    }
    try { admin();$logged=true; } catch (Problem $e) { if ($e->status!==401) throw $e; }
    if ($logged) $snapshot=state($c,fn(&$s)=>['events'=>array_reverse($s['events'],true),'requests'=>array_reverse($s['requests'],true)]);
    $message=$_SESSION['flash']??'';unset($_SESSION['flash']);
} catch (Problem $e) {
    http_response_code($e->status);$error=$e->getMessage();
    try { if ($c) {admin();$logged=true;$snapshot=state($c,fn(&$s)=>['events'=>array_reverse($s['events'],true),'requests'=>array_reverse($s['requests'],true)]);} } catch (\Throwable $ignore) {}
} catch (\Throwable $e) { http_response_code(503);$error='Administrarea nu este disponibilă acum. Configurația trebuie verificată.'; }
function token(): string { return '<input type="hidden" name="csrf" value="'.esc($_SESSION['csrf']??'').'">'; }
function control(string $action,string $id,string $label): string {return '<form method="post">'.token().'<input type="hidden" name="action" value="'.esc($action).'"><input type="hidden" name="id" value="'.esc($id).'"><button>'.esc($label).'</button></form>';}
?>
<!doctype html><html lang="ro"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Administrare · Old Mike</title><style>
*{box-sizing:border-box}body{margin:0;background:#12100d;color:#f3eadc;font:16px/1.6 system-ui,sans-serif}main{max-width:1080px;margin:auto;padding:28px 20px}a{color:#ecc58d}h1,h2{line-height:1.2}section,article{background:#211c15;border:1px solid #57452f;border-radius:12px;padding:22px;margin:22px 0}label{display:block;margin:12px 0}input,select,textarea{display:block;width:100%;padding:12px;font:inherit;background:#fffaf1;color:#17120c;border:1px solid #bbaa90;border-radius:4px}button{font:inherit;font-weight:650;background:#e1b674;color:#17120c;border:0;border-radius:5px;padding:12px 18px;cursor:pointer;min-height:44px}button:focus-visible,a:focus-visible,input:focus-visible,select:focus-visible{outline:3px solid #fff;outline-offset:3px}.actions{display:flex;flex-wrap:wrap;gap:12px}.notice{padding:14px;border:1px solid #e1b674;border-radius:5px}.error{border-color:#e9a5a5;color:#ffdada}.grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}dl{display:grid;grid-template-columns:150px 1fr;gap:8px}dd{margin:0;overflow-wrap:anywhere;white-space:pre-wrap}dt{color:#c5b9a5}img{max-width:100%;height:auto;max-height:500px;object-fit:contain}small{display:block;color:#c5b9a5}@media(max-width:650px){.grid,dl{display:block}dd{margin-bottom:12px}.actions{display:grid}}
</style></head><body><main><h1>Old Mike · Administrare</h1>
<?php if($message):?><p role="status" class="notice"><?=esc($message)?></p><?php endif?>
<?php if($error):?><p role="alert" class="notice error"><?=esc($error)?></p><?php endif?>
<?php if(!$logged && $c && isset($_SESSION['csrf']) && !empty($c['admin_user'])):?>
<section><h2>Conectare</h2><form method="post"><?=token()?><input type="hidden" name="action" value="login"><label>Utilizator<input name="username" autocomplete="username" maxlength="100" required></label><label>Parolă<input name="password" type="password" autocomplete="current-password" maxlength="1024" required></label><button>Conectează-te</button></form></section>
<?php elseif($logged):?>
<nav class="actions"><a href="#evenimente">Evenimente</a><a href="#cereri">Cereri pentru grupuri</a><a href="<?=esc(path($c,'index.html'))?>">Deschide site-ul</a><?=control('logout','','Ieșire')?></nav>
<section id="evenimente"><h2>Adaugă un eveniment</h2><p>Încarcă posterul pregătit pentru social media. Se salvează întâi ca ciornă, apoi alegi când îl publici.</p><form method="post" enctype="multipart/form-data"><?=token()?><input type="hidden" name="action" value="event_create"><label>Titlu<input name="title" maxlength="120" required></label><label>Categorie<select name="category"><option value="sport">Sport</option><option value="other">Alte evenimente</option></select></label><div class="grid"><label>Începe · ora Bucureștiului<input name="starts" type="datetime-local" required></label><label>Se încheie · ora Bucureștiului<input name="ends" type="datetime-local" required></label></div><label>Poster · JPG, PNG sau WebP, maximum1,8MB<input name="poster" type="file" accept="image/jpeg,image/png,image/webp" required></label><button>Salvează ciorna</button></form></section>
<?php foreach($snapshot['events'] as $e):?>
<article><h3><?=esc($e['title'])?></h3><p><?=esc((new DateTimeImmutable($e['starts']))->setTimezone(new DateTimeZone('Europe/Bucharest'))->format('d.m.Y H:i'))?> — <?=esc((new DateTimeImmutable($e['ends']))->setTimezone(new DateTimeZone('Europe/Bucharest'))->format('d.m.Y H:i'))?> · București</p><p><?=strtotime($e['ends'])<=time()?'Încheiat':($e['published']?'Publicat':'Ciornă / ascuns')?></p><img src="poster.php?id=<?=esc($e['id'])?>" alt="<?=esc('Poster: '.$e['title'])?>" loading="lazy"><div class="actions"><?=control($e['published']?'event_hide':'event_publish',$e['id'],$e['published']?'Ascunde':'Publică')?></div></article>
<?php endforeach?>
<section id="cereri"><h2>Cereri pentru grupuri</h2><p>Solicitările de mai jos nu reprezintă rezervări confirmate. Contactează clientul pentru disponibilitate și detalii.</p><?php if(!$snapshot['requests']):?><p>Nu sunt cereri înregistrate.</p><?php endif?></section>
<?php $labels=['name'=>'Nume','phone'=>'Telefon','email'=>'Email','date'=>'Data dorită','time'=>'Ora','guests'=>'Persoane','occasion'=>'Ocazie','area'=>'Zona','details'=>'Detalii'];$mailLabels=['pending'=>'În așteptare','sending'=>'Încercare în curs / rezultat de verificat','accepted'=>'Acceptată de server; livrare neverificată','failed'=>'Neacceptată de server','unknown'=>'Rezultat necunoscut'];foreach($snapshot['requests'] as $r):?>
<article><h3>Cerere · <?=esc(substr($r['id'],0,8))?></h3><small>Înregistrată <?=esc($r['created_at'])?> · UTC</small><dl><?php foreach($labels as $k=>$label):if((string)$r[$k]!==''):?><dt><?=esc($label)?></dt><dd><?=esc($r[$k])?></dd><?php endif;endforeach?></dl><p>Notificare email: <?=esc($mailLabels[$r['email_state']]??$r['email_state'])?></p><form method="post"><?=token()?><input type="hidden" name="action" value="request_status"><input type="hidden" name="id" value="<?=esc($r['id'])?>"><label>Starea cererii<select name="status"><?php foreach(['new'=>'Nouă','contacted'=>'Client contactat','closed'=>'Închisă'] as $v=>$label):?><option value="<?=esc($v)?>" <?=$r['status']===$v?'selected':''?>><?=esc($label)?></option><?php endforeach?></select></label><button>Salvează starea</button></form><?php if($r['email_state']==='pending'):?><?=control('notify',$r['id'],'Trimite notificarea pe email')?><?php endif?></article>
<?php endforeach;endif?>
</main></body></html>
