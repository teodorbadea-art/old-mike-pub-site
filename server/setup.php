<?php
// Run manually in cPanel Terminal. Never deploy this file inside public_html.
declare(strict_types=1);
if (PHP_SAPI!=='cli') {http_response_code(404);exit;}
$private='/home/oldmikero/oldmike-site-private';
$staging='/home/oldmikero/public_html/oldmike-test';
if (is_file($private.'/config.php')) exit("Configuratia exista deja. Nu o suprascriem.\n");
if (!is_dir($staging) || is_link($staging) || !is_file($staging.'/.htaccess') || !str_contains((string)file_get_contents($staging.'/.htaccess'),'Require all denied')) exit("Zona de test izolata trebuie pregatita inainte.\n");
foreach (['gd','session','json'] as $extension) if (!extension_loaded($extension)) exit("Lipseste extensia PHP: $extension.\n");
if (!function_exists('shell_exec') || !function_exists('system')) exit("Introducerea ascunsa a parolei nu este disponibila.\n");
$tty=trim((string)shell_exec('stty -g 2>/dev/null'));
if ($tty==='' || !preg_match('/^[a-fA-F0-9:;]+$/D',$tty)) exit("Foloseste un terminal interactiv pentru parola.\n");
echo "Configurare Old Mike - doar zona de test, fara emailuri sau cereri publice.\n";
echo "Alege utilizatorul tau (litere/cifre, 3-40 caractere): ";
$user=trim((string)fgets(STDIN));
if (!preg_match('/^[a-zA-Z0-9_-]{3,40}$/D',$user)) exit("Utilizator nevalid. Reia configurarea.\n");
try {
    system('stty -echo');
    echo "Alege parola (12-72 bytes; nu se afiseaza): ";$password=rtrim((string)fgets(STDIN),"\r\n");echo "\nRepeta parola: ";$again=rtrim((string)fgets(STDIN),"\r\n");echo "\n";
} finally { system('stty '.escapeshellarg($tty)); }
if (strlen($password)<12 || strlen($password)>72 || !hash_equals($password,$again)) exit("Parola nu indeplineste conditiile sau nu coincide. Reia configurarea.\n");
$hash=password_hash($password,PASSWORD_BCRYPT);unset($password,$again);
if (!is_dir($private) && !mkdir($private,0700)) exit("Nu se poate crea dosarul privat.\n");
chmod($private,0700);
function exclusive(string $path,string $data): void {
    $f=fopen($path,'x');if (!$f) throw new RuntimeException('Fisierul exista deja sau nu poate fi creat.');
    try {chmod($path,0600);if(fwrite($f,$data)!==strlen($data))throw new RuntimeException('Scriere incompleta.');}finally{fclose($f);}
}
$config=['enabled'=>true,'requests_enabled'=>false,'origin'=>'https://oldmike.ro','base_path'=>'/oldmike-test','private_dir'=>$private,'admin_user'=>$user,'admin_password_hash'=>$hash,'rate_secret'=>bin2hex(random_bytes(32)),'mail_transport'=>'disabled','notify_to'=>'office@oldmike.ro','mail_from'=>'office@oldmike.ro'];
exclusive($private.'/preview.htpasswd',$user.':'.$hash."\n");
exclusive($private.'/config.php',"<?php\nreturn ".var_export($config,true).";\n");
$access="Options -Indexes\nDirectoryIndex index.html index.php\nAuthType Basic\nAuthName \"Old Mike - test privat\"\nAuthUserFile $private/preview.htpasswd\nRequire valid-user\n<IfModule mod_headers.c>\nHeader always set X-Robots-Tag \"noindex, nofollow\"\nHeader always set X-Content-Type-Options \"nosniff\"\n</IfModule>\n";
$temporary=$staging.'/.htaccess.setup';
exclusive($temporary,$access);chmod($temporary,0644);
if (!rename($temporary,$staging.'/.htaccess')) exit("Configuratia privata este salvata, dar protectia zonei de test necesita verificare.\n");
echo "Gata. Zona de test are acces cu utilizator si parola. Administrarea foloseste aceleasi date. Emailurile si cererile publice sunt inca dezactivate.\n";
