<?php /* Template Name: Pàgina Espais */ get_header(); ?>

	
    <section class="billboard halfheight">
        <div class="noslider">
            <div class="overlay"></div>
            <div class="single-img bg-img-espais"></div>
        </div> <!-- /.noslider -->
    </section><!-- /.billboard  -->
    
    
    <main class="halfmargin">
        <?php if (have_posts()): while (have_posts()) : the_post(); ?>
        <section class="separator-header"></section>
        
        <section class="intro wrapper wrapper-margin">
            
            <h1><?php the_title(); ?></h1>
            
            <?php the_content(); ?>
            
        </section><!--  End Features  -->

        
        <section class="page-wrapper">
            <div class="spotlight spotlight-full">
                <div class="container container-full">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_1'); ?>
                        
                    </div>
                </div>
            </div><!-- /.spotlight -->
            
            <div class="spotlight">
                <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-espais-sala_gran.jpg" alt="Edetària - Espais" width="900" height="540" />
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_2'); ?>
                        
                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <section class="separator-middle" id="d-on-ve-edetaria"></section>
        
        <section class="page-wrapper">
            <div class="spotlight spotlight-full">
                <div class="container container-full">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_3'); ?>
                        
                    </div>
                </div>
            </div><!-- /.spotlight -->
            
            <div class="spotlight">
                <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-espais-sala_petita.jpg" alt="Edetària - Espais" width="900" height="540" />
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_4'); ?>
                        
                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <section class="separator-middle" id="d-on-ve-edetaria"></section>
        
        <section class="page-wrapper">
            <div class="spotlight spotlight-full">
                <div class="container container-full">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_5'); ?>
                        
                    </div>
                </div>
            </div><!-- /.spotlight -->
            
            <div class="spotlight">
                <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-espais-terrassa.jpg" alt="Edetària - Espais" width="900" height="540" />
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_6'); ?>
                        
                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <section class="separator-middle" id="d-on-ve-edetaria"></section>
        
        <section class="page-wrapper">
            <div class="spotlight spotlight-full">
                <div class="container container-full">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_7'); ?>
                        
                    </div>
                </div>
            </div><!-- /.spotlight -->
            
            <div class="spotlight">
                <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-espais-oliveres.jpg" alt="Edetària - Espais" width="900" height="540" />
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_8'); ?>
                        
                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <section class="separator-middle" id="d-on-ve-edetaria"></section>
        
        <section class="page-wrapper">
            <div class="spotlight spotlight-full">
                <div class="container container-full">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_9'); ?>
                        
                    </div>
                </div>
            </div><!-- /.spotlight -->
            
            <div class="spotlight">
                <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-espais-mas.jpg" alt="Edetària - Espais" width="900" height="520" />
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_10'); ?>
                        
                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <section class="separator-middle" id="d-on-ve-edetaria"></section>
        
        <section class="page-wrapper">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-espais-btm.jpg" alt="Edetària - Vinyes i terra" width="1900" height="521" />
        </section>
        
        <section class="page-wrapper separator"></section>
        <?php endwhile; endif; wp_reset_postdata(); ?>
    </main>

<?php get_footer(); ?>
