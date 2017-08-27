<?php

add_theme_support('title-tag');
add_theme_support('post-thumbnails');

function maison_woocommerce_support() {
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'maison_woocommerce_support');

function maison_scripts() {
	$maison_theme = wp_get_theme();
	$maison_theme_version = $maison_theme->get('Version');
	$resources_suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

	$has_products_list = is_shop() || is_product_category() || is_product();
	$is_checkout = is_checkout();
	
    wp_enqueue_style('bootstrap', get_theme_file_uri("/assets/css/bootstrap$resources_suffix.css"), array(), $maison_theme_version);
	wp_enqueue_style('litebox-css', get_theme_file_uri('/assets/css/lity.min.css'), array(), '2.2.2');
    wp_enqueue_style('maison-style', get_stylesheet_directory_uri() . "/style$resources_suffix.css", array('bootstrap'), $maison_theme_version);
    wp_enqueue_style('icons', get_theme_file_uri("/assets/css/fontello$resources_suffix.css"), array(), $maison_theme_version);
	if ($is_checkout) {
		wp_enqueue_style('checkout-style', get_theme_file_uri("/assets/css/checkout$resources_suffix.css"), array('maison-style'), $maison_theme_version);
	}

	$frontend_data = array(
		'ajaxUrl' => admin_url('admin-ajax.php'));

    wp_register_script( 'maison-global', get_theme_file_uri("/assets/js/global$resources_suffix.js" ), array( 'jquery' ), $maison_theme_version, true );
	wp_localize_script( 'maison-global', 'maison_frontend_data', $frontend_data );
	wp_enqueue_script ( 'maison-global' );

	wp_enqueue_script('litebox-js', get_theme_file_uri( '/assets/js/lity.min.js' ), array( 'jquery' ), '2.2.2', true );

	if ($has_products_list) {
		wp_enqueue_script( 'wc-add-to-cart-variation' ); // add add-to-cart-variation script because of the quick view
	}

	if ($is_checkout) {
		wp_enqueue_script('checkout-script', get_theme_file_uri("/assets/js/checkout$resources_suffix.js"), array('wc-checkout', 'maison-global'), $maison_theme_version);
	}
}
add_action('wp_enqueue_scripts', 'maison_scripts');

function register_maison_menus() {
  register_nav_menus(
    array(
        'top-nav-left' => 'Top Navigation Left',
        'top-nav-right' => 'Top Navigation Right',
        'top-nav-mobile-main' => 'Top Navigation Mobile Main',
        'top-nav-mobile-secondary' => 'Top Navigation Mobile Secondary',
        'footer-information' => 'Footer Information',
        'footer-online-shop' => 'Footer Online Shop',
        'footer-legal' => 'Footer Legal',
        'footer-mobile' => 'Footer Mobile',
		'single-product-additional' => 'Single Product Additional'
     )
   );
}
add_action('init', 'register_maison_menus');

function maison_wp_nav_menu_items( $items, $args, $ajax = false ) {
	// Top Navigation Area Only
	if ( ( isset( $ajax ) && $ajax ) || ( property_exists( $args, 'theme_location' ) && $args->theme_location === 'top-nav-right' ) ) {
		// WooCommerce
		if ( class_exists( 'woocommerce' ) ) {
			$css_class = 'menu-item menu-item-type-cart menu-item-type-woocommerce-cart';
			// Is this the cart page?
			if ( is_cart() )
				$css_class .= ' current-menu-item';
			$items .= '<li class="' . esc_attr( $css_class ) . '">';
				$items .= '<a class="cart-contents" href="' . esc_url( WC()->cart->get_cart_url() ) . '">';
					$items .= 'Cart (' . wp_kses_data(WC()->cart->get_cart_contents_count()) . ')';
				$items .= '</a>';
			$items .= '</li>';
		}
	}
	return $items;
}
add_filter('wp_nav_menu_items', 'maison_wp_nav_menu_items', 10, 2);

/**
 * This function updates the Top Navigation WooCommerce cart link contents when an item is added via AJAX.
 */
function maison_woocommerce_add_to_cart_fragments($fragments) {
	// Add our fragment
	$fragments['li.menu-item-type-woocommerce-cart'] = maison_wp_nav_menu_items('', new stdClass(), true);
	return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'maison_woocommerce_add_to_cart_fragments');

add_filter('use_default_gallery_style', '__return_false');

