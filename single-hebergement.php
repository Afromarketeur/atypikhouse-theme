<?php
/**
 * Single hebergement template
 * AtypikHouse Theme
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('hebergement-single'); ?>>
        <div class="container">
            <div class="hebergement-header">
                <div class="hebergement-title">
                    <h1><?php the_title(); ?></h1>
                    
                    <div class="hebergement-meta">
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
                            <span class="hebergement-location">
                                📍 <?php echo esc_html($regions[0]->name); ?>
                            </span>
                        <?php endif; ?>

                        <?php
                        $coordonnees = get_post_meta(get_the_ID(), '_coordonnees', true);
                        if ($coordonnees) :
                        ?>
                            <span class="hebergement-address">
                                <?php echo esc_html($coordonnees); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="hebergement-price-block">
                    <div class="price-main">
                        <?php echo atypikhouse_get_hebergement_price(get_the_ID()); ?>
                        <span class="price-unit"><?php _e('/nuit', 'atypikhouse'); ?></span>
                    </div>
                    
                    <?php
                    $capacite = atypikhouse_get_hebergement_capacity(get_the_ID());
                    if ($capacite) :
                    ?>
                        <div class="capacity">
                            👥 <?php echo $capacite; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="hebergement-content">
                <div class="content-main">
                    <!-- Galerie d'images -->
                    <div class="hebergement-gallery">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="main-image">
                                <img src="<?php the_post_thumbnail_url('hebergement-gallery'); ?>" 
                                     alt="<?php the_title_attribute(); ?>"
                                     loading="lazy" />
                            </div>
                        <?php endif; ?>

                        <?php
                        $gallery_images = get_post_meta(get_the_ID(), '_gallery_images', true);
                        if ($gallery_images && is_array($gallery_images)) :
                        ?>
                            <div class="gallery-thumbnails">
                                <?php foreach ($gallery_images as $image_id) : ?>
                                    <img src="<?php echo wp_get_attachment_image_url($image_id, 'thumbnail'); ?>" 
                                         alt="<?php echo get_post_meta($image_id, '_wp_attachment_image_alt', true); ?>"
                                         loading="lazy" />
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Description -->
                    <div class="hebergement-description">
                        <h2><?php _e('Description', 'atypikhouse'); ?></h2>
                        <div class="content">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <!-- Équipements -->
                    <?php
                    $equipements = atypikhouse_get_hebergement_equipments(get_the_ID());
                    if ($equipements) :
                    ?>
                        <div class="hebergement-equipements">
                            <h2><?php _e('Équipements et services', 'atypikhouse'); ?></h2>
                            <div class="equipements-list">
                                <?php
                                $equipements_array = explode("\n", $equipements);
                                foreach ($equipements_array as $equipement) :
                                    if (trim($equipement)) :
                                ?>
                                    <span class="equipement-item">
                                        ✓ <?php echo esc_html(trim($equipement)); ?>
                                    </span>
                                <?php
                                    endif;
                                endforeach;
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Calendrier de disponibilité -->
                    <div class="hebergement-availability">
                        <h2><?php _e('Disponibilités', 'atypikhouse'); ?></h2>
                        <div class="availability-calendar">
                            <p><em><?php _e('Calendrier de disponibilité à venir - Fonction en cours de développement', 'atypikhouse'); ?></em></p>
                            <!-- Ici sera intégré le calendrier de disponibilité -->
                        </div>
                    </div>
                </div>

                <!-- Sidebar avec réservation -->
                <div class="content-sidebar">
                    <div class="reservation-widget sticky">
                        <h3><?php _e('Réserver cet hébergement', 'atypikhouse'); ?></h3>
                        
                        <div class="booking-form">
                            <form class="reservation-form">
                                <div class="form-group">
                                    <label for="checkin"><?php _e('Arrivée', 'atypikhouse'); ?></label>
                                    <input type="date" id="checkin" name="checkin" required />
                                </div>

                                <div class="form-group">
                                    <label for="checkout"><?php _e('Départ', 'atypikhouse'); ?></label>
                                    <input type="date" id="checkout" name="checkout" required />
                                </div>

                                <div class="form-group">
                                    <label for="guests"><?php _e('Nombre de voyageurs', 'atypikhouse'); ?></label>
                                    <select id="guests" name="guests">
                                        <?php for ($i = 1; $i <= 10; $i++) : ?>
                                            <option value="<?php echo $i; ?>">
                                                <?php echo $i; ?> <?php echo $i > 1 ? __('personnes', 'atypikhouse') : __('personne', 'atypikhouse'); ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>

                                <div class="price-summary">
                                    <div class="price-calculation">
                                        <span class="nights">0 <?php _e('nuit(s)', 'atypikhouse'); ?></span>
                                        <span class="total-price">0€</span>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-full">
                                    <?php _e('Pré-réserver (simulation)', 'atypikhouse'); ?>
                                </button>

                                <p class="booking-notice">
                                    <small><?php _e('⚠️ Réservation simulée dans le cadre du projet étudiant', 'atypikhouse'); ?></small>
                                </p>
                            </form>
                        </div>

                        <!-- Contact du propriétaire -->
                        <div class="owner-contact">
                            <h4><?php _e('Contacter le propriétaire', 'atypikhouse'); ?></h4>
                            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-secondary btn-full">
                                <?php _e('Envoyer un message', 'atypikhouse'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hébergements similaires -->
            <div class="similar-hebergements">
                <h2><?php _e('Hébergements similaires', 'atypikhouse'); ?></h2>
                
                <?php
                $current_types = wp_get_post_terms(get_the_ID(), 'type_hebergement', array('fields' => 'ids'));
                
                if (!empty($current_types)) :
                    $similar_posts = get_posts(array(
                        'post_type' => 'hebergement',
                        'posts_per_page' => 3,
                        'post__not_in' => array(get_the_ID()),
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'type_hebergement',
                                'field' => 'term_id',
                                'terms' => $current_types,
                            ),
                        ),
                    ));

                    if ($similar_posts) :
                ?>
                        <div class="similar-grid grid grid-3">
                            <?php foreach ($similar_posts as $post) : setup_postdata($post); ?>
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
                                            <div class="card-price">
                                                <?php echo atypikhouse_get_hebergement_price(get_the_ID()); ?>
                                            </div>
                                        </div>
                                    </a>
                                </article>
                            <?php endforeach; ?>
                        </div>
                <?php
                        wp_reset_postdata();
                    endif;
                endif;
                ?>
            </div>
        </div>
    </article>
<?php endwhile; ?>

<?php get_footer(); ?>

