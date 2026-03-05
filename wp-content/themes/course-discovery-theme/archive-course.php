<?php
/**
 * Template: Course Archive / Finder (Robust Filter)
 */
get_header(); 
?>

<div class="course-finder-container">
    <h1>Course Finder</h1>

    <form method="get" aria-label="Course search filters">
        <div class="course-filter-form">
            <div>
                <label for="course_search">Search Courses:</label>
                <input type="text" id="course_search" name="course_search" placeholder="Search courses"
aria-label="Search courses" value="<?php echo esc_attr($_GET['course_search'] ?? ''); ?>">
            </div>

            <div>
                <label>Providers:</label>
                <?php
                $providers = get_posts(['post_type'=>'provider','posts_per_page'=>-1]);
                foreach($providers as $provider){
                    $checked = (isset($_GET['f_provider']) && in_array($provider->ID, (array)$_GET['f_provider'])) ? 'checked' : '';
        echo '<label><input type="checkbox" aria-label="Filter by provider" name="f_provider[]" value="'. $provider->ID .'" '. $checked .'> '. $provider->post_title .'</label>';
    }
                ?>
            </div>
            <div>
                <label>Instructors:</label>
                <?php
                $instructors = get_posts(['post_type'=>'instructor','posts_per_page'=>-1]);
                foreach($instructors as $instructor){
                    $checked = (isset($_GET['f_instructor']) && in_array($instructor->ID, (array)$_GET['f_instructor'])) ? 'checked' : '';
        echo '<label><input type="checkbox" aria-label="Filter by instructor" name="f_instructor[]" value="'. $instructor->ID .'" '. $checked .'> '. $instructor->post_title .'</label>';
    }
                ?>
            </div>

            <div>
                <label>Start Dates:</label>
                <?php
                $start_dates = [];
                $all_courses_for_dates = get_posts(['post_type'=>'course','posts_per_page'=>-1]);
                foreach($all_courses_for_dates as $c){
                    $dates_text = get_field('start_dates', $c->ID);
                    if($dates_text){
                        $dates_array = array_map('trim', explode(',', $dates_text));
                        foreach($dates_array as $d){
                            if(!in_array($d, $start_dates)) $start_dates[] = $d;
                        }
                    }
                }
                usort($start_dates, function($a, $b){ return strtotime("1 $a") - strtotime("1 $b"); });
                
                echo '<select name="start_date[]" multiple style="height: 100px;" aria-label="Filter by start date">';
                foreach($start_dates as $d){
                    $selected = (isset($_GET['start_date']) && in_array($d, (array)$_GET['start_date'])) ? 'selected' : '';
                    echo "<option value='".esc_attr($d)."' $selected>$d</option>";
                }
                echo '</select>';
                ?>
            </div>

            <div>
                <label>Categories:</label>
                <?php
                $categories = get_terms(['taxonomy'=>'course_category','hide_empty'=>false]);
                foreach($categories as $cat){
                    $checked = (isset($_GET['category']) && in_array($cat->term_id, (array)$_GET['category'])) ? 'checked' : '';
                    echo '<label><input type="checkbox" aria-label="Filter by category" name="category[]" value="'.$cat->term_id.'" '.$checked.'> '.$cat->name.'</label>';
                }
                ?>
            </div>

            <div>
                <label>Locations:</label>
                <?php
                $locations = [];
                foreach($providers as $p){
                    $loc = get_field('location', $p->ID);
                    if($loc && !in_array($loc,$locations)) $locations[] = $loc;
                }
                sort($locations);
                echo '<select name="location[]" multiple style="height: 100px;" aria-label="Filter by location">';
                foreach($locations as $loc){
                    $selected = (isset($_GET['location']) && in_array($loc, (array)$_GET['location'])) ? 'selected' : '';
                    echo "<option value='".esc_attr($loc)."' $selected>$loc</option>";
                }
                echo '</select>';
                ?>
            </div>
        </div>

        <div class="filter-buttons" style="margin-top: 20px;">
            <button type="submit">Filter</button>
            <a href="<?php echo get_post_type_archive_link('course'); ?>" class="filter-button">Reset</a>
        </div>
    </form>

    <hr>

    <div class="course-results">
        <?php
        // 1. Initialize variables
        $meta_query = ['relation' => 'AND'];
        $tax_query  = [];

        // 2. Build Providers Sub-Query (Fix for Relationship field)
        if(!empty($_GET['f_provider']) && is_array($_GET['f_provider'])){
    $provider_sub = ['relation' => 'OR'];
    foreach($_GET['f_provider'] as $p_id) {
        // Match serialized string format "123"
        $provider_sub[] = [
            'key'     => 'providers',
            'value'   => '"' . $p_id . '"', 
            'compare' => 'LIKE'
        ];
        // Match serialized integer format i:123;
        $provider_sub[] = [
            'key'     => 'providers',
            'value'   => 'i:' . $p_id . ';', 
            'compare' => 'LIKE'
        ];
    }
    $meta_query[] = $provider_sub;
}
if(!empty($_GET['f_instructor']) && is_array($_GET['f_instructor'])){
    $instructor_sub = ['relation' => 'OR'];
    foreach($_GET['f_instructor'] as $p_id) {
        // Match serialized string format "123"
        $instructor_sub[] = [
            'key'     => 'instructors',
            'value'   => '"' . $p_id . '"', 
            'compare' => 'LIKE'
        ];
        // Match serialized integer format i:123;
        $instructor_sub[] = [
            'key'     => 'instructors',
            'value'   => 'i:' . $p_id . ';', 
            'compare' => 'LIKE'
        ];
    }
    $meta_query[] = $instructor_sub;
}

        // 3. Build Start Dates Sub-Query (Fix for comma strings)
        if(!empty($_GET['start_date']) && is_array($_GET['start_date'])){
            $date_sub = ['relation' => 'OR'];
            foreach($_GET['start_date'] as $date_val) {
                $date_sub[] = [
                    'key'     => 'start_dates',
                    'value'   => $date_val,
                    'compare' => 'LIKE'
                ];
            }
            $meta_query[] = $date_sub;
        }

        // 4. Build Taxonomy Query
        if(!empty($_GET['category']) && is_array($_GET['category'])){
            $tax_query[] = [
                'taxonomy' => 'course_category',
                'field'    => 'term_id',
                'terms'    => $_GET['category'],
            ];
        }

        // 5. Final WP_Query Arguments
        $args = [
            'post_type'      => 'course',
            'posts_per_page' => -1,
            'meta_query'     => $meta_query,
            'tax_query'      => $tax_query,
        ];

        if(!empty($_GET['course_search'])){
            $args['s'] = sanitize_text_field($_GET['course_search']);
        }

        $query = new WP_Query($args);

        // 6. Manual PHP Filter for Derived Locations
        $final_courses = [];
        if($query->have_posts()){
            foreach($query->posts as $course_post){
                $providers_linked = get_field('providers', $course_post->ID);
                $course_locations = [];
                
                if($providers_linked){
                    foreach($providers_linked as $p_obj){
                        $p_id = is_object($p_obj) ? $p_obj->ID : $p_obj;
                        $loc = get_field('location', $p_id);
                        if($loc && !in_array($loc, $course_locations)) $course_locations[] = $loc;
                    }
                }

                if(!empty($_GET['location']) && is_array($_GET['location'])){
                    $matched = array_intersect($course_locations, $_GET['location']);
                    if(empty($matched)) continue; 
                }
                $final_courses[] = $course_post;
            }
        }

        // 7. Output results
        if(!empty($final_courses)){
            foreach($final_courses as $post){
                setup_postdata($post);
                $price = get_field('price');
                $instructors_linked = get_field('instructors');
                $providers_linked = get_field('providers');
                $dates_text = get_field('start_dates');

                $locations_display = [];
                if($providers_linked){
                    foreach($providers_linked as $p_obj){
                        $p_id = is_object($p_obj) ? $p_obj->ID : $p_obj;
                        $loc = get_field('location', $p_id);
                        if($loc && !in_array($loc, $locations_display)) $locations_display[] = $loc;
                    }
                }
                $course_cats = get_the_terms($post->ID, 'course_category');
                ?>
                <section class="course-results" aria-live="polite">
                <article class="course-card">
                    <h2><?php the_title(); ?></h2>
                    <p><strong>Short Description:</strong> <?php echo get_the_content(); ?></p>
                    <p><strong>Description:</strong> <?php echo get_the_excerpt(); ?></p>
                    <p class="course-price"><strong>Price:</strong> <?php echo $price; ?></p>
                    <p class="course-instructor"><strong>Instructors:</strong>
                        <?php 
                        if($instructors_linked){
                            $p_names = [];
                            foreach($instructors_linked as $p_obj) {
                                $p_names[] = get_the_title(is_object($p_obj) ? $p_obj->ID : $p_obj);
                            }
                            echo implode(', ', $p_names);
                        }
                        ?>
                    </p>
                    <p class="course-provider"><strong>Providers:</strong>
                        <?php 
                        if($providers_linked){
                            $p_names = [];
                            foreach($providers_linked as $p_obj) {
                                $p_names[] = get_the_title(is_object($p_obj) ? $p_obj->ID : $p_obj);
                            }
                            echo implode(', ', $p_names);
                        }
                        ?>
                    </p>
                    <p class="course-location"><strong>Locations:</strong> <?php echo implode(', ', $locations_display); ?></p>
                    <p class="course-start"><strong>Start Dates:</strong> <?php echo $dates_text; ?></p>
                    <p><strong>Categories:</strong> 
                <?php 
                if ($course_cats && !is_wp_error($course_cats)) {
                    $cat_names = wp_list_pluck($course_cats, 'name');
                    echo implode(', ', $cat_names);
                } else {
                    echo 'Uncategorized';
                }
                ?>
                </article></section>
                
                <?php
            }
            wp_reset_postdata();
        } else {
            echo '<p>No courses found matching your criteria.</p>';
        }
        ?>
    </div>
</div>
<?php get_footer(); ?>