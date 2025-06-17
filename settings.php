<?php
defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    global $OUTPUT;

    $plugin_name_str_id = 'auth_oauth2redirect';

    $options = [0 => get_string('select_provider_initial', $plugin_name_str_id)];
    $base_setting_description = get_string('config_issuerid_desc', $plugin_name_str_id);
    $setting_description_html = $base_setting_description;

    $messages_for_page_html = '';

    if (!class_exists('\core\oauth2\api')) {
        $critical_error_msg = get_string('error_coreoauth2apimissing', $plugin_name_str_id, '\core\oauth2\api');

        $settings->add(new admin_setting_heading(
            'auth_oauth2redirect/critical_api_error_heading',
            get_string('error_critical_title', $plugin_name_str_id),
            $OUTPUT->notification($critical_error_msg, 'error')
        ));

        $options = [0 => get_string('select_provider_systemerror', $plugin_name_str_id)];
        $setting_description_html .= '<br><span class="error">' . htmlspecialchars($critical_error_msg) . '</span>';
    } else {
        try {
            $issuers = \core\oauth2\api::get_all_issuers(true);

            if (!empty($issuers)) {
                $options = [0 => get_string('select_provider_pleaseselect', $plugin_name_str_id)];
                foreach ($issuers as $issuer) {
                    if ($issuer instanceof \core\oauth2\issuer) { 
                        if ($issuer->is_available_for_login()) { 
                            $options[$issuer->get('id')] = $issuer->get('name');
                        }
                    }
                }

                if (count($options) <= 1 && $options[0] === get_string('select_provider_pleaseselect', $plugin_name_str_id)) {
                    $no_issuers_msg = get_string('no_oauth2_issuers_configured', $plugin_name_str_id);
                    $messages_for_page_html .= $OUTPUT->notification($no_issuers_msg, 'info');
                    $options = [0 => get_string('select_provider_noneavailable', $plugin_name_str_id)];
                    $setting_description_html .= '<br><em>' . htmlspecialchars($no_issuers_msg) . '</em>';
                }

            } else {
                $no_issuers_msg = get_string('no_oauth2_issuers_configured', $plugin_name_str_id);
                $messages_for_page_html .= $OUTPUT->notification($no_issuers_msg, 'info');
                $options = [0 => get_string('select_provider_noneavailable', $plugin_name_str_id)];
                $setting_description_html .= '<br><em>' . htmlspecialchars($no_issuers_msg) . '</em>';
            }
        } catch (\Exception $e) {
            mtrace("Auth Plugin {$plugin_name_str_id}: Error fetching OAuth2 issuers. Message: " . $e->getMessage() . "\n" . $e->getTraceAsString());

            $load_error_msg = get_string('error_loading_issuers', $plugin_name_str_id, htmlspecialchars($e->getMessage()));
            $messages_for_page_html .= $OUTPUT->notification($load_error_msg, 'error');

            $options = [0 => get_string('select_provider_loaderror', $plugin_name_str_id)];
            $setting_description_html .= '<br><span class="error">' . $load_error_msg . '</span>';
        }
    }

    if (!empty($messages_for_page_html)) {
         $settings->add(new admin_setting_html(
             'auth_oauth2redirect/general_messages',
             '',
             $messages_for_page_html
         ));
    }

    $settings->add(new admin_setting_configselect(
        'auth_oauth2redirect/issuerid',
        get_string('config_issuerid', $plugin_name_str_id),
        $setting_description_html,
        0,
        $options
    ));
}