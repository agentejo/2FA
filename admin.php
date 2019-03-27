<?php

$this->on('cockpit.account.fields', function(&$fields, &$account) {

    $userId = $this->module('cockpit')->getUser('_id');

    if ($userId && isset($account['_id']) && $account['_id'] == $userId  && !isset($account['twofa_secret'])) {
        $tfa = new \RobThree\Auth\TwoFactorAuth($this['app.name']);
        $account['twofa_secret'] = $tfa->createSecret(160);
    }
});


$this->on('cockpit.account.panel', function(&$account) {

    $userId = $this->module('cockpit')->getUser('_id');

    if ($userId && isset($account['_id']) && $account['_id'] == $userId) {
        echo $this->view('2fa:views/panel.php', compact('account'));
    }
});


// overide views

$this->path('cockpit', __DIR__.'/views/cockpit');

// overide routes

$this->bind('/auth/check', function() {

    if ($data = $this->param('auth')) {

        $user = $this->module('cockpit')->authenticate($data);

        if ($user && !$this->module('cockpit')->hasaccess('cockpit', 'backend', @$user['group'])) {
            $user = null;
        }

        if (!$user) {
            return ['success' => false];
        }

        if (isset($user['twofa']) && $user['twofa']) {
            return [
                'success' => true,
                'user' => [
                   '_id' => $user['_id'],
                   'name' => $user['name'],
                   'user' => $user['user'],
                   'twofa' => true
                ]
            ];
        }

        unset($user['twofa_secret']);

        $this->trigger('cockpit.account.login', [&$user]);
        $this->module('cockpit')->setUser($user);


        return ['success' => true, 'twofa' => false, 'user' => $user];

    }

    return false;
});

$this->bind('/auth/twofavalidate', function() {

    $code = $this->param('code', null);
    $user = $this->param('user', null);

    if (!$code || !$user) {
        return ['success' => false];
    }

    $user = $this->storage->findOne('cockpit/accounts', ['_id' => $user['_id']]);

    if (!$user) {
        return ['success' => false];
    }

    $tfa = new \RobThree\Auth\TwoFactorAuth($this['app.name']);

    if ($tfa->verifyCode($user['twofa_secret'], $code)) {

        unset($user['twofa_secret']);

        $this->trigger('cockpit.account.login', [&$user]);
        $this->module('cockpit')->setUser($user);


        return ['success' => true, 'user' => $user];
    }

    return ['success' => false];
});
