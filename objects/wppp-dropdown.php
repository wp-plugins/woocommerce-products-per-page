<?php

class wppp_dropdown extends woocommerce_products_per_page {
	
	public $productPerPage;
	
	public function __construct( $productPerPage = null ) {
		
		parent::__construct();
		
		$this->productsPerPage = $this->wppp_prep_ppp( $productPerPage );
		
		if( !$productPerPage )
			$this->productsPerPage = $this->wppp_prep_ppp( apply_filters( "wppp_product_per_page", $this->options["productsPerPage"] ) );
			
		$this->wppp_create_object();
		
	}
	
	
	public function wppp_create_object() {

		?>
		<form method="post" class="form-wppp-select products-per-page">
			<?php
			do_action( "wppp_before_dropdown" );
			?>
			<select name="wppp_ppp" onchange="this.form.submit()" class="select wppp-select">
			
				<?php
				foreach( $this->productsPerPage as $key => $value ) :
					$selectedMatch = isset( $_POST["wppp_ppp"] ) ? $_POST["wppp_ppp"] : $_COOKIE["products_per_page"];
					?>
					<option value="<?php echo $value; ?>" <?php selected( $value, $selectedMatch ); ?>>
						<?php printf( __( "%s products per page", "wppp" ), $value==-1?"All" : $value ) ?>
					</option>
					<?php
				endforeach;
				?>
			</select>
			<?php
			do_action( "wppp_after_dropdown" );
			?>
		</form>
		<?php
		
	}
	
	
	public function wppp_prep_ppp( $productPerPage ) {

		return explode( " ", $productPerPage );
		
	}
	
}

?>