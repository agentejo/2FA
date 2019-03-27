<!doctype html>
<html lang="{{ $app('i18n')->locale }}" class="uk-height-1-1" data-base="@base('/')" data-route="@route('/')" data-locale="{{ $app('i18n')->locale }}">
<head>
    <meta charset="UTF-8">
    <title>@lang('Authenticate Please!')</title>
    <link rel="icon" href="@base('/favicon.png')" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <style>

        .login-container {
            width: 420px;
            max-width: 90%;
        }

        .login-dialog {
            box-shadow: 0 30px 75px 0 rgba(10, 25, 41, 0.2);
        }

        .login-image {
            background-image: url(@url('assets:app/media/logo.svg'));
            background-repeat: no-repeat;
            background-size: contain;
            background-position: 50% 50%;
            height: 80px;
        }

        .uk-panel-box-header {
            background-color: #fafafa;
            border-bottom: none;
        }

        svg path,
        svg rect,
        svg circle {
            fill: currentColor;
        }

    </style>


    {{ $app->assets($app['app.assets.base'], $app['cockpit/version']) }}
    {{ $app->assets(['assets:lib/uikit/js/components/form-password.min.js'], $app['cockpit/version']) }}

    @trigger('app.login.header')

</head>
<body class="login-page uk-height-viewport uk-flex uk-flex-middle uk-flex-center">

    <div class="uk-position-relative login-container uk-animation-scale uk-container-vertical-center" riot-view>

        <div class="uk-panel-box uk-panel-space uk-panel-card uk-nbfc uk-text-center uk-animation-slide-bottom" if="{$user}">

            <div show="{!$user.twofa}">

                <h2 class="uk-text-bold uk-text-truncate">@lang('Welcome back!')</h2>

                <p>
                    <cp-gravatar size="80" alt="{ $user.name || $user.user }" if="{$user}"></cp-gravatar>
                </p>

            </div>

            <div id="2fa-dialog" show="{$user.twofa}">

                <h2 class="uk-text-bold uk-text-truncate">@lang('Validate Account')</h2>

                <div class="uk-text-center uk-margin">
                    <img src="@base('2fa:assets/2fa.svg')" width="80" height="80" alt="Lock">
                </div>

                <form class="uk-form uk-margin-large-top" method="post" action="@route('/auth/twofavalidate')" onsubmit="{ twofavalidate }">
                    <div class="uk-form-row">
                        <input ref="code" class="uk-form-large uk-width-1-1 uk-text-center" type="text" placeholder="Code" autofocus required>
                    </div>
                    <div class="uk-margin-large-top">
                        <button class="uk-button uk-button-outline uk-button-large uk-text-primary uk-width-1-1">@lang('Authenticate')</button>
                    </div>
                </form>


            </div>

        </div>

        <div id="login-dialog" class="login-dialog uk-panel-box uk-panel-space uk-nbfc" show="{!$user}">

            <div name="header" class="uk-panel-box-header uk-text-bold uk-text-center">

                <div class="uk-margin login-image"></div>

                <h2 class="uk-text-bold uk-text-truncate"><span>{{ $app['app.name'] }}</span></h2>

                <div class="uk-animation-shake uk-margin-top" if="{ error }">
                    <span class="uk-badge uk-badge-outline uk-text-danger">{ error }</span>
                </div>
            </div>

            <form class="uk-form" method="post" action="@route('/auth/check')" onsubmit="{ submit }">

                <div class="uk-form-row">
                    <input ref="user" class="uk-form-large uk-width-1-1" type="text" placeholder="@lang('Username')" autofocus required>
                </div>

                <div class="uk-form-row">
                    <div class="uk-form-password uk-width-1-1">
                        <input ref="password" class="uk-form-large uk-width-1-1" type="password" placeholder="@lang('Password')" required>
                        <a href="#" class="uk-form-password-toggle" data-uk-form-password>@lang('Show')</a>
                    </div>
                </div>

                <div class="uk-margin-large-top">
                    <button class="uk-button uk-button-outline uk-button-large uk-text-primary uk-width-1-1">@lang('Authenticate')</button>
                </div>

            </form>

        </div>

        <p class="uk-text-center" if="{!$user}"><a class="uk-button uk-button-link uk-link-muted" href="@route('/auth/forgotpassword')">@lang('Forgot Password?')</a></p>


        @trigger('app.login.footer')

        <script type="view/script">

            this.error = false;
            this.$user  = null;

            submit(e) {

                e.preventDefault();

                this.error = false;

                App.request('/auth/check', {"auth":{"user":this.refs.user.value, "password":this.refs.password.value}}).then(function(data){

                    if (data && data.success) {

                        this.$user = data.user;

                        document.body.classList.add('app-logged-in');

                        if (!this.$user.twofa) {

                            setTimeout(function(){
                                App.reroute('/');
                            }, 2000)

                        }

                    } else {

                        this.error = '@lang("Login failed")';

                        App.$(this.header).addClass('uk-bg-danger uk-contrast');
                        App.$('#login-dialog').removeClass('uk-animation-shake');

                        setTimeout(function(){
                            App.$('#login-dialog').addClass('uk-animation-shake');
                        }, 50);
                    }

                    this.update();

                }.bind(this), function(res) {
                    App.ui.notify(res && (res.message || res.error) ? (res.message || res.error) : 'Login failed.', 'danger');
                });

                return false;
            }

            twofavalidate(e) {

                e.preventDefault();

                var code = App.$('[ref=code]').val();

                App.request('/auth/twofavalidate', {"user":this.$user, "code":code}).then(function(data){

                    if (data && data.success) {

                        setTimeout(function(){
                            App.reroute('/');
                        }, 0)

                    } else {

                        App.$('#2fa-dialog').removeClass('uk-animation-shake');

                        setTimeout(function(){
                            App.$('#2fa-dialog').addClass('uk-animation-shake');
                        }, 50);
                    }

                });

            }

            // i18n for uikit-formPassword
            UIkit.components.formPassword.prototype.defaults.lblShow = '@lang("Show")';
            UIkit.components.formPassword.prototype.defaults.lblHide = '@lang("Hide")';

        </script>

    </div>

</body>
</html>
