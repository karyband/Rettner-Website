<?php
/**
 * Plugin Name: Rettner Plugin
 * Description: Works to customize woocommerce for the Rettner Web Page needs
 * Version: 1.0
 * Author: Karina Banda
 */


header('Content-type: text/plain; charset=utf-8');



if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*header changes*/
copy('content/plugins/rettner-plugin/new/headernew.php', 'content/themes/storefront/inc/structure/header.php');
copy('content/plugins/rettner-plugin/new/template-tagsnew.php', 'content/themes/storefront/inc/woocommerce/template-tags.php');


unlink('content/plugins/woocommerce/templates/single-product/price.php'); //deletes the price.php script, so the price won't show up
unlink('content/plugins/woocommerce/templates/global/quantity-input.php'); //deletes the price.php script, so the check out quantity won't show up and can't be altered
unlink('content/plugins/woocommerce/templates/loop/price.php');//deletes the page so the price doesn't show up on the group products page

copy('content/plugins/rettner-plugin/new/abstract-wc-productnew.php', 'content/plugins/woocommerce/includes/abstracts/abstract-wc-product.php');//changes In stock to available and add to cart in singles page to reserve
copy('content/plugins/rettner-plugin/new/class-wc-product-simplenew.php', 'content/plugins/woocommerce/includes/class-wc-product-simple.php');//changes add to cart in group page to reserve

copy('content/plugins/rettner-plugin/new/add-to-cartnew.min.js', 'content/plugins/woocommerce/assets/js/frontend/add-to-cart.min.js');//disables "view cart" to come up after hitting the reserve button


copy('content/plugins/rettner-plugin/new/form-billingnew.php', 'content/plugins/woocommerce/templates/checkout/form-billing.php');//Changes the Checkout Form

//copy('content/plugins/rettner-plugin/new/form-shippingnew.php', 'content/plugins/woocommerce/templates/checkout/form-shipping.php');//In the Checkout Form, It takes off the Shipping Additional Comment box

//copy('content/plugins/rettner-plugin/new/review-ordernew.php', 'content/plugins/woocommerce/templates/checkout/review-order.php');//In the Checkout Form, It takes off the Shipping Additional Comment box

//copy('content/plugins/rettner-plugin/new/form-checkoutnew.php', 'content/plugins/woocommerce/templates/checkout/form-checkout.php');//In checkout page, the review order visibility is hidden

copy('content/plugins/rettner-plugin/new/class-wc-form-handlernew.php', 'content/plugins/woocommerce/includes/class-wc-form-handler.php'); //In single product page, after adding the product to cart, redirects to checkout page

add_filter( 'woocommerce_add_cart_item_data', 'woo_custom_add_to_cart' ); //only allows for only one item to be in the cart
function woo_custom_add_to_cart( $cart_item_data ) {

    global $woocommerce;
    $woocommerce->cart->empty_cart();

    // Do nothing with the data and return
    return $cart_item_data;
}

