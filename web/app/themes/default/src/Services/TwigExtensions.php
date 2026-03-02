<?php

declare(strict_types=1);

namespace Theme\Services;

defined('ABSPATH') || die();

use Twig\Environment;
use Twig\TwigFunction;
use Theme\Contracts\Registerable;
use Theme\Helpers\HmrHelper;

class TwigExtensions implements Registerable
{
	public function __construct(private HmrHelper $hmr)
	{
	}

	public function register(): void
	{
		add_filter('timber/twig', [$this, 'addFunctions']);
	}

	public function addFunctions(Environment $twig): Environment
	{
		$twig->addFunction(new TwigFunction('asset', [$this, 'asset']));

		// WooCommerce rendering helpers (avoids shortcode syntax in templates)
		$twig->addFunction(new TwigFunction('wc_cart', [$this, 'wcCart']));
		$twig->addFunction(new TwigFunction('wc_checkout', [$this, 'wcCheckout']));
		$twig->addFunction(new TwigFunction('wc_account', [$this, 'wcAccount']));
		$twig->addFunction(new TwigFunction('wc_notices', [$this, 'wcNotices']));
		$twig->addFunction(new TwigFunction('wc_cart_url', [$this, 'wcCartUrl']));
		$twig->addFunction(new TwigFunction('wc_cart_count', [$this, 'wcCartCount']));

		return $twig;
	}

	/**
	 * Render the WooCommerce cart page content without using a shortcode.
	 */
	public function wcCart(): void
	{
		if (class_exists('WC_Shortcode_Cart')) {
			\WC_Shortcode_Cart::output([]);
		}
	}

	/**
	 * Render the WooCommerce checkout page content without using a shortcode.
	 */
	public function wcCheckout(): void
	{
		if (class_exists('WC_Shortcode_Checkout')) {
			\WC_Shortcode_Checkout::output([]);
		}
	}

	/**
	 * Render the WooCommerce My Account page content without using a shortcode.
	 */
	public function wcAccount(): void
	{
		if (class_exists('WC_Shortcode_My_Account')) {
			\WC_Shortcode_My_Account::output([]);
		}
	}

	/**
	 * Print WooCommerce store notices (success, error, info).
	 */
	public function wcNotices(): void
	{
		if (function_exists('wc_print_notices')) {
			wc_print_notices();
		}
	}

	/**
	 * Return the WooCommerce cart URL.
	 */
	public function wcCartUrl(): string
	{
		return function_exists('wc_get_cart_url') ? wc_get_cart_url() : '/';
	}

	/**
	 * Return the number of items currently in the cart.
	 */
	public function wcCartCount(): int
	{
		if (function_exists('WC') && WC()->cart !== null) {
			return (int) WC()->cart->get_cart_contents_count();
		}

		return 0;
	}

	public function asset(string $path): string
	{
		$path = ltrim($path, '/');

		if ($this->hmr->isHMRAvailable()) {
			return $this->hmr->getViteDevServerAddress() . '/assets/' . $path;
		}

		return get_stylesheet_directory_uri() . '/dist/' . basename($path);
	}
}
