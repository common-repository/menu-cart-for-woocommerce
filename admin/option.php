<?php
include(MCFW_PLUGIN_DIR_PATH . 'admin/settings/general-setting.php');
include(MCFW_PLUGIN_DIR_PATH . 'admin/settings/flyout-setting.php');
include(MCFW_PLUGIN_DIR_PATH . 'admin/settings/sticky-cart-setting.php');
include(MCFW_PLUGIN_DIR_PATH . 'admin/settings/design-elements-setting.php');
include(MCFW_PLUGIN_DIR_PATH . 'admin/settings/shortcode-setting.php');

$default_tab = null;
$tab = "";
$tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $default_tab;

if (!class_exists('mcfw_settings')) {

    if ($tab == null) {
        $general  = new mcfw_general_settings();
        add_action('admin_init', array($general, 'register_general_settings_init'));
    }

    if ($tab == "mcfw-flyout") {
        $flyout  = new mcfw_flyout_settings();
        add_action('admin_init', array($flyout, 'register_flyout_settings_init'));
    }

    if ($tab == "mcfw-design-elements") {
        $design  = new mcfw_design_elements_settings();
        add_action('admin_init', array($design, 'register_design_elements_settings_init'));
    }

    if ($tab == "mcfw-sticky-cart") {
        $sticky  = new mcfw_sticky_cart_settings();
        add_action('admin_init', array($sticky, 'register_sticky_cart_settings_init'));
    }

    if ($tab == "mcfw-shortcode") {
        $sticky  = new mcfw_shortcode_settings();
        add_action('admin_init', array($sticky, 'shortcode_settings_init'));
    }

    class mcfw_settings
    {
        public function __construct()
        {
            add_action('admin_menu',  array($this, 'mcfw_admin_menu_setting_page'));
        }

        function mcfw_admin_menu_setting_page()
        {
            if (class_exists('WooCommerce')) {
                add_submenu_page('woocommerce', 'Menu Cart for woocommerce', 'Menu Cart', 'manage_options', 'mcfw-option-page', array($this, 'menu_page_customize_callback'));
            }
        }

        function menu_page_customize_callback()
        {
            $default_tab = null;
            $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $default_tab; ?>
            <div class="wrap">
                <?php settings_errors(); ?>
                <div class="mcfw-main-box">
                    <div class="mcfw-container">
                        <div class="mcfw-header">
                            <h1 class="mcfwp-h1">Menu Cart For WooCommerce</h1>
                        </div>
                        <div class="mcfw-option-section">
                            <div class="mcfw-sidebar">
                                <ul class="mcfw-tab-list">
                                    <li>
                                        <a href="?page=mcfw-option-page" class="nav-tab <?php if ($tab === null) : ?>nav-tab-active<?php endif; ?>">
                                            <span><img src="<?php _e(MCFW_PLUGIN_URL . '/assets/images/gear.png'); ?>" alt="gear image"></span>
                                            <?php esc_html_e('General Settings', 'menu-cart-for-woocommerce'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="?page=mcfw-option-page&tab=mcfw-flyout" class="nav-tab <?php if ($tab === 'mcfw-flyout') : ?>nav-tab-active<?php endif; ?>">
                                            <span><img src="<?php _e(MCFW_PLUGIN_URL . '/assets/images/web-browser.png'); ?>" alt=""></span>
                                            <?php esc_html_e('Flyout Settings', 'menu-cart-for-woocommerce'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="?page=mcfw-option-page&tab=mcfw-sticky-cart" class="nav-tab <?php if ($tab === 'mcfw-sticky-cart') : ?>nav-tab-active<?php endif; ?>">
                                            <span><img src="<?php _e(MCFW_PLUGIN_URL . '/assets/images/add-to-cart.png'); ?>" alt=""></span>
                                            <?php esc_html_e('Sticky Cart Settings', 'menu-cart-for-woocommerce'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="?page=mcfw-option-page&tab=mcfw-design-elements" class="nav-tab <?php if ($tab === 'mcfw-design-elements') : ?>nav-tab-active<?php endif; ?>">
                                            <span><img src="<?php _e(MCFW_PLUGIN_URL . '/assets/images/color-palette.png'); ?>" alt=""></span>
                                            <?php esc_html_e('Design Elements', 'menu-cart-for-woocommerce'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="?page=mcfw-option-page&tab=mcfw-shortcode" class="nav-tab <?php if ($tab === 'mcfw-shortcode') : ?>nav-tab-active<?php endif; ?>">
                                            <span><img src="<?php _e(MCFW_PLUGIN_URL . '/assets/images/coding.png'); ?>" alt=""></span>
                                            <?php _e('Shortcode&nbsp<span class="mcfw-pro-features">Pro!</span>', 'menu-cart-for-woocommerce'); ?>
                                        </a>
                                    </li>
                                    <li>
                                            <a href="?page=mcfw-option-page&tab=mcfw-Pro" class="nav-tab mcfw-Pro-tab <?php if ($tab === 'mcfw-Pro') : ?>nav-tab-active<?php endif; ?>">
                                                <span><img src="<?php _e(MCFW_PLUGIN_URL . '/assets/images/crown.png'); ?>" alt=""></span>
                                                <?php esc_html_e('Get Pro Version', 'menu-cart-for-woocommerce'); ?>
                                            </a>
                                        </li>
                                </ul>
                            </div>

                            <div class="mcfw-tabing-option">
                                <?php if ($tab == null) {
                                    $general  = new mcfw_general_settings();
                                    $general->register_settings_init();
                                }

                                if ($tab == "mcfw-flyout") {
                                    $flyout  = new mcfw_flyout_settings();
                                    $flyout->register_flyout_design_settings_init();
                                }

                                if ($tab == "mcfw-sticky-cart") {
                                    $sticky  = new mcfw_sticky_cart_settings();
                                    $sticky->register_cart_settings_init();
                                }

                                if ($tab == "mcfw-design-elements") {
                                    $design  = new mcfw_design_elements_settings();
                                    $design->design_elements_setting_form_option();
                                } 

                                if ($tab == "mcfw-shortcode") {
                                    $design  = new mcfw_shortcode_settings();
                                    $design->shortcode_setting_form_option();
                                } 

                                if ($tab == "mcfw-Pro") {
                                    include(MCFW_PLUGIN_DIR_PATH . 'admin/settings/pro-features.php');
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    new mcfw_settings();
}
