<?php
if (!class_exists('mcfw_design_elements_settings')) {
    $mcfw_design_options = get_option('mcfw_design_options');

    class mcfw_design_elements_settings{
        public function __construct(){       
            add_action('admin_init', array($this, 'register_design_elements_settings_init'));
        }

        public function design_elements_setting_form_option(){ ?>
            <form class="mcfw-design-setting" action="options.php?tab=mcfw-design-elements" method="post">
                <?php  settings_fields('mcfw-design-setting-options');   ?>
                <div class="mcfw-section">
                    <?php do_settings_sections('mcfw_design_setting_section'); ?>
                </div>
                <div class="mcfw-section">
                    <?php do_settings_sections('mcfw_flyout_design_setting_section'); ?>
                </div>
                <div class="mcfw-section">
                    <?php do_settings_sections('mcfw_sticky_cart_design_setting_section'); ?>
                </div>
                <div class="mcfw_button_group">
                    <?php submit_button('Save Settings'); ?>
                    <input type="reset" value="Reset">
                </div>
            </form>
        <?php }

        /* register setting */
        public function register_design_elements_settings_init(){
            register_setting('mcfw-design-setting-options', 'mcfw_design_options', array($this, 'sanitize_settings'));
          
            add_settings_section(
                'mcfw_design_setting_id',
                __('General', 'menu-cart-for-woocommerce'),
                array(),
                'mcfw_design_setting_section'
            );



            add_settings_field(
                'currency_position',
                __('Currency Position', 'menu-cart-for-woocommerce'),
                array($this, 'currency_position_callback'),
                'mcfw_design_setting_section',
                'mcfw_design_setting_id',
                [
                    'label_for'     => 'currency_position',
                ]
            );

            add_settings_field(
                'cart_color',
                __('Cart Color', 'menu-cart-for-woocommerce'),
                array($this, 'btns_color_picker_callback'),
                'mcfw_design_setting_section',
                'mcfw_design_setting_id',
                [
                    'label_for'     => 'cart_color',
                ]
            );

            add_settings_field(
                'menu_txt_color',
                __('Menu Text Color', 'menu-cart-for-woocommerce'),
                array($this, 'btns_color_picker_callback'),
                'mcfw_design_setting_section',
                'mcfw_design_setting_id',
                [
                    'label_for'     => 'menu_txt_color',
                ]
            );

            add_settings_field(
                'menu_count_text_color',
                __('Menu Count Color <span class="mcfw-pro">pro</span>', 'menu-cart-for-woocommerce'),
                array($this, 'btns_color_picker_callback'),
                'mcfw_design_setting_section',
                'mcfw_design_setting_id',
                [
                    'label_for'     => 'menu_count_text_color',
                ]
            );

            add_settings_field(
                'menu_txt_background_color',
                __('Menu count Background Color <span class="mcfw-pro">pro</span>', 'menu-cart-for-woocommerce'),
                array($this, 'btns_color_picker_callback'),
                'mcfw_design_setting_section',
                'mcfw_design_setting_id',
                [
                    'label_for'     => 'menu_txt_background_color',
                ]
            );

            add_settings_section(
                'mcfw_flyout_design_setting_id',
                __('Flyout', 'menu-cart-for-woocommerce'),
                array(),
                'mcfw_flyout_design_setting_section'
            );

            add_settings_field(
                'flyout_background_color',
                __('Flyout Background Color', 'menu-cart-for-woocommerce'),
                array($this, 'btns_color_picker_callback'),
                'mcfw_flyout_design_setting_section',
                'mcfw_flyout_design_setting_id',
                [
                    'label_for'     => 'flyout_background_color',
                ]
            );

            add_settings_field(
                'txt_color',
                __('Text Color', 'menu-cart-for-woocommerce'),
                array($this, 'btns_color_picker_callback'),
                'mcfw_flyout_design_setting_section',
                'mcfw_flyout_design_setting_id',
                [
                    'label_for'     => 'txt_color',
                ]
            );

            add_settings_field(
                'btns_background_color',
                __('Button Background Color', 'menu-cart-for-woocommerce'),
                array($this, 'btns_color_picker_callback'),
                'mcfw_flyout_design_setting_section',
                'mcfw_flyout_design_setting_id',
                [
                    'label_for'     => 'btns_background_color',
                ]
            );

            add_settings_field(
                'btns_text_color',
                __('Button Text Color', 'menu-cart-for-woocommerce'),
                array($this, 'btns_color_picker_callback'),
                'mcfw_flyout_design_setting_section',
                'mcfw_flyout_design_setting_id',
                [
                    'label_for'     => 'btns_text_color',
                ]
            );

            add_settings_field(
                'btns_hover_background_color',
                __('Button Hover Background Color', 'menu-cart-for-woocommerce'),
                array($this, 'btns_color_picker_callback'),
                'mcfw_flyout_design_setting_section',
                'mcfw_flyout_design_setting_id',
                [
                    'label_for'     => 'btns_hover_background_color',
                ]
            );

            add_settings_field(
                'btns_hover_text_color',
                __('Button Hover Text Color', 'menu-cart-for-woocommerce'),
                array($this, 'btns_color_picker_callback'),
                'mcfw_flyout_design_setting_section',
                'mcfw_flyout_design_setting_id',
                [
                    'label_for'     => 'btns_hover_text_color',
                ]
            );

            add_settings_field(
                'btns_border',
                __('Button Border', 'menu-cart-for-woocommerce'),
                array($this, 'btns_border_callback'),
                'mcfw_flyout_design_setting_section',
                'mcfw_flyout_design_setting_id',
                [
                    'label_for'     => 'btns_border',
                ]
            );

            add_settings_section(
                'mcfw_sticky_cart_design_setting_id',
                __('Sticky Cart', 'menu-cart-for-woocommerce'),
                array(),
                'mcfw_sticky_cart_design_setting_section'
            );
            
            add_settings_field(
                'count_text_color',
                __('Counter Color', 'menu-cart-for-woocommerce'),
                array($this, 'btns_color_picker_callback'),
                'mcfw_sticky_cart_design_setting_section',
                'mcfw_sticky_cart_design_setting_id',
                [
                    'label_for'     => 'count_text_color',
                ]
            );

            add_settings_field(
                'count_background_color',
                __('Counter Background Color', 'menu-cart-for-woocommerce'),
                array($this, 'btns_color_picker_callback'),
                'mcfw_sticky_cart_design_setting_section',
                'mcfw_sticky_cart_design_setting_id',
                [
                    'label_for'     => 'count_background_color',
                ]
            );

            add_settings_field(
                'cart_background_color',
                __('Cart Background Color', 'menu-cart-for-woocommerce'),
                array($this, 'btns_color_picker_callback'),
                'mcfw_sticky_cart_design_setting_section',
                'mcfw_sticky_cart_design_setting_id',
                [
                    'label_for'     => 'cart_background_color',
                ]
            );
            
            add_settings_field(
                'cart_shape',
                __('Shape', 'menu-cart-for-woocommerce'),
                array($this, 'cart_shape_callback'),
                'mcfw_sticky_cart_design_setting_section',
                'mcfw_sticky_cart_design_setting_id',
                [
                    'label_for'     => 'cart_shape',
                ]
            );
        }

        public function currency_position_callback($args){
            global $mcfw_design_options;
            $value = isset($mcfw_design_options[$args['label_for']]) ? $mcfw_design_options[$args['label_for']] : ''; 
            $options=array(
                'mcfw_currency_postion_left'          => 'Left',
                'mcfw_currency_postion_right'		 => 'Right' ,
                'mcfw_currency_postion_left_withspace'		 => 'Left With Space' ,
                'mcfw_currency_postion_right_withspace'		 => 'Right With Space' ,
            ); ?>
                <select class="mcfw-currency-position-select" name="mcfw_design_options[<?php esc_attr_e( $args['label_for'] );  ?>]" >
                <?php
                    foreach ($options as $key => $values) { ?>
                        <option value="<?php esc_attr_e($key); ?>" <?php if ($key==$value) { _e('selected'); } ?>><?php esc_attr_e($values); ?></option>
                <?php } ?>
                </select>
            <?php 
        }


        public function btns_color_picker_callback($args){
            global $mcfw_design_options;
            $value = isset($mcfw_design_options[$args['label_for']]) ? $mcfw_design_options[$args['label_for']] : ''; ?>
                <input type="text" class="mcfw_coloris" name="mcfw_design_options[<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>" value="<?php _e($value); ?>" />
            <?php
        }

        public function btns_border_callback($args){
            global $mcfw_design_options;
            $value = isset($mcfw_design_options[$args['label_for']]) ? explode(",",$mcfw_design_options[$args['label_for']]) : ''; 
            $options = array(
                'dotted',
                'dashed',
                'solid',
                'double',
                'groove',
                'ridge',
                'inset',
                'outset',
                'none',
            ); ?>
            <div class="mcfw-border-options">

                <div class="mcfw-border-number">
                    <input type="number" name="mcfw_design_options[<?php esc_attr_e( $args['label_for'] );  ?>]['px']" min="1" value="<?php esc_attr_e($value[0]); ?>">
                    <p class="mcfw-input-note"><?php esc_html_e('This field represents <strong>WIDTH</strong> of the border','menu-cart-for-woocommerce'); ?></p>
                </div>

                <div class="mcfw-border-style">
                    <select class="mcfw-border-style-select" name="mcfw_design_options[<?php esc_attr_e( $args['label_for'] );  ?>]['style']" value="<?php esc_attr_e($value[1]); ?>">
                    <?php
                        foreach ($options as $key => $values) { ?>
                            <option value="<?php esc_attr_e($values); ?>" <?php if ($values==$value[1]) { _e('selected'); } ?>><?php esc_attr_e($values); ?></option>
                    <?php } ?>
                    </select>
                    <p class="mcfw-input-note"><?php esc_html_e('This field represents <strong>STYLE</strong> of the border','menu-cart-for-woocommerce'); ?></p>
                </div>
                
                <div class="mcfw-border-color">
                    <input type="text" class="mcfw_coloris" name="mcfw_design_options[<?php esc_attr_e( $args['label_for'] ); ?>]['color']" id="<?php esc_attr_e( $args['label_for'] ); ?>" value="<?php esc_attr_e($value[2]); ?>" >
                    <p class="mcfw-input-note"><?php esc_html_e('This field represents <strong>COLOR</strong> of the border','menu-cart-for-woocommerce'); ?></p>
                </div>
            </div>
            <?php
        }

        public function cart_shape_callback($args){
            global $mcfw_design_options;
            $value = isset($mcfw_design_options[$args['label_for']]) ? $mcfw_design_options[$args['label_for']] : ''; 
            $options=array(
                'mcfw_round_cart'          => 'Round',
                'mcfw_square_cart'		 => 'Square' ,
            ); ?>
                <select class="mcfw-sticky-cart-shape" name="mcfw_design_options[<?php esc_attr_e( $args['label_for'] );  ?>]" >
                <?php
                    foreach ($options as $key => $values) { ?>
                        <option value="<?php esc_attr_e($key); ?>" <?php if ($key==$value) { _e('selected'); } ?>><?php esc_attr_e($values); ?></option>
                <?php } ?>
                </select>
            <?php 
        }
        public function sanitize_settings($input){
            $new_input = array();

            if (isset($input['currency_position']) && !empty($input['currency_position'])) {
                $new_input['currency_position']=sanitize_text_field($input['currency_position']);
            }
            
            if (isset($input['cart_color']) && !empty($input['cart_color'])) {
                $new_input['cart_color']=sanitize_text_field($input['cart_color']);
            }

            if (isset($input['menu_txt_color']) && !empty($input['menu_txt_color'])) {
                $new_input['menu_txt_color']=sanitize_text_field($input['menu_txt_color']);
            }

            if (isset($input['flyout_background_color']) && !empty($input['flyout_background_color'])) {
                $new_input['flyout_background_color']=sanitize_text_field($input['flyout_background_color']);
            }

            if (isset($input['txt_color']) && !empty($input['txt_color'])) {
                $new_input['txt_color']=sanitize_text_field($input['txt_color']);
            }

            if (isset($input['btns_background_color']) && !empty($input['btns_background_color'])) {
                $new_input['btns_background_color']=sanitize_text_field($input['btns_background_color']);
            }

            if (isset($input['btns_hover_background_color']) && !empty($input['btns_hover_background_color'])) {
                $new_input['btns_hover_background_color']=sanitize_text_field($input['btns_hover_background_color']);
            }

            if (isset($input['btns_text_color']) && !empty($input['btns_text_color'])) {
                $new_input['btns_text_color']=sanitize_text_field($input['btns_text_color']);
            }

            if (isset($input['btns_hover_text_color']) && !empty($input['btns_hover_text_color'])) {
                $new_input['btns_hover_text_color']=sanitize_text_field($input['btns_hover_text_color']);
            }

            if (isset($input['btns_border']) && !empty($input['btns_border'])) {
                $new_input['btns_border'] = sanitize_text_field(implode(",",$input['btns_border']));
            }

            if (isset($input['count_text_color']) && !empty($input['count_text_color'])) {
                $new_input['count_text_color']=sanitize_text_field($input['count_text_color']);
            }
            if (isset($input['count_background_color']) && !empty($input['count_background_color'])) {
                $new_input['count_background_color']=sanitize_text_field($input['count_background_color']);
            }
            if (isset($input['cart_background_color']) && !empty($input['cart_background_color'])) {
                $new_input['cart_background_color']=sanitize_text_field($input['cart_background_color']);
            }
            
            if (isset($input['cart_shape']) && !empty($input['cart_shape'])) {
                $new_input['cart_shape']=sanitize_text_field($input['cart_shape']);
            }
            
            return $new_input;
        }
    }
}