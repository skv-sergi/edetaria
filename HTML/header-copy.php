<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie ie6 ie-lt10 ie-lt9 ie-lt8 ie-lt7 no-js" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 ie-lt10 ie-lt9 ie-lt8 no-js" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 ie-lt10 ie-lt9 no-js" lang="en"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 ie-lt10 no-js" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="ca"><!--<![endif]-->
<head>
    <meta charset="utf-8">
	<title>Edetària - <?php echo $pageTitle ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="author" content="sokvist.com">
	<meta name="description" content=""/>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="apple-touch-icon" href="apple-touch-icon.png" />
    <link rel="apple-touch-icon-precomposed" href="apple-touch-icon-precomposed.png">
    <link href="https://fonts.googleapis.com/css?family=Crimson+Text:400,600i|Nunito:400,800" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
    
    <script src="assets/js/vendor/modernizr.js"></script>
</head>
<body class="<?php echo $bodyClass ?>">

	<header>
		<div class="wrapper wrapper-header">
            
            <a class="home" href="index.php" title="Edetària, vins autèntics">
                <svg xmlns="http://www.w3.org/2000/svg" id="edetaria" class="svg-logo" viewBox="0 0 500 500"><path id="circle" d="M5.7 250.4C5.7 115.8 114.8 6.7 249.4 6.7 383.9 6.7 493 115.8 493 250.4 493 384.9 383.9 494 249.4 494 114.8 494 5.7 384.9 5.7 250.4z"/><path id="t" d="M173.9 175.7v-10.3c28.7-16.7 57.5-39.7 69.5-72.4h8.6v67.2h65.5v15.5h-65.5v163.8c0 25.9 5.2 52.9 35.6 52.9 20.1 0 30.4-11.5 41.4-25.9l10.3 7.5c-10.3 27.6-39.1 36.8-66.7 36.8 -41.4 0-69.5-16.1-69.5-59.2V175.7H173.9z"/></svg>
            </a>
            
            <div class="hamburger hamburger--arrow-r">
                <div class="hamburger-box">
                    <div class="hamburger-inner"></div>
                </div>
            </div>
            
			<nav id="nav_menu">
                <a href="/cesta/" class="shopping-cart" title="Ves al carret de la compra"><span class="fa fa-shopping-cart"></span></a>
                <div class="language" id="dd">
                    <ul class="qtranxs_language_chooser" id="qtranslate-chooser">
                        <li class="lang-ca active"><a href="http://canmiquel/ca/" hreflang="ca" title="CA (ca)" class="qtranxs_text qtranxs_text_ca"><span>CA</span></a></li>
                        <li class="lang-es"><a href="http://masrosello.cat.mialias.net/es/" hreflang="es" title="Español" class="qtranxs_text qtranxs_text_es"><span>ES</span></a></li>
                        <li class="lang-en"><a href="http://masrosello.cat.mialias.net/en/" hreflang="en" title="English" class="qtranxs_text qtranxs_text_en"><span>EN</span></a></li>
                    </ul>
                    <div class="qtranxs_widget_end"></div>
                </div>
				<ul id="nav" class="nav navbar-nav sf-menu">
					<li <?php if ($bodyClass == 'edetaria') { ?>class="current-menu-item"<?php } ?>><a href="edetaria.php">Edetària</a></li>
					<li <?php if ($bodyClass == 'vinyes-i-terra') { ?>class="current-menu-item"<?php } ?>><a href="vinyes-i-terra.php">Vinyes i terra</a></li>
					<li <?php if ($bodyClass == 'el-celler') { ?>class="current-menu-item"<?php } ?>><a href="el-celler.php">El celler</a></li>
					<li <?php if ($bodyClass == 'experiencies') { ?>class="current-menu-item"<?php } ?>><a href="experiencies.php">Experiències</a>
                        <!--<ul class="sub-menu">
                            <li><a href="enoturisme.php">Enoturisme</a></li>
                            <li><a href="empreses.php">Empreses</a></li>
                            <li><a href="espais.php">Espais</a></li>
                        </ul>-->
                    </li>
                    <li <?php if ($bodyClass == 'noticies') { ?>class="current-menu-item"<?php } ?>><a href="noticies.php">Notícies</a></li>
                    <li <?php if ($bodyClass == 'els-vins') { ?>class="current-menu-item"<?php } ?>><a href="els-vins.php">Els vins</a></li>
                    <!--<li><a href="contacte.php">Contacte</a></li>-->
				</ul>
			</nav>
            
		</div>
	</header><!--  End Header  -->