<?php /* Template Name: Pàgina Enoturisme */ get_header(); ?>

	
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
            
        </section>


        <?php
        // Custom query to get 'enoturisme' posts without 'efimers' taxonomy term
        $args = array(
            'post_type' => 'enoturisme',
            'order'     => 'ASC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'enoturisme-categories',
                    'field'    => 'slug',
                    'terms'    => 'efimers',
                    'operator' => 'NOT IN',
                ),
            ),
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) : 
            while ($query->have_posts()) : $query->the_post(); ?>
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
                    <div class="slider">
                        <div class="flexslider-page">
                            <ul class="slides">
                                <li>
                                <?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
                                    <?php the_post_thumbnail('large_square'); ?>
                                <?php endif; ?>
                                </li>
                                <?php 
                                $photo_1 = get_field('photo_1'); 
                                if ($photo_1) : 
                                    $size = 'large_square'; // Change 'thumbnail' to any desired size
                                    $image = wp_get_attachment_image_src($photo_1['ID'], $size);
                                    if ($image) :
                                        $image_url = $image[0];
                                        $image_width = $image[1];
                                        $image_height = $image[2];
                                ?>
                                    <li>
                                        <img src="<?php echo esc_url($image_url); ?>" 
                                            alt="<?php echo esc_attr($photo_1['alt']); ?>" 
                                            width="<?php echo esc_attr($image_width); ?>" 
                                            height="<?php echo esc_attr($image_height); ?>" />
                                    </li>
                                <?php endif; endif; ?>
                                <?php 
                                $photo_2 = get_field('photo_2'); 
                                if ($photo_2) : 
                                    $size = 'large_square'; // Change 'thumbnail' to any desired size
                                    $image = wp_get_attachment_image_src($photo_2['ID'], $size);
                                    if ($image) :
                                        $image_url = $image[0];
                                        $image_width = $image[1];
                                        $image_height = $image[2];
                                ?>
                                    <li>
                                        <img src="<?php echo esc_url($image_url); ?>" 
                                            alt="<?php echo esc_attr($photo_2['alt']); ?>" 
                                            width="<?php echo esc_attr($image_width); ?>" 
                                            height="<?php echo esc_attr($image_height); ?>" />
                                    </li>
                                <?php endif; endif; ?>
                                
                                <?php 
                                $photo_3 = get_field('photo_3'); 
                                if ($photo_3) : 
                                    $size = 'large_square'; // Change 'thumbnail' to any desired size
                                    $image = wp_get_attachment_image_src($photo_3['ID'], $size);
                                    if ($image) :
                                        $image_url = $image[0];
                                        $image_width = $image[1];
                                        $image_height = $image[2];
                                ?>
                                    <li>
                                        <img src="<?php echo esc_url($image_url); ?>" 
                                            alt="<?php echo esc_attr($photo_3['alt']); ?>" 
                                            width="<?php echo esc_attr($image_width); ?>" 
                                            height="<?php echo esc_attr($image_height); ?>" />
                                    </li>
                                <?php endif; endif; ?>
                            </ul>
                        </div> <!-- /.flexslider-page -->
                    </div> <!-- /.slider -->
                </div>
                
                <div class="container is-iframe">

                    <?php if (get_field('detalls_experiencia')): ?>
                    <div class="content">
                        <?php the_field('detalls_experiencia'); ?>
                    </div>
                    <?php endif; ?>
                        
                    <?php if (get_field('event_iframe')): ?>
                        <?php the_field('event_iframe'); ?>
                    <?php endif; ?>
                        
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <?php endwhile; endif; wp_reset_query(); ?>


        <section class="intro wrapper wrapper-margin text-center text-large">
            
            <?php if (get_field('mes_info_events')): ?>
            <?php the_field( 'mes_info_events' ); ?>
            <?php endif; ?>
            
        </section>


        <section class="separator-middle" id="d-on-ve-edetaria"></section>


        <section class="page-wrapper" id="els-sols">
            <div class="spotlight reverse">
                <div class="image">
                <div class="slider">
                        <div class="flexslider-page">
                            <ul class="slides">
                                <?php 
                                $foto_picnic_1 = get_field('foto_picnic_1'); 
                                if ($foto_picnic_1) : 
                                    $size = 'large'; // Change 'thumbnail' to any desired size
                                    $image = wp_get_attachment_image_src($foto_picnic_1['ID'], $size);
                                    if ($image) :
                                        $image_url = $image[0];
                                        $image_width = $image[1];
                                        $image_height = $image[2];
                                ?>
                                    <li>
                                        <img src="<?php echo esc_url($image_url); ?>" 
                                            alt="<?php echo esc_attr($foto_picnic_1['alt']); ?>" 
                                            width="<?php echo esc_attr($image_width); ?>" 
                                            height="<?php echo esc_attr($image_height); ?>" />
                                    </li>
                                <?php endif; endif; ?>
                                <?php 
                                $foto_picnic_2 = get_field('foto_picnic_2'); 
                                if ($foto_picnic_2) : 
                                    $size = 'large'; // Change 'thumbnail' to any desired size
                                    $image = wp_get_attachment_image_src($foto_picnic_2['ID'], $size);
                                    if ($image) :
                                        $image_url = $image[0];
                                        $image_width = $image[1];
                                        $image_height = $image[2];
                                ?>
                                    <li>
                                        <img src="<?php echo esc_url($image_url); ?>" 
                                            alt="<?php echo esc_attr($foto_picnic_2['alt']); ?>" 
                                            width="<?php echo esc_attr($image_width); ?>" 
                                            height="<?php echo esc_attr($image_height); ?>" />
                                    </li>
                                <?php endif; endif; ?>
                                
                                <?php 
                                $foto_picnic_3 = get_field('foto_picnic_3'); 
                                if ($foto_picnic_3) : 
                                    $size = 'large'; // Change 'thumbnail' to any desired size
                                    $image = wp_get_attachment_image_src($foto_picnic_3['ID'], $size);
                                    if ($image) :
                                        $image_url = $image[0];
                                        $image_width = $image[1];
                                        $image_height = $image[2];
                                ?>
                                    <li>
                                        <img src="<?php echo esc_url($image_url); ?>" 
                                            alt="<?php echo esc_attr($foto_picnic_3['alt']); ?>" 
                                            width="<?php echo esc_attr($image_width); ?>" 
                                            height="<?php echo esc_attr($image_height); ?>" />
                                    </li>
                                <?php endif; endif; ?>
                            </ul>
                        </div> <!-- /.flexslider-page -->
                    </div> <!-- /.slider -->
                </div>

                <div class="container">
                    <div class="content">

                        <?php if (get_field('titol_picnic')): ?>
                        <h2><?php the_field( 'titol_picnic' ); ?></h2>
                        <?php endif; ?>

                        <?php if (get_field('content_picnic')): ?>
                        <?php the_field( 'content_picnic' ); ?>
                        <?php endif; ?>

                    </div>
                </div>
            </div><!-- /.spotlight -->
        </section>


        <section class="intro wrapper wrapper-margin text-large">

            <?php if (get_field('event_iframe')): ?>
            <?php the_field('event_iframe'); ?>
            <?php endif; ?>

            <div class="separator-hover1"></div>
            
        </section>


        <section class="intro wrapper wrapper-margin text-center text-large">
            
            <?php if (get_field('mes_info_picnic')): ?>
            <?php the_field( 'mes_info_picnic' ); ?>
            <?php endif; ?>
            
        </section>

        <section class="separator-middle" id="d-on-ve-edetaria"></section>


        
        <?php
        // Custom query to get 'enoturisme' posts WITH 'efimers' taxonomy term
        $args = array(
            'post_type' => 'enoturisme',
            'order'     => 'ASC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'enoturisme-categories',
                    'field'    => 'slug',
                    'terms'    => 'efimers',
                    'operator' => 'IN',
                ),
            ),
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) : 
            while ($query->have_posts()) : $query->the_post(); ?>
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
                    <div class="slider">
                        <div class="flexslider-page">
                            <ul class="slides">
                                <li>
                                <?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
                                    <?php the_post_thumbnail('large_square'); ?>
                                <?php endif; ?>
                                </li>
                                <?php 
                                $photo_1 = get_field('photo_1'); 
                                if ($photo_1) : 
                                    $size = 'large_square'; // Change 'thumbnail' to any desired size
                                    $image = wp_get_attachment_image_src($photo_1['ID'], $size);
                                    if ($image) :
                                        $image_url = $image[0];
                                        $image_width = $image[1];
                                        $image_height = $image[2];
                                ?>
                                    <li>
                                        <img src="<?php echo esc_url($image_url); ?>" 
                                            alt="<?php echo esc_attr($photo_1['alt']); ?>" 
                                            width="<?php echo esc_attr($image_width); ?>" 
                                            height="<?php echo esc_attr($image_height); ?>" />
                                    </li>
                                <?php endif; endif; ?>
                                <?php 
                                $photo_2 = get_field('photo_2'); 
                                if ($photo_2) : 
                                    $size = 'large_square'; // Change 'thumbnail' to any desired size
                                    $image = wp_get_attachment_image_src($photo_2['ID'], $size);
                                    if ($image) :
                                        $image_url = $image[0];
                                        $image_width = $image[1];
                                        $image_height = $image[2];
                                ?>
                                    <li>
                                        <img src="<?php echo esc_url($image_url); ?>" 
                                            alt="<?php echo esc_attr($photo_2['alt']); ?>" 
                                            width="<?php echo esc_attr($image_width); ?>" 
                                            height="<?php echo esc_attr($image_height); ?>" />
                                    </li>
                                <?php endif; endif; ?>
                                
                                <?php 
                                $photo_3 = get_field('photo_3'); 
                                if ($photo_3) : 
                                    $size = 'large_square'; // Change 'thumbnail' to any desired size
                                    $image = wp_get_attachment_image_src($photo_3['ID'], $size);
                                    if ($image) :
                                        $image_url = $image[0];
                                        $image_width = $image[1];
                                        $image_height = $image[2];
                                ?>
                                    <li>
                                        <img src="<?php echo esc_url($image_url); ?>" 
                                            alt="<?php echo esc_attr($photo_3['alt']); ?>" 
                                            width="<?php echo esc_attr($image_width); ?>" 
                                            height="<?php echo esc_attr($image_height); ?>" />
                                    </li>
                                <?php endif; endif; ?>
                            </ul>
                        </div> <!-- /.flexslider-page -->
                    </div> <!-- /.slider -->
                </div>
                
                <div class="container is-iframe">

                    <?php if (get_field('detalls_experiencia')): ?>
                    <div class="content">
                        <?php the_field('detalls_experiencia'); ?>
                    </div>
                    <?php endif; ?>
                        
                    <?php if (get_field('event_iframe')): ?>
                        <?php the_field('event_iframe'); ?>
                    <?php endif; ?>
                        
                </div>
            </div><!-- /.spotlight -->
        </section>
        
        <?php endwhile; endif; wp_reset_query(); ?>



        <h2>Test iframe</h2>

        <iframe src="https://enoticket.com/iframe/RRTMVaug2RYa" id="RRTMVaug2RYa" width="100%" scrolling="no" frameBorder="0" style="overflow: hidden;"></iframe><script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script><script type="text/javascript" src="https://enoticket.com/js/iframe/iframeResizer.min.js"></script><script>iFrameResize({ log: true }, "#RRTMVaug2RYa");</script>

        
    </main>

<?php get_footer(); ?>
