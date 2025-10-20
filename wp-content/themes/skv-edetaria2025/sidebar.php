<section class="wrapper wrapper-margin wrapper-aside">
            
    <?php if(function_exists('qtranxf_getLanguage')) { ?>
    <?php if (qtranxf_getLanguage()=='ca'): ?>
    <h2 class="aside-title">Seleccioneu un vi</h2>
    <?php endif; ?>
    <?php if (qtranxf_getLanguage()=='es'): ?>
    <h2 class="aside-title">Seleccione un vino</h2>
    <?php endif; ?>
    <?php if (qtranxf_getLanguage()=='en'): ?>
    <h2 class="aside-title">Select a wine</h2>
    <?php endif; ?>
    <?php } ?>
    

    <aside class="aside all-wines">
        <div class="widget">		
            <h3 class="widget-title"><a href="/via-terra/">Via <span>Terra</span></a></h3>		
            <nav class="cat-nav">
                <ul>
                <?php if (have_posts()) : ?>
                <?php query_posts(array( 'post_type' => 'product', 'order' => 'ASC', 'tax_query' => array( 'relation' => 'AND', array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'via-terra' )))); ?>
                <?php while (have_posts()) : the_post(); ?>
                    <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="cta"><span><?php the_title(); ?></span></a></li>
                <?php endwhile; ?>
                <?php endif; ?>
                <?php wp_reset_query(); ?>
                </ul>
            </nav>
        </div>
        <div class="widget">		
            <h3 class="widget-title"><a href="/via-edetana/">Via <span>Edetana</span></a></h3>		
            <nav class="cat-nav">
                <ul>
                <?php if (have_posts()) : ?>
                <?php query_posts(array( 'post_type' => 'product', 'order' => 'ASC', 'tax_query' => array( 'relation' => 'AND', array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'via-edetana' )))); ?>
                <?php while (have_posts()) : the_post(); ?>
                    <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="cta"><span><?php the_title(); ?></span></a></li>
                <?php endwhile; ?>
                <?php endif; ?>
                <?php wp_reset_query(); ?>
                </ul>
            </nav>
        </div>

        <div class="widget">
            <h3 class="widget-title"><a href="/edetaria-seleccio/">Edetària <span>Selecció</span></a></h3>		
            <nav class="cat-nav">
                <ul>
                <?php if (have_posts()) : ?>
                <?php query_posts(array( 'post_type' => 'product', 'order' => 'ASC', 'tax_query' => array( 'relation' => 'AND', array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'edetaria-seleccio' )))); ?>
                <?php while (have_posts()) : the_post(); ?>
                    <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="cta"><span><?php the_title(); ?></span></a></li>
                <?php endwhile; ?>
                <?php endif; ?>
                <?php wp_reset_query(); ?>
                </ul>
            </nav>
        </div>

        <div class="widget">
            <h3 class="widget-title"><a href="/les-nostres-finques/">Les nostres <span>finques</span></a></h3>		
            <nav class="cat-nav">
                <ul>
                <?php if (have_posts()) : ?>
                <?php query_posts(array( 'post_type' => 'product', 'order' => 'ASC', 'tax_query' => array( 'relation' => 'AND', array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'les-nostres-finques' )))); ?>
                <?php while (have_posts()) : the_post(); ?>
                    <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="cta"><span><?php the_title(); ?></span></a></li>
                <?php endwhile; ?>
                <?php endif; ?>
                <?php wp_reset_query(); ?>
                </ul>
            </nav>
        </div>
        
    </aside>
    
    <?php if(function_exists('qtranxf_getLanguage')) { ?>
    <?php if (qtranxf_getLanguage()=='ca'): ?>
    <h3 class="widget-title widget-title-btm"><a href="/lots/">Col·leccions <span>Especials</span></a></h3>
    <?php endif; ?>
    <?php if (qtranxf_getLanguage()=='es'): ?>
    <h3 class="widget-title widget-title-btm"><a href="/lots/">Colecciones <span>Especiales</span></a></h3>
    <?php endif; ?>
    <?php if (qtranxf_getLanguage()=='en'): ?>
    <h3 class="widget-title widget-title-btm"><a href="/lots/">Special <span>Collections</span></a></h3>
    <?php endif; ?>
    <?php } ?>
    
</section>


<section class="page-wrapper separator"></section>
