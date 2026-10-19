<?php
declare(strict_types=1);
namespace OldMike;

function draft_revision(array $event): string {return hash('sha256',json_encode($event,JSON_THROW_ON_ERROR));}
function require_draft(array $events,string $id,string $revision): array {
    if(!preg_match('/^[a-f0-9]{24}$/D',$id) || !isset($events[$id]))throw new Problem(404,'Ciorna nu mai există.');
    $event=$events[$id];
    if($event['published']??false)throw new Problem(409,'Ascunde mai întâi evenimentul publicat, apoi îl poți modifica sau șterge.');
    if(!hash_equals(draft_revision($event),$revision))throw new Problem(409,'Ciorna a fost modificată între timp. Reîncarcă pagina înainte de a continua.');
    return $event;
}
function draft_poster_cleanup(array $c,string $name): bool {
    if(!preg_match('/^[a-f0-9]{32}\.jpg$/D',$name))return false;
    try{return state($c,function(&$s)use($c,$name){
        foreach($s['events'] as $event)if(($event['poster']??'')===$name)return true;
        $file=$c['private_dir'].'/posters/'.$name;
        if(is_link($file))return false;
        return !is_file($file) || @unlink($file);
    });}catch(\Throwable $e){return false;}
}
function save_draft(array $c,string $id,string $revision,array $input,?string $replacement=null): bool {
    $values=event_values($input);
    if($replacement!==null && (!preg_match('/^[a-f0-9]{32}\.jpg$/D',$replacement) || !is_file($c['private_dir'].'/posters/'.$replacement) || is_link($c['private_dir'].'/posters/'.$replacement)))throw new Problem(422,'Posterul nou nu poate fi folosit.');
    $old=state($c,function(&$s)use($id,$revision,$values,$replacement){
        $event=require_draft($s['events'],$id,$revision);
        $s['events'][$id]=array_replace($event,$values,['published'=>false,'revision'=>(int)($event['revision']??0)+1]);
        if($replacement!==null)$s['events'][$id]['poster']=$replacement;
        audit($s,'draft_updated',$id);return $event['poster'];
    });
    return $replacement===null || $replacement===$old || draft_poster_cleanup($c,$old);
}
function delete_draft(array $c,string $id,string $revision,bool $confirmed): bool {
    if(!$confirmed)throw new Problem(422,'Confirmă ștergerea ciornei și a posterului.');
    $old=state($c,function(&$s)use($id,$revision){
        $event=require_draft($s['events'],$id,$revision);unset($s['events'][$id]);audit($s,'draft_deleted',$id);return $event['poster'];
    });
    return draft_poster_cleanup($c,$old);
}
