<?php
declare(strict_types=1);
require __DIR__.'/../lib/app.php';
use function OldMike\{config,headers,session,csrf,rate,state,request_values,save_request,public_events,active_events,notify};
use OldMike\Problem;
headers(true);
try {
    $c=config(); $action=$_GET['action']??''; $method=$_SERVER['REQUEST_METHOD']??'GET';
    if ($method==='GET' && $action==='events') { echo json_encode(['events'=>public_events($c)],JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR);exit; }
    if ($method==='GET' && $action==='poster') {
        $id=$_GET['id']??'';
        if (!is_string($id) || !preg_match('/^[a-f0-9]{24}$/D',$id)) throw new Problem(404,'Poster indisponibil.');
        $e=state($c,fn(&$s)=>$s['events'][$id]??null);
        if (!$e || !active_events([$e]) || !preg_match('/^[a-f0-9]{32}\.jpg$/D',$e['poster'])) throw new Problem(404,'Poster indisponibil.');
        $file=$c['private_dir'].'/posters/'.$e['poster'];if (!is_file($file)) throw new Problem(404,'Poster indisponibil.');
        header('Content-Type: image/jpeg');header('Content-Disposition: inline; filename="oldmike-poster.jpg"');readfile($file);exit;
    }
    if (!($c['requests_enabled']??false)) throw new Problem(503,'Cererile online nu sunt încă active. Sună la 0787 366 166.');
    session($c);
    if ($method==='GET' && $action==='form') {
        rate($c,'form',60,900);
        if (!isset($_SESSION['submission'])) { $_SESSION['submission']=bin2hex(random_bytes(24));$_SESSION['form_started']=time(); }
        echo json_encode(['csrf'=>$_SESSION['csrf'],'submission'=>$_SESSION['submission']]);exit;
    }
    if ($method!=='POST' || $action!=='request') throw new Problem(405,'Acțiune indisponibilă.');
    csrf();rate($c,'request',5,900);
    if (strlen($_POST['website']??'')>0 || time()-($_SESSION['form_started']??time())<3) throw new Problem(422,'Verifică formularul și încearcă din nou.');
    $key=$_POST['submission']??'';
    if (!is_string($key) || !hash_equals($_SESSION['submission']??'',$key)) throw new Problem(403,'Reîncarcă pagina înainte de trimitere.');
    $result=save_request($c,request_values($_POST),hash('sha256',$key));
    if (!$result['duplicate']) { try { notify($c,$result['id']); } catch (\Throwable $e) { /* Saved request remains authoritative; no false failure and no duplicate retry. */ } }
    echo json_encode(['ok'=>true,'reference'=>$result['id'],'message'=>'Am înregistrat cererea. Echipa Old Mike te va contacta. Rezervarea nu este încă confirmată.']);
} catch (Problem $e) { http_response_code($e->status);echo json_encode(['ok'=>false,'message'=>$e->getMessage()]); }
catch (\Throwable $e) { http_response_code(503);echo json_encode(['ok'=>false,'message'=>'Serviciul nu este disponibil acum. Sună la 0787 366 166.']); }
