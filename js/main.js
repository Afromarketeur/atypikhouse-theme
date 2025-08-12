/**
 * JavaScript principal AtypikHouse Theme
 * Projet étudiant DSP - Thème from scratch
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Initialisation des composants
        initCookieBanner();
        initBookingForm();
        initFilters();
        initNewsletter();
        initAccessibility();
        
        // Animation des éléments au scroll
        initScrollAnimations();
    });
    
    // === GESTION COOKIES RGPD === //
    function initCookieBanner() {
        // Vérifier si le consentement existe déjà
        if (!localStorage.getItem('atypikhouse_cookie_consent')) {
            $('#cookie-banner').show();
        }
        
        // Accepter tous les cookies
        $('#accept-all-cookies').on('click', function() {
            localStorage.setItem('atypikhouse_cookie_consent', 'all');
            $('#cookie-banner').hide();
            enableAllCookies();
            
            // Tracking événement
            trackEvent('cookie_consent', 'accept_all');
        });
        
        // Refuser les cookies non nécessaires
        $('#refuse-cookies').on('click', function() {
            localStorage.setItem('atypikhouse_cookie_consent', 'necessary');
            $('#cookie-banner').hide();
            
            trackEvent('cookie_consent', 'refuse');
        });
        
        // Personnaliser les cookies
        $('#customize-cookies').on('click', function() {
            $('#cookie-modal').show().attr('aria-hidden', 'false');
            $('#cookie-modal').focus();
        });
        
        // Fermer la modal
        $('#close-modal').on('click', function() {
            $('#cookie-modal').hide().attr('aria-hidden', 'true');
        });
        
        // Fermer modal en cliquant à l'extérieur
        $('#cookie-modal').on('click', function(e) {
            if (e.target === this) {
                $(this).hide().attr('aria-hidden', 'true');
            }
        });
        
        // Sauvegarder les préférences personnalisées
        $('#save-preferences').on('click', function() {
            var preferences = {
                necessary: true,
                analytics: $('#analytics-cookies').is(':checked'),
                marketing: $('#marketing-cookies').is(':checked')
            };
            
            localStorage.setItem('atypikhouse_cookie_preferences', JSON.stringify(preferences));
            localStorage.setItem('atypikhouse_cookie_consent', 'custom');
            
            $('#cookie-modal').hide().attr('aria-hidden', 'true');
            $('#cookie-banner').hide();
            
            applyCookiePreferences(preferences);
            trackEvent('cookie_consent', 'custom');
        });
    }
    
    function enableAllCookies() {
        // Activer Google Analytics
        if (typeof gtag !== 'undefined') {
            gtag('consent', 'update', {
                analytics_storage: 'granted',
                ad_storage: 'granted'
            });
        }
    }
    
    function applyCookiePreferences(preferences) {
        if (typeof gtag !== 'undefined') {
            gtag('consent', 'update', {
                analytics_storage: preferences.analytics ? 'granted' : 'denied',
                ad_storage: preferences.marketing ? 'granted' : 'denied'
            });
        }
    }
    
    // === FORMULAIRE DE RÉSERVATION === //
    function initBookingForm() {
        $('.reservation-form').on('submit', function(e) {
            e.preventDefault();
            
            var checkin = $('#checkin').val();
            var checkout = $('#checkout').val();
            var guests = $('#guests').val();
            
            // Validation basique
            if (!checkin || !checkout) {
                alert('⚠️ Veuillez sélectionner vos dates d\'arrivée et de départ.');
                return;
            }
            
            if (new Date(checkin) >= new Date(checkout)) {
                alert('⚠️ La date de départ doit être postérieure à la date d\'arrivée.');
                return;
            }
            
            // Simulation de réservation
            var message = '✨ Pré-réservation simulée :\n';
            message += '📅 Du ' + checkin + ' au ' + checkout + '\n';
            message += '👥 ' + guests + ' voyageur(s)\n\n';
            message += '⚠️ PROJET ÉTUDIANT FICTIF\nAucune transaction réelle n\'est effectuée.';
            
            alert(message);
            
            // Tracking événement Analytics
            trackEvent('booking_attempt', 'simulation', {
                checkin: checkin,
                checkout: checkout,
                guests: guests
            });
        });
        
        // Calcul automatique du prix total
        $('#checkin, #checkout').on('change', function() {
            calculateTotalPrice();
        });
    }
    
    function calculateTotalPrice() {
        var checkin = $('#checkin').val();
        var checkout = $('#checkout').val();
        var pricePerNight = $('.card-price').text().replace(/[^0-9]/g, '');
        
        if (checkin && checkout && pricePerNight) {
            var start = new Date(checkin);
            var end = new Date(checkout);
            var nights = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
            
            if (nights > 0) {
                var total = nights * parseInt(pricePerNight);
                $('.nights').text(nights + ' nuit' + (nights > 1 ? 's' : ''));
                $('.total-price').text(total + '€');
            }
        }
    }
    
    // === FILTRES HÉBERGEMENTS === //
    function initFilters() {
        $('.filters-form').on('submit', function(e) {
            // Le formulaire se soumet normalement via GET
            trackEvent('filter_search', 'apply');
        });
        
        // Réinitialisation des filtres
        $('.btn-secondary[href*="hebergements"]').on('click', function() {
            trackEvent('filter_search', 'reset');
        });
        
        // Auto-submit sur changement de select (optionnel)
        $('.filters-form select').on('change', function() {
            var form = $(this).closest('form');
            var hasValues = false;
            
            form.find('select, input').each(function() {
                if ($(this).val()) {
                    hasValues = true;
                    return false;
                }
            });
            
            if (hasValues) {
                // Optionnel : soumettre automatiquement
                // form.submit();
            }
        });
    }
    
    // === NEWSLETTER === //
    function initNewsletter() {
        $('.newsletter-form, .newsletter-form-hero').on('submit', function(e) {
            e.preventDefault();
            
            var form = $(this);
            var email = form.find('input[type="email"]').val();
            
            // Validation email
            if (!email || !isValidEmail(email)) {
                alert('⚠️ Veuillez saisir une adresse email valide.');
                return;
            }
            
            // Simulation inscription
            var message = '✅ Inscription newsletter simulée pour :\n' + email + '\n\n';
            message += '⚠️ PROJET ÉTUDIANT FICTIF\nAucun email ne sera réellement envoyé.';
            
            alert(message);
            
            // Vider le champ
            form.find('input[type="email"]').val('');
            
            // Tracking
            trackEvent('newsletter_signup', 'simulation', { email_domain: email.split('@')[1] });
        });
    }
    
    function isValidEmail(email) {
        var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
    
    // === ACCESSIBILITÉ === //
    function initAccessibility() {
        // Gestion du focus clavier sur les cartes
        $('.card').on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).find('a').get(0).click();
            }
        });
        
        // Navigation clavier dans les modals
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('.cookie-modal:visible').hide().attr('aria-hidden', 'true');
            }
        });
        
        // Amélioration du focus sur les éléments interactifs
        $('button, a, input, select, textarea').on('focus', function() {
            $(this).addClass('focused');
        }).on('blur', function() {
            $(this).removeClass('focused');
        });
    }
    
    // === ANIMATIONS AU SCROLL === //
    function initScrollAnimations() {
        // Intersection Observer pour les animations
        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in');
                        observer.unobserve(entry.target);
                    }
                });
            });
            
            // Observer les éléments à animer
            $('.card, .promise-item, .type-card').each(function() {
                observer.observe(this);
            });
        }
        
        // Smooth scroll pour les ancres
        $('a[href^="#"]').on('click', function(e) {
            var target = $($(this).attr('href'));
            
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 500);
            }
        });
    }
    
    // === FONCTION DE TRACKING === //
    function trackEvent(eventName, action, parameters) {
        // Google Analytics 4
        if (typeof gtag !== 'undefined') {
            gtag('event', action, {
                event_category: eventName,
                event_label: 'atypikhouse_' + eventName,
                ...parameters
            });
        }
        
        // Fallback console pour debug
        console.log('Event tracked:', eventName, action, parameters);
    }
    
    // === UTILITAIRES === //
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }
    
})(jQuery);
