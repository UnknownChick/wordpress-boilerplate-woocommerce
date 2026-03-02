<?php

declare(strict_types=1);

namespace Theme\Models;

defined('ABSPATH') || die();

use Timber\Post;
use WC_Product;

/**
 * Timber model for WooCommerce products.
 *
 * Wraps WC_Product to expose useful helpers directly in Twig templates.
 */
class Product extends Post
{
	private ?WC_Product $wc_product = null;

	/**
	 * Lazy-load the underlying WC_Product object.
	 */
	public function wcProduct(): ?WC_Product
	{
		if ($this->wc_product === null && function_exists('wc_get_product')) {
			$this->wc_product = wc_get_product($this->ID);
		}

		return $this->wc_product;
	}

	/**
	 * Formatted price HTML (respects sale / regular).
	 */
	public function priceHtml(): string
	{
		return $this->wcProduct()?->get_price_html() ?? '';
	}

	/**
	 * Raw price (for sorting / display).
	 */
	public function price(): string
	{
		return $this->wcProduct()?->get_price() ?? '';
	}

	/**
	 * Whether the product is on sale.
	 */
	public function onSale(): bool
	{
		return $this->wcProduct()?->is_on_sale() ?? false;
	}

	/**
	 * Whether the product is in stock.
	 */
	public function inStock(): bool
	{
		return $this->wcProduct()?->is_in_stock() ?? false;
	}

	/**
	 * Short description (excerpt).
	 */
	public function shortDescription(): string
	{
		return $this->wcProduct()?->get_short_description() ?? '';
	}

	/**
	 * Average rating (0-5).
	 */
	public function averageRating(): string
	{
		return $this->wcProduct()?->get_average_rating() ?? '0';
	}

	/**
	 * Review count.
	 */
	public function reviewCount(): int
	{
		return (int) ($this->wcProduct()?->get_review_count() ?? 0);
	}

	/**
	 * Product type (simple, variable, grouped, external).
	 */
	public function type(): string
	{
		return $this->wcProduct()?->get_type() ?? 'simple';
	}

	/**
	 * SKU.
	 */
	public function sku(): string
	{
		return $this->wcProduct()?->get_sku() ?? '';
	}

	/**
	 * Add-to-cart URL.
	 */
	public function addToCartUrl(): string
	{
		return $this->wcProduct()?->add_to_cart_url() ?? $this->link();
	}

	/**
	 * Add-to-cart label.
	 */
	public function addToCartText(): string
	{
		return $this->wcProduct()?->add_to_cart_text() ?? __('Add to cart', 'woocommerce');
	}

	/**
	 * Gallery image IDs (excluding main thumbnail).
	 *
	 * @return int[]
	 */
	public function galleryIds(): array
	{
		return $this->wcProduct()?->get_gallery_image_ids() ?? [];
	}

	/**
	 * Product categories as WP_Term objects.
	 *
	 * @return \WP_Term[]
	 */
	public function categories(): array
	{
		return get_the_terms($this->ID, 'product_cat') ?: [];
	}
}
