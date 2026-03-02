<?php

declare(strict_types=1);

namespace Theme\Woo;

defined('ABSPATH') || die();

use Theme\Attributes\OnHook;
use Theme\Contracts\Registerable;
use Theme\Models\Product;

/**
 * WooCommerce theme integration & support declarations.
 */
#[OnHook('after_setup_theme', priority: 20)]
class WooSetup implements Registerable
{
	public function register(): void
	{
		$this->declareSupport();
		$this->registerProductClassmap();
		$this->registerWidgetAreas();
	}

	/**
	 * Declare all WooCommerce theme supports.
	 */
	private function declareSupport(): void
	{
		add_theme_support('wc-product-gallery-zoom');
		add_theme_support('wc-product-gallery-lightbox');
		add_theme_support('wc-product-gallery-slider');

		// Enable WooCommerce high-resolution product images
		add_theme_support('woocommerce', [
			'thumbnail_image_width' => 450,
			'single_image_width'    => 800,
			'product_grid'          => [
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 4,
				'min_columns'     => 1,
				'max_columns'     => 6,
			],
		]);
	}

	/**
	 * Map the 'product' post type to our custom Timber Product model.
	 */
	private function registerProductClassmap(): void
	{
		add_filter('timber/post/classmap', function (array $classmap): array {
			$classmap['product']          = Product::class;
			$classmap['product_variation'] = Product::class;

			return $classmap;
		});
	}

	/**
	 * Register WooCommerce-specific widget areas.
	 */
	private function registerWidgetAreas(): void
	{
		add_action('widgets_init', function (): void {
			register_sidebar([
				'name'          => __('Shop Filters', 'default'),
				'id'            => 'shop-filters',
				'description'   => __('Widgets shown in the shop/category filter sidebar (e.g. layered nav, price filter).', 'default'),
				'before_widget' => '<div class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget__title">',
				'after_title'   => '</h4>',
			]);
		});
	}
}
