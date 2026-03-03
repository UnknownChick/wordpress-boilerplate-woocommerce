<?php

declare(strict_types=1);

namespace Theme\Woo;

defined('ABSPATH') || die();

use Theme\Attributes\OnHook;
use Theme\Contracts\Registerable;

/**
 * WooCommerce hook customisations.
 *
 * Removes default WC wrappers (breadcrumb, sidebar sidebar, etc.) that conflict with the Timber templates and adds theme-specific replacements.
 */
#[OnHook('after_setup_theme', priority: 30)]
class WooHooks implements Registerable
{
	public function register(): void
	{
		add_action('init', [$this, 'removeDefaultWrappers']);
		add_action('init', [$this, 'removeDefaultBreadcrumb']);
		add_action('init', [$this, 'customiseShopLoop']);
		add_filter('template_include', [$this, 'routeWooPages']);
	}

	/**
	 * Remove the default WooCommerce content wrappers so our base.twig
	 * <main> element is used instead.
	 */
	public function removeDefaultWrappers(): void
	{
		remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper');
		remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end');

		// Remove native sidebar
		remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar');
	}

	/**
	 * Remove the WooCommerce breadcrumb (handle breadcrumbs in your own template).
	 */
	public function removeDefaultBreadcrumb(): void
	{
		remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
	}

	/**
	 * Force cart, checkout and My Account pages to use woocommerce.php
	 * instead of page.php, since WordPress treats them as regular pages.
	 */
	public function routeWooPages(string $template): string
	{
		if (is_cart() || is_checkout() || is_account_page()) {
			$woo_template = get_stylesheet_directory() . '/woocommerce.php';

			if (file_exists($woo_template)) {
				return $woo_template;
			}
		}

		return $template;
	}

	/**
	 * Customise product loop columns and per-page count.
	 */
	public function customiseShopLoop(): void
	{
		// Products per page
		add_filter('loop_shop_per_page', fn() => 12, 20);

		// Columns in the loop
		add_filter('loop_shop_columns', fn() => 4);

		// Related products: 4 columns, up to 4 products
		add_filter('woocommerce_output_related_products_args', function (array $args): array {
			$args['posts_per_page'] = 4;
			$args['columns']        = 4;

			return $args;
		});
	}
}