add_filter('woocommerce_checkout_fields','custom_override_checkout_fields');//deletes all the woocommerce unnecessary fields from checkout page
function custom_override_checkout_fields($fields) {
 	unset($fields['billing']['billing_first_name']);
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_phone']);
    unset($fields['order']['order_comments']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_email']);
    unset($fields['billing']['billing_city']);
    return $fields;
	
	return $fields;
}


add_action('woocommerce_after_order_notes','my_custom_checkout_field');//add school net id and password fields to checkout page
function my_custom_checkout_field($checkout){
	echo '<div id="my_custom_checkout_field"><h2>'.__('Please enter your NetID and password.').'</h2>';
	woocommerce_form_field('net_id', array(
		'type'			=> 'text',
		'class'			=> array('form-row form-row form-row-first validate-required'),
		'label'			=> __('NetID:'),
		'placeholder'		=>__(''),
		), $checkout->get_value('net_id'));
		woocommerce_form_field('uofrpassword', array(
		'type'			=> 'password',
		'class'			=> array('form-row form-row form-row-first validate-required'),
		'label'			=> __('Password:'),
		'placeholder'		=>__(''),
		), $checkout->get_value('uofrpassword'));
	echo '</div>';
}

add_filter('woocommerce_enable_order_notes_field', '__return_false');//get ride of the "additional information" label on checkout page

add_action('woocommerce_checkout_process', 'my_custom_checkout_field_process');//check input of net id and password fields
function my_custom_checkout_field_process() {
	global $woocommerce;
	
	if(!$_POST['net_id'])
		wc_add_notice( '<strong>NetID</strong> is required.', $notice_type = 'error' );
	else if(!$_POST['uofrpassword'])
		wc_add_notice( '<strong>Password</strong> is required.', $notice_type = 'error' );
	else{
		if(validatecredentials($_POST['net_id'],$_POST['uofrpassword'])==false)//if netid and password are not valid
			wc_add_notice( '<strong>The NetID or password you entered is incorrect. Please try again.</strong>', $notice_type = 'error' );
	}
			
}

function validatecredentials($net,$passwrd){ //Validate UR credentials
	return true;
}
	

	
add_action('woocommerce_checkout_update_order_meta', 'rettner_update_order_meta');//include net id and password in the order meta
function rettner_update_order_meta( $order_id ) {
	if ($_POST['net_id']) update_post_meta( $order_id, 'net_id', esc_attr($_POST['net_id']));

}



add_action( 'init', 'active_rental_order_status' );//create new order status for active rentals
function active_rental_order_status() {
    register_post_status( 'wc-active-rental', array(
        'label'                     => 'Active Rental',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Active Rental <span class="count">(%s)</span>', 'Active Rental <span class="count">(%s)</span>' )
    ) );
}


add_filter( 'wc_order_statuses', 'add_active_rental_order_statuses' );// Add to list of WC Order statuses
function add_active_rental_order_statuses( $order_statuses ) {

    $new_order_statuses = array();

    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {

        $new_order_statuses[ $key ] = $status;

        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-active-rental'] = 'Active Rental';
        }
    }

    return $new_order_statuses;
}


add_filter('woocommerce_admin_order_actions','wdm_verify_product_limitation',5,2);//take off complete action from processing items
function wdm_verify_product_limitation( $actions, $the_order ){
    if ( $the_order->has_status( array( 'processing','on-hold') ) ) {
    unset($actions['complete']);
    }
    return $actions;
}

add_filter( 'manage_edit-shop_order_columns', 'add_order_columns' );//delete the unecessary columns and add item and netid columns
function add_order_columns($columns){
    $new_columns = (is_array($columns)) ? $columns : array();
    unset( $new_columns['shipping_address'] );
    unset( $new_columns['order_items'] );
	unset( $new_columns['customer_message'] );
	unset( $new_columns['order_total'] );


    $new_columns['item_column'] = 'Item';
    $new_columns['id_column'] = 'NetID';
    //stop editing

    $new_columns['order_actions'] = $columns['order_actions'];
    return $new_columns;
}


add_action( 'manage_shop_order_posts_custom_column', 'add_values_to_new_columns', 2 );//adds the values for the netid column
function add_values_to_new_columns($column){
    global $post;
    $data = get_post_meta( $post->ID );

   
    if ( $column == 'id_column' ) {    
        echo (isset($data['net_id']) ? $data['net_id'][0] : '');
    }


}

add_action( 'manage_shop_order_posts_custom_column' , 'item_value_column', 10, 2 );//adds the values for the item name column
function item_value_column( $column ) {
 global $post, $woocommerce, $the_order;

    switch ( $column ) {

        case 'item_column' :
            $terms = $the_order->get_items();

          if ( is_array( $terms ) ) {
                foreach($terms as $term)
        {
        echo $term['name'];
        }
              } else {
                _e( 'Unable get the products', 'woocommerce' );
        }
            break;

    }
}


add_filter( "manage_edit-shop_order_sortable_columns", 'net_id_sort' ); //allow the order review page to be sorted by net id
function net_id_sort( $columns ) {
    $custom = array(

        'id_column'    => 'net_id',

    );
    return wp_parse_args( $custom, $columns );
}







