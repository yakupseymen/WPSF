<?php 

/**
 * Set main options
 * option_name is the your main settings name. You'll get values with this name.
 */
add_filter( 'wpsf_register_options', function(){
    $options = [
        'option_name' => 'my_options',
    ];

    return $options;
} );

/**
 * 
 * Register admin menu page & settings
 * 
 */
add_filter( 'wpsf_register_settings', function( $settings ){

    /**
     * Register an Admin Page with the following configuration:
     *
     * - Page Title: Settings
     * - Menu Title: Menu Title
     * - Menu Slug (URL): my-settings
     * - Menu Icon: dashicons-admin-settings (Menu Icon (icon will be invalid if it was not called first))
     * - Menu Position: 2 (Dashboard)
     *   Available Positions:
     *     - 2: Dashboard
     *     - 4: Separator
     *     - 5: Posts
     *     - 10: Media
     *     - 15: Links
     *     - 20: Pages
     *     - 25: Comments
     *     - 59: Separator
     *     - 60: Appearance
     *     - 65: Plugins
     *     - 70: Users
     *     - 75: Tools
     *     - 80: Settings
     *     - 99: Separator
     */
    $page = [
        'page_title' => __('My Settings', 'wpsf'),
        'menu_title' => __('My Settings', 'wpsf'),
        'menu_slug' => 'my-settings',
        'icon' => 'dashicons-admin-settings',
        'position' => '2',
    ];

    /**
     * This PHP array represents a structured configuration for organizing settings and fields.
     *
     * $tabs is an associative array with tabs as keys, each containing:
     *   - 'label': The display label for the tab.
     *   - 'sections': An array of sections within the tab, each section having:
     *     - 'label': The display label for the section.
     *     - 'fields': An array of fields within the section, where each field includes:
     *       - 'id': Unique identifier for the field.
     *       - 'label': The display label for the field.
     *       - 'description': A description or additional information for the field.
     *       - 'type': The type of the field (e.g., 'text', 'url').
     *       - 'options': An array of options for select input 
     *
     * This structure is designed to organize and manage settings and fields for a developer-oriented application.
     */
    $tabs = [
		'general' => [
            'label' => __( 'General', 'wpsf' ),
            'sections' => [
                'general' => [
                   'label' => __( 'General Settings', 'wpsf' ),
                   'fields' => [
                        [
                            'id' => 'field_one',
                            'label' => __( 'Field one', 'wpsf' ),
                            'description' => __( 'This is a demo option', 'wpsf' ),
                            'type' => 'text',
                        ],
                   ]
                ],
                'api' => [
                   'label' => __( 'API Keys', 'wpsf' ),
                   'fields' => [
                        [
                            'id' => 'my_api_key',
                            'label' => __( 'Framework API Key', 'wpsf' ),
                            'description' => __( 'This is a demo option', 'wpsf' ),
                            'type' => 'text',
                        ],
                   ]
                ]
            ]
        ],
        'page_options' => [
            'label' => __( 'Page Options', 'wpsf' ),
            'sections' =>[
                'discussion' => [
                   'label' => __( 'Discussion', 'wpsf' ),
                   'fields' => [
                        [
                            'id' => 'enable_comments',
                            'label' => __( 'Enable comments?', 'wpsf' ),
                            'description' => __( 'Enable comments on the single pages?', 'wpsf' ),
                            'type' => 'checkbox',
                        ],
                        [
                            'id' => 'page_layout',
                            'label' => __( 'Page Layout', 'wpsf' ),
                            'description' => __( 'Select page layout.', 'wpsf' ),
                            'type' => 'select',
                            'options' => [
                                'boxed' => __( 'Boxed', 'wpsf' ),
                                'full-width' => __( 'Full Width', 'wpsf' ),
                            ]
                        ],
                   ]
                ]
            ]
        ]
	];

    /**
     * Combine the previously defined $page and $tabs arrays into a $settings array
     * and return it for further usage.
     */
    $settings[] = [
        'page' => $page,
        'tabs' => $tabs
    ];

    return $settings;
}, 20 );

/**
 * 
 * Register admin submenu page & settings
 * Note: If you want to convert to main page, change the filter order (example 30 to 10)
 */
