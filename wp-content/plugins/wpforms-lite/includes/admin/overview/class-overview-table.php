<?php

use WPForms\Forms\Locator;

/**
 * Generate the table on the plugin overview page.
 *
 * @since 1.0.0
 */
class WPForms_Overview_Table extends WP_List_Table {

	/**
	 * Number of forms to show per page.
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	public $per_page;

	/**
	 * Number of forms in different views.
	 *
	 * @since 1.7.2
	 *
	 * @var array
	 */
	private $count;

	/**
	 * Current view.
	 *
	 * @since 1.7.3
	 *
	 * @var string
	 */
	private $view;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Utilize the parent constructor to build the main class properties.
		parent::__construct(
			[
				'singular' => 'form',
				'plural'   => 'forms',
				'ajax'     => false,
			]
		);

		add_filter( 'default_hidden_columns', [ $this, 'default_hidden_columns' ], 10, 2 );

		// Determine the current view.
		$this->view = wpforms()->get( 'forms_views' )->get_current_view();

		// Default number of forms to show per page.
		$this->per_page = (int) apply_filters( 'wpforms_overview_per_page', 20 );
	}

	/**
	 * Retrieve the table columns.
	 *
	 * @since 1.0.0
	 *
	 * @return array $columns Array of all the list table columns.
	 */
	public function get_columns() {

		$columns = [
			'cb'        => '<input type="checkbox" />',
			'name'      => esc_html__( 'Name', 'wpforms-lite' ),
			'author'    => esc_html__( 'Author', 'wpforms-lite' ),
			'shortcode' => esc_html__( 'Shortcode', 'wpforms-lite' ),
			'created'   => esc_html__( 'Created', 'wpforms-lite' ),
		];

		return apply_filters( 'wpforms_overview_table_columns', $columns );
	}

	/**
	 * Render the checkbox column.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $form Form.
	 *
	 * @return string
	 */
	public function column_cb( $form ) {

		return '<input type="checkbox" name="form_id[]" value="' . absint( $form->ID ) . '" />';
	}

	/**
	 * Render the columns.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $form        CPT object as a form representation.
	 * @param string  $column_name Column Name.
	 *
	 * @return string
	 */
	public function column_default( $form, $column_name ) {

		switch ( $column_name ) {
			case 'id':
				$value = $form->ID;
				break;

			case 'shortcode':
				$value = '[wpforms id="' . $form->ID . '"]';
				break;

			case 'created':
				$value = get_the_date( get_option( 'date_format' ), $form );
				break;

			case 'modified':
				$value = get_post_modified_time( get_option( 'date_format' ), false, $form );
				break;

			case 'author':
				$value  = '';
				$author = get_userdata( $form->post_author );

				if ( ! $author ) {
					break;
				}

				$value         = $author->display_name;
				$user_edit_url = get_edit_user_link( $author->ID );

				if ( ! empty( $user_edit_url ) ) {
					$value = '<a href="' . esc_url( $user_edit_url ) . '">' . esc_html( $value ) . '</a>';
				}
				break;

			case 'php':
				$value = '<code style="display:block;font-size:11px;">if( function_exists( \'wpforms_get\' ) ){ wpforms_get( ' . $form->ID . ' ); }</code>';
				break;

			default:
				$value = '';
		}

		return apply_filters( 'wpforms_overview_table_column_value', $value, $form, $column_name );
	}

	/**
	 * Filter the default list of hidden columns.
	 *
	 * @since 1.7.2
	 *
	 * @param string[]  $hidden Array of IDs of columns hidden by default.
	 * @param WP_Screen $screen WP_Screen object of the current screen.
	 *
	 * @return string[]
	 */
	public function default_hidden_columns( $hidden, $screen ) {

		if ( $screen->id !== 'toplevel_page_wpforms-overview' ) {
			return $hidden;
		}

		$hidden[] = 'author';
		$hidden[] = Locator::COLUMN_NAME;

		return $hidden;
	}

	/**
	 * Render the form name column with action links.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $form Form.
	 *
	 * @return string
	 */
	public function column_name( $form ) {

		// Build the row action links and return the value.
		return $this->get_column_name_title( $form ) . $this->get_column_name_row_actions( $form );
	}

	/**
	 * Get the form name HTML for the form name column.
	 *
	 * @since 1.5.8
	 *
	 * @param WP_Post $form Form object.
	 *
	 * @return string
	 */
	protected function get_column_name_title( $form ) {

		$title = ! empty( $form->post_title ) ? $form->post_title : $form->post_name;
		$name  = sprintf(
			'<span><strong>%s</strong></span>',
			esc_html( $title )
		);

		if ( $this->view === 'trash' ) {
			return $name;
		}

		if ( wpforms_current_user_can( 'view_form_single', $form->ID ) ) {
			$name = sprintf(
				'<a href="%s" title="%s" class="row-title" target="_blank" rel="noopener noreferrer"><strong>%s</strong></a>',
				esc_url( wpforms_get_form_preview_url( $form->ID ) ),
				esc_attr__( 'View preview', 'wpforms-lite' ),
				esc_html( $title )
			);
		}

		if ( wpforms_current_user_can( 'view_entries_form_single', $form->ID ) ) {
			$name = sprintf(
				'<a href="%s" title="%s"><strong>%s</strong></a>',
				esc_url(
					add_query_arg(
						[
							'view'    => 'list',
							'form_id' => $form->ID,
						],
						admin_url( 'admin.php?page=wpforms-entries' )
					)
				),
				esc_attr__( 'View entries', 'wpforms-lite' ),
				esc_html( $title )
			);
		}

		if ( wpforms_current_user_can( 'edit_form_single', $form->ID ) ) {
			$name = sprintf(
				'<a href="%s" title="%s"><strong>%s</strong></a>',
				esc_url(
					add_query_arg(
						[
							'view'    => 'fields',
							'form_id' => $form->ID,
						],
						admin_url( 'admin.php?page=wpforms-builder' )
					)
				),
				esc_attr__( 'Edit This Form', 'wpforms-lite' ),
				esc_html( $title )
			);
		}

		return $name;
	}

	/**
	 * Get the row actions HTML for the form name column.
	 *
	 * @since 1.5.8
	 *
	 * @param WP_Post $form Form object.
	 *
	 * @return string
	 */
	protected function get_column_name_row_actions( $form ) {

		// phpcs:disable WPForms.Comments.PHPDocHooks.RequiredHookDocumentation, WPForms.PHP.ValidateHooks.InvalidHookName

		/**
		 * Filters row action links on the 'All Forms' admin page.
		 *
		 * @since 1.0.0
		 *
		 * @param array   $row_actions An array of action links for a given form.
		 * @param WP_Post $form        Form object.
		 */
		return $this->row_actions( apply_filters( 'wpforms_overview_row_actions', [], $form ) );

		// phpcs:enable
	}

	/**
	 * Define bulk actions available for our table listing.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_bulk_actions() {

		return wpforms()->get( 'forms_bulk_actions' )->get_dropdown_items();
	}

	/**
	 * Generate the table navigation above or below the table.
	 *
	 * @since 1.7.2
	 *
	 * @param string $which The location of the table navigation: 'top' or 'bottom'.
	 */
	protected function display_tablenav( $which ) {

		// If there are some forms just call the parent method.
		if ( $this->has_items() ) {
			parent::display_tablenav( $which );

			return;
		}

		// Otherwise, display bulk actions menu and "0 items" on the right (pagination).
		?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">
			<div class="alignleft actions bulkactions">
				<?php $this->bulk_actions( $which ); ?>
			</div>
			<?php
			if ( $which === 'top' ) {
				$this->pagination( $which );
			}
			?>
			<br class="clear" />
		</div>
		<?php
	}

	/**
	 * Extra controls to be displayed between bulk actions and pagination.
	 *
	 * @since 1.7.3
	 *
	 * @param string $which The location of the table navigation: 'top' or 'bottom'.
	 */
	protected function extra_tablenav( $which ) {

		wpforms()->get( 'forms_views' )->extra_tablenav( $which );
	}

	/**
	 * Message to be displayed when there are no forms.
	 *
	 * @since 1.0.0
	 */
	public function no_items() {

		esc_html_e( 'No forms found.', 'wpforms-lite' );
	}

	/**
	 * Fetch and set up the final data for the table.
	 *
	 * @since 1.0.0
	 */
	public function prepare_items() {

		// Set up the columns.
		$columns = $this->get_columns();

		// Hidden columns (none).
		$hidden = get_hidden_columns( $this->screen );

		// Define which columns can be sorted - form name, author, date.
		$sortable = [
			'name'    => [ 'title', false ],
			'author'  => [ 'author', false ],
			'created' => [ 'date', false ],
		];

		// Set column headers.
		$this->_column_headers = [ $columns, $hidden, $sortable ];

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$page     = $this->get_pagenum();
		$order    = isset( $_GET['order'] ) && $_GET['order'] === 'asc' ? 'ASC' : 'DESC';
		$orderby  = isset( $_GET['orderby'] ) ? sanitize_key( $_GET['orderby'] ) : 'ID';
		$per_page = $this->get_items_per_page( 'wpforms_forms_per_page', $this->per_page );
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		$args = [
			'orderby'        => $orderby,
			'order'          => $order,
			'nopaging'       => false,
			'posts_per_page' => $per_page,
			'paged'          => $page,
			'no_found_rows'  => false,
			'post_status'    => 'publish',
		];

		/**
		 * Filters the `get_posts()` arguments while preparing items for the forms overview table.
		 *
		 * @since 1.7.3
		 *
		 * @param array $args Arguments array.
		 */
		$args = (array) apply_filters( 'wpforms_overview_table_prepare_items_args', $args );

		// Giddy up.
		$this->items = wpforms()->get( 'form' )->get( '', $args );
		$per_page    = isset( $args['posts_per_page'] ) ? $args['posts_per_page'] : $this->get_items_per_page( 'wpforms_forms_per_page', $this->per_page );

		$this->update_count( $args );

		$count_current_view = empty( $this->count[ $this->view ] ) ? 0 : $this->count[ $this->view ];

		// Finalize pagination.
		$this->set_pagination_args(
			[
				'total_items' => $count_current_view,
				'per_page'    => $per_page,
				'total_pages' => ceil( $count_current_view / $per_page ),
			]
		);
	}

	/**
	 * Calculate and update form counts.
	 *
	 * @since 1.7.2
	 *
	 * @param array $args Get forms arguments.
	 */
	private function update_count( $args ) {

		/**
		 * Allow counting forms filtered by a given search criteria.
		 *
		 * If result will not contain `all` key, count All Forms without filtering will be performed.
		 *
		 * @since 1.7.2
		 *
		 * @param array $count Contains counts of forms in different views.
		 * @param array $args  Arguments of the `get_posts`.
		 */
		$this->count = (array) apply_filters( 'wpforms_overview_table_update_count', [], $args );

		// We do not need to perform all forms count if we have the result already.
		if ( isset( $this->count['all'] ) ) {
			return;
		}

		// Count all forms.
		$this->count['all'] = wpforms_current_user_can( 'wpforms_view_others_forms' )
			 ? (int) wp_count_posts( 'wpforms' )->publish
			 : (int) count_user_posts( get_current_user_id(), 'wpforms', true );

		/**
		 * Filters forms count data after counting all forms.
		 *
		 * This filter executes only if the result of `wpforms_overview_table_update_count` filter
		 * doesn't contain `all` key.
		 *
		 * @since 1.7.3
		 *
		 * @param array $count Contains counts of forms in different views.
		 * @param array $args  Arguments of the `get_posts`.
		 */
		$this->count = (array) apply_filters( 'wpforms_overview_table_update_count_all', $this->count, $args );
	}

	/**
	 * Display the pagination.
	 *
	 * @since 1.7.2
	 *
	 * @param string $which The location of the table pagination: 'top' or 'bottom'.
	 */
	protected function pagination( $which ) {

		if ( $this->has_items() ) {
			parent::pagination( $which );

			return;
		}

		printf(
			'<div class="tablenav-pages one-page">
				<span class="displaying-num">%s</span>
			</div>',
			esc_html__( '0 items', 'wpforms-lite' )
		);
	}

	/**
	 * Extending the `display_rows()` method in order to add hooks.
	 *
	 * @since 1.5.6
	 */
	public function display_rows() {

		do_action( 'wpforms_admin_overview_before_rows', $this );

		parent::display_rows();

		do_action( 'wpforms_admin_overview_after_rows', $this );
	}

	/**
	 * Forms search markup.
	 *
	 * @since 1.7.2
	 *
	 * @param string $text     The 'submit' button label.
	 * @param string $input_id ID attribute value for the search input field.
	 */
	public function search_box( $text, $input_id ) {

		wpforms()->get( 'forms_search' )->search_box( $text, $input_id );
	}

	/**
	 * Get the list of views available on forms overview table.
	 *
	 * @since 1.7.3
	 */
	protected function get_views() {

		return wpforms()->get( 'forms_views' )->get_views();
	}
}
