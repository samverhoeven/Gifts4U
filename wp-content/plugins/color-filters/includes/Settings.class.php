<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewduwcfSettings' ) ) {
/**
 * Class to handle configurable settings for Ultimate WooCommerce Filters
 * @since 3.0.0
 */
class ewduwcfSettings {

	/**
	 * Default values for settings
	 * @since 3.0.0
	 */
	public $defaults = array();

	/**
	 * Stored values for settings
	 * @since 3.0.0
	 */
	public $settings = array();

	public function __construct() {

		add_action( 'init', array( $this, 'set_defaults' ) );

		add_action( 'init', array( $this, 'load_settings_panel' ) );

		if ( isset( $_POST['ewd-uwcf-settings'] ) ) { add_action( 'init', array( $this, 'check_for_wc_color_taxonomy' ) ); }
		if ( isset( $_POST['ewd-uwcf-settings'] ) ) { add_action( 'init', array( $this, 'check_for_wc_size_taxonomy' ) ); }
	}

	/**
	 * Load the plugin's default settings
	 * @since 3.0.0
	 */
	public function set_defaults() {

		$this->defaults = array(

			'access-role'					=> 'manage_options',

			'color-filtering-display'		=> 'list',
			'size-filtering-display'		=> 'list',
			'category-filtering-display'	=> 'list',
			'tag-filtering-display'			=> 'list',
			'price-filtering-display'		=> 'text',
			'rating-filtering-ratings-type'	=> 'woocommerce',

			'styling-color-filter-shape'	=> 'circle',

			'label-product-page-colors'		=> __( 'Colors', 'color-filters' ),
			'label-product-page-sizes'		=> __( 'Sizes', 'color-filters' ),
		);

		$this->defaults = apply_filters( 'ewd_uwcf_defaults', $this->defaults );
	}

	/**
	 * Get a setting's value or fallback to a default if one exists
	 * @since 3.0.0
	 */
	public function get_setting( $setting ) { 

		if ( empty( $this->settings ) ) {
			$this->settings = get_option( 'ewd-uwcf-settings' );
		}
		
		if ( ! empty( $this->settings[ $setting ] ) ) {
			return apply_filters( 'ewd-uwcf-settings-' . $setting, $this->settings[ $setting ] );
		}

		if ( ! empty( $this->defaults[ $setting ] ) or isset( $this->defaults[ $setting ] ) ) { 
			return apply_filters( 'ewd-uwcf-settings-' . $setting, $this->defaults[ $setting ] );
		}

		return apply_filters( 'ewd-uwcf-settings-' . $setting, null );
	}

	/**
	 * Set a setting to a particular value
	 * @since 3.0.0
	 */
	public function set_setting( $setting, $value ) {

		$this->settings[ $setting ] = $value;
	}

	/**
	 * Save all settings, to be used with set_setting
	 * @since 3.0.0
	 */
	public function save_settings() {
		
		update_option( 'ewd-uwcf-settings', $this->settings );
	}

