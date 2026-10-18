<?php
// Copy OUTSIDE public_html to /home/oldmikero/oldmike-site-private/config.php (0600).
// Never put real settings, password hashes or runtime data in the Git repository.
return [
    'enabled' => false,
    'requests_enabled' => false, // Activate only after privacy text, retention and end-to-end tests.
    'origin' => 'https://oldmike.ro',
    'base_path' => '/oldmike-test', // Isolated installation; empty string only for final root.
    'private_dir' => '/home/oldmikero/oldmike-site-private',
    'admin_user' => '',
    'admin_password_hash' => '', // PHP password_hash; never plaintext.
    'rate_secret' => '', // At least32 random bytes, generated privately during setup.
    'mail_transport' => 'disabled', // Set mail only after explicit email test.
    'notify_to' => 'office@oldmike.ro',
    'mail_from' => 'office@oldmike.ro',
];
