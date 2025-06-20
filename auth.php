<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/authlib.php');

class auth_plugin_oauth2redirect extends auth_plugin_base {

    public function __construct() {
        $this->authtype = 'oauth2redirect';
        $this->config = get_config('auth_oauth2redirect');
    }

    public function loginpage_hook() {
        global $SESSION, $CFG;

        if (isloggedin() && !isguestuser()) {
            return;
        }

        if (optional_param('noredirect', 0, PARAM_INT) == 1) {
            return;
        }

        $issuerid = !empty($this->config->issuerid) ? (int)$this->config->issuerid : 0;

        if (empty($issuerid)) {
            return;
        }

        if (!class_exists('\core\oauth2\api')) { 
            mtrace("Auth OAuth2Redirect Plugin: Critical - Moodle core OAuth2 API class ('\\core\\oauth2\\api') not found. Cannot perform redirect.");
            return;
        }
       
        $wantsurl = $SESSION->wantsurl;
        if (empty($wantsurl) || strpos(trim(parse_url($wantsurl, PHP_URL_PATH), '/'), 'auth') === 0) {
            $wantsurl = '/my/';
        }
        try {
            $loginurl = new \moodle_url('/auth/oauth2/login.php', [
                'id' => $issuerid,
                'sesskey' => sesskey(),
                'wantsurl' => $wantsurl
            ]);
            if ($loginurl instanceof \moodle_url && $loginurl->out(false) !== '') {
                redirect($loginurl);
            } else {
                mtrace("Auth OAuth2Redirect Plugin: Failed to construct a Moodle login URL for OAuth2 issuer ID {$issuerid}. Cannot redirect.");
            }
        } catch (\Exception $e) { 
            mtrace("Auth OAuth2Redirect Plugin: Exception while preparing redirect for OAuth2 issuer ID {$issuerid}. Message: " . $e->getMessage() . ". Trace: " . $e->getTraceAsString());
            return;
        }
    }

    public function user_login($username, $password) {
        return false;
    }

    public function is_internal() {
        return false;
    }

    public function can_change_password() {
        return false;
    }

    public function change_password_url() {
        return '';
    }

    public function login_form() {
        return '';
    }
}
