<?php

/**
 * WooCommerce template router.
 *
 * Replaces shortcode-based page content with direct Timber/Twig rendering.
 * All WooCommerce pages run through this single file instead of going through
 * the standard WordPress template hierarchy.
 */

defined('ABSPATH') || die();

use Timber\Timber;

$context = Timber::context();

/* -----------------------------------------------------------------------
 * Single product
 * --------------------------------------------------------------------- */
if (is_singular('product')) {
	$post    = Timber::get_post();
	$product = wc_get_product($post->ID);

	$context['post']    = $post;
	$context['product'] = $product;

	// Related products
	$related_ids              = wc_get_related_products($post->ID, 4);
	$context['related_posts'] = Timber::get_posts($related_ids);

	wp_reset_postdata();

	Timber::render('woo/single-product.twig', $context);
	return;
}

/* -----------------------------------------------------------------------
 * Cart
 * --------------------------------------------------------------------- */
if (is_cart()) {
	Timber::render('woo/cart.twig', $context);
	return;
}

/* -----------------------------------------------------------------------
 * Checkout
 * --------------------------------------------------------------------- */
if (is_checkout()) {
	Timber::render('woo/checkout.twig', $context);
	return;
}

/* -----------------------------------------------------------------------
 * My Account
 * --------------------------------------------------------------------- */
if (is_account_page()) {
	Timber::render('woo/account.twig', $context);
	return;
}

/* -----------------------------------------------------------------------
 * Shop / Archive / Category / Tag
 * --------------------------------------------------------------------- */
$context['is_shop']     = is_shop();
$context['is_category'] = is_product_category();
$context['is_tag']      = is_product_tag();
$context['products']    = Timber::get_posts();

if (is_product_category() || is_product_tag()) {
	$queried = get_queried_object();

	$context['title']    = single_term_title('', false);
	$context['category'] = $queried;
	$context['cat_name'] = $queried->name ?? '';
	$context['cat_desc'] = $queried->description ?? '';

	// Build ancestor chain for breadcrumb / sub-navigation
	if (!empty($queried->parent)) {
		$ancestors        = get_ancestors($queried->term_id, 'product_cat');
		$root_id          = end($ancestors);
		$context['root_category'] = get_term($root_id, 'product_cat');
	}
}

if (is_shop()) {
	$context['title'] = get_the_title(wc_get_page_id('shop'));
}

wp_reset_postdata();

Timber::render('woo/archive.twig', $context);
