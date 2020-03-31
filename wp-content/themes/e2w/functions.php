<?php

/**

 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($composer_autoload)) {
    require_once($composer_autoload);
    $timber = new Timber\Timber();
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if (!class_exists('Timber')) {

    add_action('admin_notices', function () {
        echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
    });

    add_filter('template_include', function ($template) {
        return get_stylesheet_directory() . '/static/no-timber.html';
    });
    return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array('templates', 'views');

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;




function dr_adding_styles()
{
    wp_enqueue_style('style', get_template_directory_uri() . '/style.css');
}

add_action('wp_enqueue_scripts', 'js_adding_styles');

add_theme_support('post-thumbnails');

register_nav_menus(array(
    'header-menu' => 'Header-menu',
    'footer-menu' => 'Footer-menu'

));


// fonction pour créer des variables globales, accessibles dans tous les fichiers twig
function add_to_context($data)
{

    // on appelle une instance de TimberMenu avec en parametre le menu qu'on veut récupérer
    //$data['menu'] = new TimberMenu('header-menu');
    $data['menu'] = new \Timber\Menu('header-menu');

    return $data;
}
add_action('init', 'init_remove_support', 100);
function init_remove_support()
{
    $post_type = 'page';

    remove_post_type_support($post_type, 'editor');
}



// On ajoute le résultat de notre fonction au context de twig (contexte globale)
add_filter('timber/context', 'add_to_context');

add_filter('acf/settings/remove_wp_meta_box', '__return_false');


function remove_menus()
{

    // remove_menu_page('index.php');                  //Dashboard
    // remove_menu_page('edit.php');                   //Posts
    //    remove_menu_page( 'upload.php' );                 //Media
    //    remove_menu_page( 'edit.php?post_type=page' );    //Pages
    remove_menu_page('edit-comments.php');          //Comments
    //    remove_menu_page( 'themes.php' );                 //Appearance
    //    remove_menu_page( 'plugins.php' );                //Plugins
    //    remove_menu_page( 'users.php' );                  //Users
    //    remove_menu_page( 'tools.php' );                  //Tools
    //    remove_menu_page( 'options-general.php' );        //Settings

}
add_action('admin_menu', 'remove_menus');

// déclaration des custom post types

function fabulle_register_post_types()
{


    // CPT realisations
    $labels = array(
        'name' => 'realisations',
        'all_items' => 'Toutes les réalisations',  // affiché dans le sous menu
        'singular_name' => 'realisation',
        'add_new_item' => 'Nouveau',
        'edit_item' => 'Modifier le projet',
        'menu_name' => 'realisations'
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => true,
        'has_archive' => true,
        'rewrite'     => array('slug' => 'realisations'),
        'supports' => array('title','editor', 'author', 'thumbnail', 'excerpt'),
        'menu_position' => 1,
        'menu_icon' => 'dashicons-edit',
    );
    register_post_type('realisations', $args);

    // CPT prestations
    $labels = array(
        'name' => 'prestations',
        'all_items' => 'Toutes les prestations',  // affiché dans le sous menu
        'singular_name' => 'prestation',
        'add_new_item' => 'Nouvelle prestation',
        'edit_item' => 'Modifier la prestation',
        'menu_name' => 'prestations'
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => true,
        'has_archive' => true,
        'rewrite'     => array('slug' => 'prestations'),
        'supports' => array('title','editor','author','thumbnail','excerpt'),
        'menu_position' => 2,
        'menu-icon' => 'dashicons-admin-post'
    );
    register_post_type('prestations', $args);

   
}



add_action('init', 'fabulle_register_post_types'); // Le hook init lance la fonction
function custom_menu_order($menu_ord)
{
    if (!$menu_ord) return true;
    return array(
        'index.php', // this represents the dashboard link
        
        'edit.php?post_type=realisations', // this is a custom post type menu
        'edit.php?post_type=prestations',
        'edit.php', // this is the default POST admin menu
        'edit.php?post_type=page', // this is the default page menu

    );
}
add_filter('custom_menu_order', 'custom_menu_order');
add_filter('menu_order', 'custom_menu_order');
add_filter('timber/twig', function ($twig) {
    $twig->addExtension(new Twig_Extension_StringLoader());

    $twig->addFilter(
        new Twig_SimpleFilter(
            'highlight',

            function ($text, array $terms) {

                $highlight = array();
                foreach ($terms as $term) {
                    $highlight[] = '<span class="highlight">' . $term . '</span>';
                }

                return str_ireplace($terms, $highlight, $text);
            }

        )
    );

    return $twig;
});

function wpc_excerpt_pages()
{
    add_meta_box('postexcerpt', __('Extrait'), 'post_excerpt_meta_box', 'page', 'normal', 'core');
}
add_action('admin_menu', 'wpc_excerpt_pages');
