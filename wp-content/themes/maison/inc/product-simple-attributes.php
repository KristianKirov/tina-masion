<?php
global $product;
$product_attributes = array_filter( $product->get_attributes(), 'wc_attributes_array_filter_visible' );         
if (count($product_attributes) > 0) {
    echo '<p>';
    foreach ($product_attributes as $product_attribute) {
        if ($product_attribute->get_variation()) continue;
        $product_attribute_values = array();
        if ( $product_attribute->is_taxonomy() ) {
            $attribute_taxonomy = $product_attribute->get_taxonomy_object();
            $attribute_values = wc_get_product_terms( $product->get_id(), $product_attribute->get_name(), array( 'fields' => 'all' ) );
            foreach ( $attribute_values as $attribute_value ) {
                $product_attribute_values[] = esc_html( $attribute_value->name );
            }
        } else {
            $product_attribute_values = $product_attribute->get_options();
            foreach ( $product_attribute_values as &$value ) {
                $value = esc_html( $value );
            }
        }

        echo wc_attribute_label( $product_attribute->get_name() ) . ': ';
        echo apply_filters( 'woocommerce_attribute', wptexturize( implode( ', ', $product_attribute_values ) ), $product_attribute, $product_attribute_values );
        echo '<br />';
    }
    echo '</p>';
} ?>