add_filter( 'wpsf_register_settings', function( $settings ){

    /**
     * Register an Admin Page with the following configuration:
     *
     * - Page Title: Settings
     * - Menu Title: Menu Title
     * - Menu Slug (URL): my-settings
     * - Menu Icon: dashicons-admin-settings (Menu Icon (icon will be invalid if it was not called first))
     * - Menu Position: 2 (Dashboard)
     *   Available Positions:
     *     - 2: Dashboard
     *     - 4: Separator
     *     - 5: Posts
     *     - 10: Media
     *     - 15: Links
     *     - 20: Pages
     *     - 25: Comments
     *     - 59: Separator
     *     - 60: Appearance
     *     - 65: Plugins
     *     - 70: Users
     *     - 75: Tools
     *     - 80: Settings
     *     - 99: Separator
     */
    $page = [
        'page_title' => __( 'Advanced', 'wpsf' ), 
        'menu_title' => __( 'Advanced', 'wpsf' ),
        'menu_slug' => 'my-advanced-settings',
        'icon' => 'dashicons-admin-settings',
        'position' => '2',
    ];

    /**
     * This PHP array represents a structured configuration for organizing settings and fields.
     *
     * $tabs is an associative array with tabs as keys, each containing:
     *   - 'label': The display label for the tab.
     *   - 'sections': An array of sections within the tab, each section having:
     *     - 'label': The display label for the section.
     *     - 'fields': An array of fields within the section, where each field includes:
     *       - 'id': Unique identifier for the field.
     *       - 'label': The display label for the field.
     *       - 'description': A description or additional information for the field.
     *       - 'type': The type of the field (e.g., 'text', 'url').
     *       - 'options': An array of options for select input 
     *
     * This structure is designed to organize and manage settings and fields for a developer-oriented application.
     */
    $tabs = [
		'general' => [
            'label' => __( 'Post', 'wpsf' ),
            'sections' => [
                'general' => [
                   'label' => __( 'General Settings', 'wpsf' ),
                   'fields' => [
                        [
                            'id' => 'before_content',
                            'label' => __( 'Before Content', 'wpsf' ),
                            'description' => __( 'Add custom content before the post', 'wpsf' ),
                            'type' => 'wysiwyg',
                        ],
                        [
                            'id' => 'after_content',
                            'label' => __( 'After Content', 'wpsf' ),
                            'description' => __( 'Add custom content after the post', 'wpsf' ),
                            'type' => 'wysiwyg',
                        ],
                   ]
                ],
                'style' => [
                   'label' => __( 'Post Title Color' ),
                   'fields' => [
                        [
                            'id' => 'post_title_color',
                            'label' => __( 'Post Title Color', 'wpsf' ),
                            'description' => __( 'Select a color', 'wpsf' ),
                            'type' => 'color',
                        ],
                   ]
                ]
            ]
        ],
        'seo' => [
            'label' => 'SEO',
            'sections' =>[
                'general' => [
                   'label' => __('General'),
                   'fields' => [
                        [
                            'id' => 'site_title',
                            'label' => __( 'Site Title', 'wpsf' ),
                            'description' => __( 'Set site title', 'wpsf' ),
                            'type' => 'text',
                        ],
                        [
                            'id' => 'site_description',
                            'label' => __( 'Site Description', 'wpsf' ),
                            'description' => __( 'In a few words, explain what this site is about. Example: “Just another WordPress site.”', 'wpsf' ),
                            'type' => 'textarea',
                        ],
                   ]
                ]
            ]
        ]
	];

    /**
     * Combine the previously defined $page and $tabs arrays into a $settings array
     * and return it for further usage.
     */
    $settings[] = [
        'page' => $page,
        'tabs' => $tabs
    ];

    return $settings;
}, 30 );


/**
 * Usage of the 'wpsf_get_option' function:
 *
 * The 'wpsf_get_option' function is used to retrieve option values from the WPSF instance.
 * Here's how you can use it:
 *
 * - Call the 'wpsf_get_option' function with two parameters:
 *   1. $option_name: The name of the option you want to retrieve.
 *   2. $default (optional): The default value to return if the option is not set.
 *
 * Example:
 * Suppose you want to retrieve the 'site_description' option, and if it's not set, you want to
 * provide a default value of 'Default Site Description':
 *
 * $siteDescription = wpsf_get_option('site_description', 'Default Site Description');
 *
 * In this example, the 'wpsf_get_option' function is called with 'site_description' as the option name
 * and 'Default Site Description' as the default value. If 'site_description' is not set, the function
 * will return the default value.
 *
 * You can replace 'site_description' and 'Default Site Description' with your specific option name
 * and default value as needed.
 */
add_action('wp_footer',function(){
    // echo wpsf_get_option( 'site_description', 'My Default Data' );
});