function maison_gallery($html, $attr, $instance) {
	if (!isset($attr['wrapperclass']) && !isset($attr['itemclass']) && !isset($attr['imageclass'])) {
		return $html;
	}

	$wrapper_class = '';
	if (isset($attr['wrapperclass'])) {
		$wrapper_class = $attr['wrapperclass'];;
		unset($attr['wrapperclass']);
	}

	$item_class = '';
	if (isset($attr['itemclass'])) {
		$item_class = $attr['itemclass'];;
		unset($attr['itemclass']);
	}

	$image_class = '';
	if (isset($attr['imageclass'])) {
		$image_class = $attr['imageclass'];;
		unset($attr['imageclass']);
	}

	$html = gallery_shortcode($attr);

	if ($wrapper_class) {
		$html = str_replace("class='gallery ", sprintf("class='gallery %s ", esc_attr($wrapper_class)), $html);
	}

	if ($item_class) {
		$html = str_replace("class='gallery-item", "class='gallery-item " . esc_attr($item_class), $html);
	}

	if ($image_class) {
		$html = str_replace('class="attachment', sprintf('class="%s attachment', esc_attr($image_class)), $html);
	}

	return $html;
}
add_filter('post_gallery', 'maison_gallery', 10, 3);

add_theme_support('html5', array('gallery'));

function filter_ptags_on_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

add_filter('the_content', 'filter_ptags_on_images');

function maison_photo_gallery($field = null, $post_id){
	$images = get_post_meta($post_id, $field, true);
	$images = explode(',', $images);
	$args = array('post_type' => 'attachment', 'posts_per_page' => -1, 'post__in' => $images, 'orderby' => 'post__in'); 
	$images = get_posts($args);
	$images = array_filter($images);
	$array = array();
	if(count($images)):
		foreach($images as $image):
			$title = $image->post_title;
			$content = $image->post_content;
			$full_url = wp_get_attachment_url($image->ID);
			$meta_data = wp_get_attachment_metadata($image->ID);
			$orientation = 'landscape';
			if (isset($meta_data['height'], $meta_data['width'])) {
				$orientation = ($meta_data['height'] > $meta_data['width']) ? 'portrait' : 'landscape';
			}
			$array[] = array(
				'id' => $image->ID,
				'title' => $title,
				'caption' => $content,
				'full_url' => $full_url,
				'orientation' => $orientation
			);
		endforeach;
	endif;
	return $array;
}

add_filter('woocommerce_show_page_title', '__return_false');

function mainson_woocommerce_breadcrumb($args = array()) {
	if (is_product()) {
		echo '<div class="clearfix u-mb1">';

		woocommerce_breadcrumb($args);

		$prev_post_link = get_previous_post_link('%link', '&lt; Previous', true, '', 'product_cat');
		$next_post_link = get_next_post_link('%link', 'Next &gt;', true, '', 'product_cat');

		if ($prev_post_link || $next_post_link) {
			echo '<div class="u-fr u-cblack u-mb1">';
			echo $prev_post_link;
			if ($prev_post_link && $next_post_link) echo ' / ';
			echo $next_post_link;
			echo '</div>';
		}
		echo '</div>';
	}
}

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
add_action('woocommerce_before_main_content', 'mainson_woocommerce_breadcrumb', 20);

function maison_woocommerce_breadcrumb_home_url() {
	$shop_page_url = get_permalink(wc_get_page_id('shop'));

    return $shop_page_url;
}
add_filter( 'woocommerce_breadcrumb_home_url', 'maison_woocommerce_breadcrumb_home_url' );

function maison_woocommerce_breadcrumb_defaults($args) {
	$args['home'] = 'Shop';
	$args['delimiter'] = '<span class="woocommerce-breadcrumb-separator">&nbsp;&gt;&nbsp;</span>';
    return $args;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'maison_woocommerce_breadcrumb_defaults' );

remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

function maison_woocommerce_before_shop_loop() {
	echo '<div class="col-lg-17 col-lg-push-3 col-md-16 col-md-push-4 col-sm-15 col-sm-push-5">';
}
add_action( 'woocommerce_before_shop_loop', 'maison_woocommerce_before_shop_loop', 8 );

function maison_woocommerce_after_shop_loop() {
	echo '</div>';
}
add_action( 'woocommerce_after_shop_loop', 'maison_woocommerce_after_shop_loop', 50 );

add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

