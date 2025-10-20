<?php /* Template Name: Pàgina Contacte */ get_header(); ?>

	
    <section class="billboard halfheight">
        <div class="noslider">
            <div class="overlay"></div>
            <div class="single-img bg-img-contacte"></div>
        </div> <!-- /.noslider -->
    </section><!-- /.billboard  -->
    
    
    <main class="halfmargin">
        <?php if (have_posts()): while (have_posts()) : the_post(); ?>
        <section class="separator-header"></section>
        
        
        <section class="intro wrapper wrapper-margin">
            
            <h1><?php the_title(); ?></h1>
            <div class="contact-content">
            
            <?php the_content(); ?>
            
            </div>
        </section><!--  End Features  -->


        <section class="page-wrapper">
            <div class="spotlight">
                
                <div class="container">
                    <div class="content">
                        <?php if(function_exists('qtranxf_getLanguage')) { ?>
                        <?php if (qtranxf_getLanguage()=='ca'): ?>
                        <?php echo do_shortcode("[contact-form-7 id='204' title='Formulari de contacte CAT']"); ?>
                        <?php endif; ?>
                        <?php if (qtranxf_getLanguage()=='es'): ?>
                        <?php echo do_shortcode("[contact-form-7 id='203' title='Formulari de contacte ESP']"); ?>
                        <?php endif; ?>
                        <?php if (qtranxf_getLanguage()=='en'): ?>
                        <?php echo do_shortcode("[contact-form-7 id='202' title='Formulari de contacte ENG']"); ?>
                        <?php endif; ?>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="image">
                    <a class="google-link" href="https://goo.gl/maps/n23d4Zwxei82" title="Veure a Google Maps" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/google-maps-edetaria.jpg" alt="Edetària a Google Maps" width="900" height="540" /></a>
                </div>
            </div><!-- /.spotlight -->

        </section>
        
        <section class="page-wrapper separator"></section>
        <?php endwhile; endif; wp_reset_postdata(); ?>
        
    </main>

<?php get_footer(); ?>
