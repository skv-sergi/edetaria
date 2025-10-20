<?php
$pageTitle = 'Contacte';
$bodyClass = 'contacte';
include 'header.php';
?>

	
    <section class="billboard halfheight">
        <div class="noslider">
            <div class="overlay"></div>
            <div class="single-img bg-img-contacte"></div>
        </div> <!-- /.noslider -->
    </section><!-- /.billboard  -->
    
    
    <main class="halfmargin">
        
        <section class="separator-header"></section>

        <section class="intro wrapper wrapper-margin">
            
            <h1>Contacte</h1>
            <div class="contact-content">
                <address>FINCA EL MAS · Ctra. Gandesa - Vilalba, s/n · 43780 Gandesa (Tarragona)</address>
                
                Telèfon / Fax <a href="tel:0034977421534">(+34) 977 42 15 34</a><br>
                <a href="mailto:info@edetaria.com">info@edetaria.com</a> · <a href="http://www.edetaria.com" title="La web de Can Miquel">www.edetaria.com</a>
            </div>
            
        </section><!--  End Features  -->


        <section class="page-wrapper">
            <div class="spotlight">
                
                <div class="container">
                    <div class="content">
                        <form action="#" method="post" class="wpcf7-form" novalidate="novalidate">
                            <p>Nom<br>
                                <span class="wpcf7-form-control-wrap your-name">
                                    <input type="text" name="your-name" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false">
                                </span>
                            </p>
                            <p>Email<br>
                                <span class="wpcf7-form-control-wrap your-email">
                                    <input type="email" name="your-email" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email" aria-required="true" aria-invalid="false">
                                </span>
                            </p>
                            <p>Missatge<br>
                                <span class="wpcf7-form-control-wrap your-message">
                                    <textarea name="your-message" cols="40" rows="10" class="wpcf7-form-control wpcf7-textarea" aria-invalid="false"></textarea>
                                </span>
                            </p>
                            <p>
                                <input type="submit" value="Enviar" class="wpcf7-form-control wpcf7-submit">
                            </p>
                            <div class="wpcf7-response-output wpcf7-display-none"></div>
                        </form>
                    </div>
                </div>
                
                <div class="image">
                    <a class="google-link" href="https://goo.gl/maps/n23d4Zwxei82" title="Veure a Google Maps" target="_blank"><img src="assets/images/google-maps-edetaria.jpg" alt="Edetària a Google Maps" width="900" height="540" /></a>
                </div>
            </div><!-- /.spotlight -->

        </section>
        
        <section class="page-wrapper separator"></section>
        
    </main>


<?php include("footer.php"); ?>