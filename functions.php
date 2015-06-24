<?php
/**
 * Functions
 *
 * @package      Power of Moms
 * @author       Zach Swinehart <zach@zachswinehart.com>
 * @copyright    Copyright (c) 2014, Zach Swinehart
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */


//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Power of Moms' );
define( 'CHILD_THEME_URL', 'http://zachswinehart.com/' );
define( 'CHILD_THEME_VERSION', '1.0.2' );

//* Enqueue Google Fonts
/*add_action( 'wp_enqueue_scripts', 'genesis_sample_google_fonts' );
function genesis_sample_google_fonts() {

	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:300,400,700', array(), CHILD_THEME_VERSION );

}*/

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Add support for WooCommerce
add_theme_support('genesis-connect-woocommerce');
add_theme_support('woocommerce');


/*========================================================================
Reposition the primary navigation menu
========================================================================*/
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_before_header', 'genesis_do_nav', 20 );


/*========================================================================
Wrap .nav-primary in a custom div
========================================================================*/
/*add_filter( 'genesis_do_nav', 'genesis_child_nav', 21, 3 );
function genesis_child_nav($nav_output, $nav, $args) {
	return '<div class="nav-primary-wrapper">' . $nav_output . '</div>';
}*/


/*========================================================================
Enqueue JS/CSS
========================================================================*/
add_action( 'genesis_after_footer', 'after_body_js' );
function after_body_js() {
    wp_enqueue_script( 'bootstrap', get_stylesheet_directory_uri() . '/bootstrap/js/bootstrap.min.js', array( 'jquery' ), '', true );
    wp_enqueue_script( 'z-responsive-menu', get_stylesheet_directory_uri() . '/lib/js/responsive-menu.js', array( 'jquery' ), '', true );
    wp_enqueue_script( 'scripts', get_stylesheet_directory_uri() . '/js/scripts.js', array( 'jquery' ), '', true );

}

add_action( 'wp_enqueue_scripts', 'custom_load_custom_style_sheet', 0 );
function custom_load_custom_style_sheet() {
    wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/bootstrap/css/bootstrap.min.css', array(), PARENT_THEME_VERSION );
    wp_enqueue_style( 'fontello', get_stylesheet_directory_uri() . '/css/fontello.css', array(), PARENT_THEME_VERSION );

}

if ( !is_admin() ) {
    add_action( "wp_enqueue_scripts", "my_jquery_enqueue", 11 );
}
function my_jquery_enqueue() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', "//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js", array(), '', false );
    wp_enqueue_script( 'jquery' );

    wp_deregister_script('essb-counter-script');
}

function theme_typekit() {
    wp_enqueue_script( 'theme_typekit', '//use.typekit.net/vyj5nea.js', array(), false, true );
}

add_action( 'wp_enqueue_scripts', 'theme_typekit' );

function theme_typekit_inline() {
    if ( wp_script_is( 'theme_typekit', 'done' ) ) {
        ?>
        <script type="text/javascript">try {
                Typekit.load();
            } catch (e) {
            }</script>
    <?php
    }
}

add_action( 'wp_head', 'theme_typekit_inline' );


/*========================================================================
header login bar
========================================================================*/
if ( !is_user_logged_in() ) {
    add_action( 'genesis_before_header', 'login_bar', 10 );
}
function login_bar() {

    echo '<div class="login-bar collapse" id="login-bar"><div class="wrap">';
    $request = isset($_GET['wlfrom']) ? $_GET['wlfrom'] : $_SERVER['REQUEST_URI'];
    $args = array(
        'echo'           => true,
        'redirect'       => $request,
        'form_id'        => 'loginform',
        'label_username' => __( 'Username' ),
        'label_password' => __( 'Password' ),
        'label_remember' => __( 'Remember Me' ),
        'label_log_in'   => __( 'Log In' ),
        'id_username'    => 'user_login',
        'id_password'    => 'user_pass',
        'id_remember'    => 'rememberme',
        'id_submit'      => 'wp-submit',
        'remember'       => true,
        'value_username' => null,
        'value_remember' => true
    );
    echo '<h3>Member Login</h3>';
    echo wp_login_form( $args );
    echo '<div class="register"><a href="/register" class="button">Register</a></div>';
    printf('<div class="lost_password"><a href="%s" title="Lost Password">Lost Password</a></div>',
        wp_lostpassword_url( get_permalink() )
    ) ;


    echo '<button type="button" class="close" data-toggle="collapse" data-target="#login-bar"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></div></div>';

}

