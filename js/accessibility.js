/**
 * Améliorations d'accessibilité
 * AtypikHouse Theme - Conformité WCAG 2.1
 */

(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        initKeyboardNavigation();
        initAriaLabels();
        initFocusManagement();
        initScreenReaderHelpers();
    });
    
    // === NAVIGATION CLAVIER === //
    function initKeyboardNavigation() {
        // Navigation dans les grilles de cartes
        var cards = document.querySelectorAll('.card');
        
        cards.forEach(function(card, index) {
            card.setAttribute('tabindex', '0');
            card.setAttribute('role', 'article');
            
            card.addEventListener('keydown', function(e) {
                switch(e.key) {
                    case 'Enter':
                    case ' ':
                        e.preventDefault();
                        var link = card.querySelector('a');
                        if (link) link.click();
                        break;
                        
                    case 'ArrowRight':
                        e.preventDefault();
                        focusNextCard(cards, index);
                        break;
                        
                    case 'ArrowLeft':
                        e.preventDefault();
                        focusPreviousCard(cards, index);
                        break;
                        
                    case 'ArrowDown':
                        e.preventDefault();
                        focusCardBelow(cards, index);
                        break;
                        
                    case 'ArrowUp':
                        e.preventDefault();
                        focusCardAbove(cards, index);
                        break;
                }
            });
        });
        
        // Navigation dans les filtres
        var filterForm = document.querySelector('.filters-form');
        if (filterForm) {
            var selects = filterForm.querySelectorAll('select');
            
            selects.forEach(function(select) {
                select.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        filterForm.submit();
                    }
                });
            });
        }
    }
    
    function focusNextCard(cards, currentIndex) {
        var nextIndex = (currentIndex + 1) % cards.length;
        cards[nextIndex].focus();
    }
    
    function focusPreviousCard(cards, currentIndex) {
        var prevIndex = currentIndex === 0 ? cards.length - 1 : currentIndex - 1;
        cards[prevIndex].focus();
    }
    
    function focusCardBelow(cards, currentIndex) {
        // Estimation basée sur une grille de 3 colonnes
        var nextIndex = currentIndex + 3;
        if (nextIndex < cards.length) {
            cards[nextIndex].focus();
        }
    }
    
    function focusCardAbove(cards, currentIndex) {
        var prevIndex = currentIndex - 3;
        if (prevIndex >= 0) {
            cards[prevIndex].focus();
        }
    }
    
    // === LABELS ARIA === //
    function initAriaLabels() {
        // Améliorer les boutons sans texte
        var buttons = document.querySelectorAll('button:not([aria-label])');
        
        buttons.forEach(function(button) {
            var text = button.textContent.trim();
            var icon = button.querySelector('.icon, [class*="icon"]');
            
            if (!text && icon) {
                // Bouton avec icône seulement
                button.setAttribute('aria-label', getButtonLabel(button));
            }
        });
        
        // Améliorer les liens
        var links = document.querySelectorAll('a:not([aria-label])');
        
        links.forEach(function(link) {
            var text = link.textContent.trim();
            var title = link.getAttribute('title');
            
            if (!text && !title) {
                var context = getLinkContext(link);
                if (context) {
                    link.setAttribute('aria-label', context);
                }
            }
        });
        
        // États des éléments interactifs
        var toggles = document.querySelectorAll('[data-toggle]');
        
        toggles.forEach(function(toggle) {
            toggle.setAttribute('aria-expanded', 'false');
            
            toggle.addEventListener('click', function() {
                var expanded = toggle.getAttribute('aria-expanded') === 'true';
                toggle.setAttribute('aria-expanded', !expanded);
            });
        });
    }
    
    function getButtonLabel(button) {
        var className = button.className;
        
        if (className.includes('search')) return 'Rechercher';
        if (className.includes('menu')) return 'Menu de navigation';
        if (className.includes('close')) return 'Fermer';
        if (className.includes('filter')) return 'Appliquer les filtres';
        
        return 'Bouton';
    }
    
    function getLinkContext(link) {
        var parent = link.closest('.card, .hebergement-card');
        
        if (parent) {
            var title = parent.querySelector('.card-title, h1, h2, h3');
            if (title) {
                return 'Voir les détails de ' + title.textContent.trim();
            }
        }
        
        return null;
    }
    
    // === GESTION DU FOCUS === //
    function initFocusManagement() {
        // Piège de focus pour les modals
        var modals = document.querySelectorAll('.modal, .cookie-modal');
        
        modals.forEach(function(modal) {
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                        var isVisible = modal.style.display !== 'none';
                        
                        if (isVisible) {
                            trapFocus(modal);
                        } else {
                            releaseFocus();
                        }
                    }
                });
            });
            
            observer.observe(modal, { attributes: true });
        });
        
        // Focus visible au clavier uniquement
        var focusVisible = false;
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                focusVisible = true;
                document.body.classList.add('keyboard-navigation');
            }
        });
        
        document.addEventListener('mousedown', function() {
            focusVisible = false;
            document.body.classList.remove('keyboard-navigation');
        });
    }
    
    var lastFocusedElement;
    
    function trapFocus(modal) {
        lastFocusedElement = document.activeElement;
        
        var focusableElements = modal.querySelectorAll(
            'a[href], button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled])'
        );
        
        if (focusableElements.length > 0) {
            focusableElements[0].focus();
            
            modal.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    var firstElement = focusableElements[0];
                    var lastElement = focusableElements[focusableElements.length - 1];
                    
                    if (e.shiftKey && document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    } else if (!e.shiftKey && document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            });
        }
    }
    
    function releaseFocus() {
        if (lastFocusedElement) {
            lastFocusedElement.focus();
            lastFocusedElement = null;
        }
    }
    
    // === ASSISTANTS LECTEUR D'ÉCRAN === //
    function initScreenReaderHelpers() {
        // Annoncer les changements dynamiques
        var liveRegion = document.createElement('div');
        liveRegion.setAttribute('aria-live', 'polite');
        liveRegion.setAttribute('aria-atomic', 'true');
        liveRegion.className = 'sr-only';
        liveRegion.id = 'live-announcements';
        document.body.appendChild(liveRegion);
        
        // Annoncer les résultats de filtres
        var resultsCount = document.querySelector('.results-count');
        if (resultsCount) {
            announceToScreenReader(resultsCount.textContent);
        }
        
        // Annoncer les actions importantes
        document.addEventListener('submit', function(e) {
            var form = e.target;
            
            if (form.classList.contains('filters-form')) {
                announceToScreenReader('Filtres appliqués, mise à jour des résultats en cours');
            } else if (form.classList.contains('newsletter-form')) {
                announceToScreenReader('Inscription à la newsletter en cours');
            }
        });
        
        // Skip links améliorés
        var skipLinks = document.querySelectorAll('.skip-link');
        
        skipLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                var target = document.querySelector(link.getAttribute('href'));
                
                if (target) {
                    e.preventDefault();
                    target.setAttribute('tabindex', '-1');
                    target.focus();
                    
                    target.addEventListener('blur', function() {
                        target.removeAttribute('tabindex');
                    }, { once: true });
                }
            });
        });
    }
    
    function announceToScreenReader(message) {
        var liveRegion = document.getElementById('live-announcements');
        if (liveRegion) {
            liveRegion.textContent = message;
            
            // Nettoyer après annonce
            setTimeout(function() {
                liveRegion.textContent = '';
            }, 1000);
        }
    }
    
    // === CONTRASTE ET LISIBILITÉ === //
    function initContrastHelpers() {
        // Détecter les problèmes de contraste potentiels
        var elements = document.querySelectorAll('a, button, .card-title');
        
        elements.forEach(function(element) {
            var styles = window.getComputedStyle(element);
            var color = styles.color;
            var backgroundColor = styles.backgroundColor;
            
            // Ici on pourrait implémenter une vérification de contraste
            // Pour un projet étudiant, on s'assure surtout que les styles CSS sont corrects
        });
    }
    
})();
