<?php

class wppp_options extends woocommerce_products_per_page {

	
	public function __construct() {
	
		parent::__construct();
		
		$this->wppp_settings_init();
			
	}
	
	public function wppp_register_setting() {
		
		// 1. register_setting :: Done in parent
/* 		register_setting( "wppp_settings", "wppp_settings" ); */
		
	}	
	
	public function wppp_settings_init() {
		
		// 2. check if settings exist, false -> add_option :: Done in parent
		/*
		if( !get_option( "wppp_settings" ) ) :
			$defaults = $this->wppp_settings_defaults();
			add_option( "wppp_settings", $defaults );
		endif;
		*/
			
			
		// 3. Add settings section
		add_settings_section(
			"wppp_settings",				 					// ID of section
			"WooCommerce Products Per Page",			 		// Page title
			array( $this, "wppp_section_callback" ),			// Callback for page description
			"wppp_settings"										// Page to display settings
		);
		
		// 4. Add settings field
		add_settings_field(
			"wppp_location",									// ID of setting
			__( "Dropdown location", "wppp" ),					// Settings label
			array( $this, "wppp_settings_field_location" ),		// Function to render dropdown
			"wppp_settings",									// ID of the settings page
			"wppp_settings"										// ID of the settings section
		);
		
		add_settings_field( 
			"wppp_products_per_page_list", 
			__( "List of dropdown options", "wppp" ),
			array( $this, "wppp_settings_field_ppp_list" ),
			"wppp_settings",
			"wppp_settings" 
		);
		
		add_settings_field(
			"wppp_default_ppp",
			__( "Default products per page", "wppp" ),
			array( $this, "wppp_settings_field_default_ppp" ),
			"wppp_settings",
			"wppp_settings"
		);
		
		add_settings_field(
			"wppp_shop_columns", 
			__( "Shop columns", "wppp" ), 
			array( $this, "wppp_settings_field_shop_columns" ),
			"wppp_settings",
			"wppp_settings"
		);
		
		// 5. Render options page
		$this->wppp_render_settings_page();
		
	}
	
	
	public function wppp_render_settings_page() {
		
		?>
		<div class="wrap">
		
			<h2><?php _e( "WooCommerce Products Per Page", "wppp" ); ?></h2>
			
			<form method="POST" action="options.php">
				<?php
				settings_fields( "wppp_settings" );
				do_settings_sections( "wppp_settings" );
				submit_button();
				?>
			</form>
			
		</div>
		<?php
		
	}
	
	public function wppp_settings_field_location() {
		
		?>
		<select name="wppp_settings[location]" class="">
			<option value="top" <?php selected( $this->options["location"], "top" ); ?>><?php _e( "Top", "wppp" ); ?></option>
			<option value="bottom" <?php selected( $this->options["location"], "bottom" ); ?>><?php _e( "Bottom", "wppp" ); ?></option>
			<option value="topbottom" <?php selected( $this->options["location"], "topbottom" ); ?>><?php _e( "Top/Bottom", "wppp" ); ?></option>
			<option value="none" <?php selected( $this->options["location"], "none" ); ?>><?php _e( "None", "wppp" ); ?></option>
		</select>
		<?php
		
	}
	
	
	public function wppp_settings_field_ppp_list() {

		?>
		<label for="productsPerPage">
			<input type="text" id="productsPerPage" name="wppp_settings[productsPerPage]" value="<?php echo $this->options["productsPerPage"]; ?>">
		<?php _e( "Seperated by spaces <em>(-1 for all products)</em>", "wppp" ); ?></label>
		<?php
		
	}
	
	
	public function wppp_settings_field_default_ppp() {
		
		?>
		<label for="default_ppp">
			<input type="number" id="default_ppp" name="wppp_settings[default_ppp]" value="<?php echo $this->options["default_ppp"]; ?>">
		<em><?php _e( "-1 for all products", "wppp" ); ?></em></label>
		<?php
		
	}
	
	
	public function wppp_settings_field_shop_columns() {
		
		?>
		<label for="shop_columns">
			<input type="number" id="shop_columns" name="wppp_settings[shop_columns]" value="<?php echo $this->options["shop_columns"]; ?>">
		</label>		
		<?php
		
	}
	
	
	public function wppp_section_callback() {
		
		echo __( "Configure the WooCommerce Product Per Page settings here.", "wppp" );
		
	}
		
}

?>