<?php

spl_autoload_register(function($class){
    $class_path = __DIR__.'/lib/'.str_replace('\\', '/', $class).'.php';

    if(file_exists($class_path)) include_once($class_path);
});


// ADMIN
if (COCKPIT_ADMIN && !COCKPIT_API_REQUEST) {

    include_once(__DIR__.'/admin.php');
}

if (COCKPIT_API_REQUEST) {

    $this->on('cockpit.accounts.save', function(&$data) {
        unset($data['twofa'], $data['twofa_secret']);
    });
}

$this->on('cockpit.accounts.disguise', function(&$account) {
    unset($account['twofa'], $account['twofa_secret']);
});
