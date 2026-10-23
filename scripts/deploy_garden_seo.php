<?php
/** CLI-only deployment of the six approved Garden SEO files; never place in public. */
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$root = '/home/oldmikero/public_html/garden.oldmike.ro';
$private = '/home/oldmikero/oldmike-site-private';
$package = __DIR__;
$files = ['index.html', 'contact.html', 'evenimente.html', 'galerie.html', 'confidentialitate.html', 'meniu.php'];
$protected = ['.htaccess', 'assets/site.css', 'assets/site-runtime.js', 'robots.txt', 'sitemap.xml'];
$backup = null;
$staged = [];
function must(bool $ok, string $message): void { if (!$ok) { throw new RuntimeException($message); } }
function digest(string $path): string { $hash = hash_file('sha256', $path); must($hash !== false, 'Cannot hash '.$path); return $hash; }
function write_exact(string $path, string $data): void { must(file_put_contents($path, $data, LOCK_EX) === strlen($data), 'Cannot write '.$path); }
try {
    must(PHP_VERSION_ID >= 80100 && extension_loaded('curl') && extension_loaded('mbstring'), 'PHP 8.1+, cURL and mbstring required');
    must(realpath($root) === $root && realpath($private) === $private, 'Unexpected hosting path');
    must(str_starts_with(realpath($package).'/', $private.'/'), 'Package must be outside public_html');
    $manifest = json_decode(file_get_contents($package.'/manifest.json'), true, 512, JSON_THROW_ON_ERROR);
    must(array_keys($manifest) === $files, 'Unexpected deployment file list');
    $lock = fopen($private.'/seo-garden-deploy.lock', 'c');
    must($lock !== false && flock($lock, LOCK_EX | LOCK_NB), 'Another SEO deployment is running');
    foreach ($files as $name) {
        must(!is_link($root.'/'.$name), 'Unexpected public symlink');
        must(hash_equals($manifest[$name]['before_sha256'], digest($root.'/'.$name)), 'Live changed since audit: '.$name);
        must(hash_equals($manifest[$name]['after_sha256'], digest($package.'/public/'.$name)), 'Package hash mismatch: '.$name);
    }
    $before = [];
    foreach (array_merge($files, $protected) as $name) { $before[$name] = digest($root.'/'.$name); }
    echo 'CHECK PASS: PHP '.PHP_VERSION.'; all six live and package hashes match.'.PHP_EOL;
    if (($argv[1] ?? '') === '--check') { exit(0); }
    must(($argv[1] ?? '') === '--apply', 'Use --check or --apply');
    $backup = $private.'/seo-garden-backup-'.gmdate('Ymd-His');
    must(mkdir($backup, 0700), 'Cannot create private backup');
    foreach ($before as $name => $hash) {
        $target = $backup.'/'.$name;
        if (!is_dir(dirname($target))) { must(mkdir(dirname($target), 0700, true), 'Cannot create backup directory'); }
        must(copy($root.'/'.$name, $target) && chmod($target, 0600), 'Backup failed: '.$name);
        must(hash_equals($hash, digest($target)), 'Backup integrity failed: '.$name);
    }
    write_exact($backup.'/before-sha256.json', json_encode($before, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR).PHP_EOL);
    echo 'BACKUP PASS: '.$backup.PHP_EOL;
    foreach ($files as $name) {
        $temp = $root.'/.seo-'.getmypid().'-'.$name.'.tmp';
        must(copy($package.'/public/'.$name, $temp) && chmod($temp, 0644), 'Staging failed: '.$name);
        $staged[$name] = $temp;
        must(hash_equals($manifest[$name]['after_sha256'], digest($temp)), 'Staging integrity failed: '.$name);
    }
    // Recheck before committing so a concurrent edit is never overwritten silently.
    foreach ($before as $name => $hash) { must(hash_equals($hash, digest($root.'/'.$name)), 'Concurrent live edit: '.$name); }
    $installed = [];
    try {
        foreach ($files as $name) { must(rename($staged[$name], $root.'/'.$name), 'Install failed: '.$name); $installed[] = $name; }
        foreach ($files as $name) { must(hash_equals($manifest[$name]['after_sha256'], digest($root.'/'.$name)), 'Installed hash mismatch: '.$name); }
        foreach ($protected as $name) { must(hash_equals($before[$name], digest($root.'/'.$name)), 'Protected file changed: '.$name); }
    } catch (Throwable $failure) {
        foreach ($installed as $name) { must(copy($backup.'/'.$name, $root.'/'.$name) && chmod($root.'/'.$name, 0644), 'ROLLBACK FAILED: '.$name); }
        throw $failure;
    }
    $report = ['at'=>gmdate(DATE_ATOM), 'root'=>$root, 'backup'=>$backup, 'files'=>$files, 'protected_unchanged'=>$protected, 'status'=>'APPLIED'];
    write_exact($backup.'/deployment.json', json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR).PHP_EOL);
    echo json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR).PHP_EOL;
} catch (Throwable $e) {
    foreach ($staged as $temp) { if (is_file($temp)) { unlink($temp); } }
    fwrite(STDERR, 'STOP: '.$e->getMessage().PHP_EOL);
    exit(1);
}
