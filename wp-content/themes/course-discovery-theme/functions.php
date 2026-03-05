<?php
function unpause_enqueue_scripts(){
    
    wp_enqueue_style('unpause-main-style', get_template_directory_uri().'/assets/css/main.css',array(),'1.0');
}
add_action('wp_enqueue_scripts','unpause_enqueue_scripts');
add_action('init', 'register_course_cpt');
function register_course_cpt() {
    $labels = [
        'name' => 'Courses',
        'singular_name' => 'Course',
        'add_new' => 'Add New Course',
        'add_new_item' => 'Add New Course',
        'edit_item' => 'Edit Course',
        'new_item' => 'New Course',
        'view_item' => 'View Course',
        'search_items' => 'Search Courses',
        'not_found' => 'No courses found',
        'not_found_in_trash' => 'No courses found in Trash'
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true, // For Gutenberg / REST API
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail'],
        'rewrite' => ['slug' => 'courses'],
    ];

    register_post_type('course', $args);
}
add_action('init', 'register_course_category');
function register_course_category() {
    $labels = [
        'name' => 'Course Categories',
        'singular_name' => 'Course Category',
        'search_items' => 'Search Course Categories',
        'all_items' => 'All Categories',
        'edit_item' => 'Edit Category',
        'update_item' => 'Update Category',
        'add_new_item' => 'Add New Category',
        'new_item_name' => 'New Category Name',
    ];

    register_taxonomy('course_category', 'course', [
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'course-category'],
    ]);
}

add_action('init', 'register_instructor_cpt');
function register_instructor_cpt() {
    $labels = [
        'name' => 'Instructors',
        'singular_name' => 'Instructor',
        'add_new' => 'Add New Instructor',
        'add_new_item' => 'Add New Instructor',
        'edit_item' => 'Edit Instructor',
        'new_item' => 'New Instructor',
        'view_item' => 'View Instructor',
        'search_items' => 'Search Instructors',
        'not_found' => 'No instructors found',
        'not_found_in_trash' => 'No instructors found in Trash'
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true, // For Gutenberg / REST API
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail'],
        'rewrite' => ['slug' => 'instructors'],
    ];

    register_post_type('instructor', $args);
}

add_action('init', 'register_provider_cpt');
function register_provider_cpt() {
    $labels = [
        'name' => 'Providers',
        'singular_name' => 'Provider',
        'add_new' => 'Add New Provider',
        'add_new_item' => 'Add New Provider',
        'edit_item' => 'Edit Provider',
        'new_item' => 'New Provider',
        'view_item' => 'View Provider',
        'search_items' => 'Search Providers',
        'not_found' => 'No providers found',
        'not_found_in_trash' => 'No providers found in Trash'
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true, // For Gutenberg / REST API
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail'],
        'rewrite' => ['slug' => 'providers'],
    ];

    register_post_type('provider', $args);
}
function redirect_home_to_courses() {
    if (is_front_page()) {
        wp_redirect(get_post_type_archive_link('course'));
        exit;
    }
}
add_action('template_redirect', 'redirect_home_to_courses');
?>