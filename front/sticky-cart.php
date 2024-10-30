<?php
if (!class_exists('mcfw_sticky_cart')) {
    
    class mcfw_sticky_cart{
        public function __construct() {
            add_action( 'init', array( $this, 'mcfw_set_sticky_cart' ) );   
        }
        public function mcfw_set_sticky_cart(){
            if (!is_admin()) {
                add_action( 'wp_footer', array( $this, 'mcfw_add_sticky_cart' ) );   
            }  
        }
        public function mcfw_add_sticky_cart(){
            $mcfw_sticky_cart_options   = get_option('mcfw_sticky_cart_options');
            $mcfw_general_options       = get_option('mcfw_general_options');
            $redirect_page_url =  'javascript:void(0)';
            $cart_icon      = (isset($mcfw_general_options['cart_icon']) ? $mcfw_general_options['cart_icon'] : '');
            $redirect_link  = (isset($mcfw_sticky_cart_options['sticky_cart_btn_redirect']) ? $mcfw_sticky_cart_options['sticky_cart_btn_redirect'] : '');
  
            if($redirect_link == "cart"){
                $redirect_page_url =  wc_get_cart_url();
            }elseif($redirect_link == "checkout"){
                $redirect_page_url =  wc_get_checkout_url();
            }else{
                $redirect_page_url =  'javascript:void(0)';
            }

            $items_count    = count(WC()->cart->get_cart()); 
            $output = '';
            $output .= mcfw_get_select_cart_icon($cart_icon); 
            
            if ((isset($mcfw_sticky_cart_options['sticky_sidebar_cart_status']) && ($mcfw_sticky_cart_options['sticky_sidebar_cart_status'] == 'on')) && 
                       (($mcfw_sticky_cart_options['sticky_sidebar_cart_status'] == 'on') && (is_cart()==false) && (is_checkout() == false)) || 
                       (isset($mcfw_sticky_cart_options['sticky_sidebar_cart_status']) && ($mcfw_sticky_cart_options['sticky_sidebar_cart_status'] == 'on') && isset($mcfw_general_options['show_on_cart_checkout_page']) && $mcfw_general_options['show_on_cart_checkout_page']=='on')) { ?>
                <div class="mcfw-cart <?php esc_attr_e($mcfw_sticky_cart_options['sticky_cart_position']); ?>">
                    <a href="<?php esc_attr_e($redirect_page_url) ?>">
                        <?php 
                            _e($output);
                        
                            if ($mcfw_sticky_cart_options['item_count'] == 'yes') { 
                                ?>
                                <span class="mcfw-sticky-count"></span>
                        <?php } ?>
                    </a>
                </div>
                <?php
            }
        }
    }
}