<?php
declare(strict_types=1);
require __DIR__.'/../../server/lib/app.php';require __DIR__.'/../../server/lib/drafts.php';
use function OldMike\{state,draft_revision,save_draft,delete_draft,draft_poster_cleanup};use OldMike\Problem;
if(PHP_SAPI!=='cli')exit;
$count=0;function check(bool $b,string $m):void{global $count;if(!$b)throw new RuntimeException($m);$count++;}
function rejects(callable $f,int $status):void{try{$f();}catch(Problem $e){check($e->status===$status,'rejection '.$status);return;}throw new RuntimeException('not rejected');}
$root=sys_get_temp_dir().'/oldmike-drafts-'.bin2hex(random_bytes(8));mkdir($root,0700);mkdir($root.'/posters',0700);$c=['private_dir'=>$root];
$id=str_repeat('1',24);$a=str_repeat('a',32).'.jpg';$b=str_repeat('b',32).'.jpg';$d=str_repeat('d',32).'.jpg';
$event=['id'=>$id,'title'=>'Original','category'=>'sport','starts'=>'2030-11-10T19:00:00+02:00','ends'=>'2030-11-10T21:00:00+02:00','poster'=>$a,'published'=>false,'created_at'=>'2030-01-01T00:00:00Z'];
$input=['title'=>'Edited','category'=>'other','starts'=>'2030-11-11T19:00','ends'=>'2030-11-11T22:00'];
try{
 file_put_contents($root.'/posters/'.$a,'synthetic');file_put_contents($root.'/posters/'.$b,'synthetic replacement');file_put_contents($root.'/posters/'.$d,'unrelated');
 state($c,function(&$s)use($id,$event){$s['events'][$id]=$event;});$original=draft_revision($event);
 check(save_draft($c,$id,$original,$input),'metadata edit');$current=state($c,fn(&$s)=>$s['events'][$id]);check($current['title']==='Edited'&&$current['category']==='other','fields updated');check(!$current['published'],'stays draft');check($current['poster']===$a&&is_file($root.'/posters/'.$a),'existing poster retained');
 rejects(fn()=>save_draft($c,$id,$original,$input),409);rejects(fn()=>delete_draft($c,$id,$original,true),409);
 $revision=draft_revision($current);rejects(fn()=>save_draft($c,$id,$revision,array_replace($input,['ends'=>'2020-01-01T00:00'])),422);check(is_file($root.'/posters/'.$a),'invalid edit retained poster');
 rejects(fn()=>save_draft($c,$id,$revision,$input,'../not-allowed.jpg'),422);
 check(save_draft($c,$id,$revision,$input,$b),'replace poster');$current=state($c,fn(&$s)=>$s['events'][$id]);check($current['poster']===$b,'new poster referenced');check(!is_file($root.'/posters/'.$a),'old poster removed');check(is_file($root.'/posters/'.$d),'unrelated preserved');
 $revision=draft_revision($current);rejects(fn()=>delete_draft($c,$id,$revision,false),422);check(is_file($root.'/posters/'.$b),'no confirmation retained file');
 state($c,function(&$s)use($id){$s['events'][$id]['published']=true;});$published=state($c,fn(&$s)=>$s['events'][$id]);rejects(fn()=>save_draft($c,$id,draft_revision($published),$input),409);rejects(fn()=>delete_draft($c,$id,draft_revision($published),true),409);
 state($c,function(&$s)use($id){$s['events'][$id]['published']=false;});$current=state($c,fn(&$s)=>$s['events'][$id]);check(delete_draft($c,$id,draft_revision($current),true),'delete draft');check(!state($c,fn(&$s)=>isset($s['events'][$id])),'record removed');check(!is_file($root.'/posters/'.$b),'poster removed');check(is_file($root.'/posters/'.$d),'other poster kept');rejects(fn()=>delete_draft($c,$id,draft_revision($current),true),404);
 check(!draft_poster_cleanup($c,'../outside'),'cleanup traversal rejected');
 file_put_contents($root.'/posters/'.$a,'shared');state($c,function(&$s)use($id,$event){$s['events'][$id]=$event;});check(draft_poster_cleanup($c,$a)&&is_file($root.'/posters/'.$a),'referenced poster kept');
 $audit=state($c,fn(&$s)=>$s['audit']);check(count($audit)===3,'edit replacement delete audited');check(!str_contains(json_encode($audit),'Edited'),'audit no content');
 echo "PASS: $count draft checks; synthetic fixtures only, no real events or email.\n";
}finally{$it=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root,FilesystemIterator::SKIP_DOTS),RecursiveIteratorIterator::CHILD_FIRST);foreach($it as $f){$f->isDir()?rmdir($f->getPathname()):unlink($f->getPathname());}rmdir($root);}
