<?php
/**
 * Système de fil d'Ariane (breadcrumb)
 * AtypikHouse Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

function atypikhouse_breadcrumb() {
    // Ne pas afficher sur la page d'accueil
    if (is_front_page()) {
        return;
    }
    
    $breadcrumb = '<nav aria-label="' . __('Fil d\'Ariane', 'atypikhouse') . '" class="breadcrumb-nav">';
    $breadcrumb .= '<ol class="breadcrumb-list">';
    
    // Accueil
    $breadcrumb .= '<li class="breadcrumb-item">';
    $breadcrumb .= '<a href="' . esc_url(home_url('/')) . '">' . __('Accueil', 'atypikhouse') . '</a>';
    $breadcrumb .= '</li>';
    
    // Séparateur
    $separator = '<span class="separator" aria-hidden="true">›</span>';
    
    if (is_single()) {
        $post_type = get_post_type();
        
        // Si c'est un hébergement
        if ($post_type === 'hebergement') {
            $breadcrumb .= $separator;
            $breadcrumb .= '<li class="breadcrumb-item">';
            $breadcrumb .= '<a href="' . esc_url(get_post_type_archive_link('hebergement')) . '">' . __('Hébergements', 'atypikhouse') . '</a>';
            $breadcrumb .= '</li>';
            
            // Taxonomies
            $types = get_the_terms(get_the_ID(), 'type_hebergement');
            if ($types && !is_wp_error($types)) {
                $breadcrumb .= $separator;
                $breadcrumb .= '<li class="breadcrumb-item">';
                $breadcrumb .= '<a href="' . esc_url(get_term_link($types[0])) . '">' . esc_html($types[0]->name) . '</a>';
                $breadcrumb .= '</li>';
            }
        } elseif ($post_type === 'post') {
            // Si c'est un article de blog
            $breadcrumb .= $separator;
            $breadcrumb .= '<li class="breadcrumb-item">';
            $breadcrumb .= '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . __('Blog', 'atypikhouse') . '</a>';
            $breadcrumb .= '</li>';
            
            // Catégories
            $categories = get_the_category();
            if ($categories) {
                $breadcrumb .= $separator;
                $breadcrumb .= '<li class="breadcrumb-item">';
                $breadcrumb .= '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
                $breadcrumb .= '</li>';
            }
        }
        
        // Titre de la page courante
        $breadcrumb .= $separator;
        $breadcrumb .= '<li class="breadcrumb-item current" aria-current="page">';
        $breadcrumb .= get_the_title();
        $breadcrumb .= '</li>';
        
    } elseif (is_page()) {
        // Page statique
        global $post;
        
        // Pages parentes
        if ($post->post_parent) {
            $parent_id = $post->post_parent;
            $breadcrumbs = array();
            
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a href="' . esc_url(get_permalink($page->ID)) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id = $page->post_parent;
            }
            
            $breadcrumbs = array_reverse($breadcrumbs);
            
            foreach ($breadcrumbs as $crumb) {
                $breadcrumb .= $separator;
                $breadcrumb .= '<li class="breadcrumb-item">' . $crumb . '</li>';
            }
        }
        
        $breadcrumb .= $separator;
        $breadcrumb .= '<li class="breadcrumb-item current" aria-current="page">';
        $breadcrumb .= get_the_title();
        $breadcrumb .= '</li>';
        
    } elseif (is_post_type_archive('hebergement')) {
        // Archive des hébergements
        $breadcrumb .= $separator;
        $breadcrumb .= '<li class="breadcrumb-item current" aria-current="page">';
        $breadcrumb .= __('Hébergements', 'atypikhouse');
        $breadcrumb .= '</li>';
        
    } elseif (is_tax('type_hebergement') || is_tax('region')) {
        // Taxonomies des hébergements
        $breadcrumb .= $separator;
        $breadcrumb .= '<li class="breadcrumb-item">';
        $breadcrumb .= '<a href="' . esc_url(get_post_type_archive_link('hebergement')) . '">' . __('Hébergements', 'atypikhouse') . '</a>';
        $breadcrumb .= '</li>';
        
        $breadcrumb .= $separator;
        $breadcrumb .= '<li class="breadcrumb-item current" aria-current="page">';
        $breadcrumb .= single_term_title('', false);
        $breadcrumb .= '</li>';
        
    } elseif (is_category()) {
        // Catégorie
        $breadcrumb .= $separator;
        $breadcrumb .= '<li class="breadcrumb-item">';
        $breadcrumb .= '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . __('Blog', 'atypikhouse') . '</a>';
        $breadcrumb .= '</li>';
        
        $breadcrumb .= $separator;
        $breadcrumb .= '<li class="breadcrumb-item current" aria-current="page">';
        $breadcrumb .= single_cat_title('', false);
        $breadcrumb .= '</li>';
        
    } elseif (is_archive()) {
        // Autres archives
        $breadcrumb .= $separator;
        $breadcrumb .= '<li class="breadcrumb-item current" aria-current="page">';
        
        if (is_author()) {
            $breadcrumb .= __('Articles de ', 'atypikhouse') . get_the_author();
        } elseif (is_date()) {
            if (is_month()) {
                $breadcrumb .= get_the_date('F Y');
            } elseif (is_year()) {
                $breadcrumb .= get_the_date('Y');
            } else {
                $breadcrumb .= get_the_date();
            }
        } else {
            $breadcrumb .= get_the_archive_title();
        }
        
        $breadcrumb .= '</li>';
        
    } elseif (is_search()) {
        // Résultats de recherche
        $breadcrumb .= $separator;
        $breadcrumb .= '<li class="breadcrumb-item current" aria-current="page">';
        $breadcrumb .= sprintf(__('Résultats pour : %s', 'atypikhouse'), get_search_query());
        $breadcrumb .= '</li>';
        
    } elseif (is_404()) {
        // Page 404
        $breadcrumb .= $separator;
        $breadcrumb .= '<li class="breadcrumb-item current" aria-current="page">';
        $breadcrumb .= __('Page non trouvée', 'atypikhouse');
        $breadcrumb .= '</li>';
    }
    
    $breadcrumb .= '</ol>';
    $breadcrumb .= '</nav>';
    
    echo $breadcrumb;
}

// Styles CSS pour le breadcrumb
function atypikhouse_breadcrumb_styles() {
    ?>
    <style>
    .breadcrumb-nav {
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }
    
    .breadcrumb-list {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: 0.5rem;
    }
    
    .breadcrumb-item {
        display: flex;
        align-items: center;
    }
    
    .breadcrumb-item a {
        color: var(--couleur-principale);
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .breadcrumb-item a:hover,
    .breadcrumb-item a:focus {
        color: var(--couleur-secondaire);
        text-decoration: underline;
    }
    
    .breadcrumb-item.current {
        color: #666;
        font-weight: 500;
    }
    
    .separator {
        margin: 0 0.25rem;
        color: #999;
        font-size: 0.75rem;
    }
    
    @media (max-width: 768px) {
        .breadcrumb-list {
            font-size: 0.8rem;
        }
        
        .breadcrumb-item {
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    }
    </style>
    <?php
}
add_action('wp_head', 'atypikhouse_breadcrumb_styles');

// JSON-LD Schema pour le breadcrumb (SEO)
function atypikhouse_breadcrumb_schema() {
    if (is_front_page()) {
        return;
    }
    
    $items = array();
    $position = 1;
    
    // Accueil
    $items[] = array(
        '@type' => 'ListItem',
        'position' => $position++,
        'name' => get_bloginfo('name'),
        'item' => home_url('/')
    );
    
    if (is_single()) {
        $post_type = get_post_type();
        
        if ($post_type === 'hebergement') {
            $items[] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => __('Hébergements', 'atypikhouse'),
                'item' => get_post_type_archive_link('hebergement')
            );
            
            $types = get_the_terms(get_the_ID(), 'type_hebergement');
            if ($types && !is_wp_error($types)) {
                $items[] = array(
                    '@type' => 'ListItem',
                    'position' => $position++,
                    'name' => $types[0]->name,
                    'item' => get_term_link($types[0])
                );
            }
        }
        
        $items[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title(),
            'item' => get_permalink()
        );
    }
    
    if (!empty($items)) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items
        );
        
        echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
    }
}
add_action('wp_head', 'atypikhouse_breadcrumb_schema');
?>
