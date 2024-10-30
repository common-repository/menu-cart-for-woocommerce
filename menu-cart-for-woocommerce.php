<?php
/*
Plugin Name: Menu Cart For Woocommerce
Description: Use our best, most professional, an innovative plugin to show your cart to the next level. This plugin allows you to show your cart details on the menu. There is no need to go on the cart page; it lets you see your cart detail wherever you are.
Author: Geek Code Lab
Version: 1.9.2
WC tested up to: 9.2.3
Requires Plugins: woocommerce
Author URI: https://geekcodelab.com/
Text Domain : menu-cart-for-woocommerce
*/

if (!defined('ABSPATH')) exit;

if (!defined("MCFW_PLUGIN_DIR_PATH"))

    define("MCFW_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));

if (!defined("MCFW_PLUGIN_URL"))

    define("MCFW_PLUGIN_URL", plugins_url() . '/' . basename(dirname(__FILE__)));

define("MCFW_BUILD", '1.9.2');

/** Register activation default settings */
register_activation_hook(__FILE__, 'mcfw_plugin_active_menu_cart_for_woocommerce');
function mcfw_plugin_active_menu_cart_for_woocommerce(){
    if (is_plugin_active( 'menu-cart-for-woocommerce-pro/menu-cart-for-woocommerce-pro.php' ) ) {		
		deactivate_plugins('menu-cart-for-woocommerce-pro/menu-cart-for-woocommerce-pro.php');
   	} 

    $mcfw_general_defaults_options = array(
        'always_display'    => 'on',
        'price_format'      => 'currency_symbol',       
        'cart_icon'         => 'cart1',
        'menu_cart_formats' => 'icon_items',
        'page_redirect'     => 'cart',
    );

    $mcfw_flyout_defaults_options = array(
        'product_image'     => 'on',
        'product_name'      => 'on',
        'product_price'     => 'on',
        'product_quantity'  => 'on',
        'flyout_contents'   => 'hover',
        'page_redirect'     => 'cart',
        'total_price_type'  => 'subtotal',
        'max_products'      => '5',
        'cart_checkout_btn' => 'cart',
        'cart_btn_txt'      => 'View cart',
        'checkout_btn_txt'  => 'Checkout',
        'empty_note_txt'    => 'Your Cart Is Currently Empty.'
    );

    $mcfw_sticky_defaults_options = array(
        'item_count'            => 'yes',
        'sticky_cart_position'  => 'mcfw_cart_bottom_right'
    );

    $mcfw_design_defaults_options = array(
        'currency_position' => 'mcfw_currency_postion_left_withspace',
        'cart_shape'        => 'mcfw_round_cart',
        'btns_border'       => ',none,'
    );

    $mcfw_general_options       = get_option('mcfw_general_options');
    $mcfw_flyout_options        = get_option('mcfw_flyout_options');
    $mcfw_sticky_cart_options   = get_option('mcfw_sticky_cart_options');
    $mcfw_design_options        = get_option('mcfw_design_options');


    if ($mcfw_general_options == false || $mcfw_flyout_options == false || $mcfw_sticky_cart_options == false || $mcfw_design_options == false) {
        update_option('mcfw_general_options', $mcfw_general_defaults_options);
        update_option('mcfw_flyout_options', $mcfw_flyout_defaults_options);
        update_option('mcfw_sticky_cart_options', $mcfw_sticky_defaults_options);
        update_option('mcfw_design_options', $mcfw_design_defaults_options);
    }
}

require_once(MCFW_PLUGIN_DIR_PATH . 'admin/option.php');
require_once(MCFW_PLUGIN_DIR_PATH . 'front/index.php');

/** Enqueue admin assets */
add_action('admin_enqueue_scripts', 'mcfw_admin_style');
function mcfw_admin_style( $hook )
{
    if (is_admin() && $hook == 'woocommerce_page_mcfw-option-page' ) {
        $js        =    plugins_url('/assets/js/admin-script.js', __FILE__);
        wp_enqueue_style('mcfw_coloris_style', plugins_url('assets/css/coloris.min.css', __FILE__), '', MCFW_BUILD);
        wp_enqueue_style('mcfw-select2-style', plugins_url('/assets/css/select2.min.css', __FILE__), '', MCFW_BUILD);
        wp_enqueue_style('mcfw_admin_style', plugins_url('assets/css/admin-style.css', __FILE__), '', MCFW_BUILD);

        wp_enqueue_script('mcfw-admin-select2-js', plugins_url('/assets/js/select2.min.js', __FILE__), array('jquery'), MCFW_BUILD);
        wp_enqueue_script('mcfw-admin-coloris-js', plugins_url('/assets/js/coloris.min.js', __FILE__), array('jquery'), MCFW_BUILD);
        wp_enqueue_script('mcfw_admin_js',  plugins_url('/assets/js/admin-script.js', __FILE__), array('jquery', 'wp-color-picker'), MCFW_BUILD);
    }
}

/** Enqueue front assets */
add_action('wp_enqueue_scripts', 'mcfw_include_front_script');
function mcfw_include_front_script()
{
    wp_enqueue_style("mcfw_front_style", plugins_url("/assets/css/front_style.css", __FILE__), '', MCFW_BUILD);
    wp_enqueue_script( 'wc-cart-fragments' );
    wp_enqueue_script('mcfw-front-js', plugins_url('/assets/js/front-script.js', __FILE__), array('jquery'), MCFW_BUILD);
    wp_localize_script('mcfw-front-js', 'mcfwObj', ['ajaxurl' => admin_url('admin-ajax.php'), 'general_data' => get_option('mcfw_general_options')]);
}

/** Plugin setting links */
function mcfw_plugin_add_settings_link($links)
{
    $support_link = '<a href="https://geekcodelab.com/contact/"  target="_blank" >' . __('Support','menu-cart-for-woocommerce') . '</a>';
    array_unshift($links, $support_link);

    $settings_link = '<a href="admin.php?page=mcfw-option-page">' . __('Settings','menu-cart-for-woocommerce') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'mcfw_plugin_add_settings_link');

/** Menu cart navigation menu filters */
add_filter('woocommerce_add_to_cart_fragments', 'mcfw_update_menu_count');
add_filter('woocommerce_add_to_cart_fragments', 'mcfw_update_flyout');
add_filter('woocommerce_add_to_cart_fragments', 'mcfw_update_sticky_count');

/** 
 * Menu cart navigation menu html
 */
function mcfw_update_menu_count($fragments){

    ob_start();
    $mcfw_general_options1 = get_option('mcfw_general_options');
    $mcfw_flyout_options1 = get_option('mcfw_flyout_options');
   

    $items_count = esc_attr(count(WC()->cart->get_cart()));
 
    $currency = esc_attr(($mcfw_general_options1['price_format'] == 'currency') ? get_woocommerce_currency() : get_woocommerce_currency_symbol());
    $menu_cart_formats = $mcfw_general_options1['menu_cart_formats'];

    if ( WC()->cart->display_prices_including_tax() ) {
        $menu_price = (float) WC()->cart->get_subtotal() + (float) WC()->cart->get_subtotal_tax();
    } else {
        $menu_price = (float) WC()->cart->get_subtotal();
    }

    $output = '';
    $item_join_label = ((isset($items_count)) && ($items_count > 1)) ? 'items' : 'item' ;
    $mcfw_currency_class =  (isset($mcfw_design_options['currency_position']) ? $mcfw_design_options['currency_position'] : ''); 
    
    $price_currency = '<span class="mcfw-mini-cart-price-wp"><span class="mcfw-flyout-currency">' . $currency . '</span>'.$menu_price . '</span>';
    switch ($menu_cart_formats) {
        case 'icon_only':
            $output .= '';
            break;

        case 'icon_price':
            $output .= $price_currency;
            break;

        default:
            $output .= '' . $items_count . ' '.$item_join_label.' </span>';
            break;
    } ?>  
    <span class="mcfw-mini-product-price-html ">
        <?php _e($output); ?>
    </span>
    <?php
    $fragments['.mcfw-mini-product-price-html'] = ob_get_clean();
    return $fragments;
}

/**
 * Menu cart navigation flyout html
 */
function mcfw_update_flyout($fragments) {
    ob_start();
    $mcfw_flyout_options1 = get_option('mcfw_flyout_options');
    $mcfw_general_options1 = get_option('mcfw_general_options');
    $items_count = esc_attr(count(WC()->cart->get_cart()));
    $currency = esc_attr(($mcfw_general_options1['price_format'] == 'currency') ? get_woocommerce_currency() : get_woocommerce_currency_symbol());

    if (!empty($mcfw_flyout_options1['flyout_status'])  && ($mcfw_flyout_options1['flyout_status'] == 'on')) {
        $allow_pro_image    = isset($mcfw_flyout_options1['product_image']) ? $mcfw_flyout_options1['product_image'] : '' ;
        $allow_pro_name     = isset($mcfw_flyout_options1['product_name']) ? $mcfw_flyout_options1['product_name'] : '' ;
        $allow_pro_price    = isset($mcfw_flyout_options1['product_price']) ? $mcfw_flyout_options1['product_price'] : '' ;
        $allow_pro_quantity = isset($mcfw_flyout_options1['product_quantity']) ? $mcfw_flyout_options1['product_quantity'] : '' ;
        $allow_pro_link     = isset($mcfw_flyout_options1['product_link']) ? $mcfw_flyout_options1['product_link'] : '' ;
        $allow_pro_total    = isset($mcfw_flyout_options1['product_total']) ? $mcfw_flyout_options1['product_total'] : '' ;
        $allow_remove_icon  = isset($mcfw_flyout_options1['remove_product_icon']) ? $mcfw_flyout_options1['remove_product_icon'] : '' ;
        $empty_note_txt     = 'Your Cart Is Currently Empty.'; ?>

        <div class="mcfw-mini-cart-main">
            <?php 
            if ($items_count != 0) { ?>
                <div class="mcfw-flyout-product-list">
                    <?php
                    $i = 1;
                    $mcfe_total = (($mcfw_flyout_options1['max_products'] == 'all') ? $items_count : $mcfw_flyout_options1['max_products']);

                    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                        if ($i <= $mcfe_total) {
                            $i++;
                            $product_img = esc_url(get_the_post_thumbnail_url($product_id));
                            $product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                            $product_subtotal = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                            <div class="mcfw-flyout-product">
                                <?php 
                                if(!empty($allow_pro_image)){
                                    ?>
                                    <div class="mcfw-pro-img">
                                        <?php
                                        if(!empty($allow_pro_link)){ ?>
                                            <a href="<?php esc_attr_e(get_permalink($product_id)); ?>" title="<?php esc_attr_e($_product->get_name()); ?>" ><img src="<?php esc_attr_e($product_img); ?>" alt="" width="70" height="70" alt="<?php esc_attr_e($_product->get_name()); ?>"></a>
                                            <?php
                                        }else{ ?>
                                            <img src="<?php esc_attr_e($product_img); ?>" alt="" width="70" height="70" alt="<?php esc_attr_e($_product->get_name()); ?>">
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                } ?>
                                <div class="mcfw-cart-item-qp">
                                    <?php 
                                    if(!empty($allow_remove_icon)) { 
                                        ?>
                                        <span data-id="<?php _e($product_id,'menu-cart-for-woocommerce'); ?>" class="mcfw-remove-cart-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13.667" viewBox="0 0 13 13.667">
                                                <g id="Group_1" data-name="Group 1" transform="translate(-2.25 -1.75)">
                                                    <path id="Path_1" data-name="Path 1" d="M11.976,16.417H6.856a2.033,2.033,0,0,1-2-1.907L4.25,5.283a.507.507,0,0,1,.133-.367A.513.513,0,0,1,4.75,4.75h9.333a.5.5,0,0,1,.5.533L14,14.51A2.033,2.033,0,0,1,11.976,16.417ZM5.31,5.75l.513,8.7a1.033,1.033,0,0,0,1.033.967h5.12a1.04,1.04,0,0,0,1.033-.967l.54-8.667Z" transform="translate(-0.666 -1)" fill="#dd1818"/>
                                                    <path id="Path_2" data-name="Path 2" d="M14.75,5.75h-12a.5.5,0,1,1,0-1h12a.5.5,0,0,1,0,1Z" transform="translate(0 -1)" fill="#dd1818"/>
                                                    <path id="Path_3" data-name="Path 3" d="M12.75,4.75h-4a.507.507,0,0,1-.5-.5V3.05a1.333,1.333,0,0,1,1.3-1.3h2.4a1.333,1.333,0,0,1,1.3,1.333V4.25A.507.507,0,0,1,12.75,4.75Zm-3.5-1h3V3.083a.3.3,0,0,0-.3-.3H9.55a.3.3,0,0,0-.3.3Z" transform="translate(-2)" fill="#dd1818"/>
                                                    <path id="Path_4" data-name="Path 4" d="M14.75,15.083a.507.507,0,0,1-.5-.5V9.25a.5.5,0,0,1,1,0v5.333A.507.507,0,0,1,14.75,15.083Z" transform="translate(-4 -2.333)" fill="#dd1818"/>
                                                    <path id="Path_5" data-name="Path 5" d="M8.75,15.083a.507.507,0,0,1-.5-.5V9.25a.5.5,0,0,1,1,0v5.333A.507.507,0,0,1,8.75,15.083Z" transform="translate(-2 -2.333)" fill="#dd1818"/>
                                                    <path id="Path_6" data-name="Path 6" d="M11.75,15.083a.507.507,0,0,1-.5-.5V9.25a.5.5,0,0,1,1,0v5.333A.507.507,0,0,1,11.75,15.083Z" transform="translate(-3 -2.333)" fill="#dd1818"/>
                                                </g>
                                            </svg>
                                        </span>
                                        <?php
                                    }
                                    if(!empty($allow_pro_name)){
                                        if(!empty($allow_pro_link)){ ?>
                                                <a href="<?php esc_attr_e(get_permalink($product_id)); ?>" class="mcfw-cart-item-name"><?php esc_attr_e($_product->get_name()); ?></a>
                                            <?php
                                        }else{ ?>
                                                <span class="mcfw-cart-item-name"><?php esc_attr_e($_product->get_name()); ?></span>
                                            <?php
                                        }
                                    }
                                    if(!empty($allow_pro_quantity)){ ?>
                                        <span>
                                            <?php esc_attr_e($cart_item['quantity']);
                                            if(!empty($allow_pro_quantity) && !empty($allow_pro_price)){
                                                ?> X <?php
                                            }                                            
                                            ?>
                                        </span>
                                        <?php
                                    }
                                    
                                    if(!empty($allow_pro_price)){ ?>
                                        <span class="mcfw-mini-product-price-html">
                                            <span class="mcfw-mini-cart-price-wp">
                                                <?php echo $product_price; ?>
                                            </span>
                                        </span>
                                        <?php
                                        if(!empty($allow_pro_total)){ ?>
                                            <span>=</span><?php
                                        }
                                    }
                                    if(!empty($allow_pro_total)){ ?>
                                        <span class="mcfw-mini-product-price-html ">
                                            <span class="mcfw-flyout-product-total mcfw-mini-cart-price-wp" >
                                                <?php echo $product_subtotal; // PHPCS: XSS ok. ?>
                                            </span>
                                        </span>
                                        <?php
                                    } ?>
                                </div>
                            </div>
                            <?php
                        }
                    } ?>
                </div>
                <?php
                $btns = $mcfw_flyout_options1['cart_checkout_btn'];
                if ($btns != 'no_any_btn') { ?>
                    <div class="mcfw-cart-btn-wp mcfw_for_des">
                        <?php
                        if ($btns == 'cart' || $btns == 'cart_checkout') { ?> 
                            <a class="btn button mcfw-btns" href="<?php esc_attr_e(wc_get_cart_url()); ?>"><?php esc_attr_e(!empty($mcfw_flyout_options1['cart_btn_txt']) ? $mcfw_flyout_options1['cart_btn_txt'] : 'View Cart'); ?></a><?php 
                        }
                        if ($btns == 'checkout' || $btns == 'cart_checkout') { ?>
                            <a class="btn button mcfw-btns" href="<?php esc_attr_e(wc_get_checkout_url()); ?>"><?php esc_attr_e(!empty($mcfw_flyout_options1['checkout_btn_txt']) ? $mcfw_flyout_options1['checkout_btn_txt'] : 'CheckOut'); ?></a><?php 
                        } ?>
                    </div>

                    <div class="mcfw-cart-btn-wp mcfw_for_mob">
                        <?php
                        if ($btns == 'cart' || $btns == 'cart_checkout') { ?> <a class="mcfw-btns" href="<?php esc_attr_e(wc_get_cart_url()); ?>"><?php esc_attr_e(!empty($mcfw_flyout_options1['cart_btn_txt']) ? $mcfw_flyout_options1['cart_btn_txt'] : 'View Cart'); ?></a><?php }
                        
                        if ($btns == 'checkout' || $btns == 'cart_checkout') { ?>
                            <a class="mcfw-btns" href="<?php esc_attr_e(wc_get_checkout_url()); ?>"><?php esc_attr_e(!empty($mcfw_flyout_options1['checkout_btn_txt']) ? $mcfw_flyout_options1['checkout_btn_txt'] : 'CheckOut'); ?></a><?php 
                        } ?>
                    </div>
                <?php } ?>
                <div class="mcfw-sub-total mcfw-mini-product-price-html">
                    <?php
                    if (isset($mcfw_flyout_options1['total_price_type']) && $mcfw_flyout_options1['total_price_type'] == 'total_including_discount') {
                        if ( WC()->cart->display_prices_including_tax() ) {
                            $price = (float) WC()->cart->get_subtotal() + (float) WC()->cart->get_subtotal_tax();
                        } else {
                            $price = (float) WC()->cart->get_subtotal();
                        }
                    } elseif (isset($mcfw_flyout_options1['total_price_type']) && $mcfw_flyout_options1['total_price_type'] == 'checkout_total_including_shipping') {
                        $price = (float) WC()->cart->get_total('edit');
                    } else {
                        if ( WC()->cart->display_prices_including_tax() ) {
                            $price = (float) WC()->cart->get_cart_contents_total() + (float) WC()->cart->get_cart_contents_tax();
                        } else {
                            $price = (float) WC()->cart->get_cart_contents_total();
                        }
                    }
                    ?>
                    <label><?php if($mcfw_flyout_options1['total_price_type'] == 'total_including_discount') { esc_html_e('Subtotal','menu-cart-for-woocommerce'); }else{ esc_html_e('Total','menu-cart-for-woocommerce'); } ?>:</label>
                    <b>
                        <span class="mcfw-mini-cart-price-wp">
                            <span class="mcfw-flyout-currency"><?php esc_attr_e($currency); ?></span>
                            <?php esc_attr_e($price); ?>
                        </span>
                    </b>
                </div>

            <?php
            } else { ?>
                <p class="mcfw-empty-note"><?php esc_html_e($empty_note_txt); ?></p>
            <?php } ?>
        </div>
    <?php  }
    $fragments['.mcfw-mini-cart-main'] = ob_get_clean();
    return $fragments;
}

/**
 * Menu cart sticky button html
 */
function mcfw_update_sticky_count($fragments){
    ob_start();
    $mcfw_general_options1 = get_option('mcfw_general_options');
    $mcfw_sticky_cart_options = get_option('mcfw_sticky_cart_options');

    $items_count = esc_attr(count(WC()->cart->get_cart()));

    ?>
    <span class="mcfw-sticky-count">
        <span><?php esc_attr_e($items_count); ?></span>
    </span>
    <?php
    $fragments['.mcfw-sticky-count'] = ob_get_clean();
    return $fragments;
}

/**
 * Overlay of navigation flyout 
 */
add_action('wp_body_open', 'mcfw_custom_content_after_body_open_tag');
function mcfw_custom_content_after_body_open_tag() { ?>
    <div class="mcfw-overlay mcfw-hidden"></div>
    <?php
}

/**
 * Added HPOS support for woocommerce
 */
add_action( 'before_woocommerce_init', 'mcfw_before_woocommerce_init' );
function mcfw_before_woocommerce_init() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
}