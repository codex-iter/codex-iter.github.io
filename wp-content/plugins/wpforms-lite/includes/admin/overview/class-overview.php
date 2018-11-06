<?php

/**
 * Primary overview page inside the admin which lists all forms.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
 */
class WPForms_Overview {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Maybe load overview page.
		add_action( 'admin_init', array( $this, 'init' ) );

		// Setup screen options.
		add_action( 'load-toplevel_page_wpforms-overview', array( $this, 'screen_options' ) );
		add_filter( 'set-screen-option', array( $this, 'screen_options_set' ), 10, 3 );
	}

	/**
	 * Determine if the user is viewing the overview page, if so, party on.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Check what page we are on.
		$page = isset( $_GET['page'] ) ? $_GET['page'] : '';

		// Only load if we are actually on the overview page.
		if ( 'wpforms-overview' === $page ) {

			// The overview page leverages WP_List_Table so we must load it.
			if ( ! class_exists( 'WP_List_Table' ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
			}

			// Load the class that builds the overview table.
			require_once WPFORMS_PLUGIN_DIR . 'includes/admin/overview/class-overview-table.php';

			// Preview page check.
			wpforms()->preview->form_preview_check();

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueues' ) );
			add_action( 'wpforms_admin_page', array( $this, 'output' ) );

			// Provide hook for addons.
			do_action( 'wpforms_overview_init' );
		}
	}

	/**
	 * Add per-page screen option to the Forms table.
	 *
	 * @since 1.0.0
	 */
	public function screen_options() {

		$screen = get_current_screen();

		if ( 'toplevel_page_wpforms-overview' !== $screen->id ) {
			return;
		}

		add_screen_option(
			'per_page',
			array(
				'label'   => esc_html__( 'Number of forms per page:', 'wpforms' ),
				'option'  => 'wpforms_forms_per_page',
				'default' => apply_filters( 'wpforms_overview_per_page', 20 ),
			)
		);
	}

	/**
	 * Forms table per-page screen option value.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $status
	 * @param string $option
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function screen_options_set( $status, $option, $value ) {

		if ( 'wpforms_forms_per_page' === $option ) {
			return $value;
		}

		return $status;
	}

	/**
	 * Enqueue assets for the overview page.
	 *
	 * @since 1.0.0
	 */
	public function enqueues() {

		// Hook for addons.
		do_action( 'wpforms_overview_enqueue' );
	}

	/**
	 * Build the output for the overview page.
	 *
	 * @since 1.0.0
	 */
	public function output() {

		?>
		<div id="wpforms-overview" class="wrap wpforms-admin-wrap">

			<h1 class="page-title">
				<?php esc_html_e( 'Forms Overview', 'wpforms' ); ?>
				<a href="<?php echo admin_url( 'admin.php?page=wpforms-builder&view=setup' ); ?>" class="add-new-h2 wpforms-btn-orange">
					<?php esc_html_e( 'Add New', 'wpforms' ); ?>
				</a>
			</h1>

			<?php
			$overview_table = new WPForms_Overview_Table;
			$overview_table->prepare_items();
			?>

			<div class="wpforms-admin-content">

				<form id="wpforms-overview-table" method="get" action="<?php echo admin_url( 'admin.php?page=wpforms-overview' ); ?>">

					<input type="hidden" name="post_type" value="wpforms"/>

					<input type="hidden" name="page" value="wpforms-overview"/>

					<?php $overview_table->views(); ?>
					<?php $overview_table->display(); ?>

				</form>

			</div>

		</div>
		<?php
	}
}

new WPForms_Overview;
