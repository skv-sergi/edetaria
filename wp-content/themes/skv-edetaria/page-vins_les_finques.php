<?php /* Template Name: Pàgina Vins: Les Finques */ get_header(); ?>

	
    <section class="billboard noheight">
        <div class="noslider">
            <div class="overlay"></div>
            <div class="single-img bg-img-el_celler"></div>
        </div> <!-- /.noslider -->
    </section><!-- /.billboard  -->
    
    
    <main class="nomargin">
        
        <section class="separator-header"></section>
        
        <section class="intro wrapper wrapper-margin">
            
            <h1>
                <?php if(function_exists('qtranxf_getLanguage')) { ?>
                <?php if (qtranxf_getLanguage()=='ca'): ?>
                Els vins:
                <?php endif; ?>
                <?php if (qtranxf_getLanguage()=='es'): ?>
                Los vinos:
                <?php endif; ?>
                <?php if (qtranxf_getLanguage()=='en'): ?>
                Our wines:
                <?php endif; ?>
                <?php } ?>
                 <?php the_title(); ?></h1>
            
        </section><!--  End Features  -->

        <?php if (have_posts()) : ?>
        <?php query_posts(array( 'post_type' => 'product', 'order' => 'ASC', 'tax_query' => array( 'relation' => 'AND', array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'les-nostres-finques' )))); ?>
        <?php while (have_posts()) : the_post(); ?>
        <section class="page-wrapper">
            <div class="spotlight">
                <div class="image">
                    <div class="slider">
                        <div class="flexslider-page">
                            <ul class="slides">
                                <li>
                                <?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
                                    <?php the_post_thumbnail('large'); ?>
                                <?php endif; ?>
                                </li>
                            </ul>
                        </div> <!-- /.flexslider-page -->
                    </div> <!-- /.slider -->
                </div>
                
                <div class="container">
                    <div class="content">
                        <h2 class="wine-title"><a href="<?php the_permalink(); ?>"><?php the_field('titol_span'); ?></a></h2>
                        
                        <?php the_content(); ?>
                        
                        <a href="<?php the_permalink(); ?>" class="cta">
                            <?php if(function_exists('qtranxf_getLanguage')) { ?>
                            <?php if (qtranxf_getLanguage()=='ca'): ?>
                            <span>Comprar</span>
                            <?php endif; ?>
                            <?php if (qtranxf_getLanguage()=='es'): ?>
                            <span>Comprar</span>
                            <?php endif; ?>
                            <?php if (qtranxf_getLanguage()=='en'): ?>
                            <span>Shop now</span>
                            <?php endif; ?>
                            <?php } ?>
                            <svg width="10px" height="8px" viewBox="0 0 13 10">
                                <path d="M1,5 L11,5"></path>
                                <polyline points="8 1 12 5 8 9"></polyline>
                            </svg>
                        </a>
                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <section class="separator-middle" id="un-estil-propi"></section>
        <?php endwhile; ?>
        <?php endif; ?>
        <?php wp_reset_query(); ?>
        
        
        <?php get_sidebar(); ?>
        
        
    </main>

<?php get_footer(); ?>
