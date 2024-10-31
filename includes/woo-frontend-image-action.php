<?php
//echo 'here'; die;
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

if ( ! function_exists( 'woocommerce_template_loop_product_thumbnail' ) ) {
    function woocommerce_template_loop_product_thumbnail() {
        echo woocommerce_get_product_thumbnail();
    } 
}
if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {   
    function woocommerce_get_product_thumbnail( $size = 'woocommerce_thumbnail', $placeholder_width = 0, $placeholder_height = 0  ) {
        global $product, $post, $woocommerce;

        $attachment_ids = $product->get_gallery_image_ids();
        
        $woo_product_hover_img = get_post_meta( $product->get_ID(), 'woo_product_hover_img', true );

        $output = '<div class="woo-product-image-hover-box">';

        if ( has_post_thumbnail() ) { 
            
            $output .= get_the_post_thumbnail( $product->get_ID(), $size );
            if( $woo_product_hover_img != ''){
                $attachment_id = attachment_url_to_postid( $woo_product_hover_img );
                if($attachment_id){
                    $attachment = wp_get_attachment_image_src( $attachment_id, $size);
                    if( isset( $attachment[0]))
                    $output .= '<img width="'.esc_attr( $attachment[1] ).'" height="'.esc_attr( $attachment[2] ).'" src="'. esc_url( $attachment[0] ).'" alt="'. $product->get_title() . '" class="attachment-'.$size.' size-'.$size.' wp-post-image woocommerce-hover-image" />';
                }
                
            }

        } else {
            $output .= wc_placeholder_img( $size );
        }                       
        $output .= '</div>';
        return $output;
    }
}
