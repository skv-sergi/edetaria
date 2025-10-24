<?php
/*
 *  Author: Todd Motto | @toddmotto
 *  URL: html5blank.com | @html5blank
 *  Custom functions, support, custom post types and more.
 */
/*------------------------------------*\
	External Modules/Files
\*------------------------------------*/
// Load any external files you have here
/*------------------------------------*\
	Theme Support
\*------------------------------------*/
/*if (!isset($content_width))
{
    $content_width = 900;
}*/

if (function_exists('add_theme_support')) {
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 900, 600, true); // Large Thumbnail
    add_image_size('medium_large', 800, 530, true); // Medium large Thumbnail
    add_image_size('medium', 500, 333, true); // Medium Thumbnail
    add_image_size('small', 0, 0, true); // Small Thumbnail
    add_image_size('thumbnail', 0, 0, true); // Small Thumbnail
    // Frontpage hero images
	add_image_size( 'large_square', 900, 900, true ); // Call using the_post_thumbnail('large_square');

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Localisation Support
    load_theme_textdomain('html5blank', get_template_directory() . '/languages');
}

/*------------------------------------*\
	Functions
\*------------------------------------*/
// HTML5 Blank navigation
function html5blank_nav() {
	wp_nav_menu(
	array(
		'theme_location'  => 'header-menu',
		'menu'            => '',
		'container'       => 'div',
		'container_class' => 'menu-{menu slug}-container',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul id="nav" class="nav navbar-nav sf-menu">%3$s</ul>',
		'depth'           => 0,
		'walker'          => ''
		)
	);
}

// Footer navigation
function footer_nav() {
    wp_nav_menu(
    array(
        'theme_location'  => 'footer-menu',
        'menu'            => '',
        'container'       => '',
        'container_class' => '',
        'container_id'    => '',
        'menu_class'      => '',
        'menu_id'         => '',
        'echo'            => true,
        'fallback_cb'     => 'wp_page_menu',
        'before'          => '',
        'after'           => '',
        'link_before'     => '',
        'link_after'      => '',
        'items_wrap'      => '<ul class="legal-pages">%3$s</ul>',
        'depth'           => 0,
        'walker'          => ''
        )
    );
}