function maison_template_loop_product_div_open() {
	echo '<div class="article u-mb6">';
}

function maison_template_loop_product_quick_view() {
	global $post;
	echo '<a href="#product-' . $post->post_name . '" class="article-link-quickview u-ttu u-cblack" data-quick-view>Quickview</a>';
}

function maison_template_loop_product_link_open() {
	echo '<a href="' . get_the_permalink() . '" class="article-link">';
}

function maison_after_shop_loop_item() {
	echo '</div>';
}

remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );

add_action( 'woocommerce_before_shop_loop_item', 'maison_template_loop_product_div_open', 10 );
add_action( 'woocommerce_before_shop_loop_item', 'maison_template_loop_product_quick_view', 10 );
add_action( 'woocommerce_before_shop_loop_item', 'maison_template_loop_product_link_open', 10 );
add_action( 'woocommerce_after_shop_loop_item', 'maison_after_shop_loop_item', 20 );

function maison_template_loop_product_thumbnail() {
	$product_thumbnail_id = get_post_thumbnail_id();
	$product_thumbnail = wp_get_attachment_image_src($product_thumbnail_id, 'shop_catalog');
	$product_image = wp_get_attachment_image_src($product_thumbnail_id, 'full');
	?>
		<figure>
            <img class="responsive" src="<?php echo $product_thumbnail[0]; ?>" />
            <img class="responsive article-image--hover" src="<?php echo $product_image[0]; ?>" />
        </figure>
	<?php
}

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'maison_template_loop_product_thumbnail', 10 );

function woocommerce_template_loop_product_title() {
	echo '<span class="hl u-ttu u-db">' . get_the_title() . '</span>';
}

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

function maison_loop_shop_per_page( $page_size ) {
	$page_size = -1;
	return $page_size;
}

add_filter( 'loop_shop_per_page', 'maison_loop_shop_per_page', 20 );

function maison_load_product_quick_view() {
	if ( ! isset( $_REQUEST['product_slug'] ) ) {
		die();
	}

	$product_slug = $_REQUEST['product_slug'];
	wp( 'name=' . $product_slug . '&post_type=product' );
	// WC()->structured_data->generate_product_data();

	require 'product-modal.php';
	die();
}

add_action( 'wp_ajax_maison_load_product_quick_view', 'maison_load_product_quick_view' );
add_action( 'wp_ajax_nopriv_maison_load_product_quick_view', 'maison_load_product_quick_view' );

function maison_woocommerce_short_description_add_label($content) {
	if ($content) {
		$content = 'Description: ' . $content;
	}

	return $content;
}
add_filter( 'woocommerce_short_description', 'maison_woocommerce_short_description_add_label', 9 );

// function maison_woocommerce_product_tabs_remove($tabs) {
//     unset($tabs['reviews']);
//     return $tabs;
// }
// add_filter( 'woocommerce_product_tabs', 'maison_woocommerce_product_tabs_remove', 98 );

// add_filter('woocommerce_product_additional_information_heading', '__return_false');

// function maison_before_add_to_cart_quantity() {
// 	echo '<p class="real">';
// }
// add_action('woocommerce_before_add_to_cart_quantity', 'maison_before_add_to_cart_quantity');

// function maison_after_add_to_cart_quantity() {
// 	echo '</p>';
// }
// add_action('woocommerce_after_add_to_cart_quantity', 'maison_after_add_to_cart_quantity');

add_filter('woocommerce_reset_variations_link', '__return_null');

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

function maison_product_simple_attributes() {
	require 'inc/product-simple-attributes.php';
}

add_action('woocommerce_single_product_summary', 'maison_product_simple_attributes', 20);

function maison_single_product_additional_links() {
	wp_nav_menu(array(
		'theme_location' => 'single-product-additional',
		'container' => 'nav',
		'container_class' => 'item-area',
		'menu_class' => 'list-clean list-inline list-separated',
		'item_spacing' => 'discard'));
}

add_action('woocommerce_single_product_summary', 'maison_single_product_additional_links', 40);

function maison_addtoany_share_buttons() {
	echo '<div class="single-product-share-container">';
	ADDTOANY_SHARE_SAVE_KIT();
	echo '</div>';
}

add_action('woocommerce_share', 'maison_addtoany_share_buttons');

remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 10);

add_theme_support( 'wc-product-gallery-slider' );
add_theme_support( 'wc-product-gallery-lightbox' );
//add_theme_support( 'wc-product-gallery-zoom' );

