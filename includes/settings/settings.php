<?php

/**
 * Class WPSF_Core
 *
 * Configure the plugin settings page.
 */
class WPSF_Core {

	/**
	 * Capability required by the user to access the My Plugin menu entry.
	 *
	 * @var string $capability
	 */
	private $capability = 'manage_options';

	/**
	 * Array of fields that should be displayed in the settings page.
	 *
	 * @var array $settings, $options, $page_id
	 */
	private $settings;
	private $options;
	private $page_id;

	/**
	 * The Plugin Settings constructor.
	 */
	function __construct() {

        $this->settings = apply_filters( 'wpsf_register_settings', [] );
        $this->options = apply_filters( 'wpsf_register_options', [] );
		update_option( 'wpsf_option_name', $this->options['option_name'] );
        $this->page_id = isset( $_GET['page'] ) ? $_GET['page'] : null;

		add_action( 'admin_init', [$this, 'settings_init'] );
		add_action( 'admin_menu', [$this, 'options_page'] );

	}

	/**
	 * Register the settings and all fields.
	 */
	function settings_init() : void {

		// Register a new setting this page.

        foreach ( $this->settings as $settings ) { 

			$page_id = 'wpsf_' . $settings['page']['menu_slug'];

			register_setting( $page_id, $this->options['option_name'] );

            foreach( $settings['tabs'] as $tab_id => $tab ) {

				$option_group = $page_id .'_'. $tab_id;

                /* Register All The Fields. */
                foreach( $tab['sections'] as $section_id => $section ) {

                    $full_section_id = 'wpsf_' . $section_id . '_section';
    
                    // Register a new section.
                    add_settings_section(
                        $full_section_id,
                        $section['label'],
                        [$this, 'render_section'],
                        $option_group
                    );
    
                    foreach ( $section['fields'] as $field ) {
                        // Register a new field in the main section.
                        add_settings_field(
                            $field['id'], /* ID for the field. Only used internally. To set the HTML ID attribute, use $args['label_for']. */
                            $field['label'], /* Label for the field. */
                            [$this, 'render_field'], /* The name of the callback function. */
                            $option_group, /* The menu page on which to display this field. */
                            $full_section_id, /* The section of the settings page in which to show the box. */
                            [
                                'label_for' => $field['id'], /* The ID of the field. */
                                'class' => 'custom_class', /* The class of the field. */
                                'field' => $field, /* Custom data for the field. */
                            ]
                        );
                    }
    
                }
    
            }
        }
	}

	/**
	 * Add a subpage to the WordPress Settings menu.
	 */
	function options_page() : void {

        $submenu = false;
        $parent = '';
        foreach ( $this->settings as $settings ) {
            if ( ! $submenu ) {
                add_menu_page(
                    $settings['page']['page_title'],
                    $settings['page']['menu_title'],
                    $this->capability,
                    $settings['page']['menu_slug'],
                    [$this, 'render_options_page'],
                    $settings['page']['icon'],
                    $settings['page']['position'],
                );
                $submenu = true;
                $parent = $settings['page']['menu_slug'];
            } else {
                add_submenu_page(
                    $parent,
                    $settings['page']['page_title'],
                    $settings['page']['menu_title'],
                    $this->capability,
                    $settings['page']['menu_slug'],
                    [$this, 'render_options_page'],
                );
            }
        }
	}

