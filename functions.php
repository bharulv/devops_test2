<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'astra-theme-css' ) );
        wp_enqueue_script( 'custom-js', trailingslashit( get_stylesheet_directory_uri()) . 'custom.js', array( 'jquery' ), null, true);
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 )

// END ENQUEUE PARENT ACTION

// shortcode to fetch posts 
function category_tabs_shortcode($atts) {
    // Shortcode attributes
    $atts = shortcode_atts(array(
        'categories' => '', 
    ), $atts);
    
    // Get categories
    $category_slugs = !empty($atts['categories']) ? explode(',', $atts['categories']) : array();
    
    if (!empty($category_slugs)) {
        $categories = get_categories(array(
            'slug' => $category_slugs,
            'hide_empty' => true,
        ));
    } else {
        $categories = get_categories(array(
            'hide_empty' => true,
        ));
    }
    
    if (empty($categories)) {
        return '<p>No categories found.</p>';
    }
    
    ob_start(); // Start output buffering
    
    // Generate unique ID for this tab group
    $tab_group_id = 'category-tabs-' . uniqid();
    ?>
    
    <div class="category-tabs" id="<?php echo esc_attr($tab_group_id); ?>">
        <div class="category-tabs-nav">
            <?php foreach ($categories as $index => $category) : ?>
                <button class="category-tab-button <?php echo $index === 0 ? 'active' : ''; ?>" 
                        data-category="<?php echo esc_attr($category->slug); ?>" 
                        data-tab-group="<?php echo esc_attr($tab_group_id); ?>">
                    <?php echo esc_html($category->name); ?>
                </button>
            <?php endforeach; ?>
        </div>
        
        <div class="category-tabs-content">
            <?php foreach ($categories as $index => $category) : ?>
                <div class="category-tab-pane <?php echo $index === 0 ? 'active' : ''; ?>" 
                     data-category="<?php echo esc_attr($category->slug); ?>">
                    <?php
                    $posts = get_posts(array(
                        'category__in' => array($category->term_id),
                        'posts_per_page' => -1,
                    ));
                    
                    if (!empty($posts)) {
                        echo '<div class="category-posts-grid">';
                        foreach ($posts as $post) {
                            $post_id = $post->ID;
                            $featured_image = get_the_post_thumbnail_url($post_id, 'large');
                            $post_title = get_the_title($post_id);
                            $post_excerpt = get_the_excerpt($post_id);
                            $post_link = get_permalink($post_id);
                            ?>
                            <div class="category-post-card">
                                <div class="post-title-wrapper" <?php if ($featured_image) echo 'style="background-image: url(' . esc_url($featured_image) . ')"'; ?>>
                                    <h3><?php echo esc_html($post_title); ?></h3>
                                    <div class="post-title-wrapper__center-underline"></div>
                                </div>
                                <div class="post-excerpt"><?php echo wp_kses_post($post_excerpt); ?></div>
                                <a href="<?php echo esc_url($post_link); ?>" class="read-more">Find out more</a>
                            </div>
                            <?php
                        }
                        echo '</div>';
                    } else {
                        echo '<p>No posts found in this category.</p>';
                    }
                    ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <?php
    return ob_get_clean(); // Return the buffered content
}
add_shortcode('category_tabs', 'category_tabs_shortcode');