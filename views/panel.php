<div class="uk-form-row">
    <label class="uk-text-small">@lang('Two-factor authentication')</label>

    <div class="uk-margin">
        <field-boolean bind="account.twofa"></field-boolean>
    </div>

    <div class="uk-margin" show="{ account && account.twofa }">

        <div class="uk-panel-box uk-panel-framed">
            <div class="uk-grid">
                <div>
                    <?php
                        $renderer = new cls2FAQRCodeRenderer();
                        $tfa = new \RobThree\Auth\TwoFactorAuth($app['app.name'], 6, 30, 'sha1', $renderer);
                    ?>
                    <img src="{{ $tfa->getQRCodeImageAsDataUri($app['app.name'], $account['twofa_secret'], 100) }}" width="100" alt="{{ $account['twofa_secret'] }}">
                </div>
                <div class="uk-flex-item-1">

                    <p>
                        Scan the QR code with your 2FA mobile app<br>
                        or enter your secret manually:
                    </p>

                    <div class="uk-margin uk-h2 uk-text-bold" style="font-family:monospace">
                        {{ $account['twofa_secret'] }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
