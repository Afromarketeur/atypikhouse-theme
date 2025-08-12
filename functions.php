<?php
/**
 * Functions AtypikHouse Theme
 * Projet étudiant DSP - Thème from scratch
 */

if (!defined('ABSPATH')) {
    exit;
}

// === SETUP DU THÈME === //
function atypikhouse_setup() {
    // Support des images à la une
    add_theme_support('post-thumbnails');
    
    // Support des titres automatiques
    add_theme_support('title-tag');
    
    // Support des menus
    add_theme_support('menus');
    
    // Support HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Enregistrer les menus
    register_nav_menus(array(
        'header' => 'Menu Principal',
        'footer' => 'Menu Footer',
    ));
    
    // Tailles d'images personnalisées
    add_image_size('hebergement-card', 400, 250, true);
    add_image_size('hebergement-gallery', 800, 600, true);
}
add_action('after_setup_theme', 'atypikhouse_setup');

// === ENQUEUE STYLES ET SCRIPTS === //
function atypikhouse_scripts() {
    // Google Fonts
    wp_enqueue_style(
        'atypikhouse-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@300;400;500;600&display=swap',
        array(),
        null
    );
    
    // Style principal
    wp_enqueue_style(
        'atypikhouse-style',
        get_stylesheet_uri(),
        array('atypikhouse-fonts'),
        '1.0.0'
    );
    
    // Style responsive
    wp_enqueue_style(
        'atypikhouse-responsive',
        get_template_directory_uri() . '/assets/css/responsive.css',
        array('atypikhouse-style'),
        '1.0.0'
    );
    
    // Scripts JavaScript
    wp_enqueue_script(
        'atypikhouse-main',
        get_template_directory_uri() . '/js/main.js',
        array('jquery'),
        '1.0.0',
        true
    );
    
    wp_enqueue_script(
        'atypikhouse-accessibility',
        get_template_directory_uri() . '/js/accessibility.js',
        array('jquery'),
        '1.0.0',
        true
    );
    
    // Localisation pour AJAX
    wp_localize_script('atypikhouse-main', 'atypikhouse_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('atypikhouse_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'atypikhouse_scripts');

// === CUSTOM POST TYPE HÉBERGEMENT === //
function atypikhouse_register_cpt() {
    $labels = array(
        'name' => 'Hébergements',
        'singular_name' => 'Hébergement',
        'menu_name' => 'Hébergements',
        'add_new' => 'Ajouter un hébergement',
        'add_new_item' => 'Ajouter un nouvel hébergement',
        'edit_item' => 'Modifier l\'hébergement',
        'new_item' => 'Nouvel hébergement',
        'view_item' => 'Voir l\'hébergement',
        'search_items' => 'Rechercher des hébergements',
        'not_found' => 'Aucun hébergement trouvé',
        'not_found_in_trash' => 'Aucun hébergement dans la corbeille',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'hebergements'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-admin-home',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest' => true,
    );

    register_post_type('hebergement', $args);
}
add_action('init', 'atypikhouse_register_cpt');

// === TAXONOMIES === //
function atypikhouse_register_taxonomies() {
    // Taxonomie Type d'hébergement
    register_taxonomy('type_hebergement', 'hebergement', array(
        'labels' => array(
            'name' => 'Types d\'hébergement',
            'singular_name' => 'Type d\'hébergement',
            'search_items' => 'Rechercher des types',
            'all_items' => 'Tous les types',
            'edit_item' => 'Modifier le type',
            'update_item' => 'Mettre à jour le type',
            'add_new_item' => 'Ajouter un nouveau type',
            'new_item_name' => 'Nouveau nom de type',
            'menu_name' => 'Types',
        ),
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'type'),
        'show_in_rest' => true,
    ));

    // Taxonomie Région
    register_taxonomy('region', 'hebergement', array(
        'labels' => array(
            'name' => 'Régions',
            'singular_name' => 'Région',
            'search_items' => 'Rechercher des régions',
            'all_items' => 'Toutes les régions',
            'edit_item' => 'Modifier la région',
            'update_item' => 'Mettre à jour la région',
            'add_new_item' => 'Ajouter une nouvelle région',
            'new_item_name' => 'Nouveau nom de région',
            'menu_name' => 'Régions',
        ),
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'region'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'atypikhouse_register_taxonomies');

// === META BOXES POUR HÉBERGEMENTS === //
function atypikhouse_add_meta_boxes() {
    add_meta_box(
        'hebergement_details',
        'Détails de l\'hébergement',
        'atypikhouse_hebergement_meta_box',
        'hebergement',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'atypikhouse_add_meta_boxes');

function atypikhouse_hebergement_meta_box($post) {
    wp_nonce_field('atypikhouse_save_meta', 'atypikhouse_meta_nonce');
    
    $prix = get_post_meta($post->ID, '_prix', true);
    $capacite = get_post_meta($post->ID, '_capacite', true);
    $equipements = get_post_meta($post->ID, '_equipements', true);
    $coordonnees = get_post_meta($post->ID, '_coordonnees', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="prix">Prix par nuit (€)</label></th>
            <td><input type="number" id="prix" name="prix" value="<?php echo esc_attr($prix); ?>" step="0.01" /></td>
        </tr>
        <tr>
            <th><label for="capacite">Capacité (nombre de personnes)</label></th>
            <td><input type="number" id="capacite" name="capacite" value="<?php echo esc_attr($capacite); ?>" /></td>
        </tr>
        <tr>
            <th><label for="equipements">Équipements</label></th>
            <td><textarea id="equipements" name="equipements" rows="3" cols="50"><?php echo esc_textarea($equipements); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="coordonnees">Coordonnées/Adresse</label></th>
            <td><textarea id="coordonnees" name="coordonnees" rows="2" cols="50"><?php echo esc_textarea($coordonnees); ?></textarea></td>
        </tr>
    </table>
    <?php
}

function atypikhouse_save_meta($post_id) {
    if (!isset($_POST['atypikhouse_meta_nonce']) || !wp_verify_nonce($_POST['atypikhouse_meta_nonce'], 'atypikhouse_save_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array('prix', 'capacite', 'equipements', 'coordonnees');
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'atypikhouse_save_meta');

// === INCLUDES === //
require_once get_template_directory() . '/inc/theme-options.php';
require_once get_template_directory() . '/inc/breadcrumb.php';
require_once get_template_directory() . '/inc/rgpd-helpers.php';
require_once get_template_directory() . '/inc/accessibility-helpers.php';

// === FILTRES POUR RECHERCHE === //
function atypikhouse_modify_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_home() && isset($_GET['s']) && !empty($_GET['s'])) {
            $query->set('post_type', array('post', 'hebergement'));
        }
        
        if (is_post_type_archive('hebergement') || is_tax(array('type_hebergement', 'region'))) {
            // Filtres par prix
            if (isset($_GET['prix_min']) && !empty($_GET['prix_min'])) {
                $meta_query[] = array(
                    'key' => '_prix',
                    'value' => floatval($_GET['prix_min']),
                    'compare' => '>='
                );
            }
            
            if (isset($_GET['prix_max']) && !empty($_GET['prix_max'])) {
                $meta_query[] = array(
                    'key' => '_prix',
                    'value' => floatval($_GET['prix_max']),
                    'compare' => '<='
                );
            }
            
            if (isset($meta_query)) {
                $query->set('meta_query', $meta_query);
            }
            
            // Filtre par capacité
            if (isset($_GET['capacite']) && !empty($_GET['capacite'])) {
                $query->set('meta_key', '_capacite');
                $query->set('meta_value', intval($_GET['capacite']));
                $query->set('meta_compare', '>=');
            }
        }
    }
}
add_action('pre_get_posts', 'atypikhouse_modify_query');

// === HELPER FUNCTIONS === //
function atypikhouse_get_hebergement_price($post_id) {
    $prix = get_post_meta($post_id, '_prix', true);
    return $prix ? number_format($prix, 2, ',', ' ') . '€' : 'Prix sur demande';
}

function atypikhouse_get_hebergement_capacity($post_id) {
    $capacite = get_post_meta($post_id, '_capacite', true);
    return $capacite ? $capacite . ' personne' . ($capacite > 1 ? 's' : '') : '';
}

function atypikhouse_get_hebergement_equipments($post_id) {
    $equipements = get_post_meta($post_id, '_equipements', true);
    return $equipements ? $equipements : '';
}

// === SÉCURITÉ === //
function atypikhouse_remove_version() {
    return '';
}
add_filter('the_generator', 'atypikhouse_remove_version');

// Désactiver l'édition de fichiers depuis l'admin
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}
?>
