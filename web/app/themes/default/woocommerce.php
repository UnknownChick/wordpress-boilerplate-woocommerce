<?php

use Timber\Timber;

$context = Timber::context();

if (is_product_category()) {
	$current_category = get_queried_object();
	$context['current_category'] = $current_category;

	if ($current_category->parent != 0) {
		$ancestors = get_ancestors($current_category->term_id, 'product_cat');
		$root_id = end($ancestors);
	} else {
		$root_id = $current_category->term_id;
	}

	$context['category_branch'] = $category_service->getCategoryBranch($root_id);
}

$context['is_shop'] = is_shop();

if (is_singular('product')) {
	$context['post'] = Timber::get_post();
	$product = wc_get_product($context['post']->ID);
	$context['product'] = $product;

	// Get related products
	$related_limit = wc_get_loop_prop('columns');
	$related_ids = wc_get_related_products($context['post']->id, $related_limit);
	$context['related_products'] = Timber::get_posts($related_ids);

	// Restore the context and loop back to the main query loop.
	wp_reset_postdata();

	Timber::render('woo/single-product.twig', $context);
} else {
	$products = Timber::get_posts();
	$context['products'] = $products;

	if (is_product_category()) {
		$queried_object = get_queried_object();
		$term_id = $queried_object->term_id;
		$context['category'] = get_term($term_id, 'product_cat');
		$context['title'] = single_term_title('', false);
		$context['cat_title'] = get_term($context['category']->term_id, 'product_cat')->name;
		$context['cat_desc'] = get_term($context['category']->term_id, 'product_cat')->description;
	}

	wp_reset_postdata();
	Timber::render('woo/archive.twig', $context);
}
