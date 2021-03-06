<?php
/**
 * bookshop functions and definitions
 *
 * @package bookshop
 */


/* ------------------------------------------------------------------------- *
 *  OptionTree framework integration: Use in theme mode
/* ------------------------------------------------------------------------- */
        
        add_filter( 'ot_show_pages', '__return_false' );
        add_filter( 'ot_show_new_layout', '__return_false' );
        add_filter( 'ot_theme_mode', '__return_true' );
        load_template( get_template_directory() . '/option-tree/ot-loader.php' );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'bookshop_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function bookshop_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on bookshop, use a find and replace
	 * to change 'bookshop' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'bookshop', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

        // Declare WooCommerce support
        add_theme_support( 'woocommerce' );

	// This theme uses wp_nav_menu() in one location.
	//register_nav_menus( array(
	//	'primary' => __( 'Primary Menu', 'bookshop' ),
	//) );

                // Custom menu areas
                register_nav_menus( array(
                        'topbar' => 'Topbar',
                        'header' => 'Header',
                        'footer' => 'Footer',
                ) );


	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'bookshop_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array( 'comment-list', 'search-form', 'comment-form', ) );
}
endif; // bookshop_setup
add_action( 'after_setup_theme', 'bookshop_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function bookshop_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'bookshop' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'bookshop_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function bookshop_scripts() {
	wp_enqueue_style( 'bookshop-style', get_stylesheet_uri() );

	wp_enqueue_script( 'bookshop-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'bookshop-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'bookshop_scripts' );

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/*  Site name/logo
/* ------------------------------------ */
if ( ! function_exists( 'bkshp_site_title' ) ) {

        function bkshp_site_title() {
        
                // Text or image?
                if ( ot_get_option('custom-logo') ) {
                        $logo = '<img src="'.ot_get_option('custom-logo').'" alt="'.get_bloginfo('name').'">';
                } else {
                        $logo = get_bloginfo('name');
                }
                
                $link = '<a href="'.home_url('/').'" rel="home">'.$logo.'</a>';
                
                if ( is_front_page() || is_home() ) {
                        $sitename = '<h1 class="site-title">'.$link.'</h1>'."\n";
                } else {
                        $sitename = '<p class="site-title">'.$link.'</p>'."\n";
                }
                
                return $sitename;
        }
        
}

/*  Social links
/* ------------------------------------ */
if ( ! function_exists( 'bkshp_social_links' ) ) {

        function bkshp_social_links() {
                if ( !ot_get_option('social-links') =='' ) {
                        $links = ot_get_option('social-links', array());
                        if ( !empty( $links ) ) {
                                echo '<ul class="social-links">';       
                                foreach( $links as $item ) {
                                        
                                        // Build each separate html-section only if set
                                        if ( isset($item['title']) && !empty($item['title']) ) 
                                                { $title = 'title="' .$item['title']. '"'; } else $title = '';
                                        if ( isset($item['social-link']) && !empty($item['social-link']) ) 
                                                { $link = 'href="' .$item['social-link']. '"'; } else $link = '';
                                        if ( isset($item['social-target']) && !empty($item['social-target']) ) 
                                                { $target = 'target="' .$item['social-target']. '"'; } else $target = '';
                                        if ( isset($item['social-icon']) && !empty($item['social-icon']) ) 
                                                { $icon = 'class="fa ' .$item['social-icon']. '"'; } else $icon = '';
                                        if ( isset($item['social-color']) && !empty($item['social-color']) ) 
                                                { $color = 'style="color: ' .$item['social-color']. ';"'; } else $color = '';
                                        
                                        // Put them together
                                        if ( isset($item['title']) && !empty($item['title']) && isset($item['social-icon']) && !empty($item['social-icon']) && ($item['social-icon'] !='fa-') ) {
                                                echo '<li><a rel="nofollow" class="social-tooltip" '.$title.' '.$link.' '.$target.'><i '.$icon.' '.$color.'></i></a></li>';
                                        }
                                }
                                echo '</ul>';
                        }
                }
        }
        
}
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);
