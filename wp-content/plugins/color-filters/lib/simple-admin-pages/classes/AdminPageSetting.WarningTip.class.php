<?php

/**
 * Register, display and save a warning/tip in the admin menu
 *
 * @since 2.3.0
 * @package Simple Admin Pages
 */

class sapAdminPageSettingWarningTip_2_4_2 extends sapAdminPageSetting_2_4_2 {

	public $sanitize_callback = 'sanitize_text_field';

	/**
	 * Placeholder string for the input field
	 * @since 2.0
	 */
	public $placeholder = '';

	/**
	 * Display this setting
	 * @since 1.0
	 */
	public function display_setting() {

		isset( $this->type ) && $this->type == "warning" ? $warning_tip_class = ' exclamation' : $warning_tip_class = '';
		?>

		<fieldset class="sap-warning-tip">
			<div class="sap-shortcode-reminder<?php echo $warning_tip_class; ?>">
				<?php echo '<strong>' . $this->title . '</strong> ' . $this->placeholder; ?>
			</div>

			<?php $this->display_disabled(); ?>		
		</fieldset>
		
		<?php
		
		$this->display_description();
		
	}

}