// Register HTML5 Blank Navigation
function register_html5_menu() {
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'html5blank'), // Main Navigation
        'sidebar-menu' => __('Sidebar Menu', 'html5blank'), // Sidebar Navigation
        'footer-menu' => __('Footer Menu', 'html5blank') // Extra Navigation if needed (duplicate as many as you need!)
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '') {
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var) {
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist) {
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes) {
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar')) {
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'html5blank'),
        'description' => __('Description for this widget-area...', 'html5blank'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'html5blank'),
        'description' => __('Description for this widget-area...', 'html5blank'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style() {
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
/*function html5wp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}*/

// Custom Excerpts
// Create 20 Word Callback for Index page Excerpts, call using html5wp_excerpt('html5wp_index');
function html5wp_index($length) {
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using html5wp_excerpt('html5wp_custom_post');
function html5wp_custom_post($length) {
    return 40;
}

// Create the Custom Excerpts callback
function html5wp_excerpt($length_callback = '', $more_callback = '') {
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Custom View Article link to Post
function html5_blank_view_article($more) {
    global $post;
    return '...';
    //return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('View Article', 'html5blank') . '</a>';
}

// Remove Admin bar
function remove_admin_bar() {
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag) {
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html ) {
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function html5blankgravatar ($avatar_defaults) {
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments() {
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/
// Add Actions
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('init', 'register_html5_menu'); // Add HTML5 Blank Menu
add_action('init', 'create_post_type_html5'); // Add our HTML5 Blank Custom Post Type
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
//add_action('init', 'html5wp_pagination'); // Add our HTML5 Pagination

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'html5blankgravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'html5_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes
add_shortcode('html5_shortcode_demo', 'html5_shortcode_demo'); // You can place [html5_shortcode_demo] in Pages, Posts now.
add_shortcode('html5_shortcode_demo_2', 'html5_shortcode_demo_2'); // Place [html5_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
// [html5_shortcode_demo] [html5_shortcode_demo_2] Here's the page title! [/html5_shortcode_demo_2] [/html5_shortcode_demo]

/*------------------------------------*\
	Custom Post Types
\*------------------------------------*/
// Create 1 Custom Post type for a Demo, called HTML5-Blank
function create_post_type_html5() {
    // Custom Post type, Enoturisme
    register_taxonomy_for_object_type('category', 'enoturisme'); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', 'enoturisme');
    register_post_type('enoturisme', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('Enoturisme', 'html5blank'), // Rename these to suit
            'singular_name' => __('Enoturisme Post', 'html5blank'),
            'add_new' => __('Add New', 'html5blank'),
            'add_new_item' => __('Add New Enoturisme Custom Post', 'html5blank'),
            'edit' => __('Edit', 'html5blank'),
            'edit_item' => __('Edit Enoturisme Custom Post', 'html5blank'),
            'new_item' => __('New Enoturisme Custom Post', 'html5blank'),
            'view' => __('View Enoturisme Custom Post', 'html5blank'),
            'view_item' => __('View Enoturisme Custom Post', 'html5blank'),
            'search_items' => __('Search Enoturisme Custom Post', 'html5blank'),
            'not_found' => __('No Enoturisme Custom Posts found', 'html5blank'),
            'not_found_in_trash' => __('No Enoturisme Custom Posts found in Trash', 'html5blank')
        ),
        'public' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'thumbnail',
            'custom-fields'
        ), // Go to Dashboard Custom HTML5 Blank post for supports
        'can_export' => true, // Allows export in Tools > Export
        //'taxonomies' => array(
        //    'post_tag',
        //    'category'
        //), // Add Category and Post Tags support
        'rewrite' => array(
            'slug' => 'experiencies-enoturisme'
        )
    ));
    // Custom Post type, Press Room
    register_taxonomy_for_object_type('category', 'pressroom'); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', 'pressroom');
    register_post_type('pressroom', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('Press Room', 'html5blank'), // Rename these to suit
            'singular_name' => __('Press Room Custom Post', 'html5blank'),
            'add_new' => __('Add New', 'html5blank'),
            'add_new_item' => __('Add New Press Room Custom Post', 'html5blank'),
            'edit' => __('Edit', 'html5blank'),
            'edit_item' => __('Edit Press Room Custom Post', 'html5blank'),
            'new_item' => __('New Press Room Custom Post', 'html5blank'),
            'view' => __('View Press Room Custom Post', 'html5blank'),
            'view_item' => __('View Press Room Custom Post', 'html5blank'),
            'search_items' => __('Search Press Room Custom Post', 'html5blank'),
            'not_found' => __('No Press Room Custom Posts found', 'html5blank'),
            'not_found_in_trash' => __('No Press Room Custom Posts found in Trash', 'html5blank')
        ),
        'public' => true,
        'hierarchical' => false, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'editor',
            'thumbnail'
        ), // Go to Dashboard Custom HTML5 Blank post for supports
        'can_export' => true // Allows export in Tools > Export
    ));
}

// Add categories to custom post Press Room
// https://stackoverflow.com/questions/33214580/restrict-category-for-custom-post-type-in-wordpress
add_action( 'init', 'pressroom_cat' );

function pressroom_cat() {
    register_taxonomy(
        'pressroom-categories',
        'pressroom',
        array(
            'label' => __( 'Categories' ),
            'hierarchical' => false,
            'show_admin_column' => true
        )
    );
}

// Add categories to custom post Enoturisme
add_action( 'init', 'enoturisme_cat' );

function enoturisme_cat() {
    register_taxonomy(
        'enoturisme-categories',
        'enoturisme',
        array(
            'label' => __( 'Categories' ),
            'hierarchical' => true,
            'show_admin_column' => true
        )
    );
}

// Sort results by name & asc order on Archive.php
// https://wordpress.stackexchange.com/questions/39817/sort-results-by-name-asc-order-on-archive-php
add_action( 'pre_get_posts', 'my_change_sort_order');

function my_change_sort_order($query) {
    if(is_archive()):
     //If you wanted it for the archive of a custom post type use: is_post_type_archive( $post_type )
       //Set the order ASC or DESC
       $query->set( 'order', 'ASC' );
       //Set the orderby
       //$query->set( 'orderby', 'title' );
    endif;    
}

/*------------------------------------*\
	ShortCode Functions
\*------------------------------------*/
// Shortcode Demo with Nested Capability
function html5_shortcode_demo($atts, $content = null) {
    return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>'; // do_shortcode allows for nested Shortcodes
}

// Shortcode Demo with simple <h2> tag
// Demo Heading H2 shortcode, allows for nesting within above element. Fully expandable.
function html5_shortcode_demo_2($atts, $content = null) {
    return '<h2>' . $content . '</h2>';
}

/*-----------------------------------------*\
	     Edetària Custom Functions
\*-----------------------------------------*/
// remove emoji icons (WP 4.2)
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

// Getting rid of wp-embed.min.js
// https://wordpress.stackexchange.com/questions/211701/what-does-wp-embed-min-js-do-in-wordpress-4-4
/*function my_deregister_scripts() {
    wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'my_deregister_scripts' );*/

// Remove JQuery migrate
// https://dotlayer.com/what-is-migrate-js-why-and-how-to-remove-jquery-migrate-from-wordpress/
function remove_jquery_migrate($scripts)
{
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        
        if ($script->deps) { // Check whether the script has any dependencies
            $script->deps = array_diff($script->deps, array(
                'jquery-migrate'
            ));
        }
    }
}
add_action('wp_default_scripts', 'remove_jquery_migrate');

