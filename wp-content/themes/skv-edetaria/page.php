<?php get_header(); ?>
    
   
    <section class="billboard halfheight">
        <div class="noslider">
            <div class="overlay"></div>
            <div class="single-img bg-img-edetaria"></div>
        </div> <!-- /.noslider -->
    </section><!-- /.billboard  -->
    
    
    <main class="halfmargin">
        <?php if (have_posts()): while (have_posts()) : the_post(); ?>
        <section class="separator-header"></section>
        
        
        <section class="intro wrapper wrapper-margin">
            
            <h1><?php the_title(); ?></h1>
           
            <?php the_content(); ?>
            
        </section><!--  End Features  -->
        
        
        <section class="page-wrapper separator"></section>
        <?php endwhile; endif; wp_reset_postdata(); ?>
    </main>


<?php get_footer(); ?>
