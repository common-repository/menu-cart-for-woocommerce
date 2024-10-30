<?php
if (!class_exists('mcfw_menu_cart')) {
    $mcfw_general_options = get_option('mcfw_general_options');
    $mcfw_flyout_options=get_option('mcfw_flyout_options');
    $mcfw_design_options = get_option('mcfw_design_options');

    class mcfw_menu_cart{
        public function __construct() {
            add_action( 'woocommerce_init', array( $this, 'mcfw_set_menu' ) );  
        }
        
        public function mcfw_set_menu(){
            global $mcfw_general_options;
            if (isset($mcfw_general_options['menu_id']) && !empty($mcfw_general_options['menu_id'])) {
               
                add_filter( 'wp_nav_menu_items', array( $this, 'add_menus' ), 20, 2 );
            }
        } 
        
        public function add_menus( $items, $args ) {
            global $mcfw_general_options;
            global $mcfw_flyout_options;
            global $mcfw_design_options;
            global $woocommerce;
            $menus          =   explode(",",$mcfw_general_options['menu_id']); 
            $cart_icon      =   $mcfw_general_options['cart_icon'];

            $mcfw_currency =  (isset($mcfw_design_options['currency_position']) ? $mcfw_design_options['currency_position'] : '');  
            $output = '';
            $output .= mcfw_get_select_cart_icon($cart_icon);                 
            $page_redirect_url = (($mcfw_general_options['page_redirect'] == 'checkout') ? wc_get_checkout_url() : wc_get_cart_url());
            if (((is_cart()==false) && (is_checkout() == false)) || (isset($mcfw_general_options['show_on_cart_checkout_page']) && $mcfw_general_options['show_on_cart_checkout_page']=='on')) {
                foreach ($menus as $key => $value) {
                    if(isset($args->menu->term_id)){

                        if ( $args->menu->term_id ==$value) {
                            if(isset($mcfw_general_options['always_display']) && $mcfw_general_options['always_display'] == 'on') {
                                $empty_cart_hide_menu_css = '';
                            }else{
                                $empty_cart_hide_menu_css = (sizeof(WC()->cart->get_cart()) == 0) ? 'style="display:none;"' : '';
                            }
                            $mcfw_menu_link = (isset($mcfw_flyout_options['flyout_status']) && $mcfw_flyout_options['flyout_contents'] == 'click') ? 'javascript:void(0);': $page_redirect_url;
                            $items .= '<li '.$empty_cart_hide_menu_css.' class="menu-item menu-item-type-post_type menu-item-object-page mcfw-menu '.$mcfw_currency.' '.(($mcfw_flyout_options['flyout_contents'] == 'hover') ?  'mcfw-menu-hover' : '').' '.(($mcfw_general_options['menu_cart_formats'] == 'price_items' || $mcfw_general_options['menu_cart_formats'] == 'items_price') ? 'mcfw-menu-no-icon' : '').'">
                                    <a href="'.$mcfw_menu_link.'" class="mcfw-menu-list"> ';
                                    if (($mcfw_general_options['menu_cart_formats'] != 'items_price') && ($mcfw_general_options['menu_cart_formats'] != 'price_items') ) {
                                        $items .= $output;
                                    }
                                    $items .= '<span class="mcfw-mini-product-price-html "></span></a>';
                                    $items .= '<div class="mcfw-mini-cart-main"></div>';
                                                
                                $items .='</li>';
                                }
                        }
                    }
                } 
                return $items;
            }
    }
}