// Remove JQuery from head
// https://stackoverflow.com/questions/52459489/how-to-remove-wordpress-default-jquery
/*function my_jquery_enqueue() {
    wp_deregister_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'my_jquery_enqueue' );*/

/*
 * Remove the `wp-block-library.css` file from `wp_head()`
 * https://wpcrux.com/blog/remove-gutenberg-enqueued-css/
 * @author Rahul Arora
 * @since  12182018
 * @uses   wp_dequeue_style
 */
add_action( 'wp_enqueue_scripts', function() {
    wp_dequeue_style( 'wp-block-library' );
});

// Custom login page
// https://codex.wordpress.org/Customizing_the_Login_Form
function my_login_logo() { ?>
    <style type="text/css">
        body.login {
            background: #ffffff;
        }
        .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/edetaria-logo-tot.svg) !important;
            background-size: 180px 100px !important;
            width: 180px !important;
            height: 100px !important;
            margin: 0 auto 10px !important;
            padding-bottom: 0 !important;
        }
        .login a {
            color: #1D171A;
        }
        .login a:hover {
            color: #DA291C;
        }
        .login form {
            margin-top: 0 !important;
            border: 1px solid #B0976D;
            background-color: #fff !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }
        body.login div#login form#loginform p.submit input#wp-submit {
            font-weight: bold;
            text-transform: uppercase;
            color: #130809;
            background: transparent;
            border: 1px solid transparent;
            border-radius: 0;
            text-shadow: none;
            box-shadow: none;
        }
        body.login div#login form#loginform p.submit input#wp-submit:hover,
        body.login div#login form#loginform p.submit input#wp-submit:focus {
            background: #B0976D;
            border: 1px solid #B0976D;
            color: #130809;
        }
        body.login div#login p#nav a:hover,
        body.login div#login p#backtoblog a:hover {
            color: #CB333B;
        }
        body.login #login_error,
        body.login .message {
            background-color: #fff;
            border-top: 1px solid rgba(29, 23, 26, 0.25);
            border-right: 1px solid rgba(29, 23, 26, 0.25);
            border-bottom: 1px solid rgba(29, 23, 26, 0.25);
            border-radius: 0 !important;
            box-shadow: none !important;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return 'Edetària | Vins ecològics de garnatxa';
}
add_filter( 'login_headertext', 'my_login_logo_url_title' );

// Add excerpt to page
add_action( 'init', 'my_add_excerpts_to_pages' );
function my_add_excerpts_to_pages() {
     add_post_type_support( 'page', 'excerpt' );
}

// qTranslate X language switcher with language code
// https://stackoverflow.com/questions/29649898/wordpress-qtranslate-x-language-switcher-with-language-code/38365905
add_filter('template_include','start_buffer_CA',1);
function start_buffer_CA($template) {
    ob_start('end_buffer_CA');  
    return $template;
}

function end_buffer_CA($buffer) {
    return str_replace('<span>Català</span>','<span>ca</span>',$buffer);  
}

add_filter('template_include','start_buffer_EN',1);
function start_buffer_EN($template) {
    ob_start('end_buffer_EN');  
    return $template;
}