function maison_is_attribute_in_product_name($is_in_name) {
	if (is_checkout()) {
		return false;
	}

	return $is_in_name;
}
add_filter('woocommerce_is_attribute_in_product_name', 'maison_is_attribute_in_product_name');

function maison_update_checkout_fields($fields) {
	// var_dump($fields['order']);
	$fields['order']['is_gift'] = array(
		'type' => 'checkbox',
		'label' => 'This is a gift'
	);

	return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'maison_update_checkout_fields' );

function maison_checkout_field_display_admin_order_meta($order){
    echo '<p><strong>This is a gift?:</strong> ' . (get_post_meta( $order->get_id(), 'is_gift', true ) ? 'Yes' : 'No') . '</p>';
}
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'maison_checkout_field_display_admin_order_meta', 10, 1 );

function maison_checkout_update_order_meta($order_id, $data) {
	$is_gift = 0;
	if (isset($data['is_gift'])) {
		$is_gift = $data['is_gift'];
	}
	update_post_meta( $order_id, 'is_gift', $is_gift);
}
add_action( 'woocommerce_checkout_update_order_meta', 'maison_checkout_update_order_meta', 10, 2);

add_filter('woocommerce_ship_to_different_address_checked', '__return_false');

function maison_woocommerce_sale_flash($markup, $post, $product) {
	if (!$product->is_in_stock()) {
		return ''; // Do not display discount, when the product is out of stock
	}

	$product_type = $product->get_type();
	if ($product_type == 'variable') {
		$prices = $product->get_variation_prices( true );
		if ( empty( $prices['price'] ) ) {
			return $markup;
		} else {
			$active_prices = $prices['price'];
			$regular_prices = $prices['regular_price'];
			$max_percent_discount = 0;
			foreach ( $active_prices as $variation_id => $variation_price ) {
				if (!isset($regular_prices[$variation_id])) {
					continue;
				}

				$regular_price = $regular_prices[$variation_id];
				if ($variation_price >= $regular_price) {
					continue;
				}
				$variation_percent_discount = round( ( ( $regular_price - $variation_price ) * 100) / $regular_price );
				if ($variation_percent_discount > $max_percent_discount) {
					$max_percent_discount = $variation_percent_discount;
				}
			}

			if ($max_percent_discount == 0) {
				return $markup;
			}
			else {
				return '<span class="onsale">-' . $max_percent_discount . '%</span>';
			}
		}
	}
	elseif ($product_type == 'simple') {
		$percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) * 100) / $product->get_regular_price() );
		return '<span class="onsale">-' . $percentage . '%</span>';
	}
	else {
		return $markup;
	}
}
add_filter('woocommerce_sale_flash', 'maison_woocommerce_sale_flash', 10, 3);

function maison_woocommerce_before_shop_loop_item_title() {
	global $product;
    if ( !$product->is_in_stock() ) {
        echo '<span class="soldout">Sold out!</span>';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'maison_woocommerce_before_shop_loop_item_title');

function maison_woocommerce_format_sale_price($price_markup, $regular_price, $sale_price) {
	$price = '<ins>' . ( is_numeric( $sale_price ) ? wc_price( $sale_price ) : $sale_price ) . '</ins> <del>' . ( is_numeric( $regular_price ) ? wc_price( $regular_price ) : $regular_price ) . '</del>';

	return $price;
}
add_filter('woocommerce_format_sale_price', 'maison_woocommerce_format_sale_price', 10, 3);

function maison_woocommerce_product_single_add_to_cart_text($text, $product) {
	if ( !$product->is_in_stock() ) {
        return 'Sold Out!';
	}
	
	return $text;
}
add_filter('woocommerce_product_single_add_to_cart_text', 'maison_woocommerce_product_single_add_to_cart_text', 10, 2);

add_action( 'woocommerce_admin_order_data_after_billing_address', 'maison_custom_checkout_field_display_admin_order_meta', 10, 1 );
function maison_custom_checkout_field_display_admin_order_meta($order){
	$order_id = $order->get_id();
	$order_payment_method = $order->get_payment_method();
	if ($order_payment_method == 'maison_fibank_gateway') {
		echo '<p><strong>Fibank Transaction Id:</strong> ' . get_post_meta($order_id, '_fibank_transaction_id', true) . '</p>';
	}
}