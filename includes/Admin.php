<?php 

namespace Atcr;

/**
 * The Admin Class
 */
class Admin {
	
	function __construct() { 
		add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_cart_redirect_to', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_cart_redirect_to', __CLASS__ . '::update_settings' );

		add_filter( 'woocommerce_product_settings', array( $this, 'remove_a_settings' ) );
	}
 
			
	public function remove_a_settings( $settings ) {
		
		unset( $settings[ 2 ] );
		unset( $settings[ 3 ][ 'checkboxgroup' ] );
		
		$settings[ 3 ][ 'title' ] = esc_html__( 'Add to Cart behaviour', 'add-to-cart-redirect' );
		 
		
		return apply_filters( 'woo_cart_redirect_to_checkout_page_settings', $settings );
	}
 
    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['cart_redirect_to'] = __( 'Add to Cart Redirect To', 'add-to-cart-redirect' );
        return $settings_tabs;
    }


    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }


    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }


    /**
     * Get all the settings for this plugin
     *
     */
    public static function get_settings() {

        $settings = array(
            'section_title' => array(
                'name'     => '',
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'woo_cart_redirect_to_section_title'
            ),
            'woo_cart_redirect_to' => array(
				'name'    => esc_html__( 'Add to Cart redirect to', 'add-to-cart-redirect' ),
				'id'       => 'woo_cart_redirect_to',
				'default' => absint( get_option( 'woocommerce_checkout_page_id' ) ),
				'type'     => 'single_select_page',
				'class'    => 'wc-enhanced-select-nostd', 
				'css'      => 'min-width:300px;',
				'desc' => esc_html__( 'After adding product to cart, page will redirect to the selected page.', 'add-to-cart-redirect' ),
            ), 
            'section_end' => array(
                'type' => 'sectionend',
                'id' => 'woo_cart_redirect_to_section_end'
            )
        );

        return apply_filters( 'wc_cart_redirect_to_settings', $settings );
    }
}