function end_buffer_EN($buffer) {
    return str_replace('<span>English</span>','<span>en</span>',$buffer);  
}

add_filter('template_include','start_buffer_ES',1);
function start_buffer_ES($template) {
  ob_start('end_buffer_ES');
  return $template;
}

function end_buffer_ES($buffer) {  
  return str_replace('<span>Español</span>','<span>es</span>',$buffer);
}

// Blog Pagination
// http://www.wpbeginner.com/wp-themes/how-to-add-numeric-pagination-in-your-wordpress-theme/
function wp_numeric_posts_nav() {
	if( is_singular() )
		return;
	global $wp_query;
	/** Stop execution if there's only 1 page */
	if( $wp_query->max_num_pages <= 1 )
		return;
	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = intval( $wp_query->max_num_pages );
	/**	Add current page to the array */
	if ( $paged >= 1 )
		$links[] = $paged;
	/**	Add the pages around the current page to the array */
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}
	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}
	echo '<ul>' . "\n";
	/**	Previous Post Link */
	if ( get_previous_posts_link() )
		printf( '<li>%s</li>' . "\n", get_previous_posts_link('&laquo;') );
	/**	Link to first page, plus ellipses if necessary */
	if ( ! in_array( 1, $links ) ) {
		$class = 1 == $paged ? ' class="active"' : '';
		printf( '<li%s><a href="%s" class="page">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );
		if ( ! in_array( 2, $links ) )
			echo '<li>…</li>';
	}
	/**	Link to current page, plus 2 pages in either direction if necessary */
	sort( $links );
	foreach ( (array) $links as $link ) {
		$class = $paged == $link ? ' class="active"' : '';
		printf( '<li%s><a href="%s" class="page">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
	}
	/**	Link to last page, plus ellipses if necessary */
	if ( ! in_array( $max, $links ) ) {
		if ( ! in_array( $max - 1, $links ) )
			echo '<li>…</li>' . "\n";
		$class = $paged == $max ? ' class="active"' : '';
		printf( '<li%s><a href="%s" class="page">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
	}
	/**	Next Post Link */
	if ( get_next_posts_link() )
		printf( '<li>%s</li>' . "\n", get_next_posts_link('&raquo;') );
	echo '</ul>' . "\n";
}

// Add next/previous post links
// http://www.jaredatchison.com/code/add-next-previous-post-links-genesis/
function ja_prev_next_post_nav() {
	if ( is_singular( 'post' ) ) {
		echo '<div class="next-prev ">';
			previous_post_link( '<div class="prev-post">%link</div>', '%title' );
			next_post_link( '<div class="next-post pull-right">%link</div>', '%title' );
		echo '</div>';
	}
}
add_action( 'genesis_before_comments', 'ja_prev_next_post_nav' );

// Remove Contact Form 7 javascript
/*add_action( 'wp_print_scripts', 'deregister_cf7_javascript', 100 ); // Change 100 to the id of the contact form page
function deregister_cf7_javascript() {
    if ( !is_page(100) ) {
        wp_deregister_script( 'contact-form-7' );
    }
}*/

// Remove Contact Form 7 stylesheet
add_action( 'wp_print_styles', 'deregister_cf7_styles', 100 ); // Change 100 to the id of the contact form page
function deregister_cf7_styles() {
    if ( !is_page(100) ) {
        wp_deregister_style( 'contact-form-7' );
    }
}

/*----------------------------------------*\
      Edetària WooCommerce functions
\*----------------------------------------*/
// https://docs.woocommerce.com/document/disable-the-default-stylesheet/
// Disable the default stylesheet
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Forwarding WooCommerce 'Shop' page to another page on a site
 * https://stackoverflow.com/questions/40382876/forwarding-woocommerce-shop-page-to-another-page-on-a-site
 */
function custom_shop_page_redirect() {
    if( is_shop() ){
        wp_redirect( home_url( '/els-vins/' ) );
        exit();
    }
}
add_action( 'template_redirect', 'custom_shop_page_redirect' );

/**
 * Manage WooCommerce styles and scripts.
 */
function grd_woocommerce_script_cleaner() {
	// Remove the generator tag
	remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
	// Unless we're in the store, remove all the cruft!
	if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
		//wp_dequeue_script( 'wc-add-to-cart' );
		wp_dequeue_script( 'wc-cart-fragments' );
		wp_dequeue_script( 'wc-checkout' );
		wp_dequeue_script( 'wc-add-to-cart-variation' );
		wp_dequeue_script( 'wc-single-product' );
		wp_dequeue_script( 'wc-cart' );
		wp_dequeue_script( 'wc-chosen' );
		wp_dequeue_script( 'woocommerce' );
		wp_dequeue_script( 'jquery-blockui' );
		wp_dequeue_script( 'jqueryui' );
	}
}
add_action( 'wp_enqueue_scripts', 'grd_woocommerce_script_cleaner', 99 );

// Add support for Woocommerce for custom theme
// https://wordpress.org/support/topic/single-product-is-using-single-php-not-single-product-php/
function mytheme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );

