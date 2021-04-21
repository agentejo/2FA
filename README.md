# 2FA Addon for Cockpit

## Installation

Requirements:
* working installation of [cockpit](https://github.com/agentejo/cockpit)
* 2FA Authenticator (2FAS) on your mobile device (e. g. [android](https://play.google.com/store/apps/details?id=com.twofasapp)

1. [Download all files from this repository](https://github.com/agentejo/2FA/archive/refs/heads/master.zip)
2. Unpack it into the addons folder of your existing cockpit installation. It should now contain a folder 2FA-master.
3. Rename this folder from 2FA-master to 2FA

## Enable 2FA for a user

1. Login as usual into cockpit
2. Open the account settings (accounts/account). At the very bottom below the API key you will now see a new section "Two-factor authentication" with a checkbox to enable 2FA. If you do so, it will display a QR Code
3. Scan the QR code with the 2FA application. It will add a new entry called "Cockpit" in the list of the 2FAS App

## Test 2FA for a user

1. Login as usual
2. A new screen appears that asks for "Account validation".
3. Enter the code that is displayed in the 2FAS application.
4. You are logged in as usual.
 
## Troubleshooting

### Couldn't resolve 2fa:views/panel.php. 

This error appears at the bottom of the account settings if you didn't rename the folder to 2FA (see step 3)
