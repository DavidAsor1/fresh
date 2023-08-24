<?php
	/* Template Name: HomePage */
?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">

<!--

TemplateMo 548 Training Studio

https://templatemo.com/tm-548-training-studio

-->
    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="<?= get_template_directory_uri(); ?>/assets/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="<?= get_template_directory_uri(); ?>/assets/css/font-awesome.css">

    <link rel="stylesheet" href="<?= get_template_directory_uri(); ?>/assets/css/templatemo-training-studio.css?ver=<?=time();?>">

    </head>
    <style>

        body,html{
            max-height: 100vh;
            overflow: hidden;
        }
        .appointment-form-container input[type="submit"]{
            background:#ed563b;
        }
        .main-banner .caption h2{
            font-weight:inherit;
        }
        .main-banner .caption h2 em{
            font-weight:bold;
        }
        .main-banner .caption h6{
            font-weight:bold;
        }
    </style>
    <body> 
  
    <!-- ***** Main Banner Area Start ***** -->
    <div class="main-banner" id="top">
        <video autoplay muted loop id="bg-video">
            <source src="<?= get_template_directory_uri(); ?>/assets/images/barber-video2.mp4" type="video/mp4" />
        </video>

        <div class="video-overlay header-text">
            <div class="caption">
                <h6>קבע תור אונליין</h6>
                <h2>קבע תור<em> בקלות</em></h2>
                <?php echo do_shortcode('[appointment_form]'); ?>
            </div>
        </div>
    </div>
	
    
  
  </body>
</html>