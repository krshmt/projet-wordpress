<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '3.4.4' );
define( 'EHP_THEME_SLUG', 'hello-elementor' );

define( 'HELLO_THEME_PATH', get_template_directory() );
define( 'HELLO_THEME_URL', get_template_directory_uri() );
define( 'HELLO_THEME_ASSETS_PATH', HELLO_THEME_PATH . '/assets/' );
define( 'HELLO_THEME_ASSETS_URL', HELLO_THEME_URL . '/assets/' );
define( 'HELLO_THEME_SCRIPTS_PATH', HELLO_THEME_ASSETS_PATH . 'js/' );
define( 'HELLO_THEME_SCRIPTS_URL', HELLO_THEME_ASSETS_URL . 'js/' );
define( 'HELLO_THEME_STYLE_PATH', HELLO_THEME_ASSETS_PATH . 'css/' );
define( 'HELLO_THEME_STYLE_URL', HELLO_THEME_ASSETS_URL . 'css/' );
define( 'HELLO_THEME_IMAGES_PATH', HELLO_THEME_ASSETS_PATH . 'images/' );
define( 'HELLO_THEME_IMAGES_URL', HELLO_THEME_ASSETS_URL . 'images/' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup() {
		if ( is_admin() ) {
			hello_maybe_update_theme_version_in_db();
		}

		if ( apply_filters( 'hello_elementor_register_menus', true ) ) {
			register_nav_menus( [ 'menu-1' => esc_html__( 'Header', 'hello-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => esc_html__( 'Footer', 'hello-elementor' ) ] );
		}

		if ( apply_filters( 'hello_elementor_post_type_support', true ) ) {
			add_post_type_support( 'page', 'excerpt' );
		}

		if ( apply_filters( 'hello_elementor_add_theme_support', true ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
					'navigation-widgets',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);
			add_theme_support( 'align-wide' );
			add_theme_support( 'responsive-embeds' );

			/*
			 * Editor Styles
			 */
			add_theme_support( 'editor-styles' );
			add_editor_style( 'editor-styles.css' );

			/*
			 * WooCommerce.
			 */
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', true ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_theme_version';
	// The theme version saved in the database.
	$hello_theme_db_version = get_option( $theme_version_option_name );

	// If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $hello_theme_db_version || version_compare( $hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'hello_elementor_display_header_footer' ) ) {
	/**
	 * Check whether to display header footer.
	 *
	 * @return bool
	 */
	function hello_elementor_display_header_footer() {
		$hello_elementor_header_footer = true;

		return apply_filters( 'hello_elementor_header_footer', $hello_elementor_header_footer );
	}
}

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles() {
		if ( apply_filters( 'hello_elementor_enqueue_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor',
				HELLO_THEME_STYLE_URL . 'reset.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				HELLO_THEME_STYLE_URL . 'theme.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( hello_elementor_display_header_footer() ) {
			wp_enqueue_style(
				'hello-elementor-header-footer',
				HELLO_THEME_STYLE_URL . 'header-footer.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'hello_elementor_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( ! function_exists( 'hello_elementor_add_description_meta_tag' ) ) {
	/**
	 * Add description meta tag with excerpt text.
	 *
	 * @return void
	 */
	function hello_elementor_add_description_meta_tag() {
		if ( ! apply_filters( 'hello_elementor_description_meta_tag', true ) ) {
			return;
		}

		if ( ! is_singular() ) {
			return;
		}

		$post = get_queried_object();
		if ( empty( $post->post_excerpt ) ) {
			return;
		}

		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $post->post_excerpt ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'hello_elementor_add_description_meta_tag' );

// Settings page
require get_template_directory() . '/includes/settings-functions.php';

// Header & footer styling option, inside Elementor
require get_template_directory() . '/includes/elementor-functions.php';

if ( ! function_exists( 'hello_elementor_customizer' ) ) {
	// Customizer controls
	function hello_elementor_customizer() {
		if ( ! is_customize_preview() ) {
			return;
		}

		if ( ! hello_elementor_display_header_footer() ) {
			return;
		}

		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'hello_elementor_customizer' );

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	/**
	 * Check whether to display the page title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

/**
 * BC:
 * In v2.7.0 the theme removed the `hello_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		wp_body_open();
	}
}

require HELLO_THEME_PATH . '/theme.php';

HelloTheme\Theme::instance();

add_action('acf/save_post', 'generer_lat_lon', 20);
function generer_lat_lon($post_id) {
    // Évite les pages d’options ou révisions
    if (get_post_type($post_id) !== 'formations') return;

    // Récupère les valeurs ACF
    $adresse_rue = get_field('adresse_de_la_formation_numero_et_rue', $post_id);
    $complement  = get_field('complement_adresse', $post_id);
    $code_postal = get_field('code_postal', $post_id);
    $ville       = get_field('ville', $post_id);

    // Construit l’adresse complète
    $adresse_complete = trim("$adresse_rue $complement $code_postal $ville");
    if (!$adresse_complete) return;

    // Appel à Nominatim avec User-Agent obligatoire
    $url = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($adresse_complete) . '&countrycodes=fr';
    $response = wp_remote_get($url, [
        'headers' => [
            'User-Agent' => 'WordPress/GeoCoder'
        ]
    ]);

    if (is_wp_error($response)) return;

    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (!empty($data[0])) {
        update_field('latitude', $data[0]['lat'], $post_id);
        update_field('longitude', $data[0]['lon'], $post_id);
    }
}


/******************************************************************
 * 1. Shortcode [liste_structures] – version accordéon + accès admin
 ******************************************************************/
function user_structures_list_shortcode() {
    if ( ! is_user_logged_in() ) {
        return '<p>Vous devez être connecté pour voir vos structures.</p>';
    }

    $current_user_id = get_current_user_id();
    $is_admin        = current_user_can( 'administrator' );

    $args = array(
        'post_type'      => 'structures',
        'post_status'    => 'publish',
        'numberposts'    => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    );
    if ( ! $is_admin ) {
        $args['author'] = $current_user_id;
    }

    $structures = get_posts( $args );

    ob_start();

    // Trouver la page qui contient [structure_form]
    $structure_form_page_id = get_page_by_shortcode('[structure_form]');
    $structure_form_url     = $structure_form_page_id ? get_permalink( $structure_form_page_id ) : false;

    if ( empty( $structures ) ) {
        echo '<p>Aucune structure trouvée.</p>';

        if ( $structure_form_url ) {
            echo '<a href="' . esc_url( $structure_form_url ) . '" class="button button-primary" style="margin-top: 10px;">Créer une structure</a>';
        } else {
            echo '<p style="color: red;">Impossible de trouver la page contenant le formulaire de structure.</p>';
        }

        return ob_get_clean(); 
    }

    // Sinon, structures trouvées => on continue avec l'accordéon
    ?>
    <style>
        .structures-accordion details {
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 8px;
            padding: 6px;
        }
        .structures-accordion summary {
            cursor: pointer;
            font-weight: 600;
            list-style: none;
        }
        .structures-accordion summary::-webkit-details-marker {
            display: none;
        }
        .structures-accordion summary::before {
            content: "▶";
            display: inline-block;
            margin-right: 6px;
            transition: transform .2s;
        }
        details[open]>summary::before {
            transform: rotate(90deg);
        }
        .formation-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .formation-item.passee {
            background: #f5f5f5;
            opacity: 0.8;
        }
        .formations-section {
            margin-top: 20px;
        }
        .formations-section h4 {
            margin-bottom: 10px;
            color: #333;
        }
    </style>

    <div class="structures-accordion">
        <?php foreach ( $structures as $structure ) :
            $structure_id    = $structure->ID;
            $structure_title = esc_html( get_the_title( $structure_id ) );

            // IMPORTANT : envoyer vers la page [structure_form] avec create_formation=1
            if ( $structure_form_url ) {
                $formation_url = add_query_arg(
                    array(
                        'structure_id'     => $structure_id,
                        'create_formation' => '1',
                    ),
                    $structure_form_url
                ) . '#formulaire-formation';
            } else {
                // Fallback : lien désactivé si on ne trouve pas la page cible
                $formation_url = '';
            }

            // Lien "Modifier cette structure" vers la page [structure_form] en mode édition
            $edit_structure_url = add_query_arg(
                'structure_id',
                $structure_id,
                $structure_form_url ? $structure_form_url : home_url('/')
            );
            ?>
            <details>
                <summary><?php echo $structure_title; ?></summary>

                <?php
                if ( $formation_url ) {
                    echo '<a href="' . esc_url( $formation_url ) . '" class="button" style="margin-top:8px;">Créer une formation pour cette structure</a>';
                } else {
                    echo '<span class="button disabled" style="margin-top:8px;opacity:.6;pointer-events:none;">Créer une formation (page cible introuvable)</span>';
                }
                ?>
                <a href="<?php echo esc_url( $edit_structure_url ); ?>" class="button button-secondary" style="margin-top: 8px; margin-left: 10px;">Modifier cette structure</a>

                <div class="liste-formations" style="margin-top: 20px;">
                    <?php afficher_formations_structure_triees( $structure_id ); ?>
                </div>
            </details>
        <?php endforeach; ?>
    </div>
    <?php

    return ob_get_clean();
}
add_shortcode( 'liste_structures', 'user_structures_list_shortcode' );


// Notification par email aux administrateurs lors de la création d'un nouvel utilisateur
add_action('user_register', 'ea_notify_admin_new_user', 10, 1);

function ea_notify_admin_new_user($user_id) {
    $user_info = get_userdata($user_id);

    $subject = 'Nouveau compte utilisateur créé sur ' . get_bloginfo('name');
    $message  = "Un nouvel utilisateur vient de s'inscrire sur votre site.\n\n";
    $message .= "Nom d'utilisateur : " . $user_info->user_login . "\n";
    $message .= "Email : " . $user_info->user_email . "\n";
    $message .= "Rôle : " . implode(', ', $user_info->roles) . "\n";
    $message .= "\nConnectez-vous à l'administration pour vérifier ses informations.";

    // Récupérer tous les administrateurs
    $admins = get_users(array(
        'role'    => 'administrator',
        'fields'  => array('user_email')
    ));

    foreach ($admins as $admin) {
        wp_mail($admin->user_email, $subject, $message);
    }
}

/**
 * Après sauvegarde ACF : pour les CPT 'structures', définir le titre
 * avec la valeur du champ 'nom_de_la_structure'.
 */
add_action('acf/save_post', function($post_id) {
    // $post_id peut être un ID numérique ou 'new_post' pendant l'init
    if (!is_numeric($post_id)) return;

    if (get_post_type($post_id) !== 'structures') return;

    // Récupère le champ ACF (change le nom si différent dans ton setup)
    $name = get_field('nom_de_la_structure', $post_id);
    $name = is_string($name) ? trim($name) : '';

    if ($name === '') return;

    // Évite la boucle infinie
    remove_action('acf/save_post', __FUNCTION__);

    wp_update_post(array(
        'ID'         => $post_id,
        'post_title' => $name,
        'post_name'  => sanitize_title($name), // slug propre
    ));

    // Restaure le hook
    add_action('acf/save_post', __FUNCTION__);
}, 20);



/**
 * Shortcode [structure_form]
 * - Si URL contient ?create_formation=... & structure_id=XXX :
 *     -> Affiche UNIQUEMENT le formulaire d'ajout de formation (on force les CHAMPS; fallback groupes)
 * - Sinon :
 *     -> Rend le formulaire STRUCTURE (masqué par défaut si l'user en a déjà une)
 *
 * Debug : &debug=1 (à placer AVANT le #) pour info de diagnostic.
 */
function structure_form_shortcode() {
    if (!is_user_logged_in()) {
        return '<p>Vous devez être connecté pour accéder à ce formulaire.</p>';
    }

    $current_user_id = get_current_user_id();
    $debug = isset($_GET['debug']) && $_GET['debug'] == '1';

    /* ---------------------------------------------------------
     * 0) Anti-cache si on demande le formulaire de FORMATION
     * --------------------------------------------------------- */
    $has_cf_param = isset($_GET['create_formation']) && $_GET['create_formation'] !== '';
    if ( $has_cf_param ) {
        if ( ! defined('DONOTCACHEPAGE') ) define('DONOTCACHEPAGE', true);
        if ( ! headers_sent() ) nocache_headers();
        if ( function_exists('acf_enqueue_scripts') ) acf_enqueue_scripts();
    }

    /* ---------------------------------------------------------
     * 1) MODE "CRÉER UNE FORMATION ICI" (prioritaire)
     *     ➜ on force les CHAMPS; si introuvables, fallback sur GROUPES
     * --------------------------------------------------------- */
    $wants_formation_form = $has_cf_param && isset($_GET['structure_id']);
    $formation_for_structure_id = $wants_formation_form ? intval($_GET['structure_id']) : 0;

    if ($wants_formation_form) {
        $s_post = get_post($formation_for_structure_id);
        $post_ok = ($s_post && $s_post->post_type === 'structures');

        // Récupérer dynamiquement les groupes ACF liés au CPT "formations"
        $formation_group_keys = array();
        if (function_exists('acf_get_field_groups')) {
            $formation_groups = acf_get_field_groups(array('post_type' => 'formations'));
            if (!empty($formation_groups)) {
                foreach ($formation_groups as $grp) {
                    if (!empty($grp['key'])) $formation_group_keys[] = $grp['key'];
                }
            }
        }

        // Récupérer toutes les clés de champs du/des groupe(s) trouvé(s)
        $formation_field_keys = array();
        if (!empty($formation_group_keys) && function_exists('acf_get_fields')) {
            foreach ($formation_group_keys as $gk) {
                $fields = acf_get_fields($gk);
                if (is_array($fields)) {
                    foreach ($fields as $f) {
                        // On ne prend que les champs racine (les sous-champs repeater ont aussi une key, ça fonctionne quand même)
                        if (!empty($f['key'])) {
                            $formation_field_keys[] = $f['key'];
                        }
                    }
                }
            }
        }

        if ($debug) {
            echo '<div style="background:#fff3cd;border:1px solid #ffeeba;color:#856404;padding:10px;border-radius:4px;margin-bottom:12px">';
            echo '<strong>DEBUG [structure_form]</strong><br>';
            echo 'has_cf_param=' . ($has_cf_param ? 'true' : 'false') . '<br>';
            echo 'wants_formation_form=' . ($wants_formation_form ? 'true' : 'false') . '<br>';
            echo 'structure_id=' . esc_html($formation_for_structure_id) . '<br>';
            echo 'structure_found=' . ($s_post ? 'true' : 'false') . ' / type=' . ($s_post ? esc_html($s_post->post_type) : 'null') . '<br>';
            echo 'current_user_id=' . intval($current_user_id) . ' / post_author=' . ($s_post ? intval($s_post->post_author) : -1) . '<br>';
            echo 'formation_groups_found=' . count($formation_group_keys) . '<br>';
            echo 'formation_field_keys=' . count($formation_field_keys) . '<br>';
            if (!empty($formation_field_keys)) {
                echo '<ul style="margin:6px 0 0 18px;">';
                foreach ($formation_field_keys as $fk) echo '<li>' . esc_html($fk) . '</li>';
                echo '</ul>';
            }
            echo '</div>';
        }

        if ($post_ok) {
            // URL de retour après création : page sans les paramètres
            $return_after_formation = remove_query_arg(array('create_formation','structure_id','debug'));

            ob_start();

            echo '<div id="formulaire-formation" style="margin-top:0;">';
            echo '<h2>Créer une formation pour la structure : ' . esc_html(get_the_title($formation_for_structure_id)) . '</h2>';

            // === Construire les args ACF ===
            $acf_args = array(
                'post_id'        => 'new_post',
                'new_post'       => array(
                    'post_type'   => 'formations',
                    'post_status' => 'publish',   // ajuste si besoin (pending/draft)
                    'post_author' => $current_user_id,
                ),
                'submit_value'   => 'Créer la formation',
                'return'         => $return_after_formation,
                'updated_message'=> false,
                'uploader'       => 'basic',
                'html_after_fields' => '<input type="hidden" name="linked_structure_id" value="' . intval($formation_for_structure_id) . '">',
            );

            // 1) Si on a trouvé des champs → on les force
            if (!empty($formation_field_keys)) {
                $acf_args['fields'] = $formation_field_keys;
            // 2) Sinon, tenter avec les groupes trouvés (fallback)
            } elseif (!empty($formation_group_keys)) {
                $acf_args['field_groups'] = $formation_group_keys;
            // 3) Sinon, dernier fallback: laisser ACF appliquer les règles de lieu
            }

            acf_form($acf_args);

            echo '</div>';

            // Scroll sur le bloc formation
            echo '<script>document.addEventListener("DOMContentLoaded",function(){var el=document.getElementById("formulaire-formation"); if(el){el.scrollIntoView({behavior:"smooth",block:"start"});} });</script>';

            return ob_get_clean();
        } else {
            return '<div class="notice notice-error" style="background:#fde8e8;border:1px solid #f5c2c7;color:#842029;padding:12px;border-radius:4px;margin-bottom:16px;">Structure introuvable ou invalide.</div>';
        }
    }

    /* ---------------------------------------------------------
     * 2) FLUX NORMAL : formulaire de STRUCTURE
     * --------------------------------------------------------- */
    $is_edit_mode    = false;
    $post_id         = 'new_post';
    $editing_title   = '';

    if (isset($_GET['structure_id'])) {
        $structure_id   = intval($_GET['structure_id']);
        $structure_post = get_post($structure_id);

        if ($structure_post && $structure_post->post_type === 'structures' &&
            ( current_user_can('edit_post', $structure_id) || intval($structure_post->post_author) === $current_user_id )) {
            $post_id       = $structure_id;
            $is_edit_mode  = true;
            $editing_title = esc_html(get_the_title($structure_id));
        }
    }

    if (!$is_edit_mode) {
        $existing_structure = get_posts(array(
            'post_type'      => 'structures',
            'author'         => $current_user_id,
            'post_status'    => 'any',
            'numberposts'    => 1
        ));
        $post_id = $existing_structure ? $existing_structure[0]->ID : 'new_post';
    }
    $has_structure = ($post_id !== 'new_post');

    // Récupérer dynamiquement les groupes ACF du CPT "structures" (on peut rester sur groupes ici)
    $structure_group_keys = array();
    if (function_exists('acf_get_field_groups')) {
        $structure_groups = acf_get_field_groups(array('post_type' => 'structures'));
        if (!empty($structure_groups)) {
            foreach ($structure_groups as $grp) {
                if (!empty($grp['key'])) $structure_group_keys[] = $grp['key'];
            }
        }
    }

    $form_args = array(
        'post_id'     => $post_id,
        'new_post'    => array(
            'post_type'   => 'structures',
            'post_status' => 'publish',
            'post_author' => $current_user_id,
        ),
        'form'                 => true,
        'form_attributes'      => array('class' => 'acf-form-frontend'),
        'return'               => add_query_arg('updated', 'true', get_permalink()),
        'submit_value'         => $has_structure ? 'Mettre à jour ma structure' : 'Créer ma structure',
        'updated_message'      => false,
        'instruction_placement'=> 'label',
        'label_placement'      => 'top',
        'field_el'             => 'div',
    );
    if (!empty($structure_group_keys)) {
        $form_args['field_groups'] = $structure_group_keys;
    }

    ob_start();

    if (isset($_GET['updated']) && $_GET['updated'] == 'true') {
        echo '<div class="success-message" style="background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:15px;border-radius:5px;margin-bottom:20px;">Votre structure a été sauvegardée avec succès !</div>';
    }

    if ($has_structure) {
        echo '<div id="ehp-structure-toggle-bar">';
        echo '<a href="#" class="button js-ehp-edit-structure js-ehp-toggle-structure" data-label-open="Modifier les informations de ma structure" data-label-close="Masquer le formulaire de ma structure" style="margin-bottom:12px;">Modifier les informations de ma structure</a>';
        echo '</div>';
    }

    echo '<div id="ehp-structure-form-wrapper">';
    if ($is_edit_mode && $editing_title) {
        echo '<h2 id="formulaire-structure">Modifier la structure : ' . $editing_title . '</h2>';
        echo '<script>document.addEventListener("DOMContentLoaded",function(){document.body.classList.add("ehp-show-structure-form");});</script>';
    } else {
        echo '<h2 id="formulaire-structure">' . ($has_structure ? 'Ma structure' : 'Créer ma structure') . '</h2>';
    }

    acf_form($form_args);
    echo '</div>';

    if ($debug) {
        echo '<div style="background:#e2e3e5;border:1px solid #d3d6d8;color:#41464b;padding:10px;border-radius:4px;margin-top:12px">';
        echo '<strong>DEBUG [structure_form] — flux STRUCTURE</strong><br>';
        echo 'post_id=' . esc_html($post_id) . ' (has_structure=' . ($has_structure?'true':'false') . ')<br>';
        echo 'is_edit_mode=' . ($is_edit_mode?'true':'false') . '<br>';
        echo 'structure_groups_found=' . count($structure_group_keys) . '<br>';
        echo '</div>';
    }

    return ob_get_clean();
}
add_shortcode('structure_form', 'structure_form_shortcode');







function formation_form_shortcode() {
    if (!is_user_logged_in()) {
        return '<p>Vous devez être connecté pour créer une formation.</p>';
    }

    if (!isset($_GET['structure_id']) || !get_post($_GET['structure_id'])) {
        return '<p>Aucune structure sélectionnée.</p>';
    }

    $structure_id = intval($_GET['structure_id']);

    ob_start();

    echo '<div id="formulaire-formation">';
    echo '<h2>Créer une formation pour la structure : ' . esc_html(get_the_title($structure_id)) . '</h2>';

    acf_form(array(
        'post_id' => 'new_post',
        'new_post' => array(
            'post_type' => 'formations',
            'post_status' => 'publish',
            'post_title' => 'Formation de ' . wp_get_current_user()->display_name
        ),
        'field_groups' => array('group_685945b25de37'),
        'submit_value' => 'Créer la formation',
        'return' => get_permalink(),
        'html_after_fields' => '<input type="hidden" name="linked_structure_id" value="' . $structure_id . '">',
    ));

    echo '</div>';

    return ob_get_clean();
}
add_shortcode('formation_form', 'formation_form_shortcode');

/******************************************************************
 * Fonction utilitaire pour normaliser la date ACF (déplacée en global)
 ******************************************************************/
function normaliser_date_formation($post_id) {
    $date = get_field('date_de_la_formation', $post_id);

    // Log pour debug
    error_log('Date brute (formation ID ' . $post_id . '): ' . print_r($date, true));
    error_log('Type de date: ' . gettype($date));

    if (!$date) return null;

    // Gestion des objets DateTime
    if (is_object($date)) {
        if ($date instanceof DateTime) {
            // Forcer le fuseau horaire local pour éviter les décalages
            $date->setTimezone(new DateTimeZone(wp_timezone_string()));
            $result = $date->format('Y-m-d');
            error_log('Date normalisée (objet DateTime): ' . $result);
            return $result;
        }
        // Autres objets avec méthode format
        if (method_exists($date, 'format')) {
            $result = $date->format('Y-m-d');
            error_log('Date normalisée (objet avec format): ' . $result);
            return $result;
        }
    }
    
    // Gestion des timestamps numériques
    if (is_numeric($date)) {
        $result = date('Y-m-d', $date);
        error_log('Date normalisée (timestamp): ' . $result);
        return $result;
    }
    
    // Gestion des chaînes de caractères
    if (is_string($date)) {
        // Nettoyer la chaîne
        $date = trim($date);
        
        // Différents formats possibles d'ACF
        $formats = [
            'Y-m-d H:i:s',  // Format MySQL datetime
            'Y-m-d',        // Format date simple
            'd/m/Y',        // Format français
            'm/d/Y',        // Format américain
            'Y/m/d',        // Format ISO alternatif
            'd-m-Y',        // Format européen avec tirets
            'm-d-Y',        // Format américain avec tirets
        ];
        
        // Essayer de parser avec chaque format
        foreach ($formats as $format) {
            $dateTime = DateTime::createFromFormat($format, $date);
            if ($dateTime !== false) {
                $result = $dateTime->format('Y-m-d');
                error_log('Date normalisée (string format ' . $format . '): ' . $result);
                return $result;
            }
        }
        
        // Fallback avec strtotime
        $timestamp = strtotime($date);
        if ($timestamp !== false) {
            $result = date('Y-m-d', $timestamp);
            error_log('Date normalisée (strtotime): ' . $result);
            return $result;
        }
    }
    
    // Gestion des tableaux (format ACF complexe)
    if (is_array($date)) {
        if (isset($date['date'])) {
            return normaliser_date_formation_recursive($date['date']);
        }
        if (isset($date['value'])) {
            return normaliser_date_formation_recursive($date['value']);
        }
    }

    error_log('Impossible de normaliser la date pour formation ID ' . $post_id);
    return null;
}

// Fonction helper pour traiter récursivement les formats complexes
function normaliser_date_formation_recursive($date) {
    if (is_string($date)) {
        $timestamp = strtotime($date);
        return $timestamp ? date('Y-m-d', $timestamp) : null;
    }
    return null;
}


/**
 * Shortcode [edit_formation] pour modifier une formation
 */
function edit_formation_shortcode() {
    if (!is_user_logged_in()) {
        return '<p>Vous devez être connecté pour modifier une formation.</p>';
    }

    if (!isset($_GET['formation_id'])) {
        return '<p>Aucune formation sélectionnée.</p>';
    }

    $formation_id   = intval($_GET['formation_id']);
    $formation_post = get_post($formation_id);

    if (!$formation_post || $formation_post->post_type !== 'formations') {
        return '<p>Formation introuvable.</p>';
    }

    if (!current_user_can('edit_post', $formation_id) && $formation_post->post_author != get_current_user_id()) {
        return '<p>Vous n’avez pas les droits pour modifier cette formation.</p>';
    }

    // Page liste = celle qui contient [gestion_structure_et_formation]
    $liste_page_id = get_page_by_shortcode('[gestion_structure_et_formation]');
    $return_url    = $liste_page_id ? get_permalink($liste_page_id) : home_url('/');

    // Si on a la structure liée, on la passe pour ouvrir la bonne section
    $linked_structure_id = (int) get_field('linked_structure_id', $formation_id);
    if ($linked_structure_id) {
        $return_url = add_query_arg('structure_id', $linked_structure_id, $return_url) . '#formulaire-formation';
    }

    // Indicateur pour afficher un message de succès sur la page liste
    $return_url = add_query_arg('formation_updated', '1', $return_url);

    ob_start();
    echo '<h2>Modifier la formation : ' . esc_html($formation_post->post_title) . '</h2>';

    acf_form(array(
        'post_id'        => $formation_id,
        'field_groups'   => array('group_685945b25de37'),
        'submit_value'   => 'Mettre à jour la formation',
        'return'         => $return_url, // <-- redirection vers la liste
    ));

    return ob_get_clean();
}
add_shortcode('edit_formation', 'edit_formation_shortcode');


/**
 * Gestion de la suppression de formation - Version simplifiée
 */
add_action('wp_loaded', function() {
    if (!isset($_GET['action']) || $_GET['action'] !== 'delete_formation' || !isset($_GET['formation_id'])) {
        return;
    }

    $formation_id = intval($_GET['formation_id']);

    // Vérification du nonce
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'delete_formation_' . $formation_id)) {
        wp_die('Erreur de sécurité.');
    }

    // Vérifier que la formation existe
    $formation = get_post($formation_id);
    if (!$formation || $formation->post_type !== 'formations') {
        wp_die('Formation introuvable.');
    }

    // Vérifier les permissions
    if (!current_user_can('delete_posts') && $formation->post_author != get_current_user_id()) {
        wp_die('Permissions insuffisantes.');
    }

    // Supprimer la formation
    if (wp_delete_post($formation_id, true)) {
        // Redirection simple vers la page précédente ou l'accueil
        $redirect_url = wp_get_referer() ?: home_url('/');
        $redirect_url = add_query_arg('message', 'formation_deleted', $redirect_url);
        
        wp_safe_redirect($redirect_url);
        exit;
    } else {
        wp_die('Erreur lors de la suppression.');
    }
});

/******************************************************************
 * Fonction pour afficher les formations triées par date
 * (utilisée dans les deux shortcodes)
 ******************************************************************/
function afficher_formations_structure_triees($structure_id) {
    $structure_post = get_post($structure_id);
    if (!$structure_post) {
        echo '<p>Structure introuvable.</p>';
        return;
    }

    $auteur_id = $structure_post->post_author;

    // Récupérer toutes les formations de l'auteur
    $formations = get_posts(array(
        'post_type'      => 'formations',
        'post_status'    => 'publish',
        'numberposts'    => -1,
        'author'         => $auteur_id,
    ));

    if (empty($formations)) {
        echo '<p><em>Aucune formation créée pour cette structure.</em></p>';
        return;
    }

    $formations_futures = array();
    $formations_passees = array();
    
    // Utiliser le fuseau horaire WordPress pour éviter les décalages
    $timezone = wp_timezone_string();
    $date_actuelle = (new DateTime('now', new DateTimeZone($timezone)))->format('Y-m-d');
    
    // Log pour debug
    error_log('Date actuelle utilisée pour comparaison: ' . $date_actuelle . ' (timezone: ' . $timezone . ')');

    foreach ($formations as $formation) {
        $date_formation = normaliser_date_formation($formation->ID);

        // Fallback si la date n'est pas définie
        if (!$date_formation) {
            $date_formation = get_the_date('Y-m-d', $formation->ID);
        }

        // Stocker la date normalisée dans l'objet pour éviter de la recalculer
        $formation->date_normalisee = $date_formation;
        
        // Log pour debug
        error_log('Formation "' . $formation->post_title . '" - Date: ' . $date_formation . ' vs Aujourd\'hui: ' . $date_actuelle);

        if ($date_formation && $date_formation >= $date_actuelle) {
            $formations_futures[] = $formation;
            error_log('  -> Classée comme FUTURE');
        } else {
            $formations_passees[] = $formation;
            error_log('  -> Classée comme PASSÉE');
        }
    }

    // Trier les formations futures par date croissante
    usort($formations_futures, function($a, $b) {
        $date_a = $a->date_normalisee ?: '9999-12-31'; // Si pas de date, mettre à la fin
        $date_b = $b->date_normalisee ?: '9999-12-31';
        return strcmp($date_a, $date_b);
    });

    // Trier les formations passées par date décroissante
    usort($formations_passees, function($a, $b) {
        $date_a = $a->date_normalisee ?: '0000-01-01'; // Si pas de date, mettre au début
        $date_b = $b->date_normalisee ?: '0000-01-01';
        return strcmp($date_b, $date_a);
    });

    // Afficher les formations à venir
    if (!empty($formations_futures)) {
        echo '<h4>📅 Formations à venir (' . count($formations_futures) . ')</h4>';
        echo '<div class="formations-futures">';
        foreach ($formations_futures as $formation) {
            afficher_bloc_formation_simple($formation, false);
        }
        echo '</div>';
    }

    // Afficher les formations passées
    if (!empty($formations_passees)) {
        echo '<h4>📋 Formations passées (' . count($formations_passees) . ')</h4>';
        echo '<div class="formations-passees">';
        foreach ($formations_passees as $formation) {
            afficher_bloc_formation_simple($formation, true);
        }
        echo '</div>';
    }
}

// Décommenter la ligne suivante pour activer le debug
// add_action('wp_footer', 'debug_dates_formations');


/******************************************************************
 * Shortcode [gestion_structure_et_formation]
 ******************************************************************/
/**
 * Shortcode [gestion_structure_et_formation]
 * - Liste les structures en accordéon
 * - Le bouton "Créer une formation" renvoie vers la page qui contient [structure_form]
 *   avec ?structure_id=XXX&create_formation=1
 * - N'affiche plus de formulaire ACF de formation sur cette page
 */
function gestion_structure_et_formation_shortcode() {
    // Petit bouton global en haut pour ouvrir le form structure si existant (géré par ton JS)
    $output .= '<a href="#" class="js-ehp-edit-structure button">Modifier les informations de la structure</a>';

    if ( ! is_user_logged_in() ) {
        return '<p>Vous devez être connecté pour accéder à cette section.</p>';
    }

    $user_id      = get_current_user_id();
    $is_admin     = current_user_can( 'administrator' );
    $structure_id = isset( $_GET['structure_id'] ) ? intval( $_GET['structure_id'] ) : null;

    ob_start();

    // Récupérer les structures
    $args = array(
        'post_type'   => 'structures',
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby'     => 'title',
        'order'       => 'ASC',
    );
    if ( ! $is_admin ) {
        $args['author'] = $user_id;
    }
    $structures = get_posts( $args );

    // Si aucune structure, afficher un message simple
    if ( empty( $structures ) ) {
        echo '<h2>Veuillez créer votre structure</h2>';
        // On n'affiche pas d'autre formulaire ici
        return ob_get_clean();
    }

    // URL de la page qui contient [structure_form]
    $structure_form_page_id = get_page_by_shortcode('[structure_form]');
    $structure_form_url     = $structure_form_page_id ? get_permalink($structure_form_page_id) : home_url('/');

    // Styles accordéon (gardés)
    ?>
    <style>
        .structures-accordion details {
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 8px;
            padding: 6px;
        }
        .structures-accordion summary {
            cursor: pointer;
            font-weight: 600;
            list-style: none;
        }
        .structures-accordion summary::-webkit-details-marker { display: none; }
        .structures-accordion summary::before {
            content: "▶";
            display: inline-block;
            margin-right: 6px;
            transition: transform .2s;
        }
        details[open]>summary::before { transform: rotate(90deg); }
        .formation-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .formation-item.passee {
            background: #f5f5f5;
            opacity: 0.8;
        }
        .formations-section { margin-top: 20px; }
        .formations-section h4 { margin-bottom: 10px; color: #333; }
    </style>
    <?php

    echo '<h2>Sélectionner une structure</h2>';
    echo '<div class="structures-accordion">';

    foreach ( $structures as $structure ) {
        $is_open = ($structure_id === $structure->ID);

        // Lien "Créer une formation" -> vers la page [structure_form]
        $create_on_sf_url = add_query_arg(
            array(
                'structure_id'     => $structure->ID,
                'create_formation' => '1',
            ),
            $structure_form_url
        ) . '#formulaire-formation';

        echo '<details' . ( $is_open ? ' open' : '' ) . '>';
            echo '<summary>' . esc_html( $structure->post_title ) . '</summary>';

            echo '<a href="' . esc_url( $create_on_sf_url ) . '" class="button" style="margin-top:8px;">Créer une formation pour cette structure</a>';

            echo '<div class="formations-section">';
                afficher_formations_structure_triees( $structure->ID );
            echo '</div>';

        echo '</details>';
    }
    echo '</div>';

    // ⚠️ IMPORTANT : on ne rend PAS de formulaire de formation ici.
    // Le rendu du form formation se fait désormais dans [structure_form].

    return ob_get_clean();
}
add_shortcode( 'gestion_structure_et_formation', 'gestion_structure_et_formation_shortcode' );


// Ajoute 'ehp-has-structure' au <body> si l'utilisateur courant possède >=1 structure.
add_filter('body_class', function($classes){
    if (!is_singular('page')) return $classes;
    global $post; if (!$post) return $classes;

    // On n’active que si la page contient (au moins) l’un des shortcodes
    if (!has_shortcode($post->post_content, 'gestion_structure_et_formation') &&
        !has_shortcode($post->post_content, 'structure_form')) {
        return $classes;
    }

    $user_id = get_current_user_id();
    if (!$user_id) return $classes;

    $has_structure = get_posts([
        'post_type'      => 'structures',
        'author'         => $user_id,
        'posts_per_page' => 1,
        'post_status'    => ['publish','draft','pending'],
        'fields'         => 'ids',
    ]);

    if (!empty($has_structure)) $classes[] = 'ehp-has-structure';

    if (isset($_GET['create_formation']) && $_GET['create_formation'] !== '') {
        $classes[] = 'ehp-creating-formation';
    }

    return $classes;
});

add_action('wp_enqueue_scripts', function () {
    if (!is_singular('page')) return;
    global $post; if (!$post) return;

    $needs = has_shortcode($post->post_content, 'gestion_structure_et_formation')
          || has_shortcode($post->post_content, 'structure_form');

    if (!$needs) return;

    // --- CSS ---
    wp_register_style('ehp-structure-toggle', false);
    wp_enqueue_style('ehp-structure-toggle');
    wp_add_inline_style('ehp-structure-toggle', "
      /* Si l'utilisateur a déjà une structure, on cache le form par défaut */
      body.ehp-has-structure:not(.ehp-show-structure-form):not(.ehp-creating-formation) #ehp-structure-form-wrapper { display: none !important; }
      #ehp-structure-toggle-bar { display:block; margin:0 0 12px 0; }
      body.ehp-has-structure:not(.ehp-show-structure-form):not(.ehp-creating-formation) #ehp-structure-toggle-bar { display:block; }

      /* Petit confort visuel si tu veux ajouter des transitions plus tard */
      #ehp-structure-form-wrapper { transition: opacity .2s ease; }
      .ehp-inline-btn { display:inline-block; margin:0 0 12px 0; }
    ");

    // --- JS ---
    wp_register_script('ehp-structure-toggle', false, [], false, true);
    wp_enqueue_script('ehp-structure-toggle');
    wp_add_inline_script('ehp-structure-toggle', "
      (function(){
        var body  = document.body;
        var wrap  = document.getElementById('ehp-structure-form-wrapper');

        function syncToggleLabels(){
          document.querySelectorAll('.js-ehp-toggle-structure').forEach(function(btn){
            var openLabel = btn.getAttribute('data-label-open') || btn.textContent;
            var closeLabel = btn.getAttribute('data-label-close') || openLabel;
            if (body.classList.contains('ehp-show-structure-form')) {
              btn.textContent = closeLabel;
            } else {
              btn.textContent = openLabel;
            }
          });
        }

        function showForm(){
          body.classList.add('ehp-show-structure-form');
          if (wrap) {
            wrap.scrollIntoView({behavior:'smooth', block:'start'});
            var first = wrap.querySelector('input, textarea, select');
            if (first) { try { first.focus(); } catch(e){} }
          }
          syncToggleLabels();
        }
        function hideForm(){
          body.classList.remove('ehp-show-structure-form');
          syncToggleLabels();
        }

        document.querySelectorAll('.js-ehp-toggle-structure').forEach(function(btn){
          btn.addEventListener('click', function(e){
            e.preventDefault();
            if (body.classList.contains('ehp-show-structure-form')) {
              hideForm();
            } else {
              showForm();
            }
          });
        });

        document.querySelectorAll('.js-ehp-edit-structure').forEach(function(btn){
          if (btn.classList.contains('js-ehp-toggle-structure')) return;
          btn.addEventListener('click', function(e){ e.preventDefault(); showForm(); });
        });

        if (wrap) {
          var structureForm = wrap.querySelector('form');
          if (structureForm) {
            structureForm.addEventListener('submit', function(){
              hideForm();
            });
          }
        }

        var params = new URLSearchParams(location.search);
        if (params.has('updated')) hideForm();

        if (params.has('create_formation')) {
          body.classList.add('ehp-creating-formation');
        }

        if (params.has('edit_structure')) showForm();

        syncToggleLabels();
      })();
    ");
});


add_action('acf/save_post', 'mettre_a_jour_titre_automatique', 30);
function mettre_a_jour_titre_automatique($post_id) {
    // Évite les appels inutiles côté admin
    if (is_admin()) return;

    // STRUCTURES
    if (get_post_type($post_id) === 'structures') {
        $nom = get_field('nom_structure', $post_id);
        if ($nom) {
            wp_update_post(array(
                'ID' => $post_id,
                'post_title' => wp_strip_all_tags($nom)
            ));
        }
    }

    // FORMATIONS
    if (get_post_type($post_id) === 'formations') {
        $intitule = get_field('intitule', $post_id);
        if ($intitule) {
            wp_update_post(array(
                'ID' => $post_id,
                'post_title' => wp_strip_all_tags($intitule)
            ));
        }
    }
}

add_action('template_redirect', function () {
    if (is_admin()) return;
    if (!function_exists('acf_form_head')) return;
    if (!is_page()) return;

    global $post;
    $has_shortcodes = $post && (
        has_shortcode($post->post_content, 'structure_form') ||
        has_shortcode($post->post_content, 'gestion_structure_et_formation') ||
        has_shortcode($post->post_content, 'edit_formation') ||
        has_shortcode($post->post_content, 'formation_form')
    );

    // On force aussi si on arrive avec ?create_formation=1
    if ($has_shortcodes || isset($_GET['create_formation'])) {
        acf_form_head(); // IMPORTANT : avant toute sortie HTML
    }
}, 0);



add_action('acf/save_post', 'handle_structure_form_submission', 20);
function handle_structure_form_submission($post_id) {
    if (get_post_type($post_id) == 'structures' && !is_admin()) {
        // Traitement si nécessaire
    }
}

add_action('acf/save_post', 'enregistrer_lien_structure', 20);
function enregistrer_lien_structure($post_id) {
    if (get_post_type($post_id) != 'formations' || is_admin()) return;

    if (isset($_POST['linked_structure_id'])) {
        update_field('linked_structure_id', intval($_POST['linked_structure_id']), $post_id);
    }
}

function get_page_by_shortcode($shortcode) {
    $pages = get_posts(array(
        'post_type' => 'page',
        'numberposts' => -1,
        'post_status' => 'publish'
    ));

    foreach ($pages as $page) {
        if (has_shortcode($page->post_content, trim($shortcode, '[]'))) {
            return $page->ID;
        }
    }

    return false;
}

/******************************************************************
 * Fonction pour afficher un bloc de formation
 ******************************************************************/
function afficher_bloc_formation_simple($formation, $is_passee = false) {
    $date_formation = get_field('date_de_la_formation', $formation->ID);
    $lieu_formation = get_field('lieu_formation', $formation->ID);

    $class = $is_passee ? 'formation-item passee' : 'formation-item';

    echo '<div class="' . esc_attr($class) . '">';
    echo '<h5>' . esc_html($formation->post_title) . '</h5>';

    if ($date_formation && strtotime($date_formation)) {
        echo '<p><strong>📅 Date :</strong> ' . date('d/m/Y', strtotime($date_formation)) . '</p>';
    } else {
        echo '<p><strong>📅 Date :</strong> Non définie</p>';
    }

    if ($lieu_formation) {
        echo '<p><strong>📍 Lieu :</strong> ' . esc_html($lieu_formation) . '</p>';
    }

    if ($formation->post_content) {
        echo '<p>' . wp_trim_words($formation->post_content, 15) . '</p>';
    }

    // Bouton Voir les détails
    echo '<a href="' . get_permalink($formation->ID) . '" class="button button-small">Voir les détails</a>';

    // Si formation future => possibilité de modifier
    if (!$is_passee) {
        $edit_url = add_query_arg('formation_id', $formation->ID, get_permalink(get_page_by_shortcode('[edit_formation]')));
        echo ' <a href="' . esc_url($edit_url) . '" class="button button-secondary button-small">Modifier</a>';
    }

    // Bouton Supprimer avec nonce
    $delete_url = wp_nonce_url(
        add_query_arg(array(
            'action' => 'delete_formation',
            'formation_id' => $formation->ID
        ), home_url()),
        'delete_formation_' . $formation->ID
    );
    echo ' <a href="' . esc_url($delete_url) . '" class="button button-small" style="background:#dc3232;color:white;" onclick="return confirm(\'Supprimer cette formation ?\');">Supprimer</a>';

    echo '</div>';
}


/******************************************************************
 * Fonction de debug pour diagnostiquer le problème de dates
 * À utiliser temporairement pour identifier le problème
 ******************************************************************/
function debug_dates_formations() {
    echo '<div style="background: #f0f0f0; padding: 10px; margin: 10px 0; border-left: 4px solid #007cba;">';
    echo '<h4>🔍 Debug des dates de formations</h4>';
    
    $timezone = wp_timezone_string();
    $date_actuelle = (new DateTime('now', new DateTimeZone($timezone)))->format('Y-m-d');
    $timestamp_actuel = time();
    
    echo '<p><strong>Date actuelle (WordPress timezone):</strong> ' . $date_actuelle . '</p>';
    echo '<p><strong>Timezone WordPress:</strong> ' . $timezone . '</p>';
    echo '<p><strong>Timestamp actuel:</strong> ' . $timestamp_actuel . ' (' . date('Y-m-d H:i:s', $timestamp_actuel) . ')</p>';
    
    // Récupérer quelques formations pour tester
    $formations = get_posts(array(
        'post_type'      => 'formations',
        'post_status'    => 'publish',
        'numberposts'    => 5,
    ));
    
    echo '<h5>Analyse des formations:</h5>';
    foreach ($formations as $formation) {
        echo '<div style="margin: 10px 0; padding: 10px; background: white; border: 1px solid #ddd;">';
        echo '<strong>' . esc_html($formation->post_title) . '</strong><br>';
        
        $date_brute = get_field('date_de_la_formation', $formation->ID);
        $date_normalisee = normaliser_date_formation($formation->ID);
        
        echo 'Date brute ACF: ' . print_r($date_brute, true) . '<br>';
        echo 'Type: ' . gettype($date_brute) . '<br>';
        echo 'Date normalisée: ' . ($date_normalisee ?: 'NULL') . '<br>';
        
        if ($date_normalisee) {
            $comparaison = $date_normalisee >= $date_actuelle ? 'FUTURE' : 'PASSÉE';
            echo 'Classification: ' . $comparaison . '<br>';
        }
        
        echo '</div>';
    }
    
    echo '</div>';
}

// Décommenter la ligne suivante pour activer le debug
// add_action('wp_footer', 'debug_dates_formations');