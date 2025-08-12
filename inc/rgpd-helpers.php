<?php
/**
 * Helpers RGPD pour AtypikHouse
 * Gestion des cookies et du consentement
 */

if (!defined('ABSPATH')) {
    exit;
}

// Bannière de cookies
function atypikhouse_cookie_banner() {
    $enable_banner = atypikhouse_get_option('enable_cookies_banner', true);
    
    if (!$enable_banner) {
        return;
    }
    ?>
    <div id="cookie-banner" class="cookie-banner" style="display: none;" role="dialog" aria-labelledby="cookie-title" aria-describedby="cookie-description">
        <div class="cookie-content">
            <h3 id="cookie-title"><?php _e('Cookies et confidentialité', 'atypikhouse'); ?></h3>
            <p id="cookie-description">
                <?php _e('Nous utilisons des cookies pour améliorer votre expérience sur notre site. En continuant à naviguer, vous acceptez notre utilisation des cookies.', 'atypikhouse'); ?>
                <a href="<?php echo esc_url(home_url('/politique-confidentialite')); ?>" target="_blank" rel="noopener">
                    <?php _e('En savoir plus', 'atypikhouse'); ?>
                </a>
            </p>
            <div class="cookie-actions">
                <button type="button" id="accept-all-cookies" class="btn btn-primary">
                    <?php _e('Tout accepter', 'atypikhouse'); ?>
                </button>
                <button type="button" id="customize-cookies" class="btn btn-secondary">
                    <?php _e('Personnaliser', 'atypikhouse'); ?>
                </button>
                <button type="button" id="refuse-cookies" class="btn btn-secondary">
                    <?php _e('Refuser', 'atypikhouse'); ?>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de personnalisation -->
    <div id="cookie-modal" class="cookie-modal" style="display: none;" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="cookie-modal-content">
            <div class="cookie-modal-header">
                <h3 id="modal-title"><?php _e('Gestion des cookies', 'atypikhouse'); ?></h3>
                <button type="button" id="close-modal" class="close-modal" aria-label="<?php _e('Fermer', 'atypikhouse'); ?>">×</button>
            </div>
            <div class="cookie-modal-body">
                <div class="cookie-category">
                    <div class="cookie-category-header">
                        <h4><?php _e('Cookies nécessaires', 'atypikhouse'); ?></h4>
                        <span class="required"><?php _e('Toujours activé', 'atypikhouse'); ?></span>
                    </div>
                    <p><?php _e('Ces cookies sont indispensables au fonctionnement du site et ne peuvent pas être désactivés.', 'atypikhouse'); ?></p>
                </div>
                
                <div class="cookie-category">
                    <div class="cookie-category-header">
                        <h4><?php _e('Cookies analytiques', 'atypikhouse'); ?></h4>
                        <label class="cookie-toggle">
                            <input type="checkbox" id="analytics-cookies" />
                            <span class="slider"></span>
                        </label>
                    </div>
                    <p><?php _e('Ces cookies nous aident à comprendre comment les visiteurs utilisent notre site.', 'atypikhouse'); ?></p>
                </div>
                
                <div class="cookie-category">
                    <div class="cookie-category-header">
                        <h4><?php _e('Cookies marketing', 'atypikhouse'); ?></h4>
                        <label class="cookie-toggle">
                            <input type="checkbox" id="marketing-cookies" />
                            <span class="slider"></span>
                        </label>
                    </div>
                    <p><?php _e('Ces cookies sont utilisés pour vous proposer des publicités pertinentes.', 'atypikhouse'); ?></p>
                </div>
            </div>
            <div class="cookie-modal-footer">
                <button type="button" id="save-preferences" class="btn btn-primary">
                    <?php _e('Enregistrer mes préférences', 'atypikhouse'); ?>
                </button>
            </div>
        </div>
    </div>

    <style>
    .cookie-banner {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: var(--couleur-secondaire);
        color: var(--couleur-accent);
        padding: 1.5rem;
        z-index: 9999;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.3);
    }
    
    .cookie-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
    }
    
    .cookie-content h3 {
        margin: 0;
        font-size: 1.2rem;
    }
    
    .cookie-content p {
        flex: 1;
        margin: 0;
        min-width: 300px;
    }
    
    .cookie-content a {
        color: var(--couleur-principale);
        text-decoration: underline;
    }
    
    .cookie-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .cookie-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0
