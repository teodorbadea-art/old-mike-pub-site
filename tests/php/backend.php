<?php
declare(strict_types=1);
require __DIR__.'/../../server/lib/app.php';
use function OldMike\{esc,request_values,event_values,active_events,state,rate,csrf,admin,save_request,notify,config};
use OldMike\Problem;
if (PHP_SAPI!=='cli') {http_response_code(404);exit;}
$count=0;
function check(bool $value,string $message): void {global $count;if(!$value)throw new RuntimeException($message);$count++;}
function rejects(callable $fn,int $status,string $message): void {try{$fn();}catch(Problem $e){check($e->status===$status,$message.' status');return;}throw new RuntimeException($message);}
$root=sys_get_temp_dir().'/oldmike-check-'.bin2hex(random_bytes(8));mkdir($root,0700);mkdir($root.'/public',0700);mkdir($root.'/private',0700);
$c=['private_dir'=>$root.'/private','origin'=>'https://oldmike.invalid','base_path'=>'/test','rate_secret'=>bin2hex(random_bytes(32)),'enabled'=>true,'mail_transport'=>'disabled'];
try {
 $v=['name'=>'Test sintetic','phone'=>'0700000000','email'=>'','date'=>'2030-11-10','time'=>'19:00','guests'=>'12','occasion'=>'Aniversare','area'=>'Interior','details'=>'<script>alert(1)</script>','contact_permission'=>'on'];
 $r=request_values($v,'2030-11-09');check($r['guests']===12,'guest number');check($r['email']==='','optional email');
 foreach ([['date'=>'2030-02-30'],['date'=>'2020-01-01'],['time'=>'25:00'],['guests'=>'2.5'],['guests'=>'1001'],['email'=>'invalid'],['contact_permission'=>''],['name'=>['array']],['area'=>'Invented'],['phone'=>'123'],['details'=>str_repeat('a',2001)]] as $bad) rejects(fn()=>request_values(array_replace($v,$bad),'2030-11-09'),422,'invalid request');
 check(!str_contains(esc($r['details']),'<script>'),'XSS escaping');
 $event=event_values(['title'=>'Test <script>','category'=>'sport','starts'=>'2030-11-10T19:00','ends'=>'2030-11-10T21:00']);
 check(str_ends_with($event['starts'],'+02:00'),'Bucharest winter offset');
 $summer=event_values(['title'=>'Summer','category'=>'other','starts'=>'2030-06-10T19:00','ends'=>'2030-06-10T21:00']);check(str_ends_with($summer['starts'],'+03:00'),'Bucharest summer offset');
 rejects(fn()=>event_values(['title'=>'x','category'=>'sport','starts'=>'2030-02-30T19:00','ends'=>'2030-03-01T21:00']),422,'invalid event date');
 rejects(fn()=>event_values(['title'=>'x','category'=>'sport','starts'=>'2030-11-10T21:00','ends'=>'2030-11-10T19:00']),422,'end before start');
 check(count(active_events([$event+['published'=>true],$event+['published'=>false]],strtotime('2030-11-10T18:00:00+02:00')))===1,'draft exclusion');
 check(count(active_events([$event+['published'=>true]],strtotime($event['ends'])))===0,'expire at exact end');
 $_SESSION=['csrf'=>'token'];$_POST=[];rejects(fn()=>csrf(),403,'CSRF required');$_POST=['csrf'=>'wrong'];rejects(fn()=>csrf(),403,'CSRF wrong');$_POST=['csrf'=>'token'];csrf();check(true,'CSRF valid');
 rejects(fn()=>admin(),401,'anonymous admin');$_SESSION['admin_at']=time()-1801;$_SESSION['admin_login']=time();rejects(fn()=>admin(),401,'idle admin expired');
 $_SESSION['admin_at']=time();$_SESSION['admin_login']=time()-28801;rejects(fn()=>admin(),401,'absolute admin expired');
 $_SESSION['admin_at']=time();$_SESSION['admin_login']=time();admin();check(true,'active admin');
 $first=save_request($c,$r,hash('sha256','one'));$again=save_request($c,$r,hash('sha256','one'));check($first['id']===$again['id'] && $again['duplicate'],'idempotency');
 $second=save_request($c,$r,hash('sha256','two'));check($second['id']!==$first['id'],'independent submission');
 check(state($c,fn(&$s)=>count($s['requests']))===2,'only two records');
 check(notify($c,$first['id'])==='disabled','no email side effects');
 check(state($c,fn(&$s)=>$s['requests'][$first['id']]['email_state'])==='pending','disabled notification stays pending');
 $_SERVER['REMOTE_ADDR']='192.0.2.1';rate($c,'test',2,900);rate($c,'test',2,900);rejects(fn()=>rate($c,'test',2,900),429,'rate limit');
 $data=file_get_contents($c['private_dir'].'/state.json');check(!str_contains($data,'192.0.2.1'),'IP not stored plaintext');
 $before=$data;try{state($c,function(&$s){$s['requests']=[];throw new RuntimeException('abort');});}catch(RuntimeException $e){}check(file_get_contents($c['private_dir'].'/state.json')===$before,'transaction rollback');
 file_put_contents($c['private_dir'].'/state.json','{broken');try{state($c,fn(&$s)=>$s);}catch(JsonException $e){check(true,'corruption fails closed');}check(file_get_contents($c['private_dir'].'/state.json')==='{broken','corruption not overwritten');
 $_SERVER['DOCUMENT_ROOT']=$root.'/public';$file=$root.'/config.php';file_put_contents($file,'<?php return '.var_export($c,true).';');putenv('OLDMIKE_SITE_CONFIG='.$file);
 check(config()['private_dir']===$c['private_dir'],'private configuration accepted');
 $bad=$c;$bad['private_dir']=$root.'/public';file_put_contents($file,'<?php return '.var_export($bad,true).';');rejects(fn()=>config(),503,'public data directory rejected');
 $bad=$c;$bad['enabled']=false;file_put_contents($file,'<?php return '.var_export($bad,true).';');rejects(fn()=>config(),503,'disabled configuration');
 check(function_exists('imagecreatefromstring'),'GD required for poster sanitizing');
 echo 'PASS: '.$count.' backend checks; synthetic data only; no mail or production data. PHP '.PHP_VERSION.PHP_EOL;
} finally {
 putenv('OLDMIKE_SITE_CONFIG');
 $it=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root,FilesystemIterator::SKIP_DOTS),RecursiveIteratorIterator::CHILD_FIRST);
 foreach($it as $f){$f->isDir()?rmdir($f->getPathname()):unlink($f->getPathname());}rmdir($root);
}
