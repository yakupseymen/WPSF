<?php 


// Create admin page: My Settings
$prefix = 'wpsf_options';
\WPSF\Core\Admin_Page::create( $prefix, [
    'page_title' => esc_html__( 'My Settings', 'wpsf' ),
    'menu_title' => esc_html__( 'My Settings', 'wpsf' ),
    'menu_slug' => 'wpsf',
    'fields' => [
		'general' => [
            'label' => esc_html__( 'General', 'wpsf' ),
            'sections' => [
                'general' => [
                   'label' => esc_html__( 'General Settings', 'wpsf' ),
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
                   'label' => esc_html__( 'API Keys', 'wpsf' ),
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
            'label' => esc_html__( 'Page Options', 'wpsf' ),
            'sections' =>[
                'discussion' => [
                   'label' => esc_html__( 'Discussion', 'wpsf' ),
                   'fields' => [
                        [
                            'id' => 'enable_comments',
                            'label' => esc_html__( 'Enable comments?', 'wpsf' ),
                            'description' => esc_html__( 'Enable comments on the single pages?', 'wpsf' ),
                            'type' => 'checkbox',
                        ],
                        [
                            'id' => 'page_layout',
                            'label' => esc_html__( 'Page Layout', 'wpsf' ),
                            'description' => esc_html__( 'Select page layout.', 'wpsf' ),
                            'type' => 'select',
                            'options' => [
                                'boxed' => esc_html__( 'Boxed', 'wpsf' ),
                                'full-width' => esc_html__( 'Full Width', 'wpsf' ),
                            ]
                        ],
                   ]
                ]
            ]
        ]
	]
] );

// Create admin submenu for 'My Settings'
\WPSF\Core\Admin_Page::create( $prefix, [
    'page_title' => esc_html__( 'Advanced', 'wpsf' ), 
    'menu_title' => esc_html__( 'Advanced', 'wpsf' ),
    'menu_slug' => 'my-advanced-settings',
    'parent' => 'wpsf',
    'fields' => [
		'general' => [
            'label' => esc_html__( 'Post', 'wpsf' ),
            'sections' => [
                'general' => [
                   'label' => esc_html__( 'General Settings', 'wpsf' ),
                   'fields' => [
                        [
                            'id' => 'before_content',
                            'label' => esc_html__( 'Before Content', 'wpsf' ),
                            'description' => esc_html__( 'Add custom content before the post', 'wpsf' ),
                            'type' => 'wysiwyg',
                        ],
                        [
                            'id' => 'after_content',
                            'label' => esc_html__( 'After Content', 'wpsf' ),
                            'description' => esc_html__( 'Add custom content after the post', 'wpsf' ),
                            'type' => 'wysiwyg',
                        ],
                   ]
                ],
                'style' => [
                   'label' => esc_html__( 'Post Title Color' ),
                   'fields' => [
                        [
                            'id' => 'post_title_color',
                            'label' => esc_html__( 'Post Title Color', 'wpsf' ),
                            'description' => esc_html__( 'Select a color', 'wpsf' ),
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
                   'label' => esc_html__('General'),
                   'fields' => [
                        [
                            'id' => 'site_title',
                            'label' => esc_html__( 'Site Title', 'wpsf' ),
                            'description' => esc_html__( 'Set site title', 'wpsf' ),
                            'type' => 'text',
                        ],
                        [
                            'id' => 'site_description',
                            'label' => esc_html__( 'Site Description', 'wpsf' ),
                            'description' => esc_html__( 'In a few words, explain what this site is about. Example: “Just another WordPress site.”', 'wpsf' ),
                            'type' => 'textarea',
                        ],
                   ]
                ]
            ]
        ]
	]
] );


add_action('wp_footer',function(){
    $prefix = 'wpsf_options';
    $options = get_option( $prefix );
    $field_one =  $options['field_one']; // print this if you want
});

/**
 * #2 Register Taxonomy Settings
 * 
 * Registers settings for custom taxonomies using the 'wpsf_register_taxonomy_settings' filter.
 *
 * This filter allows defining custom fields for taxonomies with specific types (e.g., text, checkbox, media).
 *
 * @param array $settings An associative array containing taxonomy settings and their respective fields.
 *                        Format: $settings['taxonomy_name'] = [ fields ];
 *                        Each field contains 'label', 'id', and 'type' keys.
 *                        Available types: 'media', 'checkbox', 'radio', 'select', 'textarea', 'wysiwyg', 'pages', 'categories', 'users'.
 *
 * @return array The modified array of taxonomy settings with added fields.
 */
add_filter( 'wpsf_register_taxonomy_settings', function( $settings ){

    // Define taxonomy settings and associated fields
    $settings['post_tag'] = [
        [
            'label' => 'Text Field',
            'id' => 'text_field',
            'type' => 'text',
        ],
        [
            'label' => 'Checkbox',
            'id' => 'checkbox_1',
            'type' => 'checkbox',
        ],
        [
            'label' => 'Media',
            'id' => 'media',
            'type' => 'media',
        ],
        // Add more fields as needed for 'post_tag' or other taxonomies
    ];

    return $settings;

    // How can you get the datas?
    // Get the current term/tag ID
    $term_id = 25; // Replace this with the actual term/tag ID

    // Get the field data for 'text_field'
    $text_field_value = get_term_meta($term_id, 'text_field', true);

    // Get the field data for 'checkbox_1'
    $checkbox_value = get_term_meta($term_id, 'checkbox_1', true);

    // Get the field data for 'media' (assuming it's an attachment ID)
    $media_id = get_term_meta($term_id, 'media', true);
    $media_url = wp_get_attachment_url($media_id);
    
    // Now you have the values of these custom fields associated with the specific term/tag.

}, 20 );
