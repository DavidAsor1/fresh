<?php
	/* Template Name: HomePage-2 */
?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">

    <title>Training Studio - Free CSS Template</title>
<!--

TemplateMo 548 Training Studio

https://templatemo.com/tm-548-training-studio

-->
    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="<?= get_template_directory_uri(); ?>/assets/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="<?= get_template_directory_uri(); ?>/assets/css/font-awesome.css">

    <link rel="stylesheet" href="<?= get_template_directory_uri(); ?>/assets/css/templatemo-training-studio.css">

    </head>
    
    <body>
    
    <!-- ***** Preloader Start ***** -->
    <div id="js-preloader" class="js-preloader">
      <div class="preloader-inner">
        <span class="dot"></span>
        <div class="dots">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
    </div>
    <!-- ***** Preloader End ***** -->
    
    
    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="index.html" class="logo">Training<em> Studio</em></a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="#top" class="active">Home</a></li>
                            <li class="scroll-to-section"><a href="#features">About</a></li>
                            <li class="scroll-to-section"><a href="#our-classes">Classes</a></li>
                            <li class="scroll-to-section"><a href="#schedule">Schedules</a></li>
                            <li class="scroll-to-section"><a href="#contact-us">Contact</a></li> 
                            <li class="main-button"><a href="#">Sign Up</a></li>
                        </ul>        
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->

    <!-- ***** Main Banner Area Start ***** -->
    <div class="main-banner" id="top">
        <video autoplay muted loop id="bg-video">
            <source src="<?= get_template_directory_uri(); ?>/assets/images/barber-video2.mp4" type="video/mp4" />
        </video>

        <div class="video-overlay header-text">
            <div class="caption">
                <h6>work harder, get stronger</h6>
                <h2>easy with our <em>gym</em></h2>
                <div class="main-button scroll-to-section">
                    <a href="#features">Become a member</a>
                </div>
            </div>
        </div>
    </div>
	
	<section class="section" >
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="cta-content">
                        <?php echo do_shortcode('[appointment_form]'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- ***** Contact Us Area Starts ***** -->
    <section class="section" id="contact-us">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-xs-12">
                    <div id="map">
                      <iframe src="https://maps.google.com/maps?q=Av.+L%C3%BAcio+Costa,+Rio+de+Janeiro+-+RJ,+Brazil&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="600px" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-xs-12">
                    <div class="contact-form">
                        <form id="contact" action="" method="post">
                          <div class="row">
                            <div class="col-md-6 col-sm-12">
                              <fieldset>
                                <input name="name" type="text" id="name" placeholder="Your Name*" required="">
                              </fieldset>
                            </div>
                            <div class="col-md-6 col-sm-12">
                              <fieldset>
                                <input name="email" type="text" id="email" pattern="[^ @]*@[^ @]*" placeholder="Your Email*" required="">
                              </fieldset>
                            </div>
                            <div class="col-md-12 col-sm-12">
                              <fieldset>
                                <input name="subject" type="text" id="subject" placeholder="Subject">
                              </fieldset>
                            </div>
                            <div class="col-lg-12">
                              <fieldset>
                                <textarea name="message" rows="6" id="message" placeholder="Message" required=""></textarea>
                              </fieldset>
                            </div>
                            <div class="col-lg-12">
                              <fieldset>
                                <button type="submit" id="form-submit" class="main-button">Send Message</button>
                              </fieldset>
                            </div>
                          </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Contact Us Area Ends ***** -->
    
    <!-- ***** Footer Start ***** -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; 2020 Training Studio
                    
                    - Designed by <a rel="nofollow" href="https://templatemo.com" class="tm-text-link" target="_parent">TemplateMo</a></p>
                    
                    <!-- You shall support us a little via PayPal to info@templatemo.com -->
                    
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="<?= get_template_directory_uri(); ?>/assets/js/jquery-2.1.0.min.js"></script>

    <!-- Bootstrap -->
    <script src="<?= get_template_directory_uri(); ?>/assets/js/popper.js"></script>
    <script src="<?= get_template_directory_uri(); ?>/assets/js/bootstrap.min.js"></script>

    <!-- Plugins -->
    <script src="<?= get_template_directory_uri(); ?>/assets/js/scrollreveal.min.js"></script>
    <script src="<?= get_template_directory_uri(); ?>/assets/js/waypoints.min.js"></script>
    <script src="<?= get_template_directory_uri(); ?>/assets/js/jquery.counterup.min.js"></script>
    <script src="<?= get_template_directory_uri(); ?>/assets/js/imgfix.min.js"></script> 
    <script src="<?= get_template_directory_uri(); ?>/assets/js/mixitup.js"></script> 
    <script src="<?= get_template_directory_uri(); ?>/assets/js/accordions.js"></script>
    
    <!-- Global Init -->
    <script src="<?= get_template_directory_uri(); ?>/assets/js/custom.js"></script>

  </body>
</html>