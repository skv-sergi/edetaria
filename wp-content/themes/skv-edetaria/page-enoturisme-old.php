<?php /* Template Name: Pàgina Enoturisme (old) */ get_header(); ?>

	
    <section class="billboard halfheight">
        <div class="noslider">
            <div class="overlay"></div>
            <div class="single-img bg-img-enoturisme"></div>
        </div> <!-- /.noslider -->
    </section><!-- /.billboard  -->
    
    
    <main class="halfmargin">
        
        <section class="separator-header"></section>
        
        <section class="intro wrapper wrapper-margin">
            
            <h1><?php the_title(); ?></h1>
            
        </section><!--  End Features  -->


        <?php if (have_posts()) : ?>
        <?php query_posts(array( 'post_type' => 'enoturisme', 'order' => 'ASC' )); ?>
        <?php while (have_posts()) : the_post(); ?>
        <section class="page-wrapper" id="<?php global $post; $post_slug=$post->post_name; echo $post_slug; ?>">
            <div class="spotlight spotlight-full">
                <div class="container container-full">
                    <div class="content">
                        <h2><?php the_title(); ?></h2>
                        
                        <?php the_content(); ?>
                        
                    </div>
                </div>
            </div><!-- /.spotlight -->
            
            <div class="spotlight">
                <div class="image">
                <?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
                    <?php the_post_thumbnail('large'); ?>
                <?php endif; ?>
                </div>
                
                <div class="container">
                    <div class="content">
                        
                        <?php the_field('detalls_experiencia'); ?>
                        
                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        
        <section class="separator-middle" id="d-on-ve-edetaria"></section>
        <?php endwhile; endif; wp_reset_postdata(); ?>
        
    </main>

<?php get_footer(); ?>
