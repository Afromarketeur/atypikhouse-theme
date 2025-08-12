<?php
/**
 * Header template
 * AtypikHouse Theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link sr-only" href="#main"><?php _e('Aller au contenu principal', 'atypikhouse'); ?></a>

<header class="site-header" role="banner">
    <div class="container">
        <div class="header-content">
            <div class="site-branding">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="logo" rel="home">
                    <?php bloginfo('name'); ?>
                </a>
                <?php if (get_bloginfo('description')) : ?>
                    <p class="site-description sr-only"><?php bloginfo('description'); ?></p>
                <?php endif; ?>
            </div>

            <nav class="main-navigation" role="navigation" aria-label="<?php _e('Menu principal', 'atypikhouse'); ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'header',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'fallback_cb'    => 'atypikhouse_fallback_menu',
                ));
                ?>
            </nav>

            <div class="header-actions">
                <div class="search-form">
                    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                        <label class="sr-only" for="search-field"><?php _e('Rechercher', 'atypikhouse'); ?></label>
                        <input type="search" id="search-field" name="s" value="<?php echo get_search_query(); ?>" placeholder="<?php _e('Rechercher...', 'atypikhouse'); ?>" />
                        <button type="submit" class="btn btn-primary">
                            <span class="sr-only"><?php _e('Lancer la recherche', 'atypikhouse'); ?></span>
                            🔍
                        </button>
                    </form>
                </div>
                
                <div class="user-actions">
                    <?php if (is_user_logged_in()) : ?>
                        <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="btn btn-secondary">
                            <?php _e('Déconnexion', 'atypikhouse'); ?>
                        </a>
                    <?php else : ?>
                        <a href="<?php echo esc_url(wp_login_url()); ?>" class="btn btn-secondary">
                            <?php _e('Connexion', 'atypikhouse'); ?>
                        </a>
                        <a href="<?php echo esc_url(wp_registration_url()); ?>" class="btn btn-primary">
                            <?php _e('Inscription', 'atypikhouse'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>

<?php if (!is_front_page()) : ?>
    <div class="breadcrumb">
        <div class="container">
            <?php atypikhouse_breadcrumb(); ?>
        </div>
    </div>
<?php endif; ?>

<main id="main" class="site-main" role="main">
