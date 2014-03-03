<?PHP
/*
Plugin Name: Woocomerce Products Per Page
Plugin URI: http://www.jeroensormani.nl/
Description: Integrate a 'products per page' dropdown on your WooCommerce website! Set-up in <strong>seconds</strong>!
Version: 1.1
Author: Jeroen Sormani
Author URI: http://www.jeroensormani.nl

 * Copyright Jeroen Sormani
 *		
 *     This file is part of Woocomerce Products Per Page,
 *     a plugin for WordPress.
 *
 *     Woocomerce Products Per Page is free software:
 *     You can redistribute it and/or modify it under the terms of the
 *     GNU General Public License as published by the Free Software
 *     Foundation, either version 3 of the License, or (at your option)
 *     any later version.
 *
 *     Woocomerce Products Per Page is distributed in the hope that
 *     it will be useful, but WITHOUT ANY WARRANTY; without even the
 *     implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 *     PURPOSE. See the GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with WordPress. If not, see <http://www.gnu.org/licenses/>.
 */

class woocommerce_products_per_page {
	
	public $options;
	
	/* 	__construct()
	*	
	*	
	*/
	public function __construct() {
		
		$this->wppp_load_options();
		
		// Add an options page
		add_action( "admin_menu", array( $this, "wppp_add_options_menu" ) );
		
		// Check if ppp form is submit
		add_action( "init", array( $this, "wppp_submit_intercept" ) );
		
		// Add filter to products per page displayed
		add_filter( "loop_shop_per_page", array( $this, "wppp_products_per_page_hook" ) );
		// Add filter for product columns
		add_filter( "loop_shop_columns", array( $this, "wppp_shop_columns_hook" ) );
		// Enqueue some scripts
		add_action( "wp_enqueue_scripts", array( $this, "wppp_enqueue_scripts" ) );
		
	}
	
	/* 	wppp_hook_locations()
	*	
	*	Hook into the right look positions of WooCommerce
	*/
	public function wppp_hook_locations() {
		
		if( $this->options["location"] == "top" ) :
			add_action( "woocommerce_before_shop_loop", array( $this, "wppp_dropdown_object" ) );
		elseif( $this->options["location"] == "bottom" ) :
			add_action( "woocommerce_after_shop_loop", array( $this, "wppp_dropdown_object" ) );
		else :
			add_action( "woocommerce_before_shop_loop", array( $this, "wppp_dropdown_object" ) );
			add_action( "woocommerce_after_shop_loop", array( $this, "wppp_dropdown_object" ) );
		endif;

	}
	
	
	/* 	wppp_submit_intercept()
	*	
	*	
	*/	
	public function wppp_submit_intercept() {
		
		if ( isset( $_POST["wppp_ppp"] ) ) 
			setcookie( "products_per_page", $_POST["wppp_ppp"], time()+(3600*24*3), "/" );
		
	}
	
	
	public function wppp_products_per_page_hook() {

		if( isset( $_POST["wppp_ppp"] ) ) 
			return $_POST["wppp_ppp"];
		elseif( isset( $_COOKIE["products_per_page"] ) )
			return $_COOKIE["products_per_page"];
		else 
			return $this->options["default_ppp"];

	}	
	
	public function wppp_shop_columns_hook( $columns ) {

		$settings = get_option( "wppp_settings" );		
		if( $settings && $settings["shop_columns"] > 0 )
			$columns = $settings["shop_columns"];
		
		return $columns;
		
	}
	
	
	/* 	wppp_add_options_menu()
	*	
	*	
	*/
	public function wppp_add_options_menu() {
		
		add_options_page( "WooCommerce Products Per Page", "Products Per Page", "manage_options", "wppp_settings", array( $this, "wppp_options_page" ) );
		
		// 1. register_setting
		register_setting( "wppp_settings", "wppp_settings" );
	}
	
	public function wppp_enqueue_scripts() {
		
		wp_enqueue_style( "products-per-page", plugins_url( "/assets/css/style.css", __FILE__ ) );
		
	}
	
	
	/* 	wppp_options_page()
	*	
	*	Render options page
	*/
	public function wppp_options_page() {
		
		require_once "views/options-page.php";
		new wppp_options();
		
	}
	
	
	/* 	wppp_dropdown_object()
	*	
	*	Render dropdown object
	*/	
	public function wppp_dropdown_object() {
		
		require_once "objects/wppp-dropdown.php";
		new wppp_dropdown();
		
	}
	
	
	public function wppp_load_options() {
		
		if( !get_option( "wppp_settings" ) ) :
			$defaults = $this->wppp_settings_defaults();
			add_option( "wppp_settings", $defaults );
		endif;
		
		$this->options = get_option( "wppp_settings" );
		
	}
	
	
	public function wppp_settings_defaults() {
		
		$ppp_default = ( apply_filters( 'loop_shop_columns', 4 ) * 3) . " " . 
			( apply_filters( 'loop_shop_columns', 4 ) * 6) . " " .
			( apply_filters( 'loop_shop_columns', 4 ) * 9) . " " .
			"-1";
		
		$settings = apply_filters( "wppp_settings_defaults", array(
			"location"	 		=> "topbottom",
			"productsPerPage" 	=> $ppp_default,
			"default_ppp" 		=> apply_filters( 'loop_shop_per_page', get_option( 'posts_per_page' ) ),
			"shop_columns" 		=> apply_filters( 'loop_shop_columns', 4 ),
		) );
		
		return $settings;
		
	}

	
}
$wppp = new woocommerce_products_per_page();

$wppp->wppp_hook_locations();

?>