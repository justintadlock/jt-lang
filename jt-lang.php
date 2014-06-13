<?php
/*
Plugin Name: JT Lang
*/


/**
 * Sets up and initializes the plugin.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
final class JT_Lang_Plugin {

	/**
	 * Holds the instances of this class.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    object
	 */
	private static $instance;

	/**
	 * Sets up needed actions/filters for the plugin to initialize.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Change the locale. */
		add_filter( 'locale', array( $this, 'locale' ), 99 );

		/* Filter the permalink. */
		add_filter( 'post_link', array( $this, 'post_link' ), 10, 2 );
	}

	/**
	 * Overwrites the locale.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $locale
	 * @return string
	 */
	public function locale( $locale ) {

		/* Whitelist of languges. */
		$languages = array(
			'en_US', // U.S. English
			'ko_KR', // Korean
			'ja',    // Japanese
			'fi',    // Finnish
			'fr_FR', // French
			'zh_CN', // Chinese
			'ro_RO', // Romanian
			'th',    // Thai
			'ar',    // Arabic (RTL)
			'sv_SE'  // Swedish
		);

		if ( isset( $_GET['lang'] ) && in_array( $_GET['lang'], $languages ) )
			$locale = $_GET['lang'];

		return $locale;
	}

	/**
	 * Filters the post permalink to add a custom query arg with our language.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $permalink
	 * @param  object  $post
	 * @return string
	 */
	public function post_link( $permalink, $post ) {

		$lang = get_post_meta( $post->ID, 'lang', true );

		if ( !empty( $lang ) )
			$permalink = add_query_arg( 'lang', $lang, $permalink );

		return $permalink;
	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		if ( !self::$instance )
			self::$instance = new self;

		return self::$instance;
	}
}

JT_Lang_Plugin::get_instance();
