<?php /* Template Name: Pàgina El Celler */ get_header(); ?>
    
    
    <section class="billboard halfheight">
        <div class="noslider">
            <div class="overlay"></div>
            <div class="single-img bg-img-el_celler"></div>
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
            <div class="spotlight" id="la-verema">
                <div class="image">
                    <div class="slider">
                        <div class="flexslider-page">
                            <ul class="slides">
                                <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-elceller-2.jpg" alt="Edetària - El Celler" width="900" height="520" /></li>
                                <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-elceller-verema-1.jpg" alt="Edetària - El Celler" width="900" height="520" /></li>
                                <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-elceller-verema-2.jpg" alt="Edetària - El Celler" width="900" height="520" /></li>
                            </ul>
                        </div> <!-- /.flexslider-page -->
                    </div> <!-- /.slider -->
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_1'); ?>
                        
                        <div class="separator-hover1"></div>
                    </div>
                </div>
            </div><!-- /.spotlight -->
            
            <div class="spotlight reverse" id="vinificacions">
                <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-elceller-4.jpg" alt="Edetària - El Celler" width="900" height="520" />
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_2'); ?>
                        
                        <div class="separator-hover1"></div>
                    </div>
                </div>
            </div><!-- /.spotlight -->
            
            <div class="spotlight" id="criansa">
                <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-elceller-3.jpg" alt="Edetària - El Celler" width="900" height="520" />
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_3'); ?>
                        
                        <div class="separator-hover1"></div>
                    </div>
                </div>
            </div><!-- /.spotlight -->
            
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-elceller-bottom.jpg" alt="Edetària - El celler" width="1900" height="551" />
        </section>
        
        <section class="page-wrapper separator"></section>
        <?php endwhile; endif; wp_reset_postdata(); ?>
    </main>





<?php get_footer(); ?>
