<?php

class PIHWC_plugin_adminend{
    protected static $_instance = null;

    protected function __construct() {

        $this->hooks();
        
    }

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }

    public function hooks(){
        add_action( 'admin_enqueue_scripts', array($this, 'scripts') );
        add_action( 'add_meta_boxes', array( $this, 'register_meta_box' ) );
        add_action( 'save_post',  array( $this, 'save' ) );
    }

    //Register Meta Box
    public function register_meta_box( $post_type ) {

        // Limit meta box to certain post types.
        $post_types = array('product' );

        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box( 'woo-hover-image-meta', 
            esc_html__( 'Product Additional Image', 'product-img-hover-wc' ), 
            array( $this, 'meta_box_callback' ),
            $post_type , 'side', 'default' );
        }
    }

    //Add field
    public function meta_box_callback( $meta_id ) {

        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'woo_product_hover_img_box', 'woo_product_hover_img_nonce' );
        $woo_product_hover_img =  get_post_meta( $meta_id->ID, 'woo_product_hover_img', true ) ;
        $hover_img = isset($woo_product_hover_img) && $woo_product_hover_img != ''  ? '' : 'display:none;'; 
        $hover_no_img = isset($woo_product_hover_img) && $woo_product_hover_img != '' ? 'style="display:none;"' : '';
    ?>
        <p>
		    <label for="woo-product-hover-image">
                <?php esc_html_e( 'Product Hover Image', 'product-img-hover-wc' ); ?>
            </label> 
		    <div class="wrt-cta-content-image">
				<img id="woo_product_hover_img-preview" src="<?php echo esc_url($woo_product_hover_img); ?>" style="margin:5px 0;padding:0;max-width:180px;height:auto;<?php echo esc_attr($hover_img); ?>" />
            </div>
            <input class="widefat" id="woo_product_hover_img" name="woo_product_hover_img" type="text" value="<?php echo esc_url( $woo_product_hover_img ); ?>" style="display: none"/>
            
            <input type="button" value="<?php echo esc_attr(__('Select Image', 'product-img-hover-wc')); ?>" class="button button-primary wih-media-upload" id="woo_product_hover_img-button" <?php echo esc_attr($hover_no_img); ?>/>
            
            <input type="button" value="<?php echo esc_attr(__('Remove Image', 'product-img-hover-wc')); ?>" class="button button-secondary wih-media-remove" id="woo_product_hover_img-remove" style="<?php echo esc_attr($hover_img); ?>" />
        </p>

    <?php
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
    */

    public function save( $post_id ) {
 
        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
        */
 
        // Check if our nonce is set.
        if ( ! isset( $_POST['woo_product_hover_img_nonce'] ) ) {
            return $post_id;
        }
 
        $nonce = $_POST['woo_product_hover_img_nonce'];
 
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'woo_product_hover_img_box' ) ) {
            return $post_id;
        }
 
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
 
        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
 
        /* OK, it's safe for us to save the data now. */
        

        // Sanitize the user input.
        $woo_product_hover_img = sanitize_text_field( $_POST['woo_product_hover_img'] );

        // Update the meta field.
        update_post_meta( $post_id, 'woo_product_hover_img', $woo_product_hover_img );
 
       
    }

    public function scripts() {
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_media();
		wp_enqueue_script('woo-hover-image', PIHWC_URL . 'assets/js/admin/hover_image_box.js', array('jquery'));
	}

}
PIHWC_plugin_adminend::instance();