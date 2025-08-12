<?php
/**
 * Template Name: Page d'accueil
 * AtypikHouse Theme
 */

get_header(); ?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title fade-in">
                <?php _e('Vivez l\'extraordinaire dans des hébergements insolites', 'atypikhouse'); ?>
            </h1>
            <p class="hero-subtitle fade-in">
                <?php _e('Découvrez une sélection unique de logements atypiques pour des séjours inoubliables partout en France', 'atypikhouse'); ?>
            </p>
            
            <!-- Barre de recherche principale -->
            <div class="hero-search fade-in">
                <form action="<?php echo esc_url(home_url('/hebergements')); ?>" method="get" class="search-form-hero">
                    <div class="search-fields">
                        <div class="search-field">
                            <label for="hero-region" class="sr-only"><?php _e('Région', 'atypikhouse'); ?></label>
                            <select id="hero-region" name="region">
                                <option value=""><?php _e('Toutes les régions', 'atypikhouse'); ?></option>
                                <?php
                                $regions = get_terms(array(
                                    'taxonomy' => 'region',
                                    'hide_empty' => true,
                                ));
                                
                                if (!is_wp_error($regions)) :
                                    foreach ($regions as $region) :
                                ?>
                                    <option value="<?php echo esc_attr($region->slug); ?>">
                                        <?php echo esc_html($region->name); ?>
                                    </option>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>

                        <div class="search-field">
                            <label for="hero-type" class="sr-only"><?php _e('Type d\'hébergement', 'atypikhouse'); ?></label>
                            <select id="hero-type" name="type_hebergement">
                                <option value=""><?php _e('Tous les types', 'atypikhouse'); ?></option>
                                <?php
                                $types = get_terms(array(
                                    'taxonomy' => 'type_hebergement',
                                    'hide_empty' => true,
                                ));
                                
                                if (!is_wp_error($types)) :
                                    foreach ($types as $type) :
                                ?>
                                    <option value="<?php echo esc_attr($type->slug); ?>">
                                        <?php echo esc_html($type->name); ?>
                                    </option>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>

                        <div class="search-field">
                            <label for="hero-guests" class="sr-only"><?php _e('Nombre de voyageurs', 'atypikhouse'); ?></label>
                            <select id="hero-guests" name="capacite">
                                <option value=""><?php _e('Voyageurs', 'atypikhouse'); ?></option>
                                <option value="2"><?php _e('2 personnes', 'atypikhouse'); ?></option>
                                <option value="4"><?php _e('4 personnes', 'atypikhouse'); ?></option>
                                <option value="6"><?php _e('6 personnes', 'atypikhouse'); ?></option>
                                <option value="8"><?php _e('8+ personnes', 'atypikhouse'); ?></option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-search">
                            <?php _e('Rechercher', 'atypikhouse'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Section Types d'hébergements -->
<section class="types-section">
    <div class="container">
        <h2 class="section-title"><?php _e('Explorez nos types d\'hébergements', 'atypikhouse'); ?></h2>
        
        <div class="types-grid grid grid-4">
            <?php
            $featured_types = get_terms(array(
                'taxonomy' => 'type_hebergement',
                'hide_empty' => true,
                'number' => 4,
            ));
            
            if (!is_wp_error($featured_types)) :
                foreach ($featured_types as $type) :
            ?>
                <div class="type-card card">
                    <a href="<?php echo esc_url(get_term_link($type)); ?>">
                        <div class="type-icon">
                            <?php
                            // Icônes basiques selon le type
                            $icon = '🏠';
                            switch (strtolower($type->name)) {
                                case 'cabane':
                                case 'cabanes':
                                    $icon = '🛖';
                                    break;
                                case 'yourte':
                                case 'yourtes':
                                    $icon = '⛺';
                                    break;
                                case 'roulotte':
                                case 'roulottes':
                                    $icon = '🚛';
                                    break;
                                case 'tipis':
                                case 'tipi':
                                    $icon = '🏕️';
                                    break;
                            }
                            echo $icon;
                            ?>
                        </div>
                        <h3 class="type-name"><?php echo esc_html($type->name); ?></h3>
                        <p class="type-count">
                            <?php echo sprintf(__('%d hébergement(s)', 'atypikhouse'), $type->count); ?>
                        </p>
                    </a>
                </div>
            <?php
                endforeach;
            endif;
            ?>
        </div>
    </div>
</section>

<!-- Section Hébergements à la une -->
<section class="featured-section">
    <div class="container">
        <h2 class="section-title"><?php _e('Nos coups de cœur', 'atypikhouse'); ?></h2>
        
        <?php
        $featured_hebergements = get_posts(array(
            'post_type' => 'hebergement',
            'posts_per_page' => 6,
            'meta_query' => array(
                array(
                    'key' => '_featured',
                    'value' => '1',
                    'compare' => '='
                )
            )
        ));
        
        if (empty($featured_hebergements)) {
            // Si aucun hébergement mis en avant, prendre les plus récents
            $featured_hebergements = get_posts(array(
                'post_type' => 'hebergement',
                'posts_per_page' => 6,
            ));
        }
        
        if ($featured_hebergements) :
        ?>
            <div class="featured-grid grid grid-3">
                <?php foreach ($featured_hebergements as $post) : setup_postdata($post); ?>
                    <article class="card hebergement-card">
                        <a href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('hebergement-card'); ?>" 
                                     alt="<?php the_title_attribute(); ?>"
                                     class="card-image"
                                     loading="lazy" />
                            <?php endif; ?>

                            <div class="card-content">
                                <h3 class="card-title"><?php the_title(); ?></h3>
                                
                                <div class="card-meta">
                                    <?php
                                    $regions = get_the_terms(get_the_ID(), 'region');
                                    if ($regions && !is_wp_error($regions)) :
                                    ?>
                                        <span class="hebergement-region">
                                            📍 <?php echo esc_html($regions[0]->name); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="card-excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), 12); ?>
                                </div>

                                <div class="card-price">
                                    <?php echo atypikhouse_get_hebergement_price(get_the_ID()); ?>
                                    <span class="price-unit"><?php _e('/nuit', 'atypikhouse'); ?></span>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>

        <div class="section-cta">
            <a href="<?php echo esc_url(get_post_type_archive_link('hebergement')); ?>" class="btn btn-primary">
                <?php _e('Voir tous les hébergements', 'atypikhouse'); ?>
            </a>
        </div>
    </div>
</section>

<!-- Section Promesse de marque -->
<section class="promise-section">
    <div class="container">
        <h2 class="section-title"><?php _e('Pourquoi choisir AtypikHouse ?', 'atypikhouse'); ?></h2>
        
        <div class="promises-grid grid grid-3">
            <div class="promise-item">
                <div class="promise-icon">🏆</div>
                <h3><?php _e('Hébergements uniques', 'atypikhouse'); ?></h3>
                <p><?php _e('Une sélection rigoureuse d\'hébergements insolites pour vivre des expériences inoubliables.', 'atypikhouse'); ?></p>
            </div>
            
            <div class="promise-item">
                <div class="promise-icon">🛡️</div>
                <h3><?php _e('Réservation sécurisée', 'atypikhouse'); ?></h3>
                <p><?php _e('Vos données sont protégées et vos réservations sont garanties par notre plateforme sécurisée.', 'atypikhouse'); ?></p>
            </div>
            
            <div class="promise-item">
                <div class="promise-icon">💬</div>
                <h3><?php _e('Support dédié', 'atypikhouse'); ?></h3>
                <p><?php _e('Notre équipe vous accompagne avant, pendant et après votre séjour pour une expérience parfaite.', 'atypikhouse'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Section Newsletter -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content">
            <h2><?php _e('Restez informé de nos dernières offres', 'atypikhouse'); ?></h2>
            <p><?php _e('Recevez en exclusivité nos nouveaux hébergements et offres spéciales.', 'atypikhouse'); ?></p>
            
            <form class="newsletter-form-hero" method="post" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
                <div class="newsletter-fields">
                    <label for="newsletter-email-hero" class="sr-only"><?php _e('Adresse email', 'atypikhouse'); ?></label>
                    <input type="email" id="newsletter-email-hero" name="email" placeholder="<?php _e('Votre adresse email', 'atypikhouse'); ?>" required />
                    <button type="submit" class="btn btn-primary">
                        <?php _e('S\'inscrire', 'atypikhouse'); ?>
                    </button>
                </div>
                <input type="hidden" name="action" value="newsletter_signup" />
                <?php wp_nonce_field('newsletter_nonce', 'newsletter_nonce'); ?>
                
                <p class="newsletter-legal">
                    <small><?php _e('En vous inscrivant, vous acceptez de recevoir nos newsletters. Vous pouvez vous désinscrire à tout moment.', 'atypikhouse'); ?></small>
                </p>
            </form>
        </div>
    </div>
</section>

<style>
/* Styles spécifiques à la page d'accueil */
.hero {
    background: linear-gradient(135deg, var(--couleur-principale) 0%, #0a5040 100%);
    color: var(--couleur-accent);
    padding: 4rem 0;
    text-align: center;
}

.hero-title {
    font-size: 3rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.hero-subtitle {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.hero-search {
    background: var(--couleur-accent);
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    max-width: 800px;
    margin: 0 auto;
}

.search-fields {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    align-items: end;
}

.search-field select {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #ddd;
    border-radius: var(--border-radius);
    font-size: 1rem;
}

.btn-search {
    grid-column: span 1;
}

.section-title {
    text-align: center;
    margin-bottom: 3rem;
    font-size: 2.5rem;
}

.types-section, .featured-section, .promise-section {
    padding: 4rem 0;
}

.type-card {
    text-align: center;
    padding: 2rem;
    transition: transform 0.3s ease;
}

.type-card:hover {
    transform: translateY(-10px);
}

.type-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.type-name {
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
}

.promise-item {
    text-align: center;
    padding: 2rem;
}

.promise-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.newsletter-section {
    background: var(--couleur-gris);
    padding: 4rem 0;
    text-align: center;
}

.newsletter-fields {
    display: flex;
    gap: 1rem;
    max-width: 400px;
    margin: 0 auto 1rem;
}

.newsletter-fields input {
    flex: 1;
    padding: 0.75rem;
    border: 2px solid #ddd;
    border-radius: var(--border-radius);
}

.section-cta {
    text-align: center;
    margin-top: 3rem;
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .search-fields {
        grid-template-columns: 1fr;
    }
    
    .newsletter-fields {
        flex-direction: column;
    }
}
</style>

<?php get_footer(); ?>