// Remove breadcrumbs
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

/**
 * Remove related products output
 * https://docs.woocommerce.com/document/remove-related-posts-output/#
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

/**
 * @snippet       Remove Sidebar @ Single Product Page
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
 * @sourcecode    https://businessbloomer.com/?p=19572
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.2.6
 */
 
add_action( 'wp', 'bbloomer_remove_sidebar_product_pages' );
 
function bbloomer_remove_sidebar_product_pages() {
    if ( is_product() ) {
        remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
    }
}

/**
 * Remove product data tabs
 */
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {
    unset( $tabs['description'] );      	// Remove the description tab
    unset( $tabs['reviews'] ); 			// Remove the reviews tab
    unset( $tabs['additional_information'] );  	// Remove the additional information tab
    return $tabs;
}

/** 
 * remove on single product panel 'Additional Information' since it already says it on tab.
 * https://isabelcastillo.com/remove-additional-information-panel-title-woocommerce
 */
add_filter('woocommerce_product_description_heading', 'isa_product_additional_information_heading');
 
function isa_product_additional_information_heading() {
    echo '';
}

/** 
 * Remove Product Categories on single product page
 * https://wpmayor.com/how-to-remove-product-categories-in-woocommerce/
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

/**
 * Change the "Add to Cart" text on the single product page
 * https://nicola.blog/2015/04/07/change-the-add-to-cart-text-on-the-single-product-page-conditionally/
 * @return string
 */
/*function wc_custom_single_addtocart_text() {
    return "[:ca]Afegir al carret[:es]Añadir al carrito[:en]Add to cart";
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'wc_custom_single_addtocart_text' );*/

/**
 * Change the add to cart text on single product pages
 * https://remicorson.com/woocommerce-check-if-product-is-already-in-cart/
 */
/*add_filter('woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text');
function woo_custom_cart_button_text() {
	
	foreach( WC()->cart->get_cart() as $cart_item_key => $values ) {
		$_product = $values['data'];
	
		if( get_the_ID() == $_product->id ) {
			return __('[:ca]Ja és al carret - Afegir novament?[:es]ya está en el carrito - ¿Añadir de nuevo?[:en]Already in cart - Add again?', 'woocommerce');
		}
	}
	
	return __('[:ca]Afegir al carret[:es]Añadir al carrito[:en]Add to cart', 'woocommerce');
}*/



/**
 *                ******** mentre la botiga no estigui a punt ********
 * Add a Call to Order Button  
 * https://www.speakinginbytes.com/2016/05/add-call-order-button-woocommerce/
 */
/*function patricks_remove_loop_button() {
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 ); // removes the add to cart button on the shop page
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 ); //removes the add to cart button on the single product page
}
add_action('init','patricks_remove_loop_button');

function patricks_woocommerce_call_to_order_button() {
	global $product; //get the product object
	if ( $product ) { // if there's a product proceed
		$url = esc_url( $product->get_permalink() ); //get the permalink to the product
		echo '<a rel="nofollow" href="' . $url . '" class="button add_to_cart_button ">Call to order!</a>'; //display a button that goes to the product page
	}
}
add_action('woocommerce_after_shop_loop_item','patricks_woocommerce_call_to_order_button', 10);

function patricks_woocommerce_call_to_order_text() {
    if(function_exists('qtranxf_getLanguage')) {
    if (qtranxf_getLanguage()=='ca'):
    echo '<a href="mailto:info@edetaria.com" class="cta"><span>Envia&apos;ns un email</span></a>';
    endif;
    if (qtranxf_getLanguage()=='es'):
    echo '<a href="mailto:info@edetaria.com" class="cta"><span>M&aacute;ndanos un email</span></a>';
    endif;
    if (qtranxf_getLanguage()=='en'):
    echo '<a href="mailto:info@edetaria.com" class="cta"><span>Email us</span></a>';
    endif;
    }
}
add_action('woocommerce_single_product_summary','patricks_woocommerce_call_to_order_text', 30);
*/



