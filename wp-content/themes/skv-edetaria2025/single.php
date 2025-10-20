<?php get_header(); ?>


    <section class="billboard noheight">
        <div class="noslider">
            <div class="overlay"></div>
            <div class="single-img bg-img-noticies"></div>
        </div> <!-- /.noslider -->
    </section><!-- /.billboard  -->
    
    
    <main class="nomargin">
        <?php if (have_posts()): while (have_posts()) : the_post(); ?>
        <section class="separator-header"></section>
        
        
        <section class="wrapper wrapper-margin">
            <div class="spotlight">
                <div class="image">
                    <!-- post thumbnail -->
                    <?php if ( has_post_thumbnail()) : // Check if Thumbnail exists ?>
                        <?php the_post_thumbnail('medium_large'); // Fullsize image for the single post ?>
                    <?php endif; ?>
                    <!-- /post thumbnail -->
                </div>
                
                <div class="container">
                    <article class="content post">
                        <div class="entry-header">
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                            <div class="entry-meta single-post-meta">
                                <span class="post-category"><?php _e( '', 'html5blank' ); the_category(', '); // Separated by commas ?></span>

                                <span class="post-date"><?php the_time('F j, Y'); ?> <?php the_time('g:i a'); ?></span>

                                <span class="post-author"><?php the_author(); ?></span>
                            </div>
                        </div>
                    </article>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        
        <section class="wrapper wrapper-margin20">
            <article class="entry-content">
               
                <?php the_content(); // Dynamic Content ?>

            </article>
        </section>
        
        
        <section class="wrapper wrapper-margin">
            <div class="pagination">
                <?php ja_prev_next_post_nav(); ?>
            </div>
        </section>
        
        
        <section class="wrapper wrapper-margin">
            <aside class="aside">
                <?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-1')) ?>
            </aside>
        </section>
        
        
        <section class="page-wrapper separator"></section>
        <?php endwhile; endif; wp_reset_postdata(); ?>
    </main>


<?php get_footer(); ?>
