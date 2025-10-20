<?php /* Template Name: Pàgina Els Vins */ get_header(); ?>

	
    <section class="billboard noheight">
        <div class="noslider">
            <div class="overlay"></div>
            <div class="single-img bg-img-el_celler"></div>
        </div> <!-- /.noslider -->
    </section><!-- /.billboard  -->
    
    
    <main class="nomargin">
        
        <section class="separator-header"></section>
        
        <section class="intro wrapper wrapper-margin">
            
            <h1><?php the_title(); ?></h1>
            
        </section><!--  End Features  -->

        
        <section class="page-wrapper">
            <div class="spotlight">
                <div class="image">
                    <div class="slider">
                        <div class="flexslider-page">
                            <ul class="slides">
                            <?php if (have_posts()) : ?>
                            <?php query_posts(array( 'post_type' => 'product', 'order' => 'ASC', 'tax_query' => array( 'relation' => 'AND', array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'via-terra' )))); ?>
                            <?php while (have_posts()) : the_post(); ?>
                                <li>
                                    <?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
                                        <?php the_post_thumbnail('large'); ?>
                                    <?php endif; ?>
                                </li>
                            <?php endwhile; ?>
                            <?php endif; ?>
                            <?php wp_reset_query(); ?>
                            </ul>
                        </div> <!-- /.flexslider-page -->
                    </div> <!-- /.slider -->
                </div>
                
                <div class="container">
                    <div class="content">
                        <h2 class="wine-title"><a href="/via-terra/" title="Edetària - Via Terra"><?php the_field('titol_gamma_1'); ?></a></h2>
                        
                        <p><?php the_field('contingut_gamma_1'); ?></p>
                        
                        <nav class="cat-nav">
                            <ul>
                            <?php if (have_posts()) : ?>
                            <?php query_posts(array( 'post_type' => 'product', 'order' => 'ASC', 'tax_query' => array( 'relation' => 'AND', array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'via-terra' )))); ?>
                            <?php while (have_posts()) : the_post(); ?>
                                <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="cta"><span><?php the_title(); ?></span>
                                    <svg width="10px" height="8px" viewBox="0 0 13 10">
                                        <path d="M1,5 L11,5"></path>
                                        <polyline points="8 1 12 5 8 9"></polyline>
                                    </svg>
                                </a></li>
                            <?php endwhile; ?>
                            <?php endif; ?>
                            <?php wp_reset_query(); ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        
        <section class="separator-middle"></section>
        
        <section class="page-wrapper">
            <div class="spotlight reverse">
                <div class="image">
                    <div class="slider">
                        <div class="flexslider-page">
                            <ul class="slides">
                            <?php if (have_posts()) : ?>
                            <?php query_posts(array( 'post_type' => 'product', 'order' => 'ASC', 'tax_query' => array( 'relation' => 'AND', array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'via-edetana' )))); ?>
                            <?php while (have_posts()) : the_post(); ?>
                                <li>
                                    <?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
                                        <?php the_post_thumbnail('large'); ?>
                                    <?php endif; ?>
                                </li>
                            <?php endwhile; ?>
                            <?php endif; ?>
                            <?php wp_reset_query(); ?>
                            </ul>
                        </div> <!-- /.flexslider-page -->
                    </div> <!-- /.slider -->
                </div>
                
                <div class="container">
                    <div class="content">
                        <h2 class="wine-title"><a href="/via-edetana/" title="Edetària - Via Edetana"><?php the_field('titol_gamma_2'); ?></a></h2>
                        
                        <p><?php the_field('contingut_gamma_2'); ?></p>
                        
                        <nav class="cat-nav">
                            <ul>
                            <?php if (have_posts()) : ?>
                            <?php query_posts(array( 'post_type' => 'product', 'order' => 'ASC', 'tax_query' => array( 'relation' => 'AND', array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'via-edetana' )))); ?>
                            <?php while (have_posts()) : the_post(); ?>
                                <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="cta"><span><?php the_title(); ?></span>
                                    <svg width="10px" height="8px" viewBox="0 0 13 10">
                                        <path d="M1,5 L11,5"></path>
                                        <polyline points="8 1 12 5 8 9"></polyline>
                                    </svg>
                                </a></li>
                            <?php endwhile; ?>
                            <?php endif; ?>
                            <?php wp_reset_query(); ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <section class="separator-middle"></section>
        
        <section class="page-wrapper">
            <div class="spotlight">
                <div class="image">
                    <div class="slider">
                        <div class="flexslider-page">
                            <ul class="slides">
                            <?php if (have_posts()) : ?>
                            <?php query_posts(array( 'post_type' => 'product', 'order' => 'ASC', 'tax_query' => array( 'relation' => 'AND', array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'edetaria-seleccio' )))); ?>
                            <?php while (have_posts()) : the_post(); ?>
                                <li>
                                    <?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
                                        <?php the_post_thumbnail('large'); ?>
                                    <?php endif; ?>
                                </li>
                            <?php endwhile; ?>
                            <?php endif; ?>
                            <?php wp_reset_query(); ?>
                            </ul>
                        </div> <!-- /.flexslider-page -->
                    </div> <!-- /.slider -->
                </div>
                
                <div class="container">
                    <div class="content">
                        <h2 class="wine-title"><a href="/edetaria-seleccio/" title="Edetària Selecció"><?php the_field('titol_gamma_3'); ?></a></h2>
                        
                        <p><?php the_field('contingut_gamma_3'); ?></p>
                        
                        <nav class="cat-nav">
                            <ul>
                            <?php if (have_posts()) : ?>
                            <?php query_posts(array( 'post_type' => 'product', 'order' => 'ASC', 'tax_query' => array( 'relation' => 'AND', array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'edetaria-seleccio' )))); ?>
                            <?php while (have_posts()) : the_post(); ?>
                                <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="cta"><span><?php the_title(); ?></span>
                                    <svg width="10px" height="8px" viewBox="0 0 13 10">
                                        <path d="M1,5 L11,5"></path>
                                        <polyline points="8 1 12 5 8 9"></polyline>
                                    </svg>
                                </a></li>
                            <?php endwhile; ?>
                            <?php endif; ?>
                            <?php wp_reset_query(); ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <section class="separator-middle"></section>
        
        <section class="page-wrapper">
            <div class="spotlight reverse">
                <div class="image">
                    <div class="slider">
                        <div class="flexslider-page">
                            <ul class="slides">
                            <?php if (have_posts()) : ?>
                            <?php query_posts(array( 'post_type' => 'product', 'order' => 'ASC', 'tax_query' => array( 'relation' => 'AND', array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'les-nostres-finques' )))); ?>
                            <?php while (have_posts()) : the_post(); ?>
                                <li>
                                    <?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
                                        <?php the_post_thumbnail('large'); ?>
                                    <?php endif; ?>
                                </li>
                            <?php endwhile; ?>
                            <?php endif; ?>
                            <?php wp_reset_query(); ?>
                            </ul>
                        </div> <!-- /.flexslider-page -->
                    </div> <!-- /.slider -->
                </div>
                
                <div class="container">
                    <div class="content">
                        <h2 class="wine-title"><a href="/les-nostres-finques/" title="Edetària - Les nostres finques"><?php the_field('titol_gamma_4'); ?></a></h2>
                        
                        <p><?php the_field('contingut_gamma_4'); ?></p>
                        
                        <nav class="cat-nav">
                            <ul>
                            <?php if (have_posts()) : ?>
                            <?php query_posts(array( 'post_type' => 'product', 'order' => 'ASC', 'tax_query' => array( 'relation' => 'AND', array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'les-nostres-finques' )))); ?>
                            <?php while (have_posts()) : the_post(); ?>
                                <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="cta"><span><?php the_title(); ?></span>
                                    <svg width="10px" height="8px" viewBox="0 0 13 10">
                                        <path d="M1,5 L11,5"></path>
                                        <polyline points="8 1 12 5 8 9"></polyline>
                                    </svg>
                                </a></li>
                            <?php endwhile; ?>
                            <?php endif; ?>
                            <?php wp_reset_query(); ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <section class="separator-middle"></section>
        
        <section class="page-wrapper">
            <div class="spotlight">
                <div class="image">
                    <div class="slider">
                        <div class="flexslider-page">
                            <ul class="slides">
                            <?php if (have_posts()) : ?>
                            <?php query_posts(array( 'post_type' => 'product', 'order' => 'ASC', 'tax_query' => array( 'relation' => 'AND', array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'lots' )))); ?>
                            <?php while (have_posts()) : the_post(); ?>
                                <li>
                                    <?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
                                        <?php the_post_thumbnail('large'); ?>
                                    <?php endif; ?>
                                </li>
                            <?php endwhile; ?>
                            <?php endif; ?>
                            <?php wp_reset_query(); ?>
                            </ul>
                        </div> <!-- /.flexslider-page -->
                    </div> <!-- /.slider -->
                </div>
                
                <div class="container">
                    <div class="content">
                        <h2 class="wine-title"><a href="/lots/" title="Edetària - Les nostres finques"><?php the_field('titol_gamma_5'); ?></a></h2>
                        
                        <p><?php the_field('contingut_gamma_5'); ?></p>
                        
                        <nav class="cat-nav">
                            <ul>
                            <?php if (have_posts()) : ?>
                            <?php query_posts(array( 'post_type' => 'product', 'order' => 'ASC', 'tax_query' => array( 'relation' => 'AND', array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'lots' )))); ?>
                            <?php while (have_posts()) : the_post(); ?>
                                <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="cta"><span><?php the_title(); ?></span>
                                    <svg width="10px" height="8px" viewBox="0 0 13 10">
                                        <path d="M1,5 L11,5"></path>
                                        <polyline points="8 1 12 5 8 9"></polyline>
                                    </svg>
                                </a></li>
                            <?php endwhile; ?>
                            <?php endif; ?>
                            <?php wp_reset_query(); ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <section class="page-wrapper separator"></section>
        
    </main>

<?php get_footer(); ?>
