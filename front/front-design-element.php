<?php
if (!class_exists('mcfw_design')) {
    $mcfw_design_options = get_option('mcfw_design_options');
    $mcfw_general_options1 = get_option('mcfw_general_options');


    class mcfw_design{
        public function __construct() {
            add_action( 'init', array( $this, 'mcfw_set_design' ) );   
        }
        public function mcfw_set_design(){
            if (!is_admin()) {
                add_action( 'wp_footer', array( $this, 'mcfw_add_design' ) );   
            }  
        }

        public function mcfw_add_design(){
            global $mcfw_design_options;
            global $mcfw_general_options1;
      
            $mcfw_cart_shape = $mcfw_design_options['cart_shape'];
            $mcfw_btn_border = explode(",",$mcfw_design_options['btns_border']); ?>
    
                <style>
                    <?php
                        $cart_clr       = (isset($mcfw_design_options['cart_color']) ? $mcfw_design_options['cart_color'] : '');
                        $menu_txt_color = (isset($mcfw_design_options['menu_txt_color']) ? $mcfw_design_options['menu_txt_color'] : '');
                        if (!empty($cart_clr)) { ?>
                            .mcfw-menu svg.mcfw-svg path,
                            .mcfw-menu svg.mcfw-svg circle,
                            .mcfw-cart svg.mcfw-svg path,
                            .mcfw-cart svg.mcfw-svg circle,
                            .mcfw-shortcode svg.mcfw-svg path,
                            .mcfw-shortcode svg.mcfw-svg circle{
                                fill:<?php esc_attr_e($cart_clr . ' !important');  ?>
                            }
                        <?php }

                        
                        if (!empty($menu_txt_color)) { ?>
                            .mcfw-menu .mcfw-menu-list a,
                            .mcfw-menu .mcfw-menu-list span,
                            .mcfw-shortcode a,
                            .mcfw-shortcode span{
                                color:<?php esc_attr_e($menu_txt_color . ' !important');  ?>
                            }
                        <?php }

   
                        $mcfw_flyout_txt_clr        = (isset($mcfw_design_options['txt_color']) ? $mcfw_design_options['txt_color'] : '');
                        $flyout_background_color    = (isset($mcfw_design_options['flyout_background_color']) ? $mcfw_design_options['flyout_background_color'] : '');
                        $mcfw_btn_back_clr          = (isset($mcfw_design_options['btns_background_color']) ? $mcfw_design_options['btns_background_color'] : '');
                        $mcfw_hover_btn_clr         = (isset($mcfw_design_options['btns_hover_background_color']) ? $mcfw_design_options['btns_hover_background_color'] : '');
                        $mcfw_btn_txt_clr           = (isset($mcfw_design_options['btns_text_color']) ? $mcfw_design_options['btns_text_color'] : '');
                        $mcfw_hover_btn_txt_clr     = (isset($mcfw_design_options['btns_hover_text_color']) ? $mcfw_design_options['btns_hover_text_color'] : '');
                        $mcfw_cnt_back_clr          = (isset($mcfw_design_options['count_background_color']) ? $mcfw_design_options['count_background_color'] : '');
                        $mcfw_cnt_txt_clr           = (isset($mcfw_design_options['count_text_color']) ? $mcfw_design_options['count_text_color'] : '');
                        $mcfw_cart_back_clr         = (isset($mcfw_design_options['cart_background_color']) ? $mcfw_design_options['cart_background_color'] : '');
                        

                        if (!empty($mcfw_flyout_txt_clr)) { ?>
                            .mcfw-cart-item-qp a,
                            .mcfw-cart-item-qp span,
                            .mcfw-sub-total,
                            .mcfw-mini-cart-main .mcfw-empty-note{
                                color:<?php esc_attr_e($mcfw_flyout_txt_clr . ' !important');  ?>
                            }
                           
                       
                        <?php }

                        if (!empty($flyout_background_color)) { ?>
                            .mcfw-mini-cart-main{
                                background:<?php esc_attr_e($flyout_background_color . ' !important');  ?>
                            }
                        <?php }

                        if (!empty($mcfw_btn_back_clr)) { ?>
                            .mcfw-menu .mcfw-mini-cart-main .mcfw-cart-btn-wp a{
                                background:<?php esc_attr_e($mcfw_btn_back_clr . ' !important');  ?>
                            }
                        <?php }
                         if (!empty($mcfw_hover_btn_clr)) { ?>
                            .mcfw-menu .mcfw-mini-cart-main .mcfw-cart-btn-wp a:hover{
                                background:<?php esc_attr_e($mcfw_hover_btn_clr . ' !important');  ?>
                            }
                        <?php }
                        if (!empty($mcfw_btn_txt_clr)) { ?>
                            .mcfw-menu .mcfw-mini-cart-main .mcfw-cart-btn-wp a{
                                color:<?php esc_attr_e($mcfw_btn_txt_clr . ' !important'); ?>
                            }
                        <?php } 
                        if (!empty($mcfw_hover_btn_txt_clr)) { ?>
                            .mcfw-menu .mcfw-mini-cart-main .mcfw-cart-btn-wp a:hover{
                                color:<?php esc_attr_e($mcfw_hover_btn_txt_clr . ' !important'); ?>
                            }
                        <?php } 
                        if (!empty($mcfw_cnt_back_clr)) { ?>
                            .mcfw-sticky-count{
                                background:<?php esc_attr_e($mcfw_cnt_back_clr . ' !important'); ?>
                            }
                        <?php } 
                        if (!empty($mcfw_cnt_txt_clr)) { ?>
                            .mcfw-sticky-count{
                                color:<?php esc_attr_e($mcfw_cnt_txt_clr . ' !important'); ?>
                            }
                        <?php }
                        if (!empty($mcfw_cart_back_clr)) { ?>
                            .mcfw_cart_bottom_left, 
                            .mcfw_cart_bottom_right, 
                            .mcfw_cart_top_left, 
                            .mcfw_cart_top_right {
                                background:<?php esc_attr_e($mcfw_cart_back_clr . ' !important'); ?>
                            }
                        <?php }
                        if (!empty($mcfw_btn_border[0])) { ?>
                            .mcfw-menu .mcfw-mini-cart-main .mcfw-cart-btn-wp a{
                                border:<?php esc_attr_e($mcfw_btn_border[0]); ?>px <?php esc_attr_e($mcfw_btn_border[1]); ?> <?php esc_attr_e($mcfw_btn_border[2]); ?> !important;
                            }
                        <?php
                        } 
              
                        if ($mcfw_cart_shape == 'mcfw_square_cart') { ?>
                            .mcfw_cart_bottom_left, 
                            .mcfw_cart_bottom_right, 
                            .mcfw_cart_top_left, 
                            .mcfw_cart_top_right,
                            .mcfw-sticky-count{
                                border-radius: 0% !important;
                            }
                        <?php 
                        } ?>
                </style>
                <?php 
    
            $mcfw_always_cart = ((isset($mcfw_general_options1['always_display'])) && (!empty($mcfw_general_options1['always_display'])) ? $mcfw_general_options1['always_display'] : '');
            $items_count = esc_attr(count(WC()->cart->get_cart())); ?>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                   
                    var cnt =<?php esc_attr_e($items_count); ?>;
		            var always_display_cart = '<?php esc_attr_e($mcfw_always_cart); ?>';
                    ((cnt <= 0 && always_display_cart != 'on') ? jQuery('.mcfw-menu').hide() : jQuery('.mcfw-menu').show());
                });
            </script>
        <?php }
    }
}