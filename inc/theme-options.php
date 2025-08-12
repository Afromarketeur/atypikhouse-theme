<?php
/**
 * Options du thème AtypikHouse
 * Panneau d'administration pour gérer les options du thème
 */

if (!defined('ABSPATH')) {
    exit;
}

// Ajouter la page d'options du thème
function atypikhouse_add_theme_options() {
    add_theme_page(
        __('Options AtypikHouse', 'atypikhouse'),
        __('Options Thème', 'atypikhouse'),
        'manage_options',
        'atypikhouse-options',
        'atypikhouse_options_page'
    );
}
add_action('admin_menu', 'atypikhouse_add_theme_options');

// Enregistrer les paramètres
function atypikhouse_register_settings() {
    register_setting('atypikhouse_options', 'atypikhouse_options', 'atypikhouse_sanitize_options');
    
    // Section générale
    add_settings_section(
        'atypikhouse_general',
        __('Paramètres généraux', 'atypikhouse'),
        'atypikhouse_general_section_callback',
        'atypikhouse-options'
    );
    
    // Champs de la section générale
    add_settings_field(
        'hero_title',
        __('Titre principal (Hero)', 'atypikhouse'),
        'atypikhouse_hero_title_callback',
        'atypikhouse-options',
        'atypikhouse_general'
    );
    
    add_settings_field(
        'hero_subtitle',
        __('Sous-titre (Hero)', 'atypikhouse'),
        'atypikhouse_hero_subtitle_callback',
        'atypikhouse-options',
        'atypikhouse_general'
    );
    
    add_settings_field(
        'contact_email',
        __('Email de contact', 'atypikhouse'),
        'atypikhouse_contact_email_callback',
        'atypikhouse-options',
        'atypikhouse_general'
    );
    
    add_settings_field(
        'contact_phone',
        __('Téléphone de contact', 'atypikhouse'),
        'atypikhouse_contact_phone_callback',
        'atypikhouse-options',
        'atypikhouse_general'
    );
    
    // Section réseaux sociaux
    add_settings_section(
        'atypikhouse_social',
        __('Réseaux sociaux', 'atypikhouse'),
        'atypikhouse_social_section_callback',
        'atypikhouse-options'
    );
    
    add_settings_field(
        'facebook_url',
        __('URL Facebook', 'atypikhouse'),
        'atypikhouse_facebook_url_callback',
        'atypikhouse-options',
        'atypikhouse_social'
    );
    
    add_settings_field(
        'instagram_url',
        __('URL Instagram', 'atypikhouse'),
        'atypikhouse_instagram_url_callback',
        'atypikhouse-options',
        'atypikhouse_social'
    );
    
    add_settings_field(
        'twitter_url',
        __('URL Twitter', 'atypikhouse'),
        'atypikhouse_twitter_url_callback',
        'atypikhouse-options',
        'atypikhouse_social'
    );
    
    // Section Analytics
    add_settings_section(
        'atypikhouse_analytics',
        __('Analytics et tracking', 'atypikhouse'),
        'atypikhouse_analytics_section_callback',
        'atypikhouse-options'
    );
    
    add_settings_field(
        'google_analytics_id',
        __('ID Google Analytics', 'atypikhouse'),
        'atypikhouse_google_analytics_callback',
        'atypikhouse-options',
        'atypikhouse_analytics'
    );
    
    add_settings_field(
        'enable_cookies_banner',
        __('Activer la bannière cookies', 'atypikhouse'),
        'atypikhouse_cookies_banner_callback',
        'atypikhouse-options',
        'atypikhouse_analytics'
    );
}
add_action('admin_init', 'atypikhouse_register_settings');

// Callbacks des sections
function atypikhouse_general_section_callback() {
    echo '<p>' . __('Paramètres généraux de votre site AtypikHouse.', 'atypikhouse') . '</p>';
}

function atypikhouse_social_section_callback() {
    echo '<p>' . __('URLs de vos réseaux sociaux.', 'atypikhouse') . '</p>';
}

function atypikhouse_analytics_section_callback() {
    echo '<p>' . __('Configuration du tracking et des cookies.', 'atypikhouse') . '</p>';
}

// Callbacks des champs
function atypikhouse_hero_title_callback() {
    $options = get_option('atypikhouse_options');
    $value = isset($options['hero_title']) ? $options['hero_title'] : '';
    echo '<input type="text" name="atypikhouse_options[hero_title]" value="' . esc_attr($value) . '" class="regular-text" />';
}

function atypikhouse_hero_subtitle_callback() {
    $options = get_option('atypikhouse_options');
    $value = isset($options['hero_subtitle']) ? $options['hero_subtitle'] : '';
    echo '<textarea name="atypikhouse_options[hero_subtitle]" rows="3" cols="50">' . esc_textarea($value) . '</textarea>';
}