/*
 * Remove image links from WooCommerce thumbnail images
 */
add_filter('woocommerce_single_product_image_thumbnail_html','wc_remove_link_on_thumbnails' );
 
function wc_remove_link_on_thumbnails( $html ) {
     return strip_tags( $html,'<div><img>' );
}

// Thumbnail image sizes https://github.com/woocommerce/woocommerce/wiki/Customizing-image-sizes-in-3.3
/*add_theme_support( 'woocommerce', array(
    'thumbnail_image_width'         => 600,
    'gallery_thumbnail_image_width' => 600,
    'single_image_width'            => 600,
) );*/

add_filter( 'woocommerce_get_image_size_thumbnail', function( $size ) {
    return array(
        'width'  => 900,
        'height' => 600,
        'crop'   => 1,
    );
} );

add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function( $size ) {
    return array(
        'width'  => 900,
        'height' => 600,
        'crop'   => 1,
    );
});

add_filter( 'woocommerce_get_image_size_single', function( $size ) {
    return array(
        'width'  => 900,
        'height' => 600,
        'crop'   => 1,
    );
} );


/**
 Remove all possible fields
 https://docs.woocommerce.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/
 **/
function wc_remove_checkout_fields( $fields ) {

    // Billing fields
    unset( $fields['billing']['billing_company'] );
    //unset( $fields['billing']['billing_email'] );
    //unset( $fields['billing']['billing_phone'] );
    //unset( $fields['billing']['billing_state'] );
    //unset( $fields['billing']['billing_first_name'] );
    //unset( $fields['billing']['billing_last_name'] );
    //unset( $fields['billing']['billing_address_1'] );
    unset( $fields['billing']['billing_address_2'] );
    //unset( $fields['billing']['billing_city'] );
    //unset( $fields['billing']['billing_postcode'] );

    // Shipping fields
    unset( $fields['shipping']['shipping_company'] );
    //unset( $fields['shipping']['shipping_phone'] );
    //unset( $fields['shipping']['shipping_state'] );
    //unset( $fields['shipping']['shipping_first_name'] );
    //unset( $fields['shipping']['shipping_last_name'] );
    //unset( $fields['shipping']['shipping_address_1'] );
    unset( $fields['shipping']['shipping_address_2'] );
    //unset( $fields['shipping']['shipping_city'] );
    //unset( $fields['shipping']['shipping_postcode'] );

    // Order fields
    //unset( $fields['order']['order_comments'] );

    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'wc_remove_checkout_fields' );



// Remove CSS and/or JS for Select2 used by WooCommerce, see https://gist.github.com/Willem-Siebe/c6d798ccba249d5bf080.
// https://gist.github.com/Willem-Siebe/c6d798ccba249d5bf080
add_action( 'wp_enqueue_scripts', 'wsis_dequeue_stylesandscripts_select2', 100 );

function wsis_dequeue_stylesandscripts_select2() {
    if ( class_exists( 'woocommerce' ) ) {
        wp_dequeue_style( 'select2' );
        wp_deregister_style( 'select2' );

        wp_dequeue_script( 'select2');
        wp_deregister_script('select2');

    } 
}



/**
 * Change the placeholder image
 * https://docs.woocommerce.com/document/change-the-placeholder-image/
 */
add_filter('woocommerce_placeholder_img_src', 'custom_woocommerce_placeholder_img_src');

function custom_woocommerce_placeholder_img_src( $src ) {
	$upload_dir = wp_upload_dir();
	$uploads = untrailingslashit( $upload_dir['baseurl'] );
	// replace with path to your image
	$src = $uploads . '/2020/05/wc-placeholder.jpg';
	 
	return $src;
}




/**
 * Añade el campo NIF a la página de checkout de WooCommerce
 * https://www.tiendaonlinemurcia.es/dni-nif-facturas-woocommerce/
 */
add_action( 'woocommerce_after_order_notes', 'agrega_mi_campo_personalizado' );
 
