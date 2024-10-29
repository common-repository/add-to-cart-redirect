<?php 

namespace Atcr;

/**
 * The Redirect Class
 */
class Redirect {
	 
	public function __construct() {
		
		// Cart Redirect
		add_filter( 'woocommerce_add_to_cart_redirect', [ $this, 'woo_cart_redirect' ] );

		//  Ajax support for redirection
		add_filter( 'woocommerce_get_script_data', [ $this, 'get_script_data' ], 10, 2 );
		
	}

	public function woo_cart_redirect( $url ){
 		 
		if ( (bool) get_option( 'woo_cart_redirect_to' ) ) {
			$url = get_permalink( get_option( 'woo_cart_redirect_to' ) );
		}
		
		return apply_filters( 'woo_cart_redirect_to', $url ); 
	 

	}


	public function get_script_data( $params, $handle ) {
 
		if ( 'wc-add-to-cart' == $handle ) {
			$params = array_merge( $params, array(
				'cart_redirect_after_add' => (bool) get_option( 'woo_cart_redirect_to' ) ? 'yes' : 'no'
			) );
		}
		
		return $params;
	}
}