	/**
	 * Load the admin settings page
	 * @since 3.0.0
	 * @sa https://github.com/NateWr/simple-admin-pages
	 */
	public function load_settings_panel() {
		global $ewd_uwcf_controller;

		require_once( EWD_UWCF_PLUGIN_DIR . '/lib/simple-admin-pages/simple-admin-pages.php' );
		$sap = sap_initialize_library(
			$args = array(
				'version'       => '2.4.2',
				'lib_url'       => EWD_UWCF_PLUGIN_URL . '/lib/simple-admin-pages/',
			)
		);

		$sap->add_page(
			'submenu',
			array(
				'id'            => 'ewd-uwcf-settings',
				'title'         => __( 'Settings', 'color-filters' ),
				'menu_title'    => __( 'Settings', 'color-filters' ),
				'parent_menu'	=> 'ewd-uwcf-dashboard',
				'description'   => '',
				'capability'    => $this->get_setting( 'access-role' ),
				'default_tab'   => 'ewd-uwcf-general-tab',
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-general-tab',
				'title'         => __( 'General', 'color-filters' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-general',
				'title'         => __( 'General Options', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-general-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-general',
			'toggle',
			array(
				'id'			=> 'table-format',
				'title'			=> __( 'Table Format', 'color-filters' ),
				'description'	=> __( 'Table Format lets you display your products in a table rather than a grid format, and adds the requested sorting and filtering options to the table. Once this option is enabled, you\'ll see a new TABLE FORMAT menu item, which is where you can configure the settings for this.', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-general',
			'textarea',
			array(
				'id'			=> 'custom-css',
				'title'			=> __( 'Custom CSS', 'color-filters' ),
				'description'	=> __( 'You can add custom CSS styles in the box above.', 'color-filters' ),			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-general',
			'select',
			array(
				'id'            => 'access-role',
				'title'         => __( 'Access Role', 'color-filters' ),
				'description'   => __( 'Who should have access to the \'WC Filters\' admin menu?', 'color-filters' ),
				'blank_option'	=> false,
				'options'       => array(
					'administrator'				=> __( 'Administrator', 'color-filters' ),
					'delete_others_pages'		=> __( 'Editor', 'color-filters' ),
					'delete_published_posts'	=> __( 'Author', 'color-filters' ),
					'delete_posts'				=> __( 'Contributor', 'color-filters' ),
				)
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-general',
			'toggle',
			array(
				'id'			=> 'reset-all-button',
				'title'			=> __( 'Reset All Button', 'color-filters' ),
				'description'	=> __( 'Should a \'Reset All\' button be added to the filters section?', 'color-filters' )
			)
		);

		if ( ! $ewd_uwcf_controller->permissions->check_permission( 'premium' ) ) {
			$ewd_uwcf_premium_permissions = array(
				'disabled'		=> true,
				'disabled_image'=> '#',
				'purchase_link'	=> 'https://www.etoilewebdesign.com/plugins/woocommerce-filters/',
				'section' 		=> 'uwcf-filtering'
			);
		}
		else { $ewd_uwcf_premium_permissions = array(); }

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-filtering-tab',
				'title'         => __( 'Filtering', 'color-filters' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-color-filtering',
				'title'         => __( 'Color Filtering', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering',
			'warningtip',
			array(
				'id'			=> 'attributes-reminder',
				'title'			=> __( 'WOOCOMMERCE ATTRIBUTES FILTERING ISSUE:', 'color-filters' ),
				'placeholder'	=> __( 'WooCommerce is currently experiencing an issue with widget attribute filtering. More info can be found <a href="https://github.com/woocommerce/woocommerce/issues/27419" target="_blank">here</a>. If you are affected by the above WooCommerce issue, we suggest turning off attribute filtering for the time being.', 'color-filters' ),
				'type'			=> 'warning'
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering',
			'toggle',
			array(
				'id'			=> 'color-filtering',
				'title'			=> __( 'Enable Color Filtering', 'color-filters' ),
				'description'	=> __( 'Should the color filters be displayed when the plugin\'s widget or shortcode is used?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-color-filtering-sub',
				'title'         => __( 'Color Filtering Sub Options', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering-sub',
			'toggle',
			array(
				'id'			=> 'color-filtering-disable-text',
				'title'			=> __( 'Disable Text', 'color-filters' ),
				'description'	=> __( 'Should a color\'s name be hidden in the filtering box?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering-sub',
			'toggle',
			array(
				'id'			=> 'color-filtering-show-color',
				'title'			=> __( 'Disable Color', 'color-filters' ),
				'description'	=> __( 'Should a color\'s color swatch be hidden in the filtering box?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering-sub',
			'toggle',
			array(
				'id'			=> 'color-filtering-hide-empty',
				'title'			=> __( 'Hide Empty', 'color-filters' ),
				'description'	=> __( 'Which colors with no associated products be hidden?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering-sub',
			'toggle',
			array(
				'id'			=> 'color-filtering-show-product-count',
				'title'			=> __( 'Show Product Count', 'color-filters' ),
				'description'	=> __( 'Should the number of products for each color be displayed?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array_merge(
				array(
					'id'            => 'ewd-uwcf-color-filtering-premium',
					'title'         => __( 'Premium Color Filtering', 'color-filters' ),
					'tab'	        => 'ewd-uwcf-filtering-tab',
				),
				$ewd_uwcf_premium_permissions
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering-premium',
			'radio',
			array(
				'id'			=> 'color-filtering-display',
				'title'			=> __( 'Color Filter Layout', 'color-filters' ),
				'description'	=> 'Which type of display should be used for filter colors?',
				'options'		=> array(
					'list'			=> __( 'List', 'color-filters' ),
					'tiles'			=> __( 'Tiles', 'color-filters' ),
					'swatch'		=> __( 'Swatch', 'color-filters' ),
					'checklist'		=> __( 'Checklist', 'color-filters' ),
					'dropdown'		=> __( 'Dropdown', 'color-filters' ),
				),
				'default'		=> $this->defaults['color-filtering-display']
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering-premium',
			'toggle',
			array(
				'id'			=> 'color-filtering-display-thumbnail-colors',
				'title'			=> __( 'Display Thumbnail Colors', 'color-filters' ),
				'description'	=> __( 'Should a list of available colors be shown under each product thumbnail on the shop page?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering-premium',
			'toggle',
			array(
				'id'			=> 'color-filtering-product-page-display',
				'title'			=> __( 'Display on Product Page', 'color-filters' ),
				'description'	=> __( 'Should a product\'s color, if any, be displayed on the product page?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering-premium',
			'toggle',
			array(
				'id'			=> 'color-filtering-colors-for-variations',
				'title'			=> __( 'Use Color for Variations', 'color-filters' ),
				'description'	=> __( 'Should it be possible to use colors for variations? Save the product for new colors to be shown as options for variations.', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-size-filtering',
				'title'         => __( 'Size Filtering', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-size-filtering',
			'toggle',
			array(
				'id'			=> 'size-filtering',
				'title'			=> __( 'Enable Size Filtering', 'color-filters' ),
				'description'	=> __( 'Should the size filters be displayed when the plugin\'s widget or shortcode is used?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-size-filtering-sub',
				'title'         => __( 'Size Filtering Sub Options', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-size-filtering-sub',
			'toggle',
			array(
				'id'			=> 'size-filtering-disable-text',
				'title'			=> __( 'Disable Text', 'color-filters' ),
				'description'	=> __( 'Should a size\'s name be hidden in the filtering box?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-size-filtering-sub',
			'toggle',
			array(
				'id'			=> 'size-filtering-hide-empty',
				'title'			=> __( 'Hide Empty', 'color-filters' ),
				'description'	=> __( 'Which sizes with no associated products be hidden?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-size-filtering-sub',
			'toggle',
			array(
				'id'			=> 'size-filtering-show-product-count',
				'title'			=> __( 'Show Product Count', 'color-filters' ),
				'description'	=> __( 'Should the number of products for each size be displayed?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array_merge(
				array(
					'id'            => 'ewd-uwcf-size-filtering-premium',
					'title'         => __( 'Premium Size Filtering', 'color-filters' ),
					'tab'	        => 'ewd-uwcf-filtering-tab',
				),
				$ewd_uwcf_premium_permissions
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-size-filtering-premium',
			'radio',
			array(
				'id'			=> 'size-filtering-display',
				'title'			=> __( 'Size Filter Layout', 'color-filters' ),
				'description'	=> 'Which type of display should be used for filter sizes?',
				'options'		=> array(
					'list'			=> __( 'List', 'color-filters' ),
					'checklist'		=> __( 'Checklist', 'color-filters' ),
					'dropdown'		=> __( 'Dropdown', 'color-filters' ),
				),
				'default'		=> $this->defaults['size-filtering-display']
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-size-filtering-premium',
			'toggle',
			array(
				'id'			=> 'size-filtering-display-thumbnail-sizes',
				'title'			=> __( 'Display Thumbnail Sizes', 'color-filters' ),
				'description'	=> __( 'Should a list of available sizes be shown under each product thumbnail on the shop page?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-size-filtering-premium',
			'toggle',
			array(
				'id'			=> 'size-filtering-product-page-display',
				'title'			=> __( 'Display on Product Page', 'color-filters' ),
				'description'	=> __( 'Should a product\'s size, if any, be displayed on the product page?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-size-filtering-premium',
			'toggle',
			array(
				'id'			=> 'size-filtering-sizes-for-variations',
				'title'			=> __( 'Use Size for Variations', 'color-filters' ),
				'description'	=> __( 'Should it be possible to use sizes for variations? Save the product for new sizes to be shown as options for variations.', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-category-filtering',
				'title'         => __( 'Category Filtering', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-category-filtering',
			'toggle',
			array(
				'id'			=> 'category-filtering',
				'title'			=> __( 'Enable Category Filtering', 'color-filters' ),
				'description'	=> __( 'Should the category filters be displayed when the plugin\'s widget or shortcode is used?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-category-filtering-sub',
				'title'         => __( 'Category Filtering Sub Options', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-category-filtering-sub',
			'toggle',
			array(
				'id'			=> 'category-filtering-disable-text',
				'title'			=> __( 'Disable Text', 'color-filters' ),
				'description'	=> __( 'Should a category\'s name be hidden in the filtering box?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-category-filtering-sub',
			'toggle',
			array(
				'id'			=> 'category-filtering-hide-empty',
				'title'			=> __( 'Hide Empty', 'color-filters' ),
				'description'	=> __( 'Which categories with no associated products be hidden?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-category-filtering-sub',
			'toggle',
			array(
				'id'			=> 'category-filtering-show-product-count',
				'title'			=> __( 'Show Product Count', 'color-filters' ),
				'description'	=> __( 'Should the number of products for each category be displayed?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array_merge(
				array(
					'id'            => 'ewd-uwcf-category-filtering-premium',
					'title'         => __( 'Premium Category Filtering', 'color-filters' ),
					'tab'	        => 'ewd-uwcf-filtering-tab',
				),
				$ewd_uwcf_premium_permissions
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-category-filtering-premium',
			'radio',
			array(
				'id'			=> 'category-filtering-display',
				'title'			=> __( 'Category Filter Layout', 'color-filters' ),
				'description'	=> 'Which type of display should be used for filter categories?',
				'options'		=> array(
					'list'			=> __( 'List', 'color-filters' ),
					'checklist'		=> __( 'Checklist', 'color-filters' ),
					'dropdown'		=> __( 'Dropdown', 'color-filters' ),
				),
				'default'		=> $this->defaults['category-filtering-display']
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-category-filtering-premium',
			'toggle',
			array(
				'id'			=> 'category-filtering-display-thumbnail-cats',
				'title'			=> __( 'Display Thumbnail Categories', 'color-filters' ),
				'description'	=> __( 'Should a list of available categories be shown under each product thumbnail on the shop page?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-tag-filtering',
				'title'         => __( 'Tag Filtering', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-tag-filtering',
			'toggle',
			array(
				'id'			=> 'tag-filtering',
				'title'			=> __( 'Enable Tag Filtering', 'color-filters' ),
				'description'	=> __( 'Should the tag filters be displayed when the plugin\'s widget or shortcode is used?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-tag-filtering-sub',
				'title'         => __( 'Tag Filtering Sub Options', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-tag-filtering-sub',
			'toggle',
			array(
				'id'			=> 'tag-filtering-disable-text',
				'title'			=> __( 'Disable Text', 'color-filters' ),
				'description'	=> __( 'Should a tag\'s name be hidden in the filtering box?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-tag-filtering-sub',
			'toggle',
			array(
				'id'			=> 'tag-filtering-hide-empty',
				'title'			=> __( 'Hide Empty', 'color-filters' ),
				'description'	=> __( 'Which tags with no associated products be hidden?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-tag-filtering-sub',
			'toggle',
			array(
				'id'			=> 'tag-filtering-show-product-count',
				'title'			=> __( 'Show Product Count', 'color-filters' ),
				'description'	=> __( 'Should the number of products for each tag be displayed?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array_merge(
				array(
					'id'            => 'ewd-uwcf-tag-filtering-premium',
					'title'         => __( 'Premium Tag Filtering', 'color-filters' ),
					'tab'	        => 'ewd-uwcf-filtering-tab',
				),
				$ewd_uwcf_premium_permissions
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-tag-filtering-premium',
			'radio',
			array(
				'id'			=> 'tag-filtering-display',
				'title'			=> __( 'Tag Filter Layout', 'color-filters' ),
				'description'	=> 'Which type of display should be used for filter tags?',
				'options'		=> array(
					'list'			=> __( 'List', 'color-filters' ),
					'checklist'		=> __( 'Checklist', 'color-filters' ),
					'dropdown'		=> __( 'Dropdown', 'color-filters' ),
				),
				'default'		=> $this->defaults['tag-filtering-display']
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-tag-filtering-premium',
			'toggle',
			array(
				'id'			=> 'tag-filtering-display-thumbnail-tags',
				'title'			=> __( 'Display Thumbnail Tags', 'color-filters' ),
				'description'	=> __( 'Should a list of available tags be shown under each product thumbnail on the shop page?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-text-search',
				'title'         => __( 'Text Search', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-text-search',
			'toggle',
			array(
				'id'			=> 'text-search',
				'title'			=> __( 'Enable Text Search', 'color-filters' ),
				'description'	=> __( 'Should a text search box be displayed when the plugin\'s widget or shortcode is used?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-text-search',
			'toggle',
			array(
				'id'			=> 'text-search-autocomplete',
				'title'			=> __( 'Enable Product Title Autocomplete', 'color-filters' ),
				'description'	=> __( 'If text search is enabled, should a list of matching products be displayed when a user starts typing?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-price-filtering',
				'title'         => __( 'Price Filtering', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-price-filtering',
			'toggle',
			array(
				'id'			=> 'price-filtering',
				'title'			=> __( 'Enable Price Filtering', 'color-filters' ),
				'description'	=> __( 'Should visitors be able to filter products based on price?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-price-filtering',
			'radio',
			array(
				'id'			=> 'price-filtering-display',
				'title'			=> __( 'Price Filter Control', 'color-filters' ),
				'description'	=> 'Which type of control should be used for filtering products based on price?',
				'options'		=> array(
					'text'			=> __( 'Text', 'color-filters' ),
					'slider'		=> __( 'Slider', 'color-filters' ),
				),
				'default'		=> $this->defaults['price-filtering-display']
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-rating-filtering',
				'title'         => __( 'Ratings Filtering', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-rating-filtering',
			'toggle',
			array(
				'id'			=> 'rating-filtering',
				'title'			=> __( 'Enable Ratings Filtering', 'color-filters' ),
				'description'	=> __( 'Should a slider be added to filter products by rating?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-rating-filtering',
			'radio',
			array(
				'id'			=> 'rating-filtering-ratings-type',
				'title'			=> __( 'Reviews to Use', 'color-filters' ),
				'description'	=> 'If reviews filtering is enabled, which type of reviews should products be filtered by?',
				'options'		=> array(
					'woocommerce'		=> __( 'WooCommerce', 'color-filters' ),
					'ultimate_reviews'	=> __( 'Ultimate Reviews', 'color-filters' ),
				),
				'default'		=> $this->defaults['rating-filtering-ratings-type']
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-instock-filtering',
				'title'         => __( 'In-Stock Filtering', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-instock-filtering',
			'toggle',
			array(
				'id'			=> 'instock-filtering',
				'title'			=> __( 'Enable In-Stock Filtering', 'color-filters' ),
				'description'	=> __( 'Should an in-stock toggle be added to the filtering widget?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-onsale-filtering',
				'title'         => __( 'On-Sale Filtering', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-onsale-filtering',
			'toggle',
			array(
				'id'			=> 'onsale-filtering',
				'title'			=> __( 'Enable On-Sale Filtering', 'color-filters' ),
				'description'	=> __( 'Should an on-sale toggle be added to the filtering widget?', 'color-filters' )
			)
		);

		foreach ( ewd_uwcf_get_woocommerce_taxonomies() as $attribute_taxonomy ) {

			if ( $attribute_taxonomy->attribute_name == 'ewd_uwcf_colors' or $attribute_taxonomy->attribute_name == 'ewd_uwcf_sizes' ) { continue; }

    		$sap->add_section(
				'ewd-uwcf-settings',
				array(
					'id'            => 'ewd-uwcf-' . $attribute_taxonomy->attribute_name . '-filtering',
					'title'         => sprintf( __( '%s Attribute Filtering', 'color-filters' ), $attribute_taxonomy->attribute_label ),
					'tab'	        => 'ewd-uwcf-filtering-tab',
				)
			);
	
			$sap->add_setting(
				'ewd-uwcf-settings',
				'ewd-uwcf-' . $attribute_taxonomy->attribute_name . '-filtering',
				'toggle',
				array(
					'id'			=> $attribute_taxonomy->attribute_name . '-filtering',
					'title'			=> sprintf( __( 'Enable %s Filtering', 'color-filters' ), $attribute_taxonomy->attribute_label ),
					'description'	=> sprintf( __( 'Should the %s filters be displayed when the plugin\'s widget or shortcode is used?', 'color-filters' ), strtolower( $attribute_taxonomy->attribute_label ) )
				)
			);

			$sap->add_section(
				'ewd-uwcf-settings',
				array(
					'id'            => 'ewd-uwcf-' . $attribute_taxonomy->attribute_name . '-filtering-sub',
					'title'         => sprintf( __( '%s Attribute Filtering Sub Options', 'color-filters' ), $attribute_taxonomy->attribute_label ),
					'tab'	        => 'ewd-uwcf-filtering-tab',
				)
			);
	
	
			$sap->add_setting(
				'ewd-uwcf-settings',
				'ewd-uwcf-' . $attribute_taxonomy->attribute_name . '-filtering-sub',
				'toggle',
				array(
					'id'			=> $attribute_taxonomy->attribute_name . '-disable-text',
					'title'			=> __( 'Disable Text', 'color-filters' ),
					'description'	=> sprintf( __( 'Should a %s\'s name be hidden in the filtering box?', 'color-filters' ), strtolower( $attribute_taxonomy->attribute_label ) )
				)
			);
	
			$sap->add_setting(
				'ewd-uwcf-settings',
				'ewd-uwcf-' . $attribute_taxonomy->attribute_name . '-filtering-sub',
				'toggle',
				array(
					'id'			=> $attribute_taxonomy->attribute_name . '-hide-empty',
					'title'			=> __( 'Hide Empty', 'color-filters' ),
					'description'	=> sprintf(  __( 'Should %ss with no associated products be hidden?', 'color-filters' ), strtolower( $attribute_taxonomy->attribute_label ) )
				)
			);
	
			$sap->add_setting(
				'ewd-uwcf-settings',
				'ewd-uwcf-' . $attribute_taxonomy->attribute_name . '-filtering-sub',
				'toggle',
				array(
					'id'			=> $attribute_taxonomy->attribute_name . '-show-product-count',
					'title'			=> __( 'Show Product Count', 'color-filters' ),
					'description'	=> sprintf( __( 'Should the number of products for each %s be displayed?', 'color-filters' ), strtolower( $attribute_taxonomy->attribute_label ) )
				)
			);

			$sap->add_section(
				'ewd-uwcf-settings',
				array_merge(
					array(
						'id'            => 'ewd-uwcf-' . $attribute_taxonomy->attribute_name . '-filtering-premium',
						'title'         => sprintf( __( '%s Attribute Filtering Premium', 'color-filters' ), $attribute_taxonomy->attribute_label ),
						'tab'	        => 'ewd-uwcf-filtering-tab',
					),
					$ewd_uwcf_premium_permissions
				)
			);
	
			$sap->add_setting(
				'ewd-uwcf-settings',
				'ewd-uwcf-' . $attribute_taxonomy->attribute_name . '-filtering-premium',
				'radio',
				array(
					'id'			=> $attribute_taxonomy->attribute_name . '-display',
					'title'			=> sprintf( __( '%s Filter Layout', 'color-filters' ), $attribute_taxonomy->attribute_label ),
					'description'	=> sprintf( __( 'Which type of display should be used for filter %ss?', 'color-filters' ), strtolower( $attribute_taxonomy->attribute_label ) ),
					'options'		=> array(
						'list'			=> __( 'List', 'color-filters' ),
						'checklist'		=> __( 'Checklist', 'color-filters' ),
						'dropdown'		=> __( 'Dropdown', 'color-filters' ),
					)
				)
			);
	
			$sap->add_setting(
				'ewd-uwcf-settings',
				'ewd-uwcf-' . $attribute_taxonomy->attribute_name . '-filtering-premium',
				'toggle',
				array(
					'id'			=> $attribute_taxonomy->attribute_name . '-display-thumbnail-terms',
					'title'			=> __( 'Display Thumbnail Terms', 'color-filters' ),
					'description'	=> __( 'Should a list of available terms be shown under each product thumbnail on the shop page?', 'color-filters' )
				)
			);
    	}

		if ( ! $ewd_uwcf_controller->permissions->check_permission( 'labelling' ) ) {
			$ewd_uwcf_labelling_permissions = array(
				'disabled'		=> true,
				'disabled_image'=> '#',
				'purchase_link'	=> 'https://www.etoilewebdesign.com/plugins/woocommerce-filters/'
			);
		}
		else { $ewd_uwcf_labelling_permissions = array(); }

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-labelling-tab',
				'title'         => __( 'Labelling', 'color-filters' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array_merge(
				array(
					'id'            => 'ewd-uwcf-labelling-widget-and-shortcode',
					'title'         => __( 'Widget & Shortcode', 'color-filters' ),
					'tab'	        => 'ewd-uwcf-labelling-tab',
				),
				$ewd_uwcf_labelling_permissions
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-labelling-widget-and-shortcode',
			'text',
			array(
				'id'            => 'label-show-all-color',
				'title'         => __( 'Show All Colors', 'color-filters' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-labelling-widget-and-shortcode',
			'text',
			array(
				'id'            => 'label-show-all-size',
				'title'         => __( 'Show All Sizes', 'color-filters' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-labelling-widget-and-shortcode',
			'text',
			array(
				'id'            => 'label-show-all-category',
				'title'         => __( 'Show All Categories', 'color-filters' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-labelling-widget-and-shortcode',
			'text',
			array(
				'id'            => 'label-show-all-tag',
				'title'         => __( 'Show All Tags', 'color-filters' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-labelling-widget-and-shortcode',
			'text',
			array(
				'id'            => 'label-show-all-attribute',
				'title'         => __( 'Show All Attributes', 'color-filters' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-labelling-widget-and-shortcode',
			'text',
			array(
				'id'            => 'label-ratings',
				'title'         => __( 'Rating', 'color-filters' ),
				'description'	=> ''
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array_merge(
				array(
					'id'            => 'ewd-uwcf-labelling-product-thumbnail',
					'title'         => __( 'Product Thumbnail', 'color-filters' ),
					'tab'	        => 'ewd-uwcf-labelling-tab',
				),
				$ewd_uwcf_labelling_permissions
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-labelling-product-thumbnail',
			'text',
			array(
				'id'            => 'label-thumbnail-colors',
				'title'         => __( 'Colors', 'color-filters' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-labelling-product-thumbnail',
			'text',
			array(
				'id'            => 'label-thumbnail-sizes',
				'title'         => __( 'Sizes', 'color-filters' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-labelling-product-thumbnail',
			'text',
			array(
				'id'            => 'label-thumbnail-categories',
				'title'         => __( 'Categories', 'color-filters' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-labelling-product-thumbnail',
			'text',
			array(
				'id'            => 'label-thumbnail-tags',
				'title'         => __( 'Tags', 'color-filters' ),
				'description'	=> ''
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array_merge(
				array(
					'id'            => 'ewd-uwcf-labelling-product-page',
					'title'         => __( 'Product Page', 'color-filters' ),
					'tab'	        => 'ewd-uwcf-labelling-tab',
				),
				$ewd_uwcf_labelling_permissions
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-labelling-product-page',
			'text',
			array(
				'id'            => 'label-product-page-colors',
				'title'         => __( 'Colors', 'color-filters' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-labelling-product-page',
			'text',
			array(
				'id'            => 'label-product-page-sizes',
				'title'         => __( 'Sizes', 'color-filters' ),
				'description'	=> ''
			)
		);

		if ( ! $ewd_uwcf_controller->permissions->check_permission( 'styling' ) ) {
			$ewd_uwcf_styling_permissions = array(
				'disabled'		=> true,
				'disabled_image'=> '#',
				'purchase_link'	=> 'https://www.etoilewebdesign.com/plugins/woocommerce-filters/'
			);
		}
		else { $ewd_uwcf_styling_permissions = array(); }

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-styling-tab',
				'title'         => __( 'Styling', 'color-filters' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array_merge(
				array(
					'id'            => 'ewd-uwcf-styling-widget-and-shortcode',
					'title'         => __( 'Widget and Shortcode', 'color-filters' ),
					'tab'	        => 'ewd-uwcf-styling-tab',
				),
				$ewd_uwcf_styling_permissions
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-styling-widget-and-shortcode',
			'radio',
			array(
				'id'			=> 'styling-color-filter-shape',
				'title'			=> __( 'Color Shape', 'color-filters' ),
				'description'	=> '',
				'options'		=> array(
					'circle'		=> __( 'Circle', 'color-filters' ),
					'square'		=> __( 'Sqaure', 'color-filters' ),
				),
				'default'		=> $this->defaults['styling-color-filter-shape']
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-styling-widget-and-shortcode',
			'text',
			array(
				'id'            => 'styling-color-icon-size',
				'title'         => __( 'Color Icons Width', 'color-filters' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-styling-widget-and-shortcode',
			'colorpicker',
			array(
				'id'			=> 'styling-widget-font-color',
				'title'			=> __( 'Font Options Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-styling-widget-and-shortcode',
			'text',
			array(
				'id'            => 'styling-widget-font-size',
				'title'         => __( 'Font Options Font Size', 'color-filters' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-styling-widget-and-shortcode',
			'colorpicker',
			array(
				'id'			=> 'styling-ratings-bar-fill-color',
				'title'			=> __( 'Ratings Bar/Price Slider Fill Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-styling-widget-and-shortcode',
			'colorpicker',
			array(
				'id'			=> 'styling-ratings-bar-empty-color',
				'title'			=> __( 'Ratings Bar/Price Slider Empty Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-styling-widget-and-shortcode',
			'colorpicker',
			array(
				'id'			=> 'styling-ratings-bar-handle-color',
				'title'			=> __( 'Ratings Bar/Price Slider Handles Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-styling-widget-and-shortcode',
			'colorpicker',
			array(
				'id'			=> 'styling-ratings-bar-text-color',
				'title'			=> __( 'Ratings Bar/Price Slider Text Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-styling-widget-and-shortcode',
			'text',
			array(
				'id'            => 'styling-ratings-bar-font-size',
				'title'         => __( 'Ratings Bar/Price Slider Font Size', 'color-filters' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-styling-widget-and-shortcode',
			'colorpicker',
			array(
				'id'			=> 'styling-reset-all-button-background-color',
				'title'			=> __( 'Reset All Button Background Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-styling-widget-and-shortcode',
			'colorpicker',
			array(
				'id'			=> 'styling-reset-all-button-text-color',
				'title'			=> __( 'Reset All Button Text Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-styling-widget-and-shortcode',
			'colorpicker',
			array(
				'id'			=> 'styling-reset-all-button-hover-bg-color',
				'title'			=> __( 'Reset All Button Hover Background Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-styling-widget-and-shortcode',
			'colorpicker',
			array(
				'id'			=> 'styling-reset-all-button-hover-text-color',
				'title'			=> __( 'Reset All Button Hover Text Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-styling-widget-and-shortcode',
			'text',
			array(
				'id'            => 'styling-reset-all-button-font-size',
				'title'         => __( 'Reset All Button Font Size', 'color-filters' ),
				'description'	=> ''
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array_merge(
				array(
					'id'            => 'ewd-uwcf-styling-shop-thumbnails',
					'title'         => __( 'Shop Thumbnails', 'color-filters' ),
					'tab'	        => 'ewd-uwcf-styling-tab',
				),
				$ewd_uwcf_styling_permissions
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-styling-shop-thumbnails',
			'colorpicker',
			array(
				'id'			=> 'styling-shop-thumbnails-font-color',
				'title'			=> __( 'Font Options Color', 'ultimate-slider' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-styling-shop-thumbnails',
			'text',
			array(
				'id'            => 'styling-shop-thumbnails-font-size',
				'title'         => __( 'Font Options Font Size', 'color-filters' ),
				'description'	=> ''
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-styling-shop-thumbnails',
			'text',
			array(
				'id'            => 'styling-shop-thumbnails-color-icon-size',
				'title'         => __( 'Color Icons Width', 'color-filters' ),
				'description'	=> ''
			)
		);

		$sap = apply_filters( 'ewd_uwcf_settings_page', $sap );

		$sap->add_admin_menus();

	}

	public function get_fields() {

		$fields = array(
			'name', 
			'image', 
			'price', 
			'rating', 
			'add_to_cart', 
			'colors', 
			'sizes'
		);

		foreach ( ewd_uwcf_get_woocommerce_taxonomies() as $attribute_taxonomy ) {

			$fields[] = $attribute_taxonomy->attribute_name;
		} 

		return $fields;
	}

	/**
	 * Check if the color attribute for WC has been created, and create it if not
	 * @since 3.0.0
	 */
	public function check_for_wc_color_taxonomy() {
		global $wpdb;

		$wc_attribute_table_name = $wpdb->prefix . 'woocommerce_attribute_taxonomies';

		if ( $wpdb->get_var( $wpdb->prepare( "SELECT attribute_name FROM $wc_attribute_table_name WHERE attribute_name=%s", 'ewd_uwcf_colors' ) ) ) { return; }

		if ( ! $this->get_setting( 'color-filtering' ) ) { return; }

    	$wpdb->insert(
    		$wc_attribute_table_name,
    		array(
    			'attribute_name' => 'ewd_uwcf_colors',
    			'attribute_label' => 'UWCF Colors',
    			'attribute_type' => 'select',
    			'attribute_orderby' => 'menu_order',
    			'attribute_public' => 0
    		)
    	);

    	$attribute_taxonomies = $wpdb->get_results( "SELECT * FROM $wc_attribute_table_name order by attribute_name ASC;" );
		set_transient( 'wc_attribute_taxonomies', $attribute_taxonomies );
	}

	/**
	 * Check if the size attribute for WC has been created, and create it if not
	 * @since 3.0.0
	 */
	public function check_for_wc_size_taxonomy() {
		global $wpdb;

		$wc_attribute_table_name = $wpdb->prefix . 'woocommerce_attribute_taxonomies';

		if ( $wpdb->get_var( $wpdb->prepare( "SELECT attribute_name FROM $wc_attribute_table_name WHERE attribute_name=%s", 'ewd_uwcf_sizes' ) ) ) { return; }

		if ( ! $this->get_setting( 'size-filtering' ) ) { return; }

    	$wpdb->insert(
    		$wc_attribute_table_name,
    		array(
    			'attribute_name' => 'ewd_uwcf_sizes',
    			'attribute_label' => 'UWCF Sizes',
    			'attribute_type' => 'select',
    			'attribute_orderby' => 'menu_order',
    			'attribute_public' => 0
    		)
    	);

    	$attribute_taxonomies = $wpdb->get_results( "SELECT * FROM $wc_attribute_table_name order by attribute_name ASC;" );
		set_transient( 'wc_attribute_taxonomies', $attribute_taxonomies );
	}
}
} // endif;
