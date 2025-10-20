<?php /* Template Name: Pàgina Empreses */ get_header(); ?>

	
    <section class="billboard halfheight">
        <div class="noslider">
            <div class="overlay"></div>
            <div class="single-img bg-img-empreses"></div>
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
            <div class="spotlight">
                <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-empreses-1.jpg" alt="Edetària - Empreses" width="900" height="540" />
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_1'); ?>
                        
                        <div class="separator-hover1"></div>
                    </div>
                </div>
            </div><!-- /.spotlight -->
            
            <div class="spotlight reverse">
                <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-empreses-2.jpg" alt="Edetària - Empreses" width="900" height="540" />
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_2'); ?>
                        
                        <div class="separator-hover1"></div>
                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <section class="page-wrapper separator"></section>
        <?php endwhile; endif; wp_reset_postdata(); ?>
    </main>

<?php get_footer(); ?>
