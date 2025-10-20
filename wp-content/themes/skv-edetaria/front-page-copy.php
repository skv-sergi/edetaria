<?php get_header(); ?>


    <section class="billboard fullheight">
        <div class="title-block">
            <div class="title-bg">
                <img class="main-logo" src="<?php echo get_template_directory_uri(); ?>/assets/images/edetaria-logo-tot.svg" alt="Edetària logo" width="500" height="500" />
                <h1 class="big-title"><?php the_excerpt() ?></h1>
                <?php if (have_posts()): while (have_posts()) : the_post(); ?>
                <h2 class="title-desc"><?php the_content(); ?></h2>
                <?php endwhile; endif; wp_reset_postdata(); ?>
            </div>
        </div>
        <div class="slider">
            <div class="flexslider">
                <ul class="slides">
                    <li>
                        <div class="overlay"></div>
                        <div class="slider-caption visible-md visible-lg"></div>
                    </li>
                    <li>
                        <div class="overlay"></div>
                        <div class="slider-caption visible-md visible-lg"></div>
                    </li>
                </ul>
            </div> <!-- /.flexslider -->
        </div> <!-- /.slider -->
    </section><!-- /.billboard  -->
    
    
    <main class="fullmargin">
       
       <section class="page-wrapper">
            <div class="spotlight grid">
                <?php query_posts('post_type=page&name=edetaria'); while (have_posts ()): the_post(); ?>
                <figure class="effect-skv image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/som-01.jpg" alt="Edetària" width="900" height="520" />
                    <figcaption>
                        <div class="fig-content">
                            <h3><?php the_title() ?></h3>
                            <h2><?php the_excerpt() ?></h2>
                            <div class="fig-subcontent">
                                <p class="to-anchor"><a href="<?php the_permalink(); ?>#qui-som"><?php the_field('titol_del_link_1'); ?></a></p>
                                <p class="to-anchor"><a href="<?php the_permalink(); ?>#un-estil-propi"><?php the_field('titol_del_link_2'); ?></a></p>
                                <p class="to-anchor"><a href="<?php the_permalink(); ?>#territori"><?php the_field('titol_del_link_3'); ?></a></p>
                                <p class="to-anchor"><a href="<?php the_permalink(); ?>#d-on-ve-edetaria"><?php the_field('titol_del_link_4'); ?></a></p>
                                <p class="to-anchor"><a href="<?php the_permalink(); ?>#equip"><?php the_field('titol_del_link_5'); ?></a></p>
                                <div class="separator-hover2"></div>
                            </div>
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
                <?php query_posts('post_type=page&name=vinyes-i-terra'); while (have_posts ()): the_post(); ?>
                <figure class="effect-skv2 color1 image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/vinyes-i-terra-01.jpg" alt="Edetària" width="900" height="520" />
                    <figcaption>
                        <div class="fig-content">
							<h2><?php the_title() ?></h2>
							<p><?php the_excerpt() ?></p>
                            <div class="fig-subcontent">
                                <p class="to-anchor"><a href="<?php the_permalink(); ?>#els-sols"><?php the_field('titol_del_link_1'); ?></a></p>
                                <p class="to-anchor"><a href="<?php the_permalink(); ?>#un-estil-propi"><?php the_field('titol_del_link_2'); ?></a></p>
                                <p class="to-anchor"><a href="<?php the_permalink(); ?>#territori"><?php the_field('titol_del_link_3'); ?></a></p>
                                <p class="to-anchor"><a href="<?php the_permalink(); ?>#territori"><?php the_field('titol_del_link_4'); ?></a></p>
                            </div>
                            <div class="separator-hover6"></div>
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
            </div><!-- /.spotlight -->
        </section>
            
            
        <section class="page-wrapper">
            <div class="spotlight grid">
                <div class="container-full">
                    <?php query_posts('post_type=page&name=shop'); while (have_posts ()): the_post(); ?>
                    <figure class="effect-skv2 image">
                        <img class="large-img" src="<?php echo get_template_directory_uri(); ?>/assets/images/els-vins.jpg" alt="Edetària" width="1800" height="460" />
                        <img class="small-img" src="<?php echo get_template_directory_uri(); ?>/assets/images/els-vins-s.jpg" alt="Edetària" width="900" height="520" />
                        <figcaption>
                            <div class="fig-content">
                                <?php if(function_exists('qtranxf_getLanguage')) { ?>
                                <?php if (qtranxf_getLanguage()=='ca'): ?>
                                <h2>Els vins</h2>
                                <?php endif; ?>
                                <?php if (qtranxf_getLanguage()=='es'): ?>
                                <h2>Los vinos</h2>
                                <?php endif; ?>
                                <?php if (qtranxf_getLanguage()=='en'): ?>
                                <h2>Our wines</h2>
                                <?php endif; ?>
                                <?php } ?>
                                <p><?php the_excerpt() ?></p>
                            </div>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title() ?> - <?php the_excerpt() ?>">
                                <?php if(function_exists('qtranxf_getLanguage')) { ?>
                                <?php if (qtranxf_getLanguage()=='ca'): ?>
                                Comprar online
                                <?php endif; ?>
                                <?php if (qtranxf_getLanguage()=='es'): ?>
                                Comprar online
                                <?php endif; ?>
                                <?php if (qtranxf_getLanguage()=='en'): ?>
                                Buy online
                                <?php endif; ?>
                                <?php } ?>
                            </a>
                        </figcaption>
                    </figure>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </section>
          
            
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
        
        
        <section class="page-wrapper">
            <div class="spotlight grid">
                <?php query_posts('post_type=page&name=el-celler'); while (have_posts ()): the_post(); ?>
                <figure class="effect-skv color1 image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/el-celler.jpg" alt="Edetària" width="900" height="520" />
                    <figcaption>
                        <div class="fig-content">
                            <h3><?php the_title() ?></h3>
                            <h2><?php the_excerpt() ?></h2>
                            <div class="fig-subcontent">
                                <p class="to-anchor"><a href="<?php the_permalink(); ?>#la-verema"><?php the_field('titol_link_2'); ?></a></p>
                                <p class="no-bg"><em><?php the_field('frase_destacada_2'); ?></em></p>
                                <p class="to-anchor"><a href="<?php the_permalink(); ?>#vinificacions"><?php the_field('titol_link_3'); ?></a></p>
                                <p class="no-bg"><em><?php the_field('frase_destacada_3'); ?></em></p>
                                <p class="to-anchor"><a href="<?php the_permalink(); ?>#criansa"><?php the_field('titol_link_1'); ?></a></p>
                                <p class="no-bg"><em><?php the_field('frase_destacada_1'); ?></em></p>
                            </div>
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
                
                <figure class="effect-skv image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/noticies.jpg" alt="Edetària" width="900" height="520" />
                    <figcaption>
                        <div class="fig-content">
                            <?php query_posts('post_type=page&name=noticies'); while (have_posts ()): the_post(); ?>
							<h3><?php the_title() ?></h3>
							<h2></h2>
							<?php endwhile; wp_reset_postdata(); ?>
							
							<?php $the_query = new WP_Query( 'posts_per_page=4' ); ?>
                            <?php while ($the_query -> have_posts()) : $the_query -> the_post(); ?>
                            
							<p><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></p>
                           
                           <?php endwhile; wp_reset_postdata(); ?>
                        </div>
				        
                        <?php if(function_exists('qtranxf_getLanguage')) { ?>
                        <?php if (qtranxf_getLanguage()=='ca'): ?>
                        <a href="/noticies" title="Veure totes les notícies">Totes les notícies</a>
                        <?php endif; ?>
                        <?php if (qtranxf_getLanguage()=='es'): ?>
                        <a href="/es/noticies" title="Ver todas las noticias">Todas las noticias</a>
                        <?php endif; ?>
                        <?php if (qtranxf_getLanguage()=='en'): ?>
                        <a href="/en/noticies" title="See all news">All news</a>
                        <?php endif; ?>
                        <?php } ?>
                        
                    </figcaption>
                </figure>
            </div><!-- /.spotlight -->
        </section>
        
        
        <section class="page-wrapper separator"></section>
        
    </main>


<?php get_footer(); ?>
