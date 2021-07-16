<?php
/*
Plugin Name: Ultimate WooCommerce Filters
Plugin URI: https://www.etoilewebdesign.com/plugins/woocommerce-filters/
Description: Filter WooCommerce products by color, size, attribute, categories and tags. Easy to implement and use WooCommerce filters.
Author: Etoile Web Design
Author URI: https://www.etoilewebdesign.com
Terms and Conditions: http://www.etoilewebdesign.com/plugin-terms-and-conditions/
Text Domain: color-filters
Version: 3.0.5
WC requires at least: 3.0
WC tested up to: 5.2
*/

if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'ewduwcfInit' ) ) {
class ewduwcfInit {

	/**
	 * Initialize the plugin and register hooks
	 */
	public function __construct() {

		self::constants();
		self::includes();
		self::instantiate();
		self::wp_hooks();
	}

	/**
	 * Define plugin constants.
	 *
	 * @since  3.0.0
	 * @access protected
	 * @return void
	 */
	protected function constants() {

		define( 'EWD_UWCF_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'EWD_UWCF_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
		define( 'EWD_UWCF_PLUGIN_FNAME', plugin_basename( __FILE__ ) );
		define( 'EWD_UWCF_TEMPLATE_DIR', 'ewd-uwcf-templates' );
		define( 'EWD_UWCF_VERSION', '3.0.0' );

		define( 'EWD_UWCF_WOOCOMMERCE_POST_TYPE', 'product' );
		define( 'EWD_UWCF_PRODUCT_COLOR_TAXONOMY', 'product_color' );
		define( 'EWD_UWCF_PRODUCT_SIZE_TAXONOMY', 'product_size' );
	}

	/**
	 * Include necessary classes.
	 *
	 * @since  3.0.0
	 * @access protected
	 * @return void
	 */
	protected function includes() {

		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/Blocks.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/CustomPostTypes.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/Dashboard.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/DeactivationSurvey.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/InstallationWalkthrough.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/Permissions.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/ReviewAsk.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/Settings.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/template-functions.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/Widgets.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/WooCommerceFiltering.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/WooCommerceSync.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/WooCommerceTable.class.php' );
	}

	/**
	 * Spin up instances of our plugin classes.
	 *
	 * @since  3.0.0
	 * @access protected
	 * @return void
	 */
	protected function instantiate() {

		new ewduwcfDashboard();
		new ewduwcfDeactivationSurvey();
		new ewduwcfInstallationWalkthrough();
		new ewduwcfReviewAsk();
		new ewduwcfWidgetManager();

		$this->cpts 		= new ewduwcfCustomPostTypes();
		$this->permissions 	= new ewduwcfPermissions();
		$this->settings 	= new ewduwcfSettings();
		$this->wc_filtering = new ewduwcfWooCommerceFiltering();
		$this->wc_sync 		= new ewduwcfWooCommerceSync();
		$this->wc_table 	= new ewduwcfWooCommerceTable();

		$this->cpts->colors_enabled = $this->settings->get_setting( 'color-filtering' );
		$this->cpts->sizes_enabled = $this->settings->get_setting( 'size-filtering' );
	}

	/**
	 * Run walk-through, load assets, add links to plugin listing, etc.
	 *
	 * @since  3.0.0
	 * @access protected
	 * @return void
	 */
	protected function wp_hooks() {

		register_activation_hook( __FILE__, 	array( $this, 'run_walkthrough' ) );
		register_activation_hook( __FILE__, 	array( $this, 'convert_options' ) );

		add_action( 'init',			        	array( $this, 'load_view_files' ) );

		add_action( 'plugins_loaded',        	array( $this, 'load_textdomain' ) );

		add_action( 'admin_notices', 			array( $this, 'display_header_area' ) );

		add_action( 'admin_enqueue_scripts', 	array( $this, 'enqueue_admin_assets' ), 10, 1 );
		add_action( 'wp_enqueue_scripts', 		array( $this, 'register_assets' ) );
		add_action( 'wp_head',					'ewd_add_frontend_ajax_url' );

		add_filter( 'plugin_action_links',		array( $this, 'plugin_action_links' ), 10, 2);
	}

	/**
	 * Run the options conversion function on update if necessary
	 *
	 * @since  3.0.0
	 * @access protected
	 * @return void
	 */
	public function convert_options() {
		
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/BackwardsCompatibility.class.php' );
		new ewduwcfBackwardsCompatibility();
	}

	/**
	 * Load files needed for views
	 * @since 3.0.0
	 * @note Can be filtered to add new classes as needed
	 */
	public function load_view_files() {
	
		$files = array(
			EWD_UWCF_PLUGIN_DIR . '/views/Base.class.php' // This will load all default classes
		);
	
		$files = apply_filters( 'ewd_uwcf_load_view_files', $files );
	
		foreach( $files as $file ) {
			require_once( $file );
		}
	
	}

	/**
	 * Load the plugin textdomain for localisation
	 * @since 3.0.0
	 */
	public function load_textdomain() {
		
		load_plugin_textdomain( 'color-filters', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Set a transient so that the walk-through gets run
	 * @since 2.0.0
	 */
	public function run_walkthrough() {
		
		set_transient( 'ewd-uwcf-getting-started', true, 30 );
	} 

	/**
	 * Enqueue the admin-only CSS and Javascript
	 * @since 3.0.0
	 */
	public function enqueue_admin_assets( $hook ) {
		global $post;
		global $ewd_uwcf_controller;

		$post_type = is_object( $post ) ?  $post->post_type : '';
		$screen = get_current_screen();
		
   		// Return if not product post_type, we're not on a post-type page, or we're not on the settings or widget pages
   		if ( ( $post_type != EWD_UWCF_WOOCOMMERCE_POST_TYPE or ( $hook != 'edit.php' and $hook != 'post-new.php' and $hook != 'post.php' ) ) and $hook != 'widgets.php' and $screen->id != 'product_page_product_attributes' and $screen->id != 'toplevel_page_ewd-uwcf-dashboard' and $screen->id != 'wc-filters_page_ewd-uwcf-settings' and $screen->id != 'wc-filters_page_ewd-uwcf-table-mode' and $screen->taxonomy != 'product_color' ) { return; }

		if ( $screen->taxonomy == 'product_color' ) {
			wp_enqueue_style( 'ewd-uwcf-admin-spectrum-css', EWD_UWCF_PLUGIN_URL . '/lib/simple-admin-pages/css/spectrum.css', array(), EWD_UWCF_VERSION );
			wp_enqueue_script( 'ewd-uwcf-admin-spectrum-js', EWD_UWCF_PLUGIN_URL . '/lib/simple-admin-pages/js/spectrum.js', array( 'jquery' ), EWD_UWCF_VERSION );
		}

		if ( $screen->id == 'wc-filters_page_ewd-uwcf-table-mode' || $screen->taxonomy == 'product_color' ) {
			wp_enqueue_style( 'sap-admin-style', EWD_UWCF_PLUGIN_URL . '/lib/simple-admin-pages/css/admin.css', array(), EWD_UWCF_VERSION );
			wp_enqueue_style( 'sap-admin-settings-css', EWD_UWCF_PLUGIN_URL . '/lib/simple-admin-pages/css/admin-settings.css', array(), EWD_UWCF_VERSION );
			wp_enqueue_script( 'sap-admin-settings-js', EWD_UWCF_PLUGIN_URL . '/lib/simple-admin-pages/js/admin-settings.js', array( 'jquery' ), EWD_UWCF_VERSION );
		}

		wp_enqueue_style( 'ewd-uwcf-admin-css', EWD_UWCF_PLUGIN_URL . '/assets/css/ewd-uwcf-admin.css', array(), EWD_UWCF_VERSION );
		wp_enqueue_script( 'ewd-uwcf-admin-js', EWD_UWCF_PLUGIN_URL . '/assets/js/ewd-uwcf-admin.js', array( 'jquery' ), EWD_UWCF_VERSION, true );

		if ( $ewd_uwcf_controller->settings->get_setting( 'color-filtering' ) ) {
			
			$args = array( 
				'hide_empty' => false,
				'taxonomy' => EWD_UWCF_PRODUCT_COLOR_TAXONOMY
			);

			$colors = get_terms( $args );
		
			if ( ! is_wp_error( $colors ) ) {

				foreach ( $colors as $index => $color ) {

					$color_value = get_term_meta( $color->term_id, 'EWD_UWCF_Color', true );
					$colors[ $index ]->color = $color_value;
				}
			}
		}
		else { $colors = array(); }

		wp_localize_script( 'ewd-uwcf-admin-js', 'ewd_uwcf_color_data', $colors );
	}

	/**
	 * Register the front-end CSS and Javascript for the slider
	 * @since 3.0.0
	 */
	function register_assets() {
		global $ewd_uwcf_controller;

		wp_register_style( 'ewd-uwcf-css', EWD_UWCF_PLUGIN_URL . '/assets/css/ewd-uwcf.css', EWD_UWCF_VERSION );
		
		wp_register_script( 'ewd-uwcf-js', EWD_UWCF_PLUGIN_URL . '/assets/js/ewd-uwcf.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-autocomplete', 'jquery-ui-slider' ), EWD_UWCF_VERSION, true );
	}

	/**
	 * Add links to the plugin listing on the installed plugins page
	 * @since 3.0.0
	 */
	public function plugin_action_links( $links, $plugin ) {

		if ( $plugin == EWD_UWCF_PLUGIN_FNAME ) {

			$links['settings'] = '<a href="admin.php?page=ewd-uwcf-settings" title="' . __( 'Head to the settings page for Ultimate WooCommerce Filters', 'color-filters' ) . '">' . __( 'Settings', 'color-filters' ) . '</a>';
		}

		return $links;

	}

	/**
	 * Adds in a menu bar for the plugin
	 * @since 3.0.0
	 */
	public function display_header_area() {
		global $ewd_uwcf_controller;

		$screen = get_current_screen();
		
		if ( $screen->id != 'toplevel_page_ewd-uwcf-dashboard' && $screen->id != 'wc-filters_page_ewd-uwcf-settings' && $screen->id != 'wc-filters_page_ewd-uwcf-table-mode' ) { return; }
		
		if ( ! $ewd_uwcf_controller->permissions->check_permission( 'styling' ) or get_option( 'EWD_UWCF_Trial_Happening' ) == 'Yes' ) {
			?>
			<div class="ewd-uwcf-dashboard-new-upgrade-banner">
				<div class="ewd-uwcf-dashboard-banner-icon"></div>
				<div class="ewd-uwcf-dashboard-banner-buttons">
					<a class="ewd-uwcf-dashboard-new-upgrade-button" href="https://www.etoilewebdesign.com/license-payment/?Selected=UWCF&Quantity=1" target="_blank">UPGRADE NOW</a>
				</div>
				<div class="ewd-uwcf-dashboard-banner-text">
					<div class="ewd-uwcf-dashboard-banner-title">
						GET FULL ACCESS WITH OUR PREMIUM VERSION
					</div>
					<div class="ewd-uwcf-dashboard-banner-brief">
						Attribute filtering, advanced styling options and more!
					</div>
				</div>
			</div>
			<?php
		}
		
		?>
		<div class="ewd-uwcf-admin-header-menu">
			<h2 class="nav-tab-wrapper">
				<a id="ewd-uwcf-dash-mobile-menu-open" href="#" class="menu-tab nav-tab"><?php _e("MENU", 'color-filters'); ?><span id="ewd-uwcf-dash-mobile-menu-down-caret">&nbsp;&nbsp;&#9660;</span><span id="ewd-uwcf-dash-mobile-menu-up-caret">&nbsp;&nbsp;&#9650;</span></a>
				<a id="dashboard-menu" href="admin.php?page=ewd-uwcf-dashboard" class="menu-tab nav-tab <?php if ( $screen->id == 'toplevel_page_ewd-uwcf-dashboard' ) {echo 'nav-tab-active';}?>"><?php _e("Dashboard", 'color-filters'); ?></a>
				<?php if ( $ewd_uwcf_controller->settings->get_setting( 'color-filtering' ) ) { ?>
					<a id="colors-menu" href="edit-tags.php?taxonomy=product_color" class="menu-tab nav-tab <?php if ( $screen->id == 'wc-filters_page_' ) {echo 'nav-tab-active';}?>"><?php _e("Colors", 'color-filters'); ?></a>
				<?php } ?>
				<?php if ( $ewd_uwcf_controller->settings->get_setting( 'size-filtering' ) ) { ?>
					<a id="sizes-menu" href="edit-tags.php?taxonomy=product_size" class="menu-tab nav-tab <?php if ( $screen->id == 'wc-filters_page_' ) {echo 'nav-tab-active';}?>"><?php _e("Sizes", 'color-filters'); ?></a>
				<?php } ?>
				<a id="options-menu" href="admin.php?page=ewd-uwcf-settings" class="menu-tab nav-tab <?php if ( $screen->id == 'wc-filters_page_ewd-uwcf-settings' ) {echo 'nav-tab-active';}?>"><?php _e("Settings", 'color-filters'); ?></a>
				<?php if ( $ewd_uwcf_controller->settings->get_setting( 'table-format' ) ) { ?>
					<a id="table-mode-menu" href="admin.php?page=ewd-uwcf-table-mode" class="menu-tab nav-tab <?php if ( $screen->id == 'wc-filters_page_ewd-uwcf-table-mode' ) {echo 'nav-tab-active';}?>"><?php _e("Table Format", 'color-filters'); ?></a>
				<?php } ?>
			</h2>
		</div>
		<?php
	}

}
} // endif;

global $ewd_uwcf_controller;
$ewd_uwcf_controller = new ewduwcfInit();