add_filter( 'genesis_nav_items', 'be_follow_icons', 10, 2 );
add_filter( 'wp_nav_menu_items', 'be_follow_icons', 10, 2 );
function be_follow_icons( $menu, $args ) {
    $args = (array) $args;
    if ( 'primary' !== $args['theme_location'] ) {
        return $menu;
    }
    if ( is_user_logged_in() ) {
        $follow = '<li class="menu-item menu-login-link" ><a href="' . wp_logout_url( get_permalink() ) . '" title="Logout">Logout</a></li>';
        $follow .= '<li class="menu-item menu-login-link" ><a href="/my-programs" title="Go to My Programs">Go to My Programs</a></li>';
    } else {
        $follow = '<li class="menu-item menu-login-link" data-toggle="collapse" data-target="#login-bar"><a href="#">Log In/Register</a></li>';
    }

    return $menu . $follow;
}


/*========================================================================
Add search button to nav
========================================================================*/
add_filter( 'wp_nav_menu_items', 'genesis_search_secondary_nav_menu', 10, 2 );

function genesis_search_secondary_nav_menu( $menu, stdClass $args ) {


    if ( 'secondary' != $args->theme_location ) {
        return $menu;
    }

    if ( genesis_get_option( 'nav_extras' ) ) {
        return $menu;
    }

    $menu .= sprintf( '<div class="secondary-search">%s</div>', __( genesis_search_form() ) );


    return $menu;

}

add_filter( 'genesis_search_button_text', 'sp_search_button_text' );
function sp_search_button_text( $text ) {
    return esc_attr( '' );
}


/*========================================================================
Display author box on single posts
========================================================================*/
add_filter( 'get_the_author_genesis_author_box_single', '__return_true' );
remove_action( 'genesis_after_entry', 'genesis_do_author_box_single', 8 );
add_action( 'genesis_entry_content', 'genesis_do_author_box_single', 10 );


/*========================================================================
Post meta
========================================================================*/
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );


//edit the way the post info displays
add_filter( 'genesis_post_info', 'sp_post_info_filter' );
function sp_post_info_filter( $post_info ) {

    if ( is_single() ) {
        $post_info = 'by [post_author_posts_link] on [post_date format="M j, Y"] [post_comments] [post_edit] [post_categories sep=", " before="Posted in: "]';

        return $post_info;
    } else {
        $post_info = 'by [post_author_posts_link] on [post_date format="M j, Y"]';

        return $post_info;
    }
}

/* Display Post Author Avatars */
function wpsites_post_author_avatars() {
    if ( is_single() ) {
        echo get_avatar( get_the_author_meta( 'email' ), 60 );
    }
}

add_action( 'genesis_entry_header', 'wpsites_post_author_avatars' );


/*========================================================================
Move featured image in archives
========================================================================*/
remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
add_action( 'genesis_entry_header', 'genesis_do_post_image', 1 );

/*========================================================================
Add new archive image size
========================================================================*/
add_image_size( 'archive', 170, 170, true );
add_image_size( 'ubermenu', 137, 137, true );
add_image_size( 'sidebar', 300, 300, true );
add_image_size( 'featured-posts', 60, 60, true );

/*========================================================================
Change default avatar
========================================================================*/
add_filter( 'avatar_defaults', 'newgravatar' );
function newgravatar( $avatar_defaults ) {
    $myavatar                     = get_stylesheet_directory_uri() . '/images/default_avatar.jpg';
    $avatar_defaults[ $myavatar ] = "Power of Moms Avatar";

    return $avatar_defaults;
}


/*========================================================================
Footer Menu
========================================================================*/
function power_of_moms_footer_menu() {
    echo '<div class="footer-menu-container">';
    $args = array(
        'theme_location'  => 'tertiary',
        'container'       => 'nav',
        'container_class' => 'wrap',
        'menu_class'      => 'menu genesis-nav-menu menu-tertiary',
        'depth'           => 1,
    );
    wp_nav_menu( $args );
    echo '</div>';
}

add_theme_support( 'genesis-menus', array( 'primary'   => 'Primary Navigation Menu',
                                           'secondary' => 'Secondary Navigation Menu',
                                           'tertiary'  => 'Footer Navigation Menu'
    ) );

add_action( 'genesis_footer', 'power_of_moms_footer_menu', 0 );


/*========================================================================
Footer text display
========================================================================*/
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'power_of_moms_footer' );
function power_of_moms_footer() {
    ?>
    <p>&copy; Copyright 2008 - <?php echo date( "Y" ) ?> <a href="https://powerofmoms.com">Power of Moms</a> &middot;
        Website by <a href="http://zachswinehart.com" target="_blank">Zach Swinehart.</a></p>
<?php
}


