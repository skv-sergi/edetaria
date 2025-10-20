<?php /* Template Name: Pàgina Vinyes i terra */ get_header(); ?>
    
    
    <section class="billboard halfheight">
        <div class="noslider">
            <div class="overlay"></div>
            <div class="single-img bg-img-vinyes_i_terra"></div>
        </div> <!-- /.noslider -->
    </section><!-- /.billboard  -->
    
    
    <main class="halfmargin">
        <?php if (have_posts()): while (have_posts()) : the_post(); ?>
        <section class="separator-header"></section>

        <section class="intro wrapper wrapper-margin">
            
            <h1><?php the_title(); ?></h1>
           
            <?php the_content(); ?>
            
        </section><!--  End Features  -->


        <section class="page-wrapper" id="els-sols">
            <div class="spotlight">
                <div class="image">
                    <div class="slider">
                        <div class="flexslider-page">
                            <ul class="slides">
                                <li>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-vinyes-els-sols-4.jpg" alt="Edetària - Vinyes i terra" width="900" height="540" />
                                    <div class="slider-caption">Panal</div>
                                </li>
                                <li>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-vinyes-els-sols-1.jpg" alt="Edetària - Vinyes i terra" width="900" height="540" />
                                    <div class="slider-caption">Còdols</div>
                                </li>
                                <li>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-vinyes-els-sols-2.jpg" alt="Edetària - El Celler" width="900" height="540" />
                                    <div class="slider-caption">Tapàs</div>
                                </li>
                                <li>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-vinyes-els-sols-3.jpg" alt="Edetària - El Celler" width="900" height="540" />
                                    <div class="slider-caption">Tapàs blanc</div>
                                </li>
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
            
            <div class="spotlight reverse">
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_2'); ?>
                        
                        <div class="separator-hover1"></div>
                    </div>
                </div>
                
                <div class="image">
                    <div class="slider">
                        <div class="flexslider-page">
                            <ul class="slides">
                                <li>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-vinyes-els-raims-1.jpg" alt="Edetària - Vinyes i terra" width="900" height="540" />
                                    <div class="slider-caption">Garnatxa fina</div>
                                </li>
                                <li>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-vinyes-els-raims-1-2.jpg" alt="Edetària - Vinyes i terra" width="900" height="540" />
                                    <div class="slider-caption">Garnatxa peluda</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <section class="separator-middle" id="les-varietats-de-raim"></section>
        
        <section class="page-wrapper">
            <div class="spotlight">
                <div class="image">
                    <div class="slider">
                        <div class="flexslider-page">
                            <ul class="slides">
                                <li>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-vinyes-els-raims-2.jpg" alt="Edetària - Vinyes i terra" width="900" height="540" />
                                    <div class="slider-caption">Garnatxa blanca</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_3'); ?>
                        
                        <div class="separator-hover1"></div>
                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <section class="separator-middle" id="conreu-ecologic"></section>
        
        <section class="page-wrapper">
            <div class="spotlight reverse">
                <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-vinyes-ecologics-1.jpg" alt="Edetària - Vinyes i terra" width="900" height="540" />
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_4'); ?>
                        
                        <div class="separator-hover1"></div>
                    </div>
                </div>
            </div><!-- /.spotlight -->
            
            <div class="spotlight">
               <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-vinyes-ecologics-2.jpg" alt="Edetària - Vinyes i terra" width="900" height="540" />
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_5'); ?>
                        
                        <div class="separator-hover1"></div>
                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <section class="separator-middle" id="projectes-experimentals"></section>
        
        <section class="page-wrapper">
            <div class="spotlight reverse">
                <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-vinyes-experimentals-1.jpg" alt="Edetària - Vinyes i terra" width="900" height="540" />
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_6'); ?>
                        
                        <div class="separator-hover1"></div>
                    </div>
                </div>
            </div><!-- /.spotlight -->
            
            <div class="spotlight">
               <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-vinyes-experimentals-2.jpg" alt="Edetària - Vinyes i terra" width="900" height="540" />
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_7'); ?>
                        
                        <div class="separator-hover1"></div>
                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <section class="separator-middle" id="d-on-ve-edetaria"></section>
        
        <section class="page-wrapper">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-vinyes_i_terra-btm.jpg" alt="Edetària - Vinyes i terra" width="1900" height="600" />
        </section>
        
        <section class="page-wrapper separator"></section>
        <?php endwhile; endif; wp_reset_postdata(); ?>
    </main>


<?php get_footer(); ?>
