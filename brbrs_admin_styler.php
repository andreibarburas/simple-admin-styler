<?php
/**
 * Plugin Name: Simple Admin Styler
 * Plugin URI:  https://github.com/andreibarburas/simple-admin-styler
 * Description: Customize the WordPress login page and dashboard — background image, font size, admin bar, and dashboard widget visibility.
 * Version:     1.1.0
 * Text Domain: simple-admin-styler
 * Author:      andrei BARBURAS
 * Author URI:  https://barburas.com
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.0
 * Requires PHP:      8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ─────────────────────────────────────────────
// 0. AUTO-UPDATES via Plugin Update Checker
// ─────────────────────────────────────────────

$brbrs_as_puc = plugin_dir_path( __FILE__ ) . 'vendor/plugin-update-checker/plugin-update-checker.php';
if ( file_exists( $brbrs_as_puc ) ) {
    require_once $brbrs_as_puc;
    $brbrs_as_updater = YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
        'https://github.com/andreibarburas/simple-admin-styler/',
        __FILE__,
        'simple-admin-styler'
    );
    $brbrs_as_updater->setBranch( 'main' );
    $brbrs_as_updater->getVcsApi()->enableReleaseAssets();
}

define( 'BRBRS_AS_VERSION', '1.1.0' );
define( 'BRBRS_AS_OPTION',  'simple_admin_styler_settings' );

// ─────────────────────────────────────────────
// 1. LOGIN PAGE — enqueue dynamic CSS
// ─────────────────────────────────────────────

function brbrs_as_login_styles() {
    $opts      = brbrs_as_get_options();
    $bg_url    = esc_url( $opts['login_bg_url'] );
    $font_size = absint( $opts['login_font_size'] );
    $logo_url  = esc_url( $opts['login_logo_url'] );

    $css = "
.login {
    background-color: #666666;
    display: block;
    width: 100%;
    height: 100%;
    left: 0;
    overflow: auto;
    position: absolute;
    top: 0;
    margin: 0;
    padding: 0;
    text-rendering: optimizeLegibility;
}
";

    if ( $bg_url ) {
        $css .= "
.login {
    background-image: url('{$bg_url}');
    background-repeat: repeat-x;
    background-attachment: fixed;
    background-size: cover;
}
";
    }

    if ( $font_size ) {
        $css .= "
.login {
    font-size: {$font_size}px !important;
}
";
    }

    if ( $logo_url ) {
        $css .= "
#login h1 a {
    background-image: url('{$logo_url}') !important;
    background-size: contain !important;
    background-repeat: no-repeat !important;
    background-position: center !important;
    width: 100% !important;
    height: 80px !important;
}
";
    }

    $css .= "
#nav, #backtoblog {
    display: none;
}
#login {
    width: 100% !important;
}
#loginform {
    background-color: rgba(255, 255, 255, .8);
    width: 33.33%;
    left: auto;
    right: auto;
    margin: 0 auto;
}
@media screen and (max-width: 780px) {
    #loginform {
        width: 66.66%;
    }
}
#login form p {
    line-height: 3.3em;
}
.backup-methods-wrap {
    text-align: center;
}
";

    wp_register_style( 'brbrs-as-login', false );
    wp_enqueue_style( 'brbrs-as-login' );
    wp_add_inline_style( 'brbrs-as-login', $css );
}
add_action( 'login_enqueue_scripts', 'brbrs_as_login_styles' );

function brbrs_as_login_logo_url() {
    $opts = brbrs_as_get_options();
    if ( ! empty( $opts['login_logo_url'] ) ) {
        return home_url();
    }
    return 'https://wordpress.org/';
}
add_filter( 'login_headerurl', 'brbrs_as_login_logo_url' );


// ─────────────────────────────────────────────
// 2. ADMIN BAR
// ─────────────────────────────────────────────

function brbrs_as_maybe_hide_admin_bar() {
    $opts = brbrs_as_get_options();
    if ( ! empty( $opts['hide_admin_bar'] ) ) {
        show_admin_bar( false );
    }
}
add_action( 'after_setup_theme', 'brbrs_as_maybe_hide_admin_bar' );


// ─────────────────────────────────────────────
// 3. DASHBOARD METABOXES — remove disabled ones
// ─────────────────────────────────────────────

function brbrs_as_remove_dashboard_widgets() {
    $opts     = brbrs_as_get_options();
    $disabled = isset( $opts['disabled_widgets'] ) ? (array) $opts['disabled_widgets'] : array();

    $all_widgets = brbrs_as_widget_definitions();

    foreach ( $all_widgets as $id => $widget ) {
        if ( in_array( $id, $disabled, true ) ) {
            if ( $id === 'welcome_panel' ) {
                remove_action( 'welcome_panel', 'wp_welcome_panel' );
            } else {
                remove_meta_box( $id, 'dashboard', $widget['context'] );
            }
        }
    }
}
add_action( 'admin_init', 'brbrs_as_remove_dashboard_widgets' );


// ─────────────────────────────────────────────
// 4. SETTINGS PAGE
// ─────────────────────────────────────────────

function brbrs_as_add_settings_page() {
    add_options_page(
        'Simple Admin Styler',
        'Admin Styler',
        'manage_options',
        'simple-admin-styler',
        'brbrs_as_render_settings_page'
    );
}
add_action( 'admin_menu', 'brbrs_as_add_settings_page' );

function brbrs_as_register_settings() {
    register_setting(
        'brbrs_as_settings_group',
        BRBRS_AS_OPTION,
        array(
            'sanitize_callback' => 'brbrs_as_sanitize_options',
        )
    );
}
add_action( 'admin_init', 'brbrs_as_register_settings' );

function brbrs_as_render_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $opts            = brbrs_as_get_options();
    $all_widgets     = brbrs_as_widget_definitions();
    $disabled        = isset( $opts['disabled_widgets'] ) ? (array) $opts['disabled_widgets'] : array();
    $settings_url    = admin_url( 'options-general.php?page=simple-admin-styler' );
    ?>
    <div class="wrap brbrs-as-wrap">
        <h1>Simple Admin Styler</h1>
        <p class="brbrs-as-subtitle">Customize your WordPress login page and dashboard.</p>

        <?php settings_errors(); ?>

        <form method="post" action="options.php">
            <?php settings_fields( 'brbrs_as_settings_group' ); ?>

            <div class="brbrs-as-sections">

                <!-- LOGIN PAGE -->
                <div class="brbrs-as-section">
                    <h2>Login Page</h2>

                    <table class="form-table brbrs-as-table" role="presentation">
                        <tr>
                            <th scope="row">
                                <label for="brbrs_as_login_bg_url">Background Image URL</label>
                            </th>
                            <td>
                                <input
                                    type="url"
                                    id="brbrs_as_login_bg_url"
                                    name="<?php echo esc_attr( BRBRS_AS_OPTION ); ?>[login_bg_url]"
                                    value="<?php echo esc_attr( $opts['login_bg_url'] ); ?>"
                                    class="regular-text"
                                    placeholder="https://example.com/image.jpg"
                                />
                                <p class="description">Full URL to the background image for the login screen.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="brbrs_as_login_logo_url">Logo Image URL</label>
                            </th>
                            <td>
                                <input
                                    type="url"
                                    id="brbrs_as_login_logo_url"
                                    name="<?php echo esc_attr( BRBRS_AS_OPTION ); ?>[login_logo_url]"
                                    value="<?php echo esc_attr( $opts['login_logo_url'] ); ?>"
                                    class="regular-text"
                                    placeholder="https://example.com/logo.png"
                                />
                                <p class="description">Replaces the WordPress logo above the login form. Leave blank to keep the default.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="brbrs_as_login_font_size">Font Size (px)</label>
                            </th>
                            <td>
                                <input
                                    type="number"
                                    id="brbrs_as_login_font_size"
                                    name="<?php echo esc_attr( BRBRS_AS_OPTION ); ?>[login_font_size]"
                                    value="<?php echo esc_attr( $opts['login_font_size'] ); ?>"
                                    class="small-text"
                                    min="8"
                                    max="72"
                                    placeholder="12"
                                />
                                <p class="description">Base font size for the login page. Leave blank to use the theme default.</p>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- ADMIN BAR -->
                <div class="brbrs-as-section">
                    <h2>Admin Bar</h2>
                    <table class="form-table brbrs-as-table" role="presentation">
                        <tr>
                            <th scope="row">Hide Admin Bar</th>
                            <td>
                                <label class="brbrs-as-toggle">
                                    <input
                                        type="checkbox"
                                        name="<?php echo esc_attr( BRBRS_AS_OPTION ); ?>[hide_admin_bar]"
                                        value="1"
                                        <?php checked( ! empty( $opts['hide_admin_bar'] ) ); ?>
                                    />
                                    <span class="brbrs-as-toggle-slider"></span>
                                </label>
                                <p class="description">Hide the admin toolbar for all users on the front end.</p>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- DASHBOARD WIDGETS -->
                <div class="brbrs-as-section">
                    <h2>Dashboard Widgets</h2>
                    <p class="description">Toggle each dashboard widget on or off. Disabled widgets are hidden from the dashboard.</p>

                    <table class="form-table brbrs-as-table" role="presentation">
                        <?php foreach ( $all_widgets as $id => $widget ) : ?>
                            <tr>
                                <th scope="row"><?php echo esc_html( $widget['label'] ); ?></th>
                                <td>
                                    <label class="brbrs-as-toggle">
                                        <input
                                            type="checkbox"
                                            name="<?php echo esc_attr( BRBRS_AS_OPTION ); ?>[disabled_widgets][]"
                                            value="<?php echo esc_attr( $id ); ?>"
                                            <?php checked( in_array( $id, $disabled, true ) ); ?>
                                        />
                                        <span class="brbrs-as-toggle-slider"></span>
                                    </label>
                                    <span class="brbrs-as-widget-status">
                                        <?php echo in_array( $id, $disabled, true ) ? '<span class="brbrs-status-off">Hidden</span>' : '<span class="brbrs-status-on">Visible</span>'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

            </div><!-- .brbrs-as-sections -->

            <?php submit_button( 'Save Settings' ); ?>
        </form>
    </div><!-- .wrap -->
    <?php
}


// ─────────────────────────────────────────────
// 5. ENQUEUE SETTINGS PAGE STYLES
// ─────────────────────────────────────────────

function brbrs_as_admin_styles( $hook ) {
    if ( 'settings_page_simple-admin-styler' !== $hook ) {
        return;
    }
    wp_enqueue_style(
        'brbrs-as-admin',
        plugin_dir_url( __FILE__ ) . 'css/admin.css',
        array(),
        BRBRS_AS_VERSION
    );
}
add_action( 'admin_enqueue_scripts', 'brbrs_as_admin_styles' );


// ─────────────────────────────────────────────
// 6. HELPERS
// ─────────────────────────────────────────────

function brbrs_as_get_options() {
    $defaults = array(
        'login_bg_url'     => '',
        'login_logo_url'   => '',
        'login_font_size'  => '',
        'hide_admin_bar'   => 0,
        'disabled_widgets' => array(),
    );

    $saved = get_option( BRBRS_AS_OPTION, array() );

    return wp_parse_args( $saved, $defaults );
}

function brbrs_as_sanitize_options( $input ) {
    $clean = array();

    $clean['login_bg_url']    = isset( $input['login_bg_url'] ) ? esc_url_raw( trim( $input['login_bg_url'] ) ) : '';
    $clean['login_logo_url']  = isset( $input['login_logo_url'] ) ? esc_url_raw( trim( $input['login_logo_url'] ) ) : '';
    $clean['login_font_size'] = isset( $input['login_font_size'] ) ? absint( $input['login_font_size'] ) : 0;
    $clean['hide_admin_bar']  = ! empty( $input['hide_admin_bar'] ) ? 1 : 0;

    $valid_widget_ids = array_keys( brbrs_as_widget_definitions() );
    $clean['disabled_widgets'] = array();

    if ( ! empty( $input['disabled_widgets'] ) && is_array( $input['disabled_widgets'] ) ) {
        foreach ( $input['disabled_widgets'] as $widget_id ) {
            if ( in_array( $widget_id, $valid_widget_ids, true ) ) {
                $clean['disabled_widgets'][] = sanitize_key( $widget_id );
            }
        }
    }

    return $clean;
}

function brbrs_as_widget_definitions() {
    return array(
        'dashboard_right_now'        => array( 'label' => 'At a Glance',        'context' => 'normal' ),
        'dashboard_activity'         => array( 'label' => 'Activity',            'context' => 'normal' ),
        'dashboard_quick_press'      => array( 'label' => 'Quick Draft',         'context' => 'side'   ),
        'dashboard_primary'          => array( 'label' => 'WordPress News',      'context' => 'side'   ),
        'dashboard_recent_comments'  => array( 'label' => 'Recent Comments',     'context' => 'normal' ),
        'dashboard_recent_drafts'    => array( 'label' => 'Recent Drafts',       'context' => 'side'   ),
        'dashboard_incoming_links'   => array( 'label' => 'Incoming Links',      'context' => 'normal' ),
        'dashboard_plugins'          => array( 'label' => 'Plugins',             'context' => 'normal' ),
        'dashboard_site_health'      => array( 'label' => 'Site Health Status',  'context' => 'side'   ),
        'wpdm_dashboard_widget'      => array( 'label' => 'WP Download Manager', 'context' => 'side'   ),
        'simple_history_dashboard_widget' => array( 'label' => 'Simple History', 'context' => 'side'   ),
        'welcome_panel'              => array( 'label' => 'Welcome Panel',        'context' => 'normal' ),
    );
}
