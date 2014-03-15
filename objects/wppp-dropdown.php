<?php

class wppp_dropdown extends woocommerce_products_per_page {
	
	public $productPerPage;
	
	public function __construct( $productPerPage = null ) {
		
		parent::__construct();
		
		$this->productsPerPage = $this->wppp_prep_ppp( $productPerPage );
		
		if ( false == $productPerPage )
			$this->productsPerPage = $this->wppp_prep_ppp( apply_filters( 'wppp_products_per_page', $this->options['productsPerPage'] ) );
			
		$this->wppp_create_object();
		
	}
	
	
	public function wppp_create_object() {
		
		global $wp_query;
		
		$cat = $wp_query->get_queried_object();
		
		// Set action url if option behaviour is true
		if ( true == $cat->term_id && true == $this->options['behaviour'] ) :
			$action = ' action="' . get_term_link( $cat->term_id, 'product_cat' ) . '"';
		elseif ( true == $this->options['behaviour'] ) :
			$action = 'action="' . get_permalink( woocommerce_get_page_id( 'shop' ) ) . '"';
		endif;
		
		?>
		<form method="post" <?php echo $action; ?> class="form-wppp-select products-per-page">
			<?php
			do_action( 'wppp_before_dropdown' );
			?>
			<select name="wppp_ppp" onchange="this.form.submit()" class="select wppp-select">
			
				<?php
				global $woocommerce;
				foreach( $this->productsPerPage as $key => $value ) :
				
					$selectedMatch = isset( $_POST['wppp_ppp'] ) ? $_POST['wppp_ppp'] : $woocommerce->session->get( 'products_per_page' );
					?>
					<option value="<?php echo $value; ?>" <?php selected( $value, $selectedMatch ); ?>>
						<?php 
						$ppp_text = apply_filters( 'wppp_ppp_text', __( '%s products per page', 'wppp' ) );
						printf( $ppp_text, $value == -1 ? __( 'All', 'wppp' ) : $value ); // Set to 'All' when value is -1
						?>
					</option>
					<?php
					
				endforeach;
				?>
			</select>
			<?php
			do_action( 'wppp_after_dropdown' );
			?>
		</form>
		<?php
		
	}
	
	
	public function wppp_prep_ppp( $productPerPage ) {

		return explode( ' ', $productPerPage );
		
	}
	
}

?>