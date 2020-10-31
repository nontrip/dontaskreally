<?php

if (!defined('ABSPATH')) exit;


function instashow_lite_add_action_links($links) {
    $links[] = '<a href="' . esc_url(admin_url('admin.php?page=instashow-lite')) . '">Settings</a>';
    $links[] = '<a href="http://codecanyon.net/user/elfsight/portfolio?ref=Elfsight" target="_blank">More plugins by Elfsight</a>';
    return $links;
}
add_filter('plugin_action_links_' . INSTASHOW_LITE_PLUGIN_SLUG, 'instashow_lite_add_action_links');


function instashow_lite_admin_init() {
    wp_register_style('instashow-lite-admin', plugins_url('assets/instashow-lite-admin.css', INSTASHOW_LITE_FILE));
    wp_register_script('instashow-lite', plugins_url('assets/instashow-lite/dist/jquery.instashow-lite.packaged.js', INSTASHOW_LITE_FILE), array('jquery'), INSTASHOW_LITE_VERSION);
    wp_register_script('instashow-lite-admin', plugins_url('assets/instashow-lite-admin.js', INSTASHOW_LITE_FILE), array('jquery', 'instashow-lite'));
}

function instashow_lite_admin_scripts() {
    wp_enqueue_style('instashow-lite-admin');
    wp_enqueue_script('instashow-lite');
    wp_enqueue_script('instashow-lite-admin');
}

function instashow_lite_create_menu() {
    $page_hook = add_menu_page(__('InstaShow', INSTASHOW_LITE_TEXTDOMAIN) , __('InstaShow', INSTASHOW_LITE_TEXTDOMAIN), 'manage_options', 'instashow-lite', 'instashow_lite_settings_page', plugins_url('assets/img/instashow-wp-icon.png', INSTASHOW_LITE_FILE));
    add_action('admin_init', 'instashow_lite_admin_init');
    add_action('admin_print_styles-' . $page_hook, 'instashow_lite_admin_scripts');
}
add_action('admin_menu', 'instashow_lite_create_menu');


function instashow_lite_underscore_to_cc($l) {
    return strtoupper(substr($l[0], 1));
}


function instashow_lite_update_instagram_connect() {
    if (!wp_verify_nonce($_REQUEST['nonce'], 'instashow_lite_update_instagram_connect_nonce')) {
        exit;
    }

    update_option('instashow_instagram_access_token', !empty($_REQUEST['access_token']) ? $_REQUEST['access_token'] : '');
}
add_action('wp_ajax_instashow_lite_update_instagram_connect', 'instashow_lite_update_instagram_connect');


