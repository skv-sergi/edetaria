<?php /* Template Name: Pàgina Experiències */ get_header(); ?>
    
    
    <section class="billboard halfheight">
        <div class="noslider">
            <div class="overlay"></div>
            <div class="single-img bg-img-experiencies"></div>
        </div> <!-- /.noslider -->
    </section><!-- /.billboard  -->
    
    
    <main class="halfmargin">
        
        <section class="separator-header"></section>
        
        <?php if (have_posts()): while (have_posts()) : the_post(); ?>
        <section class="intro wrapper wrapper-margin">
            
            <h1><?php the_title(); ?></h1>
            
            <?php the_content(); ?>
            
        </section><!--  End Features  -->
        <?php endwhile; endif; wp_reset_postdata(); ?>

        
        <section class="page-wrapper">
            <div class="spotlight grid">
                <div class="container33">
                    <figure class="effect-skv3 image">
                        <img class="large-img" src="<?php echo get_template_directory_uri(); ?>/assets/images/experiencies-enoturisme.jpg" alt="Edetària" width="900" height="460" />
                        <img class="small-img" src="<?php echo get_template_directory_uri(); ?>/assets/images/experiencies-enoturisme-2.jpg" alt="Edetària" width="900" height="520" />
                        <figcaption>
                            <div class="fig-content">
                                <?php query_posts('post_type=page&name=enoturisme'); while (have_posts ()): the_post(); ?>
                                <h2><?php the_title() ?></h2>
                                <?php endwhile; wp_reset_postdata(); ?>
                                <ul>
                                    <?php query_posts('post_type=enoturisme&order=ASC'); while (have_posts ()): the_post(); ?>
                                    <li><a href="/experiencies/enoturisme#<?php global $post; $post_slug=$post->post_name; echo $post_slug; ?>"><?php the_title() ?></a></li>
                                    <?php endwhile; wp_reset_postdata(); ?>
                                </ul>
                            </div>
                            
                            <?php query_posts('post_type=page&name=enoturisme'); while (have_posts ()): the_post(); ?>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title() ?> - <?php the_excerpt() ?>">
                                <?php if(function_exists('qtranxf_getLanguage')) { ?>
                                <?php if (qtranxf_getLanguage()=='ca'): ?>
                                Veure més
                                <?php endif; ?>
                                <?php if (qtranxf_getLanguage()=='es'): ?>
                                Ver más
                                <?php endif; ?>
                                <?php if (qtranxf_getLanguage()=='en'): ?>
                                Read more
                                <?php endif; ?>
                                <?php } ?>
                            </a>
                            <?php endwhile; wp_reset_postdata(); ?>
                            
                        </figcaption>
                    </figure>
                </div>
                
                <div class="container33">
                    <?php query_posts('post_type=page&name=empreses'); while (have_posts ()): the_post(); ?>
                    <figure class="effect-skv3 image">
                        <img class="large-img" src="<?php echo get_template_directory_uri(); ?>/assets/images/experiencies-empreses.jpg" alt="Edetària" width="900" height="460" />
                        <img class="small-img" src="<?php echo get_template_directory_uri(); ?>/assets/images/experiencies-empreses-2.jpg" alt="Edetària" width="900" height="520" />
                        <figcaption>
                            <div class="fig-content">
                                <h2><?php the_title() ?></h2>
                                <p><em><?php the_excerpt() ?></em></p>
                            </div>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title() ?> - <?php the_excerpt() ?>">
                                <?php if(function_exists('qtranxf_getLanguage')) { ?>
                                <?php if (qtranxf_getLanguage()=='ca'): ?>
                                Veure més
                                <?php endif; ?>
                                <?php if (qtranxf_getLanguage()=='es'): ?>
                                Ver más
                                <?php endif; ?>
                                <?php if (qtranxf_getLanguage()=='en'): ?>
                                Read more
                                <?php endif; ?>
                                <?php } ?>
                            </a>
                        </figcaption>
                    </figure>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
                
                <div class="container33">
                    <?php query_posts('post_type=page&name=espais'); while (have_posts ()): the_post(); ?>
                    <figure class="effect-skv3 image">
                        <img class="large-img" src="<?php echo get_template_directory_uri(); ?>/assets/images/experiencies-espais.jpg" alt="Edetària" width="900" height="460" />
                        <img class="small-img" src="<?php echo get_template_directory_uri(); ?>/assets/images/experiencies-espais-2.jpg" alt="Edetària" width="900" height="520" />
                        <figcaption>
                            <div class="fig-content">
                                <h2><?php the_title() ?></h2>
                                <p><em><?php the_excerpt() ?></em></p>
                            </div>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title() ?> - <?php the_excerpt() ?>">
                                <?php if(function_exists('qtranxf_getLanguage')) { ?>
                                <?php if (qtranxf_getLanguage()=='ca'): ?>
                                Veure més
                                <?php endif; ?>
                                <?php if (qtranxf_getLanguage()=='es'): ?>
                                Ver más
                                <?php endif; ?>
                                <?php if (qtranxf_getLanguage()=='en'): ?>
                                Read more
                                <?php endif; ?>
                                <?php } ?>
                            </a>
                        </figcaption>
                    </figure>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        
        <section class="page-wrapper separator"></section>
        
    </main>


<?php get_footer(); ?>
