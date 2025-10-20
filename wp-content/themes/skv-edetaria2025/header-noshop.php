<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="author" content="sokvist.com">
	<meta name="description" content="<?php bloginfo('description'); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" />
    <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon.png" />
    <link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon-precomposed.png">
    <link href="https://fonts.googleapis.com/css?family=Crimson+Text:400,600i|Nunito:400,800" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/css/style.css">
   
    <!-- Open Graph -->
    <meta property="og:locale" content="es-ES">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Edetària - L'esperit d'una terra">
    <meta property="og:description" content="Edetària produces Mediterranean Grenaches & Barcelona Wines. Taste sunlight wines speaking terroir.">
    <meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/assets/images/og-image.jpg">
    <meta property="og:url" content="https://edetaria.com/">
    <meta property="og:site_name" content="Edetària">
    
    <!-- Twitter Card  -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@EdetariaCeller">
    <meta name="twitter:creator" content="@SokvistWeb">
    <meta name="twitter:url" content="https://www.edetaria.eu/">
    <meta name="twitter:title" content="Edetària - L'esperit d'una terra">
    <meta name="twitter:description" content="Edetària produces Mediterranean Grenaches & Barcelona Wines. Taste sunlight wines speaking terroir.">
    <meta name="twitter:image" content="<?php echo get_template_directory_uri(); ?>/assets/images/og-image.jpg">
    
    <script src="<?php echo get_template_directory_uri(); ?>/assets/js/vendor/modernizr.js"></script>

    <?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
    
    <div class="eupopup eupopup-top"></div>
        
	<header>
		<div class="wrapper wrapper-header">
            
            <a class="home" href="<?php echo home_url(); ?>" title="Edetària, vins autèntics">
                <svg xmlns="http://www.w3.org/2000/svg" id="edetaria" class="svg-logo" viewBox="0 0 500 500"><path id="circle" d="M5.7 250.4C5.7 115.8 114.8 6.7 249.4 6.7 383.9 6.7 493 115.8 493 250.4 493 384.9 383.9 494 249.4 494 114.8 494 5.7 384.9 5.7 250.4z"/><path id="t" d="M173.9 175.7v-10.3c28.7-16.7 57.5-39.7 69.5-72.4h8.6v67.2h65.5v15.5h-65.5v163.8c0 25.9 5.2 52.9 35.6 52.9 20.1 0 30.4-11.5 41.4-25.9l10.3 7.5c-10.3 27.6-39.1 36.8-66.7 36.8 -41.4 0-69.5-16.1-69.5-59.2V175.7H173.9z"/></svg>
            </a>
            
            <div class="hamburger hamburger--arrow-r">
                <div class="hamburger-box">
                    <div class="hamburger-inner"></div>
                </div>
            </div>
            
			<nav id="nav_menu">
                <div class="shopping-cart">
                    <span class="fa fa-shopping-cart"></span>
                    <!--<span class="cart-contents-count">4</span>-->
                </div>
                <div class="language" id="dd">
                    <?php qtranxf_generateLanguageSelectCode('text') ?>
                </div>
				<?php html5blank_nav(); ?>
			</nav>
            
		</div>
	</header><!--  End Header  -->
	
	<div class="woocommerce widget_shopping_cart">

        <div class="cart-dropdown">
            <div class="cart-dropdown-inner">
                <div class="dropdown-cart-wrap isempty">
                    <?php if(function_exists('qtranxf_getLanguage')) { ?>
                    <?php if (qtranxf_getLanguage()=='ca'): ?>
                    <p>Compra online disponible properament.</p>
                    <?php endif; ?>
                    <?php if (qtranxf_getLanguage()=='es'): ?>
                    <p>Compra online disponible próximamente.</p>
                    <?php endif; ?>
                    <?php if (qtranxf_getLanguage()=='en'): ?>
                    <p>Online shopping available soon.</p>
                    <?php endif; ?>
                    <?php } ?>
                </div>
            </div>
        </div>

    </div><!-- /widget_shopping_cart -->
