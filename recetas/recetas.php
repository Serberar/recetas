<?php
/*
Plugin Name: Recetas
Description: Añade el shortcode [mostrar_recetas] para añadir tus recetas en cualquier entrada
Author: Sergio Bernabé Arahuetes
*/

// Cambiar el icono del menú
function cambiar_icono_menu() {
    ?>
    <style>
        #adminmenu #menu-posts-receta div.wp-menu-image:before {
            content: "\f187"; /* código de Dashicons */
        }
    </style>
    <?php
}

// Cambiar el texto de los botones
function cambiar_texto_botones() {
    $campos = array(
        'name'               => _x( 'Recetas', 'nombre general del tipo de publicación', 'traductor_recetas' ),
        'singular_name'      => _x( 'Receta', 'nombre singular del tipo de publicación', 'traductor_recetas' ),
        'menu_name'          => _x( 'Recetas', 'nombre del menú en el panel de administración', 'traductor_recetas' ),
        'name_admin_bar'     => _x( 'Receta', 'nombre para agregar nueva receta en la barra de administración', 'traductor_recetas' ),
        'add_new'            => _x( 'Agregar Nueva', 'agregar nueva receta', 'traductor_recetas' ),
        'add_new_item'       => __( 'Agregar Nueva Receta', 'traductor_recetas' ),
        'new_item'           => __( 'Nueva Receta', 'traductor_recetas' ),
        'edit_item'          => __( 'Editar Receta', 'traductor_recetas' ),
        'view_item'          => __( 'Ver Receta', 'traductor_recetas' ),
        'all_items'          => __( 'Todas las Recetas', 'traductor_recetas' ),
        'search_items'       => __( 'Buscar Recetas', 'traductor_recetas' ),
        'parent_item_colon'  => __( 'Recetas Padre:', 'traductor_recetas' ),
        'not_found'          => __( 'No se encontraron recetas.', 'traductor_recetas' ),
        'not_found_in_trash' => __( 'No se encontraron recetas en la papelera.', 'traductor_recetas' )
    );

    $parametros = array(
        'labels'             => $campos,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'receta' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
        'taxonomies'         => array( 'category', 'post_tag' )
    );

    register_post_type( 'receta', $parametros );
}

// Agregar campos personalizados para recetas
function crear_campo_personalizado() {
    add_meta_box(
        'campo_recetas',
        __( 'Detalles de la Receta', 'traductor_recetas' ),
        'mostrar_campo_recetas',
        'receta',
        'normal',
        'high'
    );
}

// Mostrar el cuadro de campos personalizados
function mostrar_campo_recetas( $receta ) {
    // Obtener valores existentes de la base de datos
    $ingredientes_receta = get_post_meta( $receta->ID, '_ingredientes_receta', true );
    $instrucciones_receta = get_post_meta( $receta->ID, '_instrucciones_receta', true );
    $tiempo_preparacion_receta = get_post_meta( $receta->ID, '_tiempo_preparacion_receta', true );

    ?>
    <h3>Ingredientes</h3>
    <textarea rows="5" cols="80" name="ingredientes_receta"><?php echo esc_textarea( $ingredientes_receta ); ?></textarea>

    <h3>Instrucciones de Preparación</h3>
    <textarea rows="10" cols="80" name="instrucciones_receta"><?php echo esc_textarea( $instrucciones_receta ); ?></textarea>

    <h3>Tiempo de Preparación</h3>
    <input type="text" size="20" name="tiempo_preparacion_receta" value="<?php echo esc_attr( $tiempo_preparacion_receta ); ?>" />
    <?php
}

// Guardar metadatos de la receta
function guardar_datos_receta( $receta_id ) {

    // Actualizar el campo meta en la base de datos.
    if ( isset( $_POST['ingredientes_receta'] ) ) {
        update_post_meta( $receta_id, '_ingredientes_receta', sanitize_text_field( $_POST['ingredientes_receta'] ) );
    }
    if ( isset( $_POST['instrucciones_receta'] ) ) {
        update_post_meta( $receta_id, '_instrucciones_receta', sanitize_text_field( $_POST['instrucciones_receta'] ) );
    }
    if ( isset( $_POST['tiempo_preparacion_receta'] ) ) {
        update_post_meta( $receta_id, '_tiempo_preparacion_receta', sanitize_text_field( $_POST['tiempo_preparacion_receta'] ) );
    }
}

// Shortcode para mostrar recetas
function shortcode_mostrar_recetas() {
    $parametros = array(
        'post_type'      => 'receta',
        'posts_per_page' => -1,
    );

    $recetas = new WP_Query( $parametros );

    if ( $recetas->have_posts() ) {
        $output = '<ul>';
        while ( $recetas->have_posts() ) {
            $recetas->the_post();
            $output .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
        }
        $output .= '</ul>';
        wp_reset_postdata();
        return $output;
    } else {
        return 'No hay recetas para mostrar.';
    }
}
add_shortcode( 'mostrar_recetas', 'shortcode_mostrar_recetas' );

// plantilla para mostrar en el front
function cargar_plantilla_receta($plantilla) {
    if (is_singular('receta')) {
        $plantilla = plugin_dir_path(__FILE__) . 'assets/templates/plantilla.php';
    }
    return $plantilla;
}
add_filter('single_template', 'cargar_plantilla_receta');


// Acciones para WordPress
add_action( 'admin_head', 'cambiar_icono_menu' ); // Cambia el icono del menú
add_action( 'init', 'cambiar_texto_botones' ); // Cambia el texto de los botones
add_action( 'add_meta_boxes', 'crear_campo_personalizado' ); // Agrega campos personalizados
add_action( 'save_post', 'guardar_datos_receta' ); // Guarda la los datos y crea los campos en la base de datos
?>
