<?php
/*
Plugin Name: Product Image Hover Addon For WooCommerce
Description: This plugin help to create an image hover on woocommerce featured image.
Version: 1.0.0
Author: Narinder Singh Bisht
Author URI: http://narindersingh.in
License: GPL3
License URI: http://www.gnu.org/licenses/gpl.html
Text Domain: product-img-hover-wc
*/

// Block direct access to the main plugin file.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class PIHWC_plugin_init{
    public function __construct(){

        add_action( 'plugins_loaded', array( $this, 'plugin_textdomain' ) );
		if ( in_array( 'woocommerce/woocommerce.php', 
        apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			
			$this->plugin_constants();
            if ( is_admin() ){
                require_once PIHWC_PATH . 'includes/woo-adminend-image-action.php';
            }
            
            else{
                add_action( 'wp_enqueue_scripts', array ( $this, 'enqueue_styles_scripts' ) );
                
			    require_once PIHWC_PATH . 'includes/woo-frontend-image-action.php';
            }
            
            
		} else {
			add_action( 'admin_notices', array( $this, 'WIH_admin_error_notice' ) );
		}

    }

    public function plugin_textdomain(){
        load_plugin_textdomain( 'product-img-hover-wc', false, basename( dirname( __FILE__ ) ) . '/languages/' );
    }

    /*
		register admin notice if woocommcer is not active.
	*/
	public function WIH_admin_error_notice(){
		$message = sprintf( esc_html__( 'The %1$sWoocommerce Product Image Hover%2$s plugin requires %1$sWooCommerce%2$s plugin active to run properly. 
        Please install %1$sWooCommerce%2$s and activate', 'product-img-hover-wc' ),'<strong>', '</strong>');

		printf( '<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}
	
	/*
		set plugin constants
	*/
	public function plugin_constants(){
		
		if ( ! defined( 'PIHWC_PATH' ) ) {
			define( 'PIHWC_PATH', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'PIHWC_URL' ) ) {
			define( 'PIHWC_URL', plugin_dir_url( __FILE__ ) );
		}
		
	}

    public function enqueue_styles_scripts(){
        wp_enqueue_style( 'woo-product-image-hover', PIHWC_URL . 'assets/css/style.css' );
    }


}
// Instantiate the plugin class.
$woo_img_hover = new PIHWC_plugin_init();