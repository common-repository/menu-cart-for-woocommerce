<?php
if (!class_exists('mcfw_sticky_cart_settings')) {
    $mcfw_sticky_cart_options = get_option('mcfw_sticky_cart_options');

    class mcfw_sticky_cart_settings
    {
        public function __construct()
        {
            add_action('admin_init', array($this, 'register_sticky_cart_settings_init'));
        }

        public function register_cart_settings_init()
        { ?>
            <form class="mcfw-general-setting" action="options.php?tab=mcfw-sticky-cart" method="post">
                <?php settings_fields('mcfw-sticky-setting-options');   ?>
                <div class="mcfw-section">
                    <?php do_settings_sections('mcfw_sticky_setting_section'); ?>
                </div>
                <?php submit_button('Save Settings'); ?>
            </form>
        <?php
        }

        /* register setting */
        public function register_sticky_cart_settings_init()
        {
            register_setting('mcfw-sticky-setting-options', 'mcfw_sticky_cart_options', array($this, 'sanitize_settings'));

            add_settings_section(
                'mcfw_sticky_setting_id',
                __('', 'menu-cart-for-woocommerce'),
                array(),
                'mcfw_sticky_setting_section'
            );

            add_settings_field(
                'sticky_sidebar_cart_status',
                __('Sticky Cart Button', 'menu-cart-for-woocommerce'),
                array($this, 'sticky_sidebar_cart_status_callback'),
                'mcfw_sticky_setting_section',
                'mcfw_sticky_setting_id',
                [
                    'label_for'     => 'sticky_sidebar_cart_status',
                ]
            );

            add_settings_field(
                'item_count',
                __('Display Cart Item Count', 'menu-cart-for-woocommerce'),
                array($this, 'item_count_callback'),
                'mcfw_sticky_setting_section',
                'mcfw_sticky_setting_id',
                [
                    'label_for'     => 'item_count',
                ]
            );

            add_settings_field(
                'sticky_cart_position',
                __("Sticky Cart Button's Position", 'menu-cart-for-woocommerce'),
                array($this, 'sticky_cart_position_callback'),
                'mcfw_sticky_setting_section',
                'mcfw_sticky_setting_id',
                [
                    'label_for'     => 'sticky_cart_position',
                ]
            );

            add_settings_field(
                'sticky_cart_btn_redirect',
                __('Redirect To (When Click On Sticky Cart Button)', 'menu-cart-for-woocommerce'),
                array($this, 'redirect_page_callback'),
                'mcfw_sticky_setting_section',
                'mcfw_sticky_setting_id',
                [
                    'label_for'     => 'sticky_cart_btn_redirect',
                ]
            );
        }

        public function sticky_sidebar_cart_status_callback($args)
        {
            global $mcfw_sticky_cart_options;
            $value = isset($mcfw_sticky_cart_options[$args['label_for']]) ? $mcfw_sticky_cart_options[$args['label_for']] : ''; ?>
            <label class="mcfw-switch">
                <input type="checkbox" class="mcfw-checkbox" name="mcfw_sticky_cart_options[<?php esc_attr_e($args['label_for']);  ?>]" value="on" <?php if ($value == 'on') {_e('checked'); } ?>>
                <span class="mcfw-slider"></span>
            </label>
        <?php
        }

        public function item_count_callback($args)
        {
            global $mcfw_sticky_cart_options;
            $value = isset($mcfw_sticky_cart_options[$args['label_for']]) ? $mcfw_sticky_cart_options[$args['label_for']] : '';
            $options = array(
                'yes'   => 'Yes',
                'no'    => 'No',
            ); ?>
            <div class="mcfw_price_wrap">
                <?php
                foreach ($options as $key => $values) { ?>
                    <div class="mcfw_price_main">
                        <label>
                        <input type="radio" name="mcfw_sticky_cart_options[<?php esc_attr_e($args['label_for']);  ?>]" value="<?php esc_attr_e($key); ?>" <?php if ($key == $value) {_e('checked');} ?>><?php esc_attr_e($values); ?>
                        </label>
                    </div>
                <?php } ?>
            </div>
            <?php
        }

        public function sticky_cart_position_callback($args)
        {
            global $mcfw_sticky_cart_options;
            $value = isset($mcfw_sticky_cart_options[$args['label_for']]) ? $mcfw_sticky_cart_options[$args['label_for']] : '';
            $options = array(
                'mcfw_cart_top_left'        => __('Top Left','menu-cart-for-woocommerce'),
                'mcfw_cart_top_right'       => __('Top Right','menu-cart-for-woocommerce'),
                'mcfw_cart_bottom_left'     => __('Bottom Left','menu-cart-for-woocommerce'),
                'mcfw_cart_bottom_right'    => __('Bottom Right','menu-cart-for-woocommerce'),
            ); ?>
            <select name="mcfw_sticky_cart_options[<?php esc_attr_e($args['label_for']);  ?>]">
                <?php
                foreach ($options as $key => $values) { ?>
                    <option value="<?php esc_attr_e($key); ?>" <?php if ($key == $value) { _e('selected'); } ?>><?php esc_attr_e($values); ?></option>
                <?php } ?>
            </select>
            <?php
        }

        public function redirect_page_callback($args){

            global $mcfw_sticky_cart_options;
            $value = isset($mcfw_sticky_cart_options[$args['label_for']]) ? $mcfw_sticky_cart_options[$args['label_for']] : 'none';
            $options = array(
                'cart'      => __('Cart','menu-cart-for-woocommerce'),
                'checkout'  => __('Checkout','menu-cart-for-woocommerce'),
                'none'      => __('None','menu-cart-for-woocommerce'),
            ); ?>
            <div class="mcfw_price_wrap">
                <?php
                foreach ($options as $key => $values) { ?>
                    <div class="mcfw_price_main">
                       
                        <label> <input type="radio" name="mcfw_sticky_cart_options[<?php esc_attr_e($args['label_for']);  ?>]" value="<?php esc_attr_e($key); ?>" <?php if ($key == $value) { _e('checked'); } ?>><?php esc_attr_e($values); ?></label>
                    </div>
                <?php } ?>
            </div>
            <?php
        }

        public function sanitize_settings($input){

            $new_input = array();

            if (isset($input['sticky_sidebar_cart_status']) && !empty($input['sticky_sidebar_cart_status'])) {
                $new_input['sticky_sidebar_cart_status'] = sanitize_text_field($input['sticky_sidebar_cart_status']);
            }

            if (isset($input['item_count']) && !empty($input['item_count'])) {
                $new_input['item_count'] = sanitize_text_field($input['item_count']);
            }

            if (isset($input['sticky_cart_position']) && !empty($input['sticky_cart_position'])) {
                $new_input['sticky_cart_position'] = sanitize_text_field($input['sticky_cart_position']);
            }

            if (isset($input['sticky_cart_btn_redirect']) && !empty($input['sticky_cart_btn_redirect'])) {
                $new_input['sticky_cart_btn_redirect'] = sanitize_text_field($input['sticky_cart_btn_redirect']);
            }
            return $new_input;
        }

        
    }
}