	/**
	 * Render the settings page.
	 */
	function render_options_page() : void {

		// check user capabilities
		if ( ! current_user_can( $this->capability ) ) {
			return;
		}

		/**
		 * add error/update messages
		 * check if the user have submitted the settings
		 * WordPress will add the "settings-updated" $_GET parameter to the url
		 */
		if ( isset( $_GET['settings-updated'] ) ) {
			// add settings saved message with the class of "updated"
			add_settings_error( 'wpsf_messages', 'wpsf_message', __( 'Settings Saved', 'wpsf' ), 'updated' );
		}

		// show error/update messages
		settings_errors( 'wpsf_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

            <div id="wpsf-settings-tabs-wrapper" class="nav-tab-wrapper">
				<?php
                foreach ( $this->settings as $settings ) {
                    if ( $settings['page']['menu_slug'] !== $this->page_id ) {
                        continue;
                    }
					$first = true;
                    foreach ( $settings['tabs'] as $tab_id => $tab ) {
    
                        $active_class = '';
  
                        if ( $first ) {
                            $active_class = ' nav-tab-active';
							$first = false;
                        }
    
                        $sanitized_tab_id = esc_attr( $tab_id );
                        $sanitized_tab_label = esc_html( $tab['label'] );
    
                        // PHPCS - Escaped the relevant strings above.
                        echo "<a id='wpsf-settings-tab-{$sanitized_tab_id}' class='nav-tab{$active_class}' href='#tab-{$sanitized_tab_id}'>{$sanitized_tab_label}</a>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    }
                }
				?>
			</div>

			<form action="options.php" method="post">
				<?php
				
                foreach ( $this->settings as $settings ) {
					
					$hidden = false;
					if ( $settings['page']['menu_slug'] !== $this->page_id ) {
						$hidden = true;
                    }

					$page_id = 'wpsf_' . $settings['page']['menu_slug'];

					settings_fields( $page_id );
					$first = true;
                    foreach ( $settings['tabs'] as $tab_id => $tab ) {

						$option_group = $page_id .'_'. $tab_id;
                        $active_class = '';
    
                        if ( $first ) {
                            $active_class = ' active';
							$first = false;
                        }
    
                        $sanitized_tab_id = esc_attr( $tab_id );
    
						if ( $hidden ) {
							echo "<div style='display:none'>";
						} else {
							// PHPCS - $active_class is a non-dynamic string and $sanitized_tab_id is escaped above.
							echo "<div id='tab-{$sanitized_tab_id}' class='wpsf-settings-form-page{$active_class}'>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
                        foreach ( $tab['sections'] as $section_id => $section ) {
                            $full_section_id = 'wpsf_' . $section_id . '_section';
    
                            if ( ! empty( $section['label'] ) ) {
                                echo '<h2>' . esc_html( $section['label'] ) . '</h2>';
                            }
    
                            if ( ! empty( $section['callback'] ) ) {
                                $section['callback']();
                            }
    
                            echo '<table class="form-table">';
                            do_settings_fields( $option_group, $full_section_id );
    
                            echo '</table>';
                        }
    
                        echo '</div>';
                    }
                }

                submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render a settings field.
	 *
	 * @param array $args Args to configure the field.
	 */
	function render_field( array $args ) : void {

		$field = $args['field'];

		// Get the value of the setting we've registered with register_setting()
		$options = get_option( $this->options['option_name'] );

		switch ( $field['type'] ) {

			case "text": {
				?>
				<input
					type="text"
					id="<?php echo esc_attr( $field['id'] ); ?>"
					name="<?php echo esc_attr( sprintf( '%s[%s]', $this->options['option_name'], $field['id'] ) );?>"
					value="<?php echo isset( $options[ $field['id'] ] ) ? esc_attr( $options[ $field['id'] ] ) : ''; ?>"
				>
				<p class="description">
					<?php echo $field['description']; ?> 
				</p>
				<?php
				break;
			}

			case "checkbox": {
				?>
				<input
					type="checkbox"
					id="<?php echo esc_attr( $field['id'] ); ?>"
					name="<?php echo esc_attr( sprintf( '%s[%s]', $this->options['option_name'], $field['id'] ) );?>"
					value="1"
					<?php echo isset( $options[ $field['id'] ] ) ? ( checked( $options[ $field['id'] ], 1, false ) ) : ( '' ); ?>
				>
				<p class="description">
					<?php echo $field['description']; ?>
				</p>
				<?php
				break;
			}

			case "textarea": {
				?>
				<textarea
					id="<?php echo esc_attr( $field['id'] ); ?>"
					name="<?php echo esc_attr( sprintf( '%s[%s]', $this->options['option_name'], $field['id'] ) );?>"
				><?php echo isset( $options[ $field['id'] ] ) ? esc_attr( $options[ $field['id'] ] ) : ''; ?></textarea>
				<p class="description">
					<?php echo $field['description']; ?>
				</p>
				<?php
				break;
			}

			case "select": {
				?>
				<select
					id="<?php echo esc_attr( $field['id'] ); ?>"
					name="<?php echo esc_attr( sprintf( '%s[%s]', $this->options['option_name'], $field['id'] ) );?>"
				>
					<?php foreach( $field['options'] as $key => $option ) { ?>
						<option value="<?php echo $key; ?>" 
							<?php echo isset( $options[ $field['id'] ] ) ? ( selected( $options[ $field['id'] ], $key, false ) ) : ( '' ); ?>
						>
							<?php echo $option; ?>
						</option>
					<?php } ?>
				</select>
				<p class="description">
					<?php echo $field['description']; ?>
				</p>
				<?php
				break;
			}

			case "password": {
				?>
				<input
					type="password"
					id="<?php echo esc_attr( $field['id'] ); ?>"
					name="<?php echo esc_attr( sprintf( '%s[%s]', $this->options['option_name'], $field['id'] ) );?>"
					value="<?php echo isset( $options[ $field['id'] ] ) ? esc_attr( $options[ $field['id'] ] ) : ''; ?>"
				>
				<p class="description">
					<?php echo $field['description']; ?>
				</p>
				<?php
				break;
			}

			case "wysiwyg": {
				wp_editor(
					isset( $options[ $field['id'] ] ) ? $options[ $field['id'] ] : '',
					$field['id'],
					array(
						'textarea_name' => sprintf( '%s[%s]', $this->options['option_name'], $field['id'] ),
						'textarea_rows' => 5,
					)
				);
				break;
			}

			case "email": {
				?>
				<input
					type="email"
					id="<?php echo esc_attr( $field['id'] ); ?>"
					name="<?php echo esc_attr( sprintf( '%s[%s]', $this->options['option_name'], $field['id'] ) );?>"
					value="<?php echo isset( $options[ $field['id'] ] ) ? esc_attr( $options[ $field['id'] ] ) : ''; ?>"
				>
				<p class="description">
					<?php echo $field['description']; ?>
				</p>
				<?php
				break;
			}

			case "url": {
				?>
				<input
					type="url"
					id="<?php echo esc_attr( $field['id'] ); ?>"
					name="<?php echo esc_attr( sprintf( '%s[%s]', $this->options['option_name'], $field['id'] ) );?>"
					value="<?php echo isset( $options[ $field['id'] ] ) ? esc_attr( $options[ $field['id'] ] ) : ''; ?>"
				>
				<p class="description">
					<?php echo $field['description']; ?>
				</p>
				<?php
				break;
			}

			case "color": {
				?>
				<input
					type="color"
					id="<?php echo esc_attr( $field['id'] ); ?>"
					name="<?php echo esc_attr( sprintf( '%s[%s]', $this->options['option_name'], $field['id'] ) );?>"
					value="<?php echo isset( $options[ $field['id'] ] ) ? esc_attr( $options[ $field['id'] ] ) : ''; ?>"
				>
				<p class="description">
					<?php echo $field['description']; ?>
				</p>
				<?php
				break;
			}

			case "date": {
				?>
				<input
					type="date"
					id="<?php echo esc_attr( $field['id'] ); ?>"
					name="<?php echo esc_attr( sprintf( '%s[%s]', $this->options['option_name'], $field['id'] ) );?>"
					value="<?php echo isset( $options[ $field['id'] ] ) ? esc_attr( $options[ $field['id'] ] ) : ''; ?>"
				>
				<p class="description">
					<?php echo $field['description']; ?>
				</p>
				<?php
				break;
			}

		}
	}

	/**
	 * Render a section on a page, with an ID and a text label.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args {
	 *     An array of parameters for the section.
	 *
	 *     @type string $id The ID of the section.
	 * }
	 */
	function render_section( array $args ) : void {
		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Section', 'wpsf' ); ?></p>
		<?php
	}

}

new WPSF_Core();
