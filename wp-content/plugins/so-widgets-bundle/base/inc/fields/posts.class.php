<?php

/**
 * Class SiteOrigin_Widget_Field_Posts
 */
class SiteOrigin_Widget_Field_Posts extends SiteOrigin_Widget_Field_Container_Base {
	/**
	 * An array of post types to use in the query for posts when the 'Select Content' button is clicked.
	 *
	 * @var array
	 */
	protected $post_types;

	/**
	 * Whether to show the total number of posts returned by the query or not.
	 * Enabled by default.
	 *
	 * @var bool
	 */
	protected $show_count = true;

	/**
	 * Whether to add the Maximum Posts to Output field.
	 *
	 * @var bool
	 */
	protected $posts_limit = false;

	public function __construct( $base_name, $element_id, $element_name, $field_options, SiteOrigin_Widget $for_widget, $parent_container = array() ) {
		parent::__construct( $base_name, $element_id, $element_name, $field_options, $for_widget, $parent_container );

		$types = get_post_types( array( 'public' => true ), 'objects' );
		$type_options = array();

		if ( empty( $this->post_types ) || in_array( '_all', $this->post_types ) ) {
			$type_options['_all'] = __( 'All', 'so-widgets-bundle' );
		}

		$do_post_type_permission_check = apply_filters( 'siteorigin_widgets_post_selector_post_type_permission_check', true );

		foreach ( $types as $id => $type ) {
			if ( $do_post_type_permission_check && ! siteorigin_widget_user_can_edit_post_type( $id ) ) {
				continue;
			}

			if (
				empty( $this->post_types ) ||
				in_array( $id, $this->post_types )
			) {
				$type_options[ $id ] = $type->labels->name;
			}
		}

		$this->fields = array(
			'post_type' => array(
				'type' => 'select',
				'label' => __( 'Post Type', 'so-widgets-bundle' ),
				'multiple' => true,
				'options' => $type_options,
				'default' => 'post',
			),

			'post__in' => array(
				'type' => 'autocomplete',
				'label' => __( 'Post In', 'so-widgets-bundle' ),
				'source' => 'posts',
			),

			'tax_query' => array(
				'type' => 'autocomplete',
				'label' => __( 'Taxonomies', 'so-widgets-bundle' ),
				'source' => 'terms',
				'description' => __( 'Taxonomies are groups such as categories, tags, posts and products.', 'so-widgets-bundle' ),
			),

			'tax_query_relation' => array(
				'type' => 'radio',
				'label' => __( 'Taxonomies Relationship', 'so-widgets-bundle' ),
				'options' => array(
					'OR' => __( 'OR', 'so-widgets-bundle' ),
					'AND' => __( 'AND', 'so-widgets-bundle' ),
				),
				'description' => __( 'The relationship between taxonomies. OR requires posts to have at least one of the specified taxonomies. AND requires posts to have all of the specified taxonomies.', 'so-widgets-bundle' ),
				'default' => 'OR',
			),

			'date_type' => array(
				'type' => 'radio',
				'label' => __( 'Date Selection Type', 'so-widgets-bundle' ),
				'options' => array(
					'specific' => __( 'Specific', 'so-widgets-bundle' ),
					'relative' => __( 'Relative', 'so-widgets-bundle' ),
				),
				'description' => __( 'Select a range between specific dates or relative to the current date.', 'so-widgets-bundle' ),
				'default' => 'specific',
				'state_emitter' => array(
					'callback' => 'select',
					'args' => array( 'date_type' ),
				),
			),

			'date_query' => array(
				'type' => 'date-range',
				'label' => __( 'Dates', 'so-widgets-bundle' ),
				'date_type' => 'specific',
				'state_handler' => array(
					'date_type[specific]' => array( 'show' ),
					'_else[date_type]' => array( 'hide' ),
				),
			),

			'date_query_relative' => array(
				'type' => 'date-range',
				'label' => __( 'Dates', 'so-widgets-bundle' ),
				'date_type' => 'relative',
				'state_handler' => array(
					'date_type[relative]' => array( 'show' ),
					'_else[date_type]' => array( 'hide' ),
				),
			),

			'orderby' => array(
				'type' => 'select',
				'label' => __( 'Order By', 'so-widgets-bundle' ),
				'options' => array(
					'none' => __( 'No order', 'so-widgets-bundle' ),
					'ID' => __( 'Post ID', 'so-widgets-bundle' ),
					'author' => __( 'Author', 'so-widgets-bundle' ),
					'title' => __( 'Title', 'so-widgets-bundle' ),
					'date' => __( 'Published date', 'so-widgets-bundle' ),
					'modified' => __( 'Modified date', 'so-widgets-bundle' ),
					'parent' => __( 'By parent', 'so-widgets-bundle' ),
					'rand' => __( 'Random order', 'so-widgets-bundle' ),
					'comment_count' => __( 'Comment count', 'so-widgets-bundle' ),
					'menu_order' => __( 'Menu order', 'so-widgets-bundle' ),
					'meta_value' => __( 'By meta value', 'so-widgets-bundle' ),
					'meta_value_num' => __( 'By numeric meta value', 'so-widgets-bundle' ),
					'post__in' => __( 'By include order', 'so-widgets-bundle' ),
				),
				'default' => 'date',
			),

			'order' => array(
				'type' => 'radio',
				'label' => __( 'Order Direction', 'so-widgets-bundle' ),
				'options' => array(
					'ASC' => __( 'Ascending', 'so-widgets-bundle' ),
					'DESC' => __( 'Descending', 'so-widgets-bundle' ),
				),
				'default' => 'DESC',
			),

			'posts_per_page' => array(
				'type' => 'number',
				'label' => __( 'Posts Per Page', 'so-widgets-bundle' ),
			),

			'posts_limit' => array(
				'type' => 'number',
				'label' => __( 'Maximum Posts to Output', 'so-widgets-bundle' ),
			),

			'sticky' => array(
				'type' => 'select',
				'label' => __( 'Sticky Posts', 'so-widgets-bundle' ),
				'options' => array(
					'' => __( 'Default', 'so-widgets-bundle' ),
					'ignore' => __( 'Ignore sticky', 'so-widgets-bundle' ),
					'exclude' => __( 'Exclude sticky', 'so-widgets-bundle' ),
					'only' => __( 'Only sticky', 'so-widgets-bundle' ),
				),
			),

			'additional' => array(
				'type' => 'text',
				'label' => __( 'Additional', 'so-widgets-bundle' ),
				'description' => __( 'Additional query arguments. See <a href="https://developer.wordpress.org/reference/functions/query_posts/" target="_blank" rel="noopener noreferrer">query_posts</a>.', 'so-widgets-bundle' ),
			),
		);

		if ( empty( $this->posts_limit ) ) {
			unset( $this->fields['posts_limit'] );
		}
	}

