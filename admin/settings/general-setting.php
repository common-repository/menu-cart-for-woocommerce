<?php
if (!class_exists('mcfw_general_settings')) {
    $mcfw_general_options = get_option('mcfw_general_options');

    class mcfw_general_settings
    {
        public function __construct(){
            add_action('admin_init', array($this, 'register_general_settings_init'));
        }

        public function register_settings_init(){ ?>
            <form action="options.php" method="post" class="mcfw-general-setting">
                <?php settings_fields('mcfw-general-setting-options');   ?>
                <div class="mcfw-section">
                    <?php
                    if (empty(wp_get_nav_menus())) { ?>
                        <div class="error mcfw-input-note mcfw-error">
                            <?php echo wp_kses( __('You need to create a menu before you can use Menu Cart. Go to <strong>Appearence > Menus</strong> and create menu to add the cart to.','menu-cart-for-woocommerce'), array( 'strong' => array() ) ); ?>
                        </div>
                    <?php } ?>
                    <?php do_settings_sections('mcfw_general_setting_section'); ?>
                </div>
                <?php submit_button('Save Settings'); ?>
            </form>
        <?php
        }

        /* register setting */
        public function register_general_settings_init(){
            register_setting('mcfw-general-setting-options', 'mcfw_general_options', array($this, 'sanitize_settings'));

            add_settings_section(
                'mcfw_general_setting_id',
                __('', 'menu-cart-for-woocommerce'),
                array(),
                'mcfw_general_setting_section'
            );

            add_settings_field(
                'menu_id',
                __('Select The Menu(s) In Which You Want To Display The Cart Menu Item', 'menu-cart-for-woocommerce'),
                array($this, 'menus_callback'),
                'mcfw_general_setting_section',
                'mcfw_general_setting_id',
                [
                    'label_for'     => 'menu_id',
                ]
            );

            add_settings_field(
                'always_display',
                __("Always display cart, even if it's empty", 'menu-cart-for-woocommerce'),
                array($this, 'checkbox_element_callback'),
                'mcfw_general_setting_section',
                'mcfw_general_setting_id',
                [
                    'label_for'     => 'always_display',
                ]
            );

            add_settings_field(
                'show_on_cart_checkout_page',
                __("</label> <label for='show_on_cart_checkout_page' >Show On Cart & Checkout Page</label> <p>To avoid distracting your customers with duplicate information we do not display the menu cart item on the cart & checkout pages by default.</p><label>", 'menu-cart-for-woocommerce'),
                array($this, 'checkbox_element_callback'),
                'mcfw_general_setting_section',
                'mcfw_general_setting_id',
                [
                    'label_for'     => 'show_on_cart_checkout_page',
                    'description'    => '',
                ]
            );

            add_settings_field(
                'price_format',
                __("How Prices Are Displayed In The Flyout", 'menu-cart-for-woocommerce'),
                array($this, 'price_format_callback'),
                'mcfw_general_setting_section',
                'mcfw_general_setting_id',
                [
                    'label_for'     => 'price_format',
                ]
            );

            

            add_settings_field(
                'cart_icon',
                __("Choose A Cart Icon", 'menu-cart-for-woocommerce'),
                array($this, 'menu_cart_icon_callback'),
                'mcfw_general_setting_section',
                'mcfw_general_setting_id',
                [
                    'label_for'     => 'cart_icon',
                ]
            );

            add_settings_field(
                'menu_cart_formats',
                __("How Would You Like To Display A Menu Item In The Menu?", 'menu-cart-for-woocommerce'),
                array($this, 'menu_cart_formats_callback'),
                'mcfw_general_setting_section',
                'mcfw_general_setting_id',
                [
                    'label_for'     => 'menu_cart_formats',
                ]
            );

            add_settings_field(
                'page_redirect',
                __("Redirect To Cart Page Or Checkout Page (When Click On Menu Item)", 'menu-cart-for-woocommerce'),
                array($this, 'redirect_page_callback'),
                'mcfw_general_setting_section',
                'mcfw_general_setting_id',
                [
                    'label_for'     => 'page_redirect',
                ]
            );
        }

        public function menus_callback($args){
            global $mcfw_general_options;
            $value = isset($mcfw_general_options[$args['label_for']]) ? explode(",", $mcfw_general_options[$args['label_for']]) : '';
            $mcfw_menus = wp_get_nav_menus();
            ?>
            <select name="mcfw_general_options[<?php esc_attr_e($args['label_for']);  ?>][]" class="mcfw-select js-select2-multi" multiple="multiple">
                <option value="" disabled></option>
                <?php
                if (isset($mcfw_menus) && !empty($mcfw_menus)) {
                    foreach ($mcfw_menus as $key => $values) {  ?>
                        <option value="<?php esc_attr_e($values->term_id) ?>" <?php if (isset($value) && (!empty($value)) && (in_array($values->term_id, $value))) { _e('selected', 'menu-cart-for-woocommerce');} ?>>
                            <?php esc_attr_e($values->name); ?></option>
                <?php }
                } ?>
            </select>
            <p class="mcfw-select-err mcfw-input-note">
                <?php esc_html_e('The below options will be ineffective if not select any menu.', 'menu-cart-for-woocommerce'); ?></p>
            <?php
        }

        public function checkbox_element_callback($args){

            global $mcfw_general_options;
            $value = isset($mcfw_general_options[$args['label_for']]) ? $mcfw_general_options[$args['label_for']] : ''; ?>
            <label class="mcfw-switch">
                <input type="checkbox" class="mcfw-checkbox" name="mcfw_general_options[<?php esc_attr_e($args['label_for']);  ?>]" <?php if ($value == "on") { _e('checked', 'menu-cart-for-woocommerce');} ?>>
                <span class="mcfw-slider"></span>
            </label>
            <?php
            if (!empty($args['description'])) { ?> <p class="mcfw-input-note">
                    <?php esc_attr_e($args['description'], 'menu-cart-for-woocommerce') ?></p>
            <?php }
        }

        public function price_format_callback($args){

            global $mcfw_general_options;
            $value = isset($mcfw_general_options[$args['label_for']]) ? $mcfw_general_options[$args['label_for']] : '';
            ?>
            <div class="mcfw_price_wrap currency_price">
                <div class="mcfw_price_main currency_price">
                    <input type="radio" id="Currency" name="mcfw_general_options[<?php esc_attr_e($args['label_for']);  ?>]" value="currency" <?php if ($value == 'currency') { _e('checked', 'menu-cart-for-woocommerce'); } ?>>
                    <div class="mcfw-input-note-wp">
                        <label for="Currency"><?php esc_html_e('Currency', 'menu-cart-for-woocommerce'); ?></label>
                        <span class="mcfw-input-note"><?php _e('e.g. USD', 'menu-cart-for-woocommerce'); ?></span>
                    </div>
                </div>
                <div class="mcfw_price_main currency_symbol_price">
                    <input type="radio" id="Currency Symbol" name="mcfw_general_options[<?php esc_attr_e($args['label_for']);  ?>]" value="currency_symbol" <?php if ($value == 'currency_symbol') { _e('checked'); } ?>>
                    <div class="mcfw-input-note-wp">
                        <label for="Currency Symbol"><?php esc_html_e('Currency Symbol', 'menu-cart-for-woocommerce'); ?></label>
                        <span class="mcfw-input-note"><?php _e('e.g. $', 'menu-cart-for-woocommerce'); ?></span>
                    </div>
                </div>
            </div>
        <?php
        }

        

        public function menu_cart_icon_callback($args){

            global $mcfw_general_options;
            $value = isset($mcfw_general_options[$args['label_for']]) ? $mcfw_general_options[$args['label_for']] : '';
            $options = array('cart1','cart10','cart4');
            $pro_options = array('cart2','cart3','cart5','cart6','cart7','cart8','cart9'); ?>
            <div class="mcfw-cart-options-wp">
                <?php
                foreach ($options as $key => $values) {
                    $icons = esc_url(MCFW_PLUGIN_URL . '/assets/images/menu_icons/' . $values . '.png'); ?>

                    <div class="mcfw-cart-options <?php esc_html_e(($values == $value) ? 'mcfw-current-cart-options' : ''); ?>">
                        <input type="radio" name="mcfw_general_options[<?php esc_attr_e($args['label_for']);  ?>]" value="<?php esc_attr_e($values); ?>" <?php if ($values == $value) {_e('checked');} ?>>
                        <img src="<?php esc_attr_e($icons); ?>" width="35" height="35" alt="<?php esc_attr_e($values); ?> cart icon">
                    </div>
                <?php } 
                 foreach ($pro_options as $key => $values) {
                    $icons = esc_url(MCFW_PLUGIN_URL . '/assets/images/menu_icons/' . $values . '.png'); ?>

                    <div class="mcfw-cart-options mcfw-pro-disabled mcfw-cart-disabled <?php esc_html_e(($values == $value) ? 'mcfw-current-cart-options' : ''); ?>">
                        <input type="radio"  >
                        <img src="<?php esc_attr_e($icons); ?>" width="35" height="35" alt="<?php esc_attr_e($values); ?> cart icon">
                    </div>
                <?php }
                ?>
            </div>
  
            <span class="mcfw-pro-icon"><i><?php esc_html_e('Additional icons are only available in','menu-cart-for-woocommerce') ?> <a href="https://geekcodelab.com/wordpress-plugins/menu-cart-for-woocommerce-pro/" target="_blank" title="Buy Menu Cart For Woocommerce Pro">Menu Cart Pro.</a></i></span>
            <?php
        }

        public function menu_cart_formats_callback($args){

            global $mcfw_general_options;
            $value = isset($mcfw_general_options[$args['label_for']]) ? $mcfw_general_options[$args['label_for']] : '';
            $options = array('icon_only','icon_items','icon_price'); 
            $options_pro = array('items_price','price_items','icon_items_price','icon_price_items','icon_item_count'); ?>
            <div class="mcfw-cart-btn-img-wp">
                <?php
                foreach ($options as $key => $values) {
                    $icons = esc_url(MCFW_PLUGIN_URL . '/assets/images/menu_cart_formats/' . $values . '.png'); ?>
                    <div class="mcfw-cart-btn-img <?php esc_html_e(($values == $value) ? 'mcfw-current-cart-options' : ''); ?>">
                        <input type="radio" name="mcfw_general_options[<?php esc_attr_e($args['label_for']);  ?>]" value="<?php esc_attr_e($values); ?>" <?php if ($values == $value) {_e('checked', 'menu-cart-for-woocommerce');} ?>>
                        <img src="<?php esc_attr_e($icons); ?>" alt="<?php esc_attr_e($values); ?> cart button image">
                    </div>

                <?php }
                foreach ($options_pro as $key => $values) {
                    $icons = esc_url(MCFW_PLUGIN_URL . '/assets/images/menu_cart_formats/' . $values . '.png'); ?>
                    <div class="mcfw-cart-btn-img mcfw-pro-disabled mcfw-menu-design-disabled <?php esc_html_e(($values == $value) ? 'mcfw-current-cart-options' : ''); ?>">
                        <input type="radio" >
                        <img src="<?php esc_attr_e($icons); ?>" alt="<?php esc_attr_e($values); ?> cart button image">
                    </div>
                <?php }
                
                ?>
            </div> 
            <span class="mcfw-pro-icon"><i><?php esc_html_e('Additional design layouts are only available in','menu-cart-for-woocommerce') ?> <a href="https://geekcodelab.com/wordpress-plugins/menu-cart-for-woocommerce-pro/" target="_blank" title="Buy Menu Cart For Woocommerce Pro">Menu Cart Pro.</a></i></span>
            <?php
        }

        public function redirect_page_callback($args){

            global $mcfw_general_options;
            $value = isset($mcfw_general_options[$args['label_for']]) ? $mcfw_general_options[$args['label_for']] : '';
            $options = array('cart'=> 'Cart','checkout'=> 'Checkout',); ?>
            <div class="mcfw_price_wrap">
                <?php
                foreach ($options as $key => $values) { ?>
                    <div class="mcfw_price_main">                        
                        <label><input type="radio" name="mcfw_general_options[<?php esc_attr_e($args['label_for']);  ?>]" value="<?php esc_attr_e($key); ?>" <?php if ($key == $value) { _e('checked'); } ?>><?php esc_attr_e($values); ?></label>
                    </div>
                <?php } ?>
            </div>
            <?php
        }

        public function sanitize_settings($input){
            $new_input = array();

            if (isset($input['menu_id']) && !empty($input['menu_id'])) {
                if (is_array($input['menu_id'])) {
                    $menu_id_list = implode(",", $input['menu_id']);
                } else {
                    $menu_id_list = sanitize_text_field($input['menu_id']);
                }
                $new_input['menu_id'] = sanitize_text_field($menu_id_list);
            }

            if (isset($input['always_display']) && !empty($input['always_display'])) {
                $new_input['always_display'] = sanitize_text_field($input['always_display']);
            }

            if (isset($input['show_on_cart_checkout_page']) && !empty($input['show_on_cart_checkout_page'])) {
                $new_input['show_on_cart_checkout_page'] = sanitize_text_field($input['show_on_cart_checkout_page']);
            }

            if (isset($input['price_format']) && !empty($input['price_format'])) {
                $new_input['price_format'] = sanitize_text_field($input['price_format']);
            }


            if (isset($input['cart_icon']) && !empty($input['cart_icon'])) {
                $new_input['cart_icon'] = sanitize_text_field($input['cart_icon']);
            }

            if (isset($input['menu_cart_formats']) && !empty($input['menu_cart_formats'])) {
                $new_input['menu_cart_formats'] = sanitize_text_field($input['menu_cart_formats']);
            }

            if (isset($input['page_redirect']) && !empty($input['page_redirect'])) {
                $new_input['page_redirect'] = sanitize_text_field($input['page_redirect']);
            }

            return $new_input;
        }
    }
}
