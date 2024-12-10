<?php


function aw_specialist_question() {
	register_post_type('specialist_question',
		array(
            'labels' => array(
				'name'                  => __('Specialist Questions', 'jointswp'),
				'singular_name'         => __('Specialist Question', 'jointswp'),
                'all_items' => __('All Specialist Questions', 'jointswp'),
                'add_new' => __('Add New Q & A', 'jointswp'),
                'add_new_item' => __('Add New Q & A', 'jointswp'),
                'edit' => __( 'Edit', 'jointswp' ),
                'edit_item' => __('Edit Q & A', 'jointswp'),
                'new_item' => __('New Q & A', 'jointswp'),
                'view_item' => __('View Specialist Question', 'jointswp'),
                'search_items' => __('Search Our Specialist Questions', 'jointswp'),
                'not_found' =>  __('Nothing found in the Database.', 'jointswp'),
                'not_found_in_trash' => __('Nothing found in Trash', 'jointswp'),
                'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( "Questions and Doctor's Answers post type", 'jointswp' ),
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
                        'show_in_rest' => true,
			'query_var' => true,
            'menu_icon'             => 'dashicons-format-chat',
            'supports'              => array( 'title', 'author','custom-fields', 'revisions'),
			'capability_type' => 'page',
			'hierarchical' => false,
	 	)
	);
}
add_action('init', 'aw_specialist_question');

