<?php
/**
 * Shop breadcrumb – theme override.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! empty( $breadcrumb ) ) {

	echo $wrap_before;

	$last_key = count( $breadcrumb ) - 1;

	foreach ( $breadcrumb as $key => $crumb ) {

		echo $before;

		$is_last = ( $key === $last_key );

		if ( ! empty( $crumb[1] ) && ! $is_last ) {
			echo '<a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a>';
		} elseif ( $is_last ) {
			echo '<span class="woocommerce-breadcrumb__current">' . esc_html( $crumb[0] ) . '</span>';
		} else {
			echo esc_html( $crumb[0] );
		}

		echo $after;

		if ( ! $is_last ) {
			echo $delimiter;
		}
	}

	echo $wrap_after;

}
