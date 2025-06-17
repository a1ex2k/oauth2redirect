# OAuth2 Redirect

## Description
This Moodle authentication plugin automatically redirects users from the standard Moodle login page to a pre-configured OAuth 2.0 Identity Provider (IdP). It simplifies the login process by immediately sending users to the chosen OAuth2 provider, bypassing the Moodle username/password form.

The redirect can be bypassed for administrative or troubleshooting purposes by appending `?noredirect=1` to the login page URL (e.g., `yourmoodlesite.com/login/index.php?noredirect=1`).

This plugin relies on Moodle's core "OAuth 2 services" for the actual authentication flow after the redirect. Your chosen OAuth 2.0 provider must be correctly set up there.

## Requirements
*   Moodle 3.x, 4.x or later (developed/tested with Moodle versions where `\core\oauth2\api` is the standard namespace for OAuth2 services).
*   At least one OAuth 2.0 service must be configured and enabled in Moodle under `Site administration > Server > OAuth 2 services`.

## Installation
1.  Download the plugin ZIP file (or clone the repository).
2.  Extract the contents to the `auth/oauth2redirect` directory in your Moodle installation.
3.  Navigate to `Site administration > Notifications`. Moodle should detect the new plugin and prompt you to install it. Click "Upgrade Moodle database now".
4.  [Alternatively] Upload a ZIP file on `Site administration > Plugins > Install plugins`.
5.  Enable the plugin:
    *   Go to `Site administration > Plugins > Authentication > Manage authentication`.
    *   Find "OAuth2 Redirect" in the list and enable it by clicking the closed eye icon (it should turn into an open eye).
    *   You might want to disable other login methods if this is intended to be the primary/sole method (except for self-registration if needed).

## Configuration
1.  After installation and enabling the plugin, navigate to its settings page:
    `Site administration > Plugins > Authentication > OAuth2 Redirect`.
2.  **OAuth2 Provider**: Select the desired OAuth 2.0 service provider from the dropdown list.
    *   This list is populated from the OAuth 2.0 services you have already configured and enabled in Moodle under `Site administration > Server > OAuth 2 services`.
    *   If the list is empty or your desired provider is not showing, ensure it's properly set up and enabled in the core Moodle OAuth 2 services settings first.
3.  Save changes.


## License
The plugin is licensed under the GNU GPL v3.
