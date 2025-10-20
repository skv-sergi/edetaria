<?php /* Template Name: Pàgina Press Room */ get_header(); ?>
    
   
    <main class="nomargin">
        
        <section class="separator-header"></section>

        <section class="intro wrapper wrapper-margin">
            
            <h1><?php the_title(); ?></h1>
            
        </section><!--  End Features  -->
        
        
        <section class="wrapper">
            <?php
            /**
            * list taxonomy accoeding to the custom post type
            * https://gist.github.com/ajithrn/9730807
            * ref: http://codex.wordpress.org/Function_Reference/get_terms
            */
            $taxonomy = 'pressroom-categories';
            $tax_terms = get_terms($taxonomy, array('orderby' => 'name', 'hide_empty' => 1, 'current_category'   => 0  ) );
            echo '<ul class="pressroom-categories">';
            foreach ($tax_terms as $tax_term) {
                echo '<li>' . '<a href="' . esc_attr(get_term_link($tax_term, $taxonomy)) . '" title="' . sprintf( __( "View all posts in %s" ), $tax_term->name ) . '" ' . '>' . $tax_term->name.'</a></li>';
            }
            echo '</ul>';
            ?>
        </section>


        <?php if (have_posts()) : ?>
        <?php query_posts(array( 'post_type' => 'pressroom', 'order' => 'ASC', 'posts_per_page' => 24, 'paged' => $paged )); ?>
        <?php while (have_posts()) : the_post(); ?>
        <section class="wrapper wrapper-margin">
            <div class="spotlight">
                <div class="image entry-image">
                    <?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
                        <?php the_post_thumbnail('large'); ?>
                    <?php else: ?>
                        <div class="no-image">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 width="512px" height="512px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                            <path d="M399.3,168.9c-0.7-2.9-2-5-3.5-6.8l-83.7-91.7c-1.9-2.1-4.1-3.1-6.6-4.4c-2.9-1.5-6.1-1.6-9.4-1.6H136.2
                                c-12.4,0-23.7,9.6-23.7,22.9v335.2c0,13.4,11.3,25.9,23.7,25.9h243.1c12.4,0,21.2-12.5,21.2-25.9V178.4
                                C400.5,174.8,400.1,172.2,399.3,168.9z M305.5,111l58,63.5h-58V111z M144.5,416.5v-320h129v81.7c0,14.8,13.4,28.3,28.1,28.3h66.9
                                v210H144.5z"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="container entry-container">
                    <article class="content post download-post">
                        <div class="entry-header">
                            <h2><?php the_title(); ?></h2>
                        </div>
                        <div class="entry-excerpt clearfix">
                            
                            <?php the_content(); ?>
                            
                            <div class="read-more cl-effect-14">

                            <?php if( get_field('link_del_document') ): ?>
                                <a href="<?php the_field('link_del_document'); ?>" class="more-link" target="_blank">
                                    <?php if(function_exists('qtranxf_getLanguage')) { ?>
                                    <?php if (qtranxf_getLanguage()=='ca'): ?>
                                    Descarregar arxiu 
                                    <?php endif; ?>
                                    <?php if (qtranxf_getLanguage()=='es'): ?>
                                    Descargar archivo 
                                    <?php endif; ?>
                                    <?php if (qtranxf_getLanguage()=='en'): ?>
                                    Download file 
                                    <?php endif; ?>
                                    <?php } ?>
                                    <span class="meta-nav">↓</span></a>
                            <?php else: ?>
                                <a href="<?php the_post_thumbnail_url('full'); ?>" class="more-link" target="_blank">
                                    <?php if(function_exists('qtranxf_getLanguage')) { ?>
                                    <?php if (qtranxf_getLanguage()=='ca'): ?>
                                    Descarregar imatge 
                                    <?php endif; ?>
                                    <?php if (qtranxf_getLanguage()=='es'): ?>
                                    Descargar imagen 
                                    <?php endif; ?>
                                    <?php if (qtranxf_getLanguage()=='en'): ?>
                                    Download image 
                                    <?php endif; ?>
                                    <?php } ?>
                                    <span class="meta-nav">↓</span></a>
                            <?php endif; ?>
                            
                            </div>
                        </div>
                    </article>
                </div>
            </div><!-- /.spotlight -->
        </section>
        <?php endwhile; endif; wp_reset_postdata(); ?>
           
            
        <section class="wrapper wrapper-margin">
            <div class="pagination">
                <?php wp_numeric_posts_nav(); ?>
            </div>
        </section>    
            
            
        <section class="page-wrapper separator"></section>
        
    </main>


<?php get_footer(); ?>
