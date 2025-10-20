<?php get_header(); ?>


    <section class="billboard noheight">
        <div class="noslider">
            <div class="overlay"></div>
            <div class="single-img bg-img-noticies"></div>
        </div> <!-- /.noslider -->
    </section><!-- /.billboard  -->
    
    
    <main class="nomargin">
        
        <section class="separator-header"></section>
        

        <section class="intro wrapper wrapper-margin">
            
            <h1><?php _e( 'Categories for ', 'html5blank' ); single_cat_title(); ?></h1>
            
        </section><!--  End Features  -->

        
        <?php if (have_posts()): while (have_posts()) : the_post(); ?>
        <section class="wrapper wrapper-margin20">
            <div class="spotlight">
                <div class="image entry-image">
                <?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
                    <?php the_post_thumbnail('medium_large'); ?>
                <?php endif; ?>
                </div>
                
                <div class="container entry-container">
                    <article class="content post">
                        <div class="entry-header">
                            <h2>
                                <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <div class="entry-meta">
                                <span class="post-category"><?php _e( '', 'html5blank' ); the_category(', '); // Separated by commas ?></span>

                                <span class="post-date"><?php the_time('F j, Y'); ?> <?php the_time('g:i a'); ?></span>

                                <span class="post-author"><?php the_author(); ?></span>

                                <!--<span class="comments-link"><?php if (comments_open( get_the_ID() ) ) comments_popup_link( __( '[:ca]Deixa el teu comentari[:es]Deja tu comentario[:en]Leave your thoughts', 'html5blank' ), __( '1 Comment', 'html5blank' ), __( '% Comments', 'html5blank' )); ?></span>-->
                            </div>
                        </div>
                        <div class="entry-excerpt clearfix">
                            <p><?php html5wp_excerpt('html5wp_index'); ?></p>
                            <div class="read-more cl-effect-14">
                                <a href="<?php the_permalink() ?>" class="more-link" title="<?php the_title_attribute(); ?>">
                                
                                    <?php if(function_exists('qtranxf_getLanguage')) { ?>
                                    <?php if (qtranxf_getLanguage()=='ca'): ?>
                                    Seguir llegint
                                    <?php endif; ?>
                                    <?php if (qtranxf_getLanguage()=='es'): ?>
                                    Sigue leyendo
                                    <?php endif; ?>
                                    <?php if (qtranxf_getLanguage()=='en'): ?>
                                    Continue reading
                                    <?php endif; ?>
                                    <?php } ?>
                                
                                <span class="meta-nav"> →</span></a>
                            </div>
                        </div>
                    </article>
                </div>
            </div><!-- /.spotlight -->
        </section>
        <?php endwhile; ?>
        <?php endif; ?>
         
        
        <section class="wrapper wrapper-margin">
            <div class="pagination">
                <?php wp_numeric_posts_nav(); ?>
            </div>
        </section>
        
        
        <section class="wrapper wrapper-margin">
            <aside class="aside">
                <?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-1')) ?>
            </aside>
        </section>
        
        
        <section class="page-wrapper separator"></section>
        
    </main>


<?php get_footer(); ?>