/*========================================================================
Homepage widget
========================================================================*/
genesis_register_sidebar( array(
    'id'          => 'home_large_featured',
    'name'        => __( 'Home Large Feature', 'powerofmoms' ),
    'description' => __( 'This is the large home section.', 'news' ),
) );
genesis_register_sidebar( array(
    'id'          => 'home_left',
    'name'        => __( 'Home Left Feature', 'powerofmoms' ),
    'description' => __( 'This is the left home featured content section.', 'news' ),
) );
genesis_register_sidebar( array(
    'id'          => 'home_middle',
    'name'        => __( 'Home Middle Feature', 'powerofmoms' ),
    'description' => __( 'This is the middle home featured content section.', 'news' ),
) );
genesis_register_sidebar( array(
    'id'          => 'home_right',
    'name'        => __( 'Home Right Feature', 'powerofmoms' ),
    'description' => __( 'This is the right home featured content section.', 'news' ),
) );


add_action( 'genesis_before_content', 'home_large_featured' );
function home_large_featured() {
    if ( is_home() ) {
        genesis_widget_area( 'home_large_featured', array(
            'before' => '<div class="home-large-featured"><div class="wrap">',
            'after'  => '</div></div>',
        ) );
    }
}

add_action( 'genesis_before_content', 'home_featured_widgets' );
function home_featured_widgets() {
    if ( is_home() ) {
        echo '<div class="home-featured-widgets"><div class="wrap"><h2 class="panel-title">What\'s New</h2>';
        genesis_widget_area( 'home_left', array(
            'before' => '<div class="home-featured-widget-1 widget-area home-featured-widget"><div class="home-featured-widget-inner">',
            'after'  => '</div></div>',
        ) );
        genesis_widget_area( 'home_middle', array(
            'before' => '<div class="home-featured-widget-2 widget-area home-featured-widget"><div class="home-featured-widget-inner">',
            'after'  => '</div></div>',
        ) );
        genesis_widget_area( 'home_right', array(
            'before' => '<div class="home-featured-widget-3 widget-area home-featured-widget"><div class="home-featured-widget-inner">',
            'after'  => '</div></div>',
        ) );
        echo '</div></div>';
    }
}


/*========================================================================
Display titles on category archives
========================================================================*/
function themeprefix_category_header() {
    if ( is_category() ) {
        echo '<h1 class="archive-title">Posts in the "';
        echo single_cat_title();
        echo '" category:</h1>';
    }
}

add_action( 'genesis_before_loop', 'themeprefix_category_header' );

function add_alternate_social_urls($content) {
     if(!is_feed() && (is_single() || is_page())) {
        global $post;
        $url = get_permalink($post->ID);
        $url_variations = array();
        // https/http
        if(strpos($url,'https://') === 0){
            $url_variations[] = str_replace('https://', 'http://', $url);
        } else {
            $url_variations[] = str_replace('http://', 'https://', $url);
        }
         $alternates = get_post_custom_values( 'essb_alternate_url', $post->ID );
         if($alternates) {
             foreach ( $alternates as $val ) {
                 $url_variations[] = $val;
             }
         }
        $toAdd='';
        foreach($url_variations as $variation) {
            $toAdd .= sprintf('<input type="hidden" class="essb_alternate_url" value="%s" />',$variation);
        }
        $content = $toAdd.$content;
    }
    return $content;
}
add_filter('the_content', 'add_alternate_social_urls',10, 1);

function get_protected_pages() {
    if(false === ($ids = get_transient('pom_protected_pages'))) {
        $ids = [ ];
        if ( function_exists( 'wlmapi_get_protected_pages' ) ) {
            $pages = wlmapi_get_protected_pages();
            $ids   = array_merge( $ids, array_map( function ( $item ) {
                return $item->ID;
            }, $pages['pages']['page'] ) );
        }
        if ( function_exists( 'wlmapi_get_protected_posts' ) ) {
            $pages = wlmapi_get_protected_posts();
            $ids   = array_merge( $ids, array_map( function ( $item ) {
                return $item->ID;
            }, $pages['posts']['post'] ) );
        }
        $ids = array_map( 'intval', $ids );
        set_transient('pom_protected_pages', $ids, HOUR_IN_SECONDS);
    }
    return $ids;
}

function hide_on_protected_pages($metadata, $object_id, $meta_key, $single) {
    if($meta_key === 'essb_off' && in_array($object_id, get_protected_pages())) {
        return 'true';
    }
}
add_filter('get_post_metadata', 'hide_on_protected_pages', 10, 4);
add_filter('get_page_metadata', 'hide_on_protected_pages', 10, 4);

