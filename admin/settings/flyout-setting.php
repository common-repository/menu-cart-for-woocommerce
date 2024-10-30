<?php
if (!class_exists('mcfw_flyout_settings')) {
    $mcfw_flyout_options = get_option('mcfw_flyout_options');

    class mcfw_flyout_settings{

        public function __construct(){
            add_action('admin_init', array($this, 'register_flyout_settings_init'));
        }

        public function register_flyout_design_settings_init(){ ?>

            <form class="mcfw-general-setting" action="options.php?tab=mcfw-flyout" method="post">
                <?php settings_fields('mcfw-flyout-setting-options');   ?>
                <div class="mcfw-section">
                    <?php do_settings_sections('mcfw_flyout_setting_section'); ?>
                </div>
                <?php submit_button('Save Settings'); ?>
            </form>
            <?php
        }

        /* register setting */
        public function register_flyout_settings_init(){

            register_setting('mcfw-flyout-setting-options', 'mcfw_flyout_options', array($this, 'sanitize_settings'));

            add_settings_section(
                'mcfw_flyout_setting_id',
                __('', 'menu-cart-for-woocommerce'),
                array(),
                'mcfw_flyout_setting_section'
            );

            add_settings_field(
                'flyout_status',
                __('Flyout <br><p>Only the desktop displays Flyout.<br>(window width above 991px)</p>', 'menu-cart-for-woocommerce'),
                array($this, 'flyout_checkbox_callback'),
                'mcfw_flyout_setting_section',
                'mcfw_flyout_setting_id',
                [
                    'label_for'     => 'flyout_status',
                ]
            );

           
            add_settings_field(
                'product_image',
                __('Product Image', 'menu-cart-for-woocommerce'),
                array($this, 'flyout_checkbox_callback'),
                'mcfw_flyout_setting_section',
                'mcfw_flyout_setting_id',
                [
                    'label_for'     => 'product_image',
                    'option_group' => 'wpdevref_options', 
                    'class'         => 'mcfw_flyout_options mcfw_flyout_first_options'
                ]
            );
            add_settings_field(
                'product_name',
                __('Product Name', 'menu-cart-for-woocommerce'),
                array($this, 'flyout_checkbox_callback'),
                'mcfw_flyout_setting_section',
                'mcfw_flyout_setting_id',
                [
                    'label_for'     => 'product_name',
                    'option_group' => 'wpdevref_options', 
                    'class'         => 'mcfw_flyout_options'
                ]
            );
            add_settings_field(
                'product_price',
                __('Product Price', 'menu-cart-for-woocommerce'),
                array($this, 'flyout_checkbox_callback'),
                'mcfw_flyout_setting_section',
                'mcfw_flyout_setting_id',
                [
                    'label_for'     => 'product_price',
                    'option_group' => 'wpdevref_options', 
                    'class'         => 'mcfw_flyout_options'
                ]
            );

            add_settings_field(
                'product_quantity',
                __('Product Quantity', 'menu-cart-for-woocommerce'),
                array($this, 'flyout_checkbox_callback'),
                'mcfw_flyout_setting_section',
                'mcfw_flyout_setting_id',
                [
                    'label_for'     => 'product_quantity',
                    'option_group' => 'wpdevref_options', 
                    'class'         => 'mcfw_flyout_options'
                ]
            );

            add_settings_field(
                'product_link',
                __('Link To A Product Page <span class="mcfw-pro">pro</span>', 'menu-cart-for-woocommerce'),
                array($this, 'flyout_checkbox_callback'),
                'mcfw_flyout_setting_section',
                'mcfw_flyout_setting_id',
                [
                    'label_for'     => 'product_link',
                    'option_group' => 'wpdevref_options', 
                    'class'         => 'mcfw_flyout_options',
                    'attr'          => 'disabled'
                ]
            );

            add_settings_field(
                'product_total',
                __('Product Total <span class="mcfw-pro">pro</span>', 'menu-cart-for-woocommerce'),
                array($this, 'flyout_checkbox_callback'),
                'mcfw_flyout_setting_section',
                'mcfw_flyout_setting_id',
                [
                    'label_for'     => 'product_total',
                    'option_group' => 'wpdevref_options', 
                    'class'         => 'mcfw_flyout_options',
                    'attr'          => 'disabled'
                ]
            );

            add_settings_field(
                'remove_product_icon',
                __('Remove Product From Cart <span class="mcfw-pro">pro</span>', 'menu-cart-for-woocommerce'),
                array($this, 'flyout_checkbox_callback'),
                'mcfw_flyout_setting_section',
                'mcfw_flyout_setting_id',
                [
                    'label_for'     => 'remove_product_icon',
                    'option_group' => 'wpdevref_options', 
                    'class'         => 'mcfw_flyout_options mcfw_flyout_last_options',
                    'last_option'   => 1,
                    'attr'          => 'disabled'
                ]
            );

            add_settings_field(
                'flyout_contents',
                __("Display Flyout’s Content", 'menu-cart-for-woocommerce'),
                array($this, 'flyout_contents_radio_callback'),
                'mcfw_flyout_setting_section',
                'mcfw_flyout_setting_id',
                [
                    'label_for'     => 'flyout_contents',
                ]
            );

            add_settings_field(
                'total_price_type',
                __('Display Total', 'menu-cart-for-woocommerce'),
                array($this, 'price_type_callback'),
                'mcfw_flyout_setting_section',
                'mcfw_flyout_setting_id',
                [
                    'label_for'     => 'total_price_type',
                ]
            );

            add_settings_field(
                'max_products',
                __("Set Maximum Number Of Products To Display In The Flyout", 'menu-cart-for-woocommerce'),
                array($this, 'max_products_callback'),
                'mcfw_flyout_setting_section',
                'mcfw_flyout_setting_id',
                [
                    'label_for'     => 'max_products',
                ]
            );

            add_settings_field(
                'cart_checkout_btn',
                __("Display The Cart Or Checkout Button In The Flyout", 'menu-cart-for-woocommerce'),
                array($this, 'cart_checkout_btn_callback'),
                'mcfw_flyout_setting_section',
                'mcfw_flyout_setting_id',
                [
                    'label_for'     => 'cart_checkout_btn',
                ]
            );

            add_settings_field(
                'cart_btn_txt',
                __('Cart Button’s Text <span class="mcfw-pro">pro</span>', 'menu-cart-for-woocommerce'),
                array($this, 'cart_btn_txt_callback'),
                'mcfw_flyout_setting_section',
                'mcfw_flyout_setting_id',
                [
                    'label_for'     => 'cart_btn_txt',
                    'value'         => 'View cart',
                    'placeholder'   => 'Cart Button text'
                ]
            );

            add_settings_field(
                'checkout_btn_txt',
                __('Checkout Button’s Text <span class="mcfw-pro">pro</span>', 'menu-cart-for-woocommerce'),
                array($this, 'cart_btn_txt_callback'),
                'mcfw_flyout_setting_section',
                'mcfw_flyout_setting_id',
                [
                    'label_for'     => 'checkout_btn_txt',
                    'value'         => 'Checkout',
                    'placeholder'   => 'Checkout Button Text'
                ]
            );

            add_settings_field(
                'empty_note_txt',
                __('Empty Cart’s Note <span class="mcfw-pro">pro</span>', 'menu-cart-for-woocommerce'),
                array($this, 'cart_btn_txt_callback'),
                'mcfw_flyout_setting_section',
                'mcfw_flyout_setting_id',
                [
                    'label_for'     => 'empty_note_txt',
                    'value'         => 'Your Cart Is Currently Empty.',
                    'placeholder'   => 'Empty Note'
                ]
            );
        }

        public function flyout_checkbox_callback($args) {
            global $mcfw_flyout_options;
            $value = isset($mcfw_flyout_options[$args['label_for']]) ? $mcfw_flyout_options[$args['label_for']] : ''; 
            $dis_attr = isset($args['attr']) ? 'disabled' : ''; 
            ?>
            <label class="mcfw-switch mcfw-product-option-ck mcfw-checkbox-<?php _e($dis_attr); ?> ">
                <input type="checkbox" class="mcfw-checkbox" name="mcfw_flyout_options[<?php esc_attr_e($args['label_for']);  ?>]" value="on" <?php  _e($dis_attr); ?> <?php if ($value == 'on') { _e('checked');} ?>>
                <span class="mcfw-slider"></span>
            </label>            
            <?php
            if(isset($args['last_option'])){
                ?>
                <span class="mcfw-pro-icon mcfw-pro-product-option"><i><?php esc_html_e("Additional detail's options are only available in","menu-cart-for-woocommerce") ?> <a href="https://geekcodelab.com/wordpress-plugins/menu-cart-for-woocommerce-pro/" target="_blank" title="Buy Menu Cart For Woocommerce Pro">Menu Cart Pro.</a></i></span>
                <?php
            }
        }

        public function price_type_callback($args){

            global $mcfw_flyout_options;
            $value = isset($mcfw_flyout_options[$args['label_for']]) ? $mcfw_flyout_options[$args['label_for']] : '';
            $options = array(
                'total_including_discount'          => 'Subtotal (total of products)',
                'subtotal'         => 'Cart total (including discounts)',
                'checkout_total_including_shipping' => 'Checkout total (including discounts, fees & shipping)',
            ); ?>
            <select name="mcfw_flyout_options[<?php esc_attr_e($args['label_for']);  ?>]">
                <?php
                foreach ($options as $key => $values) { ?>
                    <option value="<?php esc_attr_e($key); ?>" <?php if ($key == $value) { _e('selected');} ?>>
                        <?php esc_attr_e($values); ?></option>
                <?php } ?>
            </select>
            <?php
        }

        public function flyout_contents_radio_callback($args){

            global $mcfw_flyout_options;
            $value = isset($mcfw_flyout_options[$args['label_for']]) ? $mcfw_flyout_options[$args['label_for']] : '';
            $options = array(
                'click'          => 'On Menu Click',
                'hover'         => 'On Menu Hover',
            );     
            ?>
            <div class="mcfw_price_wrap">
                <?php
                foreach ($options as $key => $values) { ?>
                    <div class="mcfw_price_main">
                        <label><input type="radio" class="mcfw_content" name="mcfw_flyout_options[<?php esc_attr_e($args['label_for']);  ?>]" value="<?php esc_attr_e($key); ?>" <?php if ($key == $value) { _e('checked'); } ?>><?php esc_attr_e($values); ?></label>
                    </div>
                <?php } ?>
            </div>
            <?php
        }

        public function redirect_page_callback($args){

            global $mcfw_flyout_options;
            $value = isset($mcfw_flyout_options[$args['label_for']]) ? $mcfw_flyout_options[$args['label_for']] : '';
            $options = array(
                'cart'          => 'Cart',
                'checkout'         => 'Checkout',
            ); ?>
            <?php
            foreach ($options as $key => $values) { ?>
                <div class="mcfw_price_main">
                    <input type="radio" name="mcfw_flyout_options[<?php esc_attr_e($args['label_for']);  ?>]" value="<?php esc_attr_e($key); ?>" <?php if ($key == $value) { _e('checked'); } ?>><?php esc_attr_e($values); ?>
                </div>
            <?php }
        }


        public function max_products_callback($args){

            global $mcfw_flyout_options;
            $value = isset($mcfw_flyout_options[$args['label_for']]) ? $mcfw_flyout_options[$args['label_for']] : '';  ?>
            <select name="mcfw_flyout_options[<?php esc_attr_e($args['label_for']);  ?>]">
                <?php
                for ($i = 1; $i <= 5; $i++) {  ?>
                    <option value="<?php esc_attr_e($i); ?>" <?php if ($value == $i) { _e('selected'); } ?>><?php esc_attr_e($i); ?></option>
                <?php }
                for ($i = 6; $i <= 10; $i++) {  ?>
                    <option  disabled><?php esc_attr_e($i); ?></option>
                <?php } ?>
                <option disabled >All</option>
            </select>
            <span class="mcfw-pro-icon "><i><?php esc_html_e('Additional number counters are only available in','menu-cart-for-woocommerce') ?> <a href="https://geekcodelab.com/wordpress-plugins/menu-cart-for-woocommerce-pro/" target="_blank" title="Buy Menu Cart For Woocommerce Pro">Menu Cart Pro.</a></i></span>
        <?php
        }

        public function cart_checkout_btn_callback($args){

            global $mcfw_flyout_options;
            $value = isset($mcfw_flyout_options[$args['label_for']]) ? $mcfw_flyout_options[$args['label_for']] : '';
            $options = array(
                'cart'          => 'View cart only'
            ); 
            $options_pro = array(
                'checkout'         => 'Checkout only',
                'cart_checkout' => 'Both cart and checkout',
                'no_any_btn'    =>  'Not any one'
            ); 
            ?>
            <select name="mcfw_flyout_options[<?php esc_attr_e($args['label_for']);  ?>]">
                <?php
                foreach ($options as $key => $values) { ?>
                    <option value="<?php esc_attr_e($key); ?>" <?php if ($key == $value) { _e('selected'); } ?>><?php esc_attr_e($values); ?></option>
                <?php }
                foreach ($options_pro as $key => $values) { ?>
                    <option disabled><?php esc_attr_e($values); ?></option>
                <?php }
                 ?>
            </select>
            <span class="mcfw-pro-icon "><i><?php esc_html_e("Additional button’s options are only available in","menu-cart-for-woocommerce") ?> <a href="https://geekcodelab.com/wordpress-plugins/menu-cart-for-woocommerce-pro/" target="_blank" title="Buy Menu Cart For Woocommerce Pro">Menu Cart Pro.</a></i></span>
        <?php
        }

        public function cart_btn_txt_callback($args){

            ?>
            <input type="text" disabled value="<?php esc_attr_e($args['value']); ?>">
            <?php
        }

        public function sanitize_settings($input){

            if (isset($input['flyout_status']) && !empty($input['flyout_status'])) {
                $new_input['flyout_status'] = sanitize_text_field($input['flyout_status']);
            }

            if (isset($input['product_image']) && !empty($input['product_image'])) {
                $new_input['product_image'] = sanitize_text_field($input['product_image']);
            }

            if (isset($input['product_name']) && !empty($input['product_name'])) {
                $new_input['product_name'] = sanitize_text_field($input['product_name']);
            }

            if (isset($input['product_price']) && !empty($input['product_price'])) {
                $new_input['product_price'] = sanitize_text_field($input['product_price']);
            }

            if (isset($input['product_quantity']) && !empty($input['product_quantity'])) {
                $new_input['product_quantity'] = sanitize_text_field($input['product_quantity']);
            }

            if (isset($input['flyout_contents']) && !empty($input['flyout_contents'])) {
                $new_input['flyout_contents'] = sanitize_text_field($input['flyout_contents']);
            }

            if (isset($input['max_products']) && !empty($input['max_products'])) {
                if($input['max_products'] >= 5){
                    $input['max_products'] = 5;
                }
                $new_input['max_products'] = sanitize_text_field($input['max_products']);
            }
            
            if (isset($input['total_price_type']) && !empty($input['total_price_type'])) {
                $new_input['total_price_type'] = sanitize_text_field($input['total_price_type']);
            }

            if (isset($input['cart_checkout_btn']) && !empty($input['cart_checkout_btn'])) {
                $new_input['cart_checkout_btn'] = sanitize_text_field($input['cart_checkout_btn']);
            }

            return $new_input;
        }
    }
}
