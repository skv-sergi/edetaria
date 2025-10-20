<?php /* Template Name: Pàgina Edetària */ get_header(); ?>

	
    <section class="billboard halfheight">
        <div class="noslider">
            <div class="overlay"></div>
            <div class="single-img bg-img-edetaria"></div>
        </div> <!-- /.noslider -->
    </section><!-- /.billboard  -->
    
    
    <main class="halfmargin">
        <?php if (have_posts()): while (have_posts()) : the_post(); ?>
        <section class="separator-header"></section>
        
        <section class="intro wrapper wrapper-margin" id="qui-som">
            
            <h1><?php the_title(); ?></h1>
            
            <?php the_content(); ?>
            
        </section><!--  End Features  -->

        <section class="page-wrapper">
            <div class="spotlight">
                <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-edetaria-qui_som-2.jpg" alt="Edetària - Qui som" width="900" height="520" />
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
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-edetaria-qui_som-1.jpg" alt="Edetària - Qui som" width="900" height="520" />
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <section class="separator-middle" id="un-estil-propi"></section>
        
        <section class="page-wrapper">
            <div class="spotlight">
                <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-edetaria-estil_propi-1.jpg" alt="Edetària - Un estil propi" width="900" height="520" />
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_3'); ?>
                        
                        <div class="separator-hover1"></div>
                    </div>
                </div>
            </div><!-- /.spotlight -->
            
            <div class="spotlight reverse">
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_4'); ?>
                        
                        <div class="separator-hover1"></div>
                    </div>
                </div>
                
                <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-edetaria-estil_propi-2.jpg" alt="Edetària - Un estil propi" width="900" height="520" />
                </div>
            </div><!-- /.spotlight -->
            
            <div class="spotlight">
                <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-edetaria-estil_propi-3.jpg" alt="Edetària - Un estil propi" width="900" height="520" />
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_5'); ?>
                        
                        <div class="separator-hover1"></div>
                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <section class="separator-middle" id="territori"></section>
        
        <section class="page-wrapper">
            <div class="spotlight reverse">
                <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-edetaria-territori-1.jpg" alt="Edetària - Territori" width="900" height="520" />
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
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-edetaria-territori-2.jpg" alt="Edetària - Territori" width="900" height="520" />
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_7'); ?>
                        
                        <div class="separator-hover1"></div>
                    </div>
                </div>
            </div><!-- /.spotlight -->
            
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-edetaria-territori-3-large.jpg" alt="Edetària - Territori" width="1900" height="600" />
        </section>
        
        <section class="separator-middle" id="d-on-ve-edetaria"></section>
        
        <section class="page-wrapper">
            <div class="spotlight reverse">
                <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-edetaria-via_edetaria-1.jpg" alt="Edetària - D'on ve Via Edetària" width="900" height="580" />
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_8'); ?>
                        
                        <div class="separator-hover1"></div>
                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <section class="separator-middle" id="equip"></section>
        
        <section class="page-wrapper">
            <div class="spotlight">
                <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-edetaria-equip-1.jpg" alt="Edetària - L'equip" width="900" height="520" />
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_9'); ?>
                        
                        <div class="separator-hover1"></div>
                    </div>
                </div>
            </div><!-- /.spotlight -->
            
            <div class="spotlight reverse">
                <div class="image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-edetaria-equip-2.jpg" alt="Edetària - L'equip" width="900" height="520" />
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('contingut_addicional_10'); ?>
                        
                        <div class="separator-hover1"></div>
                    </div>
                </div>
            </div><!-- /.spotlight -->

            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/page-edetaria-equip-2-large.jpg" alt="Edetària - L'equip" width="1900" height="600" />
        </section>
        
        <section class="page-wrapper separator"></section>
        <?php endwhile; endif; wp_reset_postdata(); ?>
    </main>

<?php get_footer(); ?>
