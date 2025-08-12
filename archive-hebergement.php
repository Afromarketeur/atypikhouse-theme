<?php
/**
 * Archive template for hebergement post type
 * AtypikHouse Theme
 */

get_header(); ?>

<div class="container">
    <div class="archive-header">
        <h1><?php _e('Nos hébergements insolites', 'atypikhouse'); ?></h1>
        <?php if (is_tax()) : ?>
            <p><?php echo get_the_archive_description(); ?></p>
        <?php endif; ?>
    </div>

    <!-- Filtres de recherche -->
    <div class="filters">
        <form method="get" class="filters-form">
            <div class="filters-grid">
                <div class="form-group">
                    <label for="filter-type"><?php _e('Type d\'hébergement', 'atypikhouse'); ?></label>
                    <select id="filter-type" name="type_hebergement">
                        <option value=""><?php _e('Tous les types', 'atypikhouse'); ?></option>
                        <?php
                        $types = get_terms(array(
                            'taxonomy' => 'type_hebergement',
                            'hide_empty' => true,
                        ));
                        
                        if (!is_wp_error($types)) :
                            foreach ($types as $type) :
                        ?>
                            <option value="<?php echo esc_attr($type->slug); ?>" <?php selected(get_query_var('type_hebergement'), $type->slug); ?>>
                                <?php echo esc_html($type->name); ?>
                            </option>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="filter-region"><?php _e('Région', 'atypikhouse'); ?></label>
                    <select id="filter-region" name="region">
                        <option value=""><?php _e('Toutes les régions', 'atypikhouse'); ?></option>
                        <?php
                        $regions = get_terms(array(
                            'taxonomy' => 'region',
                            'hide_empty' => true,
                        ));
                        
                        if (!is_wp_error($regions)) :
                            foreach ($regions as $region) :
                        ?>
                            <option value="<?php echo esc_attr($region->slug); ?>" <?php selected(get_query_var('region'), $region->slug); ?>>
                                <?php echo esc_html($region->name); ?>
                            </option>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="filter-prix-min"><?php _e('Prix minimum (€)', 'atypikhouse'); ?></label>
                    <input type="number" id="filter-prix-min" name="prix_min" value="<?php echo esc_attr(get_query_var('prix_min')); ?>" min="0" step="10" />
                </div>

                <div class="form-group">
                    <label for="filter-prix-max"><?php _e('Prix maximum (€)', 'atypikhouse'); ?></label>
                    <input type="number" id="filter-prix-max" name="prix_max" value="<?php echo esc_attr(get_query_var('prix_max')); ?>" min="0" step="10" />
                </div>

                <div class="form-group">
                    <label for="filter-capacite"><?php _e('Capacité minimum', 'atypikhouse'); ?></label>
                    <select id="filter-capacite" name="capacite">
                        <option value=""><?php _e('Toutes capacités', 'atypikhouse'); ?></option>
                        <option value="2" <?php selected(get_query_var('capacite'), '2'); ?>><?php _e('2 personnes', 'atypikhouse'); ?></option>
                        <option value="4" <?php selected(get_query_var('capacite'), '4'); ?>><?php _e('4 personnes', 'atypikhouse'); ?></option>
                        <option value="6" <?php selected(get_query_var('capacite'), '6'); ?>><?php _e('6 personnes', 'atypikhouse'); ?></option>
                        <option value="8" <?php selected(get_query_var('capacite'), '8'); ?>><?php _e('8 personnes+', 'atypikhouse'); ?></option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <?php _e('Filtrer', 'atypikhouse'); ?>
                    </button>
                    <a href="<?php echo esc_url(get_post_type_archive_link('hebergement')); ?>" class="btn btn-secondary">
                        <?php _e('Réinitialiser', 'atypikhouse'); ?>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Résultats -->
    <div class="archive-content">
        <?php if (have_posts()) : ?>
            <div class="results-count">
                <p><?php echo sprintf(__('%d hébergement(s) trouvé(s)', 'atypikhouse'), $wp_query->found_posts); ?></p>
            </div>

            <div class="hebergements-grid grid grid-3">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('card hebergement-card'); ?>>
                        <a href="<?php the_permalink(); ?>" class="card-link">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('hebergement-card'); ?>" 
                                     alt="<?php the_title_attribute(); ?>"
                                     class="card-image"
                                     loading="lazy" />
                            <?php else : ?>
                                <div class="card-image placeholder-image">
                                    <span><?php _e('Image à venir', 'atypikhouse'); ?></span>
                                </div>
                            <?php endif; ?>

                            <div class="card-content">
                                <h2 class="card-title"><?php the_title(); ?></h2>
                                
                                <div class="card-meta">
                                    <?php
                                    $types = get_the_terms(get_the_ID(), 'type_hebergement');
                                    if ($types && !is_wp_error($types)) :
                                    ?>
                                        <span class="hebergement-type">
                                            <?php echo esc_html($types[0]->name); ?>
                                        </span>
                                    <?php endif; ?>

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
                                    <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                </div>

                                <div class="card-details">
                                    <?php
                                    $capacite = atypikhouse_get_hebergement_capacity(get_the_ID());
                                    if ($capacite) :
                                    ?>
                                        <span class="capacite">👥 <?php echo $capacite; ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="card-price">
                                    <?php echo atypikhouse_get_hebergement_price(get_the_ID()); ?>
                                    <span class="price-unit"><?php _e('/nuit', 'atypikhouse'); ?></span>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <?php
                echo paginate_links(array(
                    'prev_text' => __('‹ Précédent', 'atypikhouse'),
                    'next_text' => __('Suivant ›', 'atypikhouse'),
                ));
                ?>
            </div>

        <?php else : ?>
            <div class="no-results">
                <h2><?php _e('Aucun hébergement trouvé', 'atypikhouse'); ?></h2>
                <p><?php _e('Essayez de modifier vos critères de recherche ou', 'atypikhouse'); ?> 
                   <a href="<?php echo esc_url(get_post_type_archive_link('hebergement')); ?>">
                       <?php _e('voir tous les hébergements', 'atypikhouse'); ?>
                   </a>.
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