function instashow_lite_settings_page() {
    global $instashow_lite_defaults;

    $logout = !empty($_GET['logout']) && $_GET['logout'] === 'true';
    $access_token = !$logout && !empty($_GET['__is_access_token']) ? $_GET['__is_access_token'] : '';

    if ($access_token || $logout) {
        update_option('instashow_instagram_access_token', $access_token);

        echo '<script>document.location.href = \'' . admin_url('admin.php?page=instashow-lite') . '\'</script>';
        exit;
    }

    $access_token = get_option('instashow_instagram_access_token', '');

    $instashow_json = array();

    foreach ($instashow_lite_defaults as $name => $val) {
        $instashow_json[preg_replace_callback('/(_.)/', 'instashow_lite_underscore_to_cc', $name)] = $val;
    }

    ?><div class="instashow-admin wrap">
        <h2 class="instashow-admin-wp-messages-hack"></h2>
        
        <?php if (INSTASHOW_LITE_SUPPORT_LINK) { ?>
            <div class="elfsight-support elfsight-support--hidden">
                <a class="elfsight-support-close">+</a>
                <div class="elfsight-support-heading"><span class="elfsight-support-heading-icon"></span> <?php _e("Need help?", INSTASHOW_LITE_TEXTDOMAIN); ?></div>
                <div class="elfsight-support-text"><?php _e("If you have any question about our plugin or you need help with its installation, leave us a message and we'll glad to help you absolutely for free!", INSTASHOW_LITE_TEXTDOMAIN); ?></div>
                <a class="elfsight-support-button" target="_blank" href="<?php echo INSTASHOW_LITE_SUPPORT_LINK; ?>"><?php _e("GET FREE HELP", INSTASHOW_LITE_TEXTDOMAIN); ?></a>
                <a class="elfsight-support-nevershow"><?php _e("Never show again", INSTASHOW_LITE_TEXTDOMAIN); ?></a>
            </div>
        <?php } ?>


        <div class="instashow-admin-header">
            <div class="instashow-admin-header-pro">
                <a class="instashow-admin-header-pro-button" href="<?php echo INSTASHOW_LITE_PRO_URL; ?>" target="_blank"><?php _e('Upgrade to Pro', INSTASHOW_LITE_TEXTDOMAIN); ?></a>

                <div class="instashow-admin-header-pro-text"><?php _e('Unlock 60+ awesome features', INSTASHOW_LITE_TEXTDOMAIN); ?></div>
            </div>

            <!-- <div class="instashow-admin-header-support">
                <span class="instashow-admin-icon-support instashow-admin-icon"></span>

                <h3 class="instashow-admin-header-support-title"><?php _e('Support', INSTASHOW_LITE_TEXTDOMAIN); ?></h3>

                <a class="instashow-admin-header-support-email" href="mailto:support@elfsight.com">
                    support@elfsight.com 
                    <svg class="instashow-admin-svg-arrow-more">
                        <line x1="0" y1="0" x2="4" y2="4"></line>
                        <line x1="0" y1="8" x2="4" y2="4"></line>
                    </svg>
                </a>

                <div class="instashow-admin-header-support-description"><?php _e('Face any issue installing our plugin? Got some ideas to improve it?<br>Reach us via email and we will answer any question!', INSTASHOW_LITE_TEXTDOMAIN); ?></div>
            </div> -->

            <a class="instashow-admin-header-logo" href="<?php echo admin_url('admin.php?page=instashow-lite'); ?>" title="<?php _e('InstaShow Lite - WordPress Instagram Feed', INSTASHOW_LITE_TEXTDOMAIN); ?>">
                <img src="<?php echo plugins_url('assets/img/logo.png', INSTASHOW_LITE_FILE); ?>" width="349" height="63" alt="<?php _e('InstaShow Lite - WordPress Instagram Feed', INSTASHOW_LITE_TEXTDOMAIN); ?>">
            </a>

            <div class="instashow-admin-header-title"><?php _e('WordPress Instagram Feed', INSTASHOW_LITE_TEXTDOMAIN); ?></div>
        </div>

        <div class="instashow-admin-instagram-connect<?php echo !empty($access_token) ? ' instashow-admin-instagram-connected' : ''?> instashow-admin-block">
            <div class="instashow-admin-block-icon"><span class="instashow-admin-icon-key instashow-admin-icon"></span></div>

            <div class="instashow-admin-block-inner">
                <h2><?php _e('Connect to Instagram', INSTASHOW_LITE_TEXTDOMAIN); ?></h2>

                <div class="instashow-admin-instagram-connect-access-token">
                    <div class="instashow-admin-instagram-connect-access-token-text">
                        <h3><?php _e('Instagram Access Token', INSTASHOW_LITE_TEXTDOMAIN); ?></h3>

                        <?php if (!$access_token) {?>
                            <p><?php _e('You need to authorize in Instagram to get an access token.', INSTASHOW_LITE_TEXTDOMAIN); ?></p>

                        <?php } else {?>
                            <p><?php printf(__('You have got Instagram access token: <strong>%s</strong>', INSTASHOW_LITE_TEXTDOMAIN), $access_token);?></p>
                        <?php }?>
                    </div>

                    <?php if (!$access_token) {?>
                        <a class="instashow-admin-instagram-connect-access-token-button" href="javascript:void(0)"><?php _e('Authorize in Instagram', INSTASHOW_LITE_TEXTDOMAIN); ?></a>

                    <?php } else {?>
                        <a class="instashow-admin-instagram-connect-access-token-logout" href="<?php echo admin_url('admin.php?page=instashow-lite&logout=true'); ?>"><?php _e('Log out', INSTASHOW_LITE_TEXTDOMAIN); ?></a>
                    <?php }?>
                </div>

                <div class="instashow-admin-instagram-connect-note">
                    <h3><?php _e('Note:', INSTASHOW_LITE_TEXTDOMAIN); ?></h3>

                    <ul class="instashow-admin-instagram-connect-note-list">
                        <li><?php _e('Works with @usernames, #hashtags, locations', INSTASHOW_LITE_TEXTDOMAIN); ?></li>
                        <li><?php _e('24 color options and 10 predefined color schemes', INSTASHOW_LITE_TEXTDOMAIN); ?></li>
                        <li><?php _e('Premium support', INSTASHOW_LITE_TEXTDOMAIN); ?></li>
                    </ul>

                    <p><?php _e('Are available in <b>PRO version</b>!', INSTASHOW_LITE_TEXTDOMAIN); ?></p>

                    <a class="instashow-admin-instagram-connect-note-button" href="<?php echo INSTASHOW_LITE_PRO_URL;?>" target="_blank"><?php _e('Upgrade to PRO', INSTASHOW_LITE_TEXTDOMAIN); ?></a>
                </div>
            </div>
        </div>

        <div class="instashow-admin-demo instashow-admin-block">
            <div class="instashow-admin-block-icon"><span class="instashow-admin-icon-settings instashow-admin-icon"></span></div>

            <div class="instashow-admin-block-inner">
                <div class="instashow-admin-demo-header">
                    <h2><?php _e('Installation', INSTASHOW_LITE_TEXTDOMAIN); ?></h2>
                    <span class="instashow-admin-demo-header-hint"><?php _e('Adjust the widget as you wish, get the shortcode and paste it into any page or post.', INSTASHOW_LITE_TEXTDOMAIN); ?></span>
                </div>

                <?php include(INSTASHOW_LITE_PATH . '/includes/instashow-demo.php'); ?>

                <script>
                    function getInstaShowDefaults() {
                        return <?php echo json_encode($instashow_json); ?>;
                    }
                </script>
            </div>
        </div>

        <div class="instashow-admin-pro">
            <div class="instashow-admin-pro-features">
                <h2><?php _e('Lite vs Pro', INSTASHOW_LITE_TEXTDOMAIN); ?></h2>

                <table class="instashow-admin-pro-features-list">
                    <thead>
                        <tr>
                            <th class="instashow-admin-pro-features-list-heading-options"><?php _e('Options', INSTASHOW_LITE_TEXTDOMAIN); ?></th>
                            <th class="instashow-admin-pro-features-list-heading-lite"><?php _e('Lite', INSTASHOW_LITE_TEXTDOMAIN); ?></th>
                            <th class="instashow-admin-pro-features-list-heading-pro">
                                <a href="<?php echo INSTASHOW_LITE_DEMO_URL; ?>" target="_blank">
                                    <?php _e('Pro', INSTASHOW_LITE_TEXTDOMAIN); ?><br>
                                    <span><?php _e('Try Demo Now', INSTASHOW_LITE_TEXTDOMAIN); ?></span>
                                </a>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Source', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite">1</td>
                            <td class="instashow-admin-pro-features-list-item-pro"><?php _e('Unlimited', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Cache media time', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Columns', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Rows', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite">1 or 2</td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Effect', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Speed', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Easing', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Arrows control', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Drag control', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Auto', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Auto pause on hover', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Popup speed', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Popup easing', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Filter only', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Filter except', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Filter', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Limit', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Width', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Height', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Gutter', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Responsive breakpoints', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Direction', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Free mode', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Scroll control', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Scrollbar', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Languages', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Mode', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Info', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Popup info', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Popup deep linking', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Gallery background color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Gallery counters color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Gallery description color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Gallery overlay color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Gallery arrows color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Gallery arrows on hover color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Gallery arrows background color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Gallery arrows background on hover color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Gallery scrollbar color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Gallery scrollbar slider color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Popup overlay color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Popup background color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Popup username color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Popup username on hover color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Popup Instagram link color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Popup Instagram link on hover color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Popup counters color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Popup passed time color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Popup anchor color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Popup anchor on hover color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Popup text color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Popup controls color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Popup controls on hover color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Popup mobile controls color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Popup mobile controls background color', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><span class="instashow-admin-icon-feature-not-available instashow-admin-icon"></span></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><span class="instashow-admin-icon-feature-available instashow-admin-icon"></span></td>
                        </tr>
                        <tr>
                            <td class="instashow-admin-pro-features-list-item-option"><?php _e('Support', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-lite"><?php _e('Bug fixes', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                            <td class="instashow-admin-pro-features-list-item-pro"><?php _e('Full support 24/7', INSTASHOW_LITE_TEXTDOMAIN); ?></td>
                        </tr>


                        <tr class="instashow-admin-pro-features-upgrade-row">
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="instashow-admin-pro-features-upgrade">
                                <a href="<?php echo INSTASHOW_LITE_PRO_URL; ?>" target="_blank"><?php _e('Upgrade to Pro', INSTASHOW_LITE_TEXTDOMAIN); ?></a>
                            </td>
                    </tbody>
                </table>
            </div>

            <div class="instashow-admin-pro-key-features">
                <h2><?php _e('Key Features of Pro Version', INSTASHOW_LITE_TEXTDOMAIN); ?></h2>
                <img src="<?php echo plugins_url('assets/img/pro-key-features.jpg', INSTASHOW_LITE_FILE); ?>">
            </div>
        </div>
    </div>
<?php } ?>