function agrega_mi_campo_personalizado( $checkout ) {
 
    echo '<div id="additional_checkout_field"><h3>' . __('[:ca]Informació adicional[:es]Información adicional[:en]Additional information') . '</h3>';
 
    woocommerce_form_field( 'nif', array(
        'type'          => 'text',
        'class'         => array('my-field-class form-row-wide'),
        'label'         => __('NIF-DNI'),
        'required'      => false,
        'placeholder'   => __('[:ca]Introduïu el vostre NIF-DNI [:es]Introduzca el Nº NIF-DNI'),
        ), $checkout->get_value( 'nif' ));
 
    echo '</div>';
 
}
/**
 * Comprueba que el campo NIF no esté vacío
 */
/*add_action('woocommerce_checkout_process', 'comprobar_campo_nif');
 
function comprobar_campo_nif() {
    
    // Comprueba si se ha introducido un valor y si está vacío se muestra un error.
    if ( ! $_POST['nif'] )
        wc_add_notice( __( 'NIF-DNI, es un campo requerido. Debe de introducir su NIF DNI para finalizar la compra.' ), 'error' );
}*/

/**
 * Actualiza la información del pedido con el nuevo campo
 */
add_action( 'woocommerce_checkout_update_order_meta', 'actualizar_info_pedido_con_nuevo_campo' );
 
function actualizar_info_pedido_con_nuevo_campo( $order_id ) {
    if ( ! empty( $_POST['nif'] ) ) {
        update_post_meta( $order_id, 'NIF', sanitize_text_field( $_POST['nif'] ) );
    }
}

/**
 * Muestra el valor del nuevo campo NIF en la página de edición del pedido
 */
add_action( 'woocommerce_admin_order_data_after_billing_address', 'mostrar_campo_personalizado_en_admin_pedido', 10, 1 );
 
function mostrar_campo_personalizado_en_admin_pedido($order){
    echo '<p><strong>'.__('NIF').':</strong> ' . get_post_meta( $order->id, 'NIF', true ) . '</p>';
}

/**
 * Incluye el campo NIF en el email de notificación del cliente
 */
 
add_filter('woocommerce_email_order_meta_keys', 'muestra_campo_personalizado_email');
 
function muestra_campo_personalizado_email( $keys ) {
    $keys[] = 'NIF';
    return $keys;
}

/**
*Incluir NIF en la factura (necesario el plugin WooCommerce PDF Invoices & Packing Slips)
*/
 
add_filter( 'wpo_wcpdf_billing_address', 'incluir_nif_en_factura' );
 
function incluir_nif_en_factura( $address ){
  global $wpo_wcpdf;
 
  echo $address . '<p>';
  $wpo_wcpdf->custom_field( 'NIF', 'NIF: ' );
  echo '</p>';
}



/* 
 * Add SVG support to ACF 
 * https://www.advancedcustomfields.com/resources/html-escaping/
 */
function acf_add_allowed_svg_tag( $tags, $context ) {
    if ( $context === 'acf' ) {
        $tags['svg']  = array(
            'xmlns'       => true,
            'fill'        => true,
            'viewbox'     => true,
            'role'        => true,
            'aria-hidden' => true,
            'focusable'   => true,
        );
        $tags['path'] = array(
            'd'    => true,
            'fill' => true,
        );
    }

    return $tags;
}
add_filter( 'wp_kses_allowed_html', 'acf_add_allowed_svg_tag', 10, 2 );


/* 
 * Add iFrame support to ACF 
 * https://www.advancedcustomfields.com/resources/html-escaping/
 */
function acf_add_allowed_iframe_tag( $tags, $context ) {
    if ( $context === 'acf' ) {
        $tags['iframe'] = array(
            'src'             => true,
            'height'          => true,
            'width'           => true,
            'frameborder'     => true,
            'allowfullscreen' => true,
        );
    }

    return $tags;
}
add_filter( 'wp_kses_allowed_html', 'acf_add_allowed_iframe_tag', 10, 2 );


/*
 * ACF - allow that field to output potentially unsafe HTML (the iframe)
 * https://www.advancedcustomfields.com/blog/acf-6-2-5-security-release/
 */
add_filter( 'acf/the_field/allow_unsafe_html', function( $allowed, $selector ) {
    if ( $selector === "event_iframe" ) {
        return true;
    }
    return $allowed;
}, 10, 2);
