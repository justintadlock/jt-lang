<?php
/**
 * Plugin Name: JT Lang
 * Plugin URI: https://github.com/justintadlock/jt-lang
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

		/* Load meta box on edit post screen. */
		add_action( 'load-post.php',     array( $this, 'load_post' ) );
		add_action( 'load-post-new.php', array( $this, 'load_post' ) );
	}

	/**
	 * Sanitizes locale. WP function available?
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $locale
	 * @return string
	 */
	public function sanitize_locale( $locale ) {
		return preg_replace( '/[^A-Za-z_]/i', '', $locale );
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
		return isset( $_GET['lang'] ) ? $this->sanitize_locale( $_GET['lang'] ) : $locale;
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

		return $lang ? add_query_arg( 'lang', $this->sanitize_locale( $lang ), $permalink ) : $permalink;
	}

	/**
	 * Adds actions for the edit post screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function load_post() {
		add_action( 'save_post',       array( $this, 'save_language' ), 10, 2 );
		add_action( 'add_attachment',  array( $this, 'save_language' )        );
		add_action( 'edit_attachment', array( $this, 'save_language' )        );

		add_action( 'add_meta_boxes', array( $this, 'add_lang_meta_box' ) );
	}

	/**
	 * Adds the meta box.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $post_type
	 * @return void
	 */
	public function add_lang_meta_box( $post_type ) {

		add_meta_box( 'jt-lang', esc_html__( 'Language', 'jt-lang' ), array( $this, 'display_lang_meta_box' ), $post_type, 'side', 'default' );
	}

	/**
	 * Displays the language meta box.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $post
	 * @return void
	 */
	public function display_lang_meta_box( $post ) {

		wp_nonce_field( basename( __FILE__ ), 'jt-lang-nonce' );

		$language = get_post_meta( $post->ID, 'lang', true ); ?>

		<p>
			<?php wp_dropdown_languages(
				array(
					'name'         => 'jt-lang',
					'languages'    => get_available_languages(),
					'selected'     => $language ? $language : get_locale(),
				)
			); ?>
		</p>

		<?php if ( current_user_can( 'manage_options' ) ) : ?>

			<p class="description">
				<?php printf(
					// Translators: The two %s placeholders represents HTML and cannot be moved around.
					esc_html__( 'To install new languages for testing, do so from the %sGeneral Settings%s screen.', 'jt-lang' ),
					'<a href="' . esc_url( admin_url( 'options-general.php#WPLANG' ) ) . '">',
					'</a>'
				); ?>
			</p>

		<?php endif;
	}

	/**
	 * Saves the language.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int     $post_id
	 * @param  object  $post
	 * @return void
	 */
	public function save_language( $post_id, $post = '' ) {

		// Fix for attachment save issue in WordPress 3.5. @link http://core.trac.wordpress.org/ticket/21963
		if ( !is_object( $post ) )
			$post = get_post();

		// Verify the nonce before proceeding.
		if ( !isset( $_POST['jt-lang-nonce'] ) || !wp_verify_nonce( $_POST['jt-lang-nonce'], basename( __FILE__ ) ) )
			return;

		// Return here if the template is not set. There's a chance it won't be if the post type doesn't have any templates.
		if ( !isset( $_POST['jt-lang'] ) || !current_user_can( 'edit_post', $post_id ) )
			return;

		// Get the posted meta value.
		$new_meta_value =  $this->sanitize_locale( $_POST['jt-lang'] );

		// Get the meta value of the meta key.
		$meta_value = get_post_meta( $post_id, 'lang', true );

		// If there is no new meta value but an old value exists, delete it.
		if ( '' == $new_meta_value && $meta_value )
			delete_post_meta( $post_id, 'lang', $meta_value );

		// If the new meta value does not match the old value, update it.
		elseif ( $new_meta_value != $meta_value )
			update_post_meta( $post_id, 'lang', $new_meta_value );
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
