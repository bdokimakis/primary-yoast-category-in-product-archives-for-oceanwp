<?php

/**
* Plugin Name: Primary Yoast Category In Product Archives for OceanWP
* Description: Replaces the OceanWP owp-archive-product.php and makes sure every product shows only the primary category assigned via the relevant Yoast SEO functionality, without a link.
* Author: b.dokimakis.gr
* Version: 1.0
**/

add_filter( 'woocommerce_locate_template', 'pycipafo_replace_template', 10, 3 );
function pycipafo_replace_template( $template, $template_name, $template_path ) {
	$basename = basename( $template );
	if( $basename == 'owp-archive-product.php' ) {
		$template = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'templates/owp-archive-product.php';
	}
	return $template;
}

function pycipafo_get_primary_taxonomy_term( $post = 0, $taxonomy = 'category' ) {
	$terms = get_the_terms( $post, $taxonomy );

	if ( $terms && class_exists( 'WPSEO_Primary_Term' ) ) {
		$wpseo_primary_term = new WPSEO_Primary_Term( $taxonomy, $post );
		$wpseo_primary_term = $wpseo_primary_term->get_primary_term();
		$term = get_term( $wpseo_primary_term );
		if ( !is_wp_error( $term ) ) {
			return $term->name;
		} else {
			return false;
		}
	}
	else {
		return false;
	}
}