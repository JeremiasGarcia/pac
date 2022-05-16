<?php
/**
 * Template name: Agregar usuarios
 *
 * @package flatsome
 */


if(flatsome_option('pages_template') != 'default') {
	
	// Get default template from theme options.
	get_template_part('page', flatsome_option('pages_template'));
	return;

} else {

get_header();
do_action( 'flatsome_before_page' ); ?>
<div id="content" class="content-area page-wrapper" role="main">
	<div class="row row-main">
        <form method="post" action="<?php echo admin_url( 'admin-post.php' ) ?>" >
            <label for="name">Nombre de usuario:</label>
            <input type="text" name="user" id="user" required>

            <br>
            <label for="email">Correo electrónico:</label>
            <input type="email" name="email" id="email" required>

            <br>
            <label for="email">Nombre:</label>
            <input type="text" name="name" id="name" required>

            <br>
            <label for="email">Apellido:</label>
            <input type="text" name="lastname" id="lastname" required>

            <br>
            <label for="message">Contraseña:</label>
            <input type="password" name="password" id="password" required>

            <?php 
            $current_user = wp_get_current_user();
            $caps = $current_user->caps;
            $rol = "";
            foreach($caps as $capsKey => $capsValue){
                $rol = $capsKey;
            ?>
                <select name="caps" id="caps">
                    <option value="<?php echo $rol ?>"><?php echo $rol ?></option>
                </select>
            <?php
            }
            ?>

            <br>
            <input type="hidden" name="action" value="add_user">
            <input type="submit" name="submit" value="Enviar">
        </form>
        </div>
    </div>
    
    <?php
do_action( 'flatsome_after_page' );
get_footer();

}

?>
<!-- <div class="large-12 col">
    <div class="col-inner">
        
        <?php if(get_theme_mod('default_title', 0)){ ?>
        <header class="entry-header">
            <h1 class="entry-title mb uppercase"><?php the_title(); ?></h1>
        </header>
        <?php } ?>

        <?php while ( have_posts() ) : the_post(); ?>
            <?php do_action( 'flatsome_before_page_content' ); ?>
            
                <?php the_content(); ?>

                <?php if ( comments_open() || '0' != get_comments_number() ){
                    comments_template(); } ?>

            <?php do_action( 'flatsome_after_page_content' ); ?>
        <?php endwhile; // end of the loop. ?>
    </div>
</div> -->