<?php
// Obtener el ID de la receta actual
$receta_id = get_the_ID();

// obtener los datos del post
$titulo_post = get_post_field('post_title', $receta_id);
$fecha_publicacion = get_the_date('Y-m-d', $receta_id);
$contenido_post = get_post_field('post_content', $receta_id);

// Obtener metadatos de la receta
$ingredientes_receta = get_post_meta($receta_id, '_ingredientes_receta', true);
$instrucciones_receta = get_post_meta($receta_id, '_instrucciones_receta', true);
$tiempo_preparacion_receta = get_post_meta($receta_id, '_tiempo_preparacion_receta', true);
?>

<!-- Estructura HTML para mostrar la receta -->
<div class="receta-div">

    <div class="receta-seccion-titulo-post">
        <h1 class="receta-titulo-post"><?php echo $titulo_post; ?></h1>
    </div>
    
    <div class="receta-seccion">
        <p class="receta-fecha"> Fecha de publicación: <?php echo $fecha_publicacion; ?></p>
    </div>

    <div class="receta-seccion">
        <h3 class="receta-subtitulo">Contenido</h3>
        <div class="receta-contenido"><?php echo $contenido_post; ?></div>
    </div>
    
    <div class="receta-seccion">
        <h3 class="receta-subtitulo">Ingredientes</h3>
        <div class="receta-ingredientes"><?php echo $ingredientes_receta; ?></div>
    </div>

    <div class="receta-seccion">
        <h3 class="receta-subtitulo">Instrucciones de Preparación</h3>
        <div class="receta-instrucciones"><?php echo $instrucciones_receta; ?></div>
    </div>

    <div class="receta-seccion">
        <h3 class="receta-subtitulo">Tiempo de Preparación</h3>
        <div class="receta-tiempo"><?php echo $tiempo_preparacion_receta; ?></div>
    </div>
</div>


<link rel="stylesheet" href="<?php echo plugins_url( '../css/plantilla.css', __FILE__ ); ?>">