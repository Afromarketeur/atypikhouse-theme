<?php
/**
 * Index template
 * AtypikHouse Theme
 */
get_header(); ?>

<div class="container">
    <?php if (have_posts()) : ?>
        <h1><?php _e('Bienvenue sur AtypikHouse', 'atypikhouse'); ?></h1>
        <p><?php _e('Découvrez nos hébergements insolites pour des séjours inoubliables.', 'atypikhouse'); ?></p>
        
        <div class="grid grid-3">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>
                    <a href="<?php the_permalink(); ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <img src="<?php the_post_thumbnail_url('medium'); ?>" 
                                 alt="<?php the_title_attribute(); ?>"
                                 class="card-image" />
                        <?php endif; ?>
                        
                        <div class="card-content">
                            <h2 class="card-title"><?php the_title(); ?></h2>
                            <div class="card-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                    </a>
                </article>
            <?php endwhile; ?>
        </div>
        
        <div class="section-cta">
            <a href="<?php echo esc_url(get_post_type_archive_link('hebergement')); ?>" class="btn btn-primary">
                <?php _e('Voir tous les hébergements', 'atypikhouse'); ?>
            </a>
        </div>
        
    <?php else : ?>
        <h1><?php _e('Aucun contenu trouvé', 'atypikhouse'); ?></h1>
        <p><?php _e('Il semble qu\'il n\'y ait pas encore de contenu ici.', 'atypikhouse'); ?></p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
