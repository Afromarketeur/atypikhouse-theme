<?php
/**
 * Footer template
 * AtypikHouse Theme
 */
?>
</main><!-- #main -->

<footer class="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3><?php _e('AtypikHouse', 'atypikhouse'); ?></h3>
                <p><?php _e('Découvrez des hébergements insolites pour des séjours inoubliables dans toute la France.', 'atypikhouse'); ?></p>
                <div class="social-links">
                    <a href="#" aria-label="<?php _e('Facebook', 'atypikhouse'); ?>">📘</a>
                    <a href="#" aria-label="<?php _e('Instagram', 'atypikhouse'); ?>">📷</a>
                    <a href="#" aria-label="<?php _e('Twitter', 'atypikhouse'); ?>">🐦</a>
                </div>
            </div>

            <div class="footer-section">
                <h3><?php _e('Navigation', 'atypikhouse'); ?></h3>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'container'      => false,
                    'fallback_cb'    => false,
                ));
                ?>
            </div>

            <div class="footer-section">
                <h3><?php _e('Hébergements', 'atypikhouse'); ?></h3>
                <ul>
                    <?php
                    $terms = get_terms(array(
                        'taxonomy' => 'type_hebergement',
                        'hide_empty' => true,
                        'number' => 5,
                    ));
                    
                    if (!is_wp_error($terms) && !empty($terms)) :
                        foreach ($terms as $term) :
                    ?>
                        <li>
                            <a href="<?php echo esc_url(get_term_link($term)); ?>">
                                <?php echo esc_html($term->name); ?>
                            </a>
                        </li>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </ul>
            </div>

            <div class="footer-section">
                <h3><?php _e('Newsletter', 'atypikhouse'); ?></h3>
                <p><?php _e('Recevez nos meilleures offres et actualités.', 'atypikhouse'); ?></p>
                <form class="newsletter-form" method="post" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
                    <div class="form-group">
                        <label class="sr-only" for="newsletter-email"><?php _e('Adresse email', 'atypikhouse'); ?></label>
                        <input type="email" id="newsletter-email" name="email" placeholder="<?php _e('Votre email', 'atypikhouse'); ?>" required />
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <?php _e('S\'inscrire', 'atypikhouse'); ?>
                    </button>
                    <input type="hidden" name="action" value="newsletter_signup" />
                    <?php wp_nonce_field('newsletter_nonce', 'newsletter_nonce'); ?>
                </form>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('Tous droits réservés.', 'atypikhouse'); ?></p>
            
            <div class="legal-links">
                <a href="<?php echo esc_url(home_url('/mentions-legales')); ?>">
                    <?php _e('Mentions légales', 'atypikhouse'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/cgu')); ?>">
                    <?php _e('CGU', 'atypikhouse'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/cgv')); ?>">
                    <?php _e('CGV', 'atypikhouse'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/politique-confidentialite')); ?>">
                    <?php _e('Politique de confidentialité', 'atypikhouse'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/contact')); ?>">
                    <?php _e('Contact', 'atypikhouse'); ?>
                </a>
            </div>
            
            <div class="projet-etudiant">
                <strong><?php _e('⚠️ PROJET ÉTUDIANT FICTIF', 'atypikhouse'); ?></strong><br>
                <?php _e('Ce site est réalisé dans le cadre d\'un projet pédagogique. Aucune réservation ou achat réel n\'est possible.', 'atypikhouse'); ?>
            </div>
        </div>
    </div>
</footer>

<?php atypikhouse_cookie_banner(); ?>

<?php wp_footer(); ?>

</body>
</html>

<?php
// Menu de fallback si aucun menu n'est défini
function atypikhouse_fallback_menu() {
    echo '<ul id="primary-menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">' . __('Accueil', 'atypikhouse') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/hebergements')) . '">' . __('Hébergements', 'atypikhouse') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/concept')) . '">' . __('Concept', 'atypikhouse') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/comment-ca-marche')) . '">' . __('Comment ça marche', 'atypikhouse') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/contact')) . '">' . __('Contact', 'atypikhouse') . '</a></li>';
    echo '</ul>';
}
?>
