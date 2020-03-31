<?php

//OBLIGATOIRE : Récupère les variables globales de Wordpress
$context = Timber::get_context();

// on récupère le contenu du post actuel grâce à TimberPost
$post = new TimberPost();

// on ajoute la variable post (qui contient le post) à la variable
// qu'on enverra à la vue twig
$context['post'] = $post;



// tableau d'arguments pour modifier la requête en base
// de données, et venir récupérer uniquement les trois
// derniers articles



$args_reals = [
    'post_type' => 'reals',
    
    // 'meta_key' => 'note',
    // 'orderby' => [

    //     'note' => 'DESC'
    // ],
    'posts_per_page' => 5
];
$args_prestas = [
    'post_type' => 'prestas',
    'posts_per_page' => 5
];
// $args_temperas = [
//     'post_type' => 'toiles',
//     'meta_query' => [
//         'relation' => 'AND',
//         [
//             'key' => 'type',
//             'value' => 'temperas',
//             'compare' => 'LIKE'
//         ],
//         [
//             'key' => 'format',
//             'value' => 'oeuvre',
//             'compare' => 'LIKE'
//         ],

//     ],
//     'meta_key' => 'note',
//     'orderby' => [

//         'note' => 'DESC'
//     ],
//     'posts_per_page' => 5
// ];
// $args_carnets = [
//     'post_type' => 'toiles',
//     'meta_query' => [
//         'relation' => 'AND',

//         [
//             'key' => 'format',
//             'value' => 'carnet/reperage',
//             'compare' => 'LIKE'
//         ],

//     ],
//     'meta_key' => 'note',
//     'orderby' => [

//         'note' => 'DESC'
//     ],
//     'posts_per_page' => 5
// ];

// $args_expos = [
//     'post_type' => 'expos',
//     'meta_key' => 'debut',
//     'order' => 'ASC',
//     'orderby' => 'debut',
//     'posts_per_page' => 10
// ];
// $args_ateliers = [
//     'post_type' => 'ateliers',
//     'order' => 'ASC',
//     'orderby' => 'debut',
//     'posts_per_page' => 100
// ];
// $args_stages = [
//     'post_type' => 'stages',
//     'order' => 'ASC',
//     'orderby' => 'debut',
//     'posts_per_page' => 100
// ];
// $args_contacts = [
//     'post_type' => 'contacts',
// ];
// $args_actus = [
//     'post_type' => 'actus',
//     'orderby' => 'date',
//     'order' => 'DESC',
//     'posts_per_page' => 4
// ];

//// récupère les articles en fonction du tableau d'argument $args_posts
//// en utilisant la méthode de Timber get_posts
//// puis on les enregistre dans l'array $context sous la clé "posts"

$context['reals'] = Timber::get_posts($args_reals);
$context['prestas'] = Timber::get_posts($args_prestas);
$context['url'] = $_SERVER["REQUEST_URI"];


// appelle la vue twig "page-7.twig" située dans le dossier views
// en lui passant la variable $context qui contient notamment ici les articles
Timber::render('pages/page-6.twig', $context);