	protected function render_field_label( $value, $instance ) {
		?>
		<div class="posts-container-label-wrapper<?php if ( $this->state == 'open' ) {
			echo ' siteorigin-widget-section-visible';
		} ?>">
			<?php parent::render_field_label( $value, $instance ); ?>

			<?php if ( ! empty( $this->show_count ) ) { ?>
				<span class="sow-current-count">
					<?php echo esc_html( siteorigin_widget_post_selector_count_posts( $value ) ); ?>
				</span>
			<?php } ?>
		</div>
		<?php
	}

	protected function render_field( $value, $instance ) {
		$value = wp_parse_args( $value );

		if ( ! empty( $value['post_type'] ) ) {
			$value['post_type'] = strpos( $value['post_type'], ',' ) !== false ? explode( ',', $value['post_type'] ) : $value['post_type'];
		}

		if ( $this->collapsible ) {
			?><div class="siteorigin-widget-section <?php if ( $this->state == 'closed' ) {
				echo 'siteorigin-widget-section-hide';
			} ?>"><?php
		}

		if ( isset( $this->field_options['fields'] ) ) {
			$this->override_fields();
		}

		$this->create_and_render_sub_fields(
			$value,
			array(
				'name' => $this->base_name,
				'type' => 'composite'
			)
		);

		if ( $this->collapsible ) {
			?></div><?php
		}
	}

	private function override_fields() {
		foreach ( $this->field_options['fields'] as $field => $options ) {
			// Are we removing, or updating this field?
			if ( ! empty( $options['remove'] ) ) {
				unset( $this->fields[ $field ] );
			} else {
				$this->fields[ $field ] = wp_parse_args( $this->field_options['fields'][ $field ], $this->fields[ $field ] );
			}
		}
	}

	public function enqueue_scripts() {
		wp_enqueue_script(
			'so-posts-selector-field',
			plugin_dir_url( __FILE__ ) . 'js/posts-field' . SOW_BUNDLE_JS_SUFFIX . '.js',
			array( 'jquery' ),
			SOW_BUNDLE_VERSION,
			true
		);
	}

	protected function sanitize_field_input( $value, $instance ) {
		// Special handling for the 'additional' args field.
		if ( ! empty( $value['additional'] ) ) {
			$value['additional'] = urlencode( $value['additional'] );
		}
		$value = parent::sanitize_field_input( $value, $instance );
		$result = '';

		foreach ( $value as $key => $item ) {
			if ( ! empty( $item ) ) {
				if ( is_array( $item ) ) {
					$item = implode( ',', $item );
				}
				$result .= ( empty( $result ) ? '' : '&' ) . $key . '=' . $item;
			}
		}

		return $result;
	}
}
