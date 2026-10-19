<?php
/** Read-only real HTTPS probe, run in CI; no production writes. */
declare(strict_types=1);
require __DIR__.'/../../server/lib/menu/data.php';
$rows=OldMikeMenu\fetch_live();
if($rows===null||count($rows)===0){fwrite(STDERR,"Public menu RPC did not return a valid nonempty menu\n");exit(1);}
$report=['pass'=>true,'products'=>count($rows),'transport'=>'PHP cURL HTTPS','checked_at'=>gmdate(DATE_ATOM)];
file_put_contents(__DIR__.'/../../output/menu-m3/live-rpc-check.json',json_encode($report,JSON_PRETTY_PRINT));
echo 'PASS real PHP public RPC: '.count($rows)." products; read-only\n";