function atypikhouse_contact_email_callback() {
    $options = get_option('atypikhouse_options');
    $value = isset($options['contact_email']) ? $options['contact_email'] : '';
    echo '<input type="email" name="atypikhouse_options[contact_email]" value="' . esc_attr($value) . '" class="regular-text" />';
}

function atypikhouse_contact_phone_callback() {
    $options = get_option('atypikhouse_options');
    $value = isset($options['contact_phone']) ? $options['contact_phone'] : '';
    echo '<input type="text" name="atypikhouse_options[contact_phone]" value="' . esc_attr($value) . '" class="regular-text" />';
}

function atypikhouse_facebook_url_callback() {
    $options = get_option('atypikhouse_options');
    $value = isset($options['facebook_url']) ? $options['facebook_url'] : '';
    echo '<input type="url" name="atypikhouse_options[facebook_url]" value="' . esc_attr($value) . '" class="regular-text" />';
}

function atypikhouse_instagram_url_callback() {
    $options = get_option('atypikhouse_options');
    $value = isset($options['instagram_url']) ? $options['instagram_url'] : '';
    echo '<input type="url" name="atypikhouse_options[instagram_url]" value="' . esc_attr($value) . '" class="regular-text" />';
}

function atypikhouse_twitter_url_callback() {
    $options = get_option('atypikhouse_options');
    $value = isset($options['twitter_url']) ? $options['twitter_url'] : '';
    echo '<input type="url" name="atypikhouse_options[twitter_url]" value="' . esc_attr($value) . '" class="regular-text" />';
}

function atypikhouse_google_analytics_callback() {
    $options = get_option('atypikhouse_options');
    $value = isset($options['google_analytics_id']) ? $options['google_analytics_id'] : '';
    echo '<input type="text" name="atypikhouse_options[google_analytics_id]" value="' . esc_attr($value) . '" class="regular-text" />';
    echo '<p class="description">' . __('Format: G-XXXXXXXXXX', 'atypikhouse') . '</p>';
}

function atypikhouse_cookies_banner_callback() {
    $options = get_option('atypikhouse_options');
    $value = isset($options['enable_cookies_banner']) ? $options['enable_cookies_banner'] : false;
    echo '<input type="checkbox" name="atypikhouse_options[enable_cookies_banner]" value="1" ' . checked(1, $value, false) . ' />';
    echo '<label>' . __('Afficher la bannière de consentement aux cookies', 'atypikhouse') . '</label>';
}

// Page d'options
function atypikhouse_options_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('atypikhouse_options');
            do_settings_sections('atypikhouse-options');
            submit_button(__('Enregistrer les modifications', 'atypikhouse'));
            ?>
        </form>
    </div>
    <?php
}

// Sanitization
function atypikhouse_sanitize_options($input) {
    $sanitized = array();
    
    if (isset($input['hero_title'])) {
        $sanitized['hero_title'] = sanitize_text_field($input['hero_title']);
    }
    
    if (isset($input['hero_subtitle'])) {
        $sanitized['hero_subtitle'] = sanitize_textarea_field($input['hero_subtitle']);
    }
    
    if (isset($input['contact_email'])) {
        $sanitized['contact_email'] = sanitize_email($input['contact_email']);
    }
    
    if (isset($input['contact_phone'])) {
        $sanitized['contact_phone'] = sanitize_text_field($input['contact_phone']);
    }
    
    if (isset($input['facebook_url'])) {
        $sanitized['facebook_url'] = esc_url_raw($input['facebook_url']);
    }
    
    if (isset($input['instagram_url'])) {
        $sanitized['instagram_url'] = esc_url_raw($input['instagram_url']);
    }
    
    if (isset($input['twitter_url'])) {
        $sanitized['twitter_url'] = esc_url_raw($input['twitter_url']);
    }
    
    if (isset($input['google_analytics_id'])) {
        $sanitized['google_analytics_id'] = sanitize_text_field($input['google_analytics_id']);
    }
    
    if (isset($input['enable_cookies_banner'])) {
        $sanitized['enable_cookies_banner'] = 1;
    } else {
        $sanitized['enable_cookies_banner'] = 0;
    }
    
    return $sanitized;
}

// Helper function pour récupérer les options
function atypikhouse_get_option($option_name, $default = '') {
    $options = get_option('atypikhouse_options');
    return isset($options[$option_name]) ? $options[$option_name] : $default;
}

// Ajouter Google Analytics dans le head si configuré
function atypikhouse_add_analytics() {
    $ga_id = atypikhouse_get_option('google_analytics_id');
    
    if (!empty($ga_id)) {
        ?>
        <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($ga_id); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php echo esc_attr($ga_id); ?>', {
                anonymize_ip: true,
                cookie_flags: 'SameSite=None;Secure'
            });
        </script>
        <?php
    }
}
add_action('wp_head', 'atypikhouse_add_analytics');
?>
