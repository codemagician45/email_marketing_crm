<!DOCTYPE html>
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title><?php echo $this->config->item('product_name'); if($this->config->item('slogan')!='') echo " | ".$this->config->item('slogan')?></title>
	<meta name="description" content="Smart SMS/Email manager Tool">
	<meta name="author" content="<?php echo $this->config->item('institute_address1');?>">

	<!-- Mobile Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Favicon -->
	<link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon.png">

    <!--====== STYLESHEETS ======-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/site_new/css/normalize.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/site_new/css/animate.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/site_new/css/modal-video.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/site_new/css/stellarnav.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/site_new/css/owl.carousel.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/site_new/css/slick.css">
    <link href="<?php echo base_url();?>assets/site_new/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/site_new/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/site_new/css/material-icons.css" rel="stylesheet">

    <!--====== MAIN STYLESHEETS ======-->
    <!-- <link href="<?php echo base_url();?>assets/site_new/style.css" rel="stylesheet"> -->
    <?php include("application/views/website/style.php"); ?>
    <link href="<?php echo base_url();?>assets/site_new/css/responsive.css" rel="stylesheet">

    <script src="<?php echo base_url();?>assets/site_new/js/vendor/modernizr-2.8.3.min.js"></script>
    <!--[if lt IE 9]>
        <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>

<body class="home-two" data-spy="scroll" data-target=".mainmenu-area" data-offset="90">

    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <!--- PRELOADER -->
    <div class="preeloader">
        <div class="preloader-spinner"></div>
    </div>

    <!--SCROLL TO TOP-->
    <a href="#home" class="scrolltotop"><i class="fa fa-long-arrow-up"></i></a>

    <!--START TOP AREA-->
    <header class="top-area" id="home">
        <div class="header-top-area">
            <!--MAINMENU AREA-->
            <div class="mainmenu-area" id="mainmenu-area">
                <div class="mainmenu-area-bg"></div>
                <nav class="navbar">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <a href="#home" class="navbar-brand">
                                <img style="max-height:40px !important;" src="<?php echo base_url();?>assets/images/logo.png" alt="<?php echo $this->config->item('product_name');?>"></a>
                        </div>
                        <div id="main-nav" class="stellarnav">
                            <div class="search-and-signup-button white pull-right hidden-sm hidden-xs">
                                <a href="<?php echo site_url('home/login'); ?>" class="sign-up"><?php echo $this->lang->line('Login'); ?></a>
                            </div>
                            <ul id="nav" class="nav">
                                <li class="active">
                                    <a href="#home"><?php echo $this->lang->line('home'); ?></a>
                                </li>
                                <li>
                                    <a href="#features"><?php echo $this->lang->line('Features');?></a>
                                </li>
                                <li>
                                    <a href="#download"><?php echo $this->lang->line('Pricing'); ?></a>
                                </li>
                                <li>
                                    <a href="#contact"><?php echo $this->lang->line('Contact');?></a>
                                </li>
                                <li class="hidden-md hidden-lg">
                                    <a href="<?php echo site_url('home/login'); ?>"><?php echo $this->lang->line('Login'); ?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <!--END MAINMENU AREA END-->
        </div>

        
        <div class="welcome-text-area white">
            <div class="area-bg"></div>
            <div class="welcome-area">
                <div class="container">
                    <div class="row flex-v-center">
                        <div class="col-md-7 col-lg-7 col-sm-12 col-xs-12">
                            <div class="welcome-mockup center">
                                <img src="<?php echo base_url();?>assets/site_new/img/home/watch-mockup.png" alt="">
                            </div>
                        </div>
                        <div class="col-md-7 col-lg-7 col-sm-12 col-xs-12">
                            <div class="welcome-text">
                                <h1 style="border-bottom: none !important;padding:0 !important;margin: 0 0 15px !important;"><span><?php echo $this->config->item('product_name');?></span></h1>
                                <span class="em"><?php echo $this->lang->line("SSEM can manage your contacts, create SMS/Email template, send SMS/Email, schedule SMS/Email, wish your contacts’ birthday etc using Smart SMS & Email Manager (SSEM) in a smarter way. SSEM has built-in support for world’s most popular SMS & Email gateways like Plivo, Twilio, Clickatell, Nexmo, Mandrill, Sendgrid, Mailgun etc."); ?></span>

                                <div class="home-button">
                                    <a href="#features"><?php echo $this->lang->line("detailed features"); ?></a>
                                    <a href="<?php echo site_url('home/sign_up'); ?>"><?php echo $this->lang->line("Sign up now"); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--END TOP AREA-->

    <!--FEATURES TOP AREA-->
    <section class="features-top-area padding-100-50" id="features">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2 col-sm-12 col-xs-12">
                    <div class="area-title text-center wow fadeIn">
                        <h2><?php echo $this->lang->line("Key Features").' : '.$this->config->item('product_name'); ?></h2>
                        <p><?php echo $this->lang->line("Smart SMS and Email manager tool."); ?></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
                    <div class="qs-box relative mb50 center wow fadeInUp" data-wow-delay="0.2s">
                        <div class="qs-box-icon">
                            <i class="fa fa-line-chart"></i>
                        </div>
                        <h3><?php echo $this->lang->line("SMS/Email gateway support"); ?></h3>
                        <p><?php echo $this->lang->line("Built-in support for SMS & Email gateways like Plivo, Twilio, Clickatell, Nexmo, Mandrill, Sendgrid, Mailgun etc."); ?></p>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
                    <div class="qs-box relative mb50 center  wow fadeInUp" data-wow-delay="0.3s">
                        <div class="qs-box-icon">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <h3><?php echo $this->lang->line("Bulk SMS/Email sending"); ?></h3>
                        <p><?php echo $this->lang->line("You can send bulk SMS/Email to your contacts with attachment and designed template."); ?></p>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
                    <div class="qs-box relative mb50 center wow fadeInUp" data-wow-delay="0.4s">
                        <div class="qs-box-icon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <h3><?php echo $this->lang->line("Scheduled SMS/Email sending"); ?></h3>
                        <p><?php echo $this->lang->line("You can set scheduled SMS/Email campaign to send SMS/Email later to your contacts."); ?></p>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
                    <div class="qs-box relative mb50 center wow fadeInUp" data-wow-delay="0.4s">
                        <div class="qs-box-icon">
                            <i class="fa fa-gift"></i>
                        </div>
                        <h3><?php echo $this->lang->line("Birthday wish SMS/Email"); ?></h3>
                        <p><?php echo $this->lang->line("You can send birthday wishes via SMS/Email to your contacts on their birthday using SSEM."); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--FEATURES TOP AREA END-->
 

    <!--FEATURES AREA-->
        <section class="features-area relative padding-100-50 gray-bg">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <div class="area-title text-center wow fadeIn">                        
                            <h2><?php echo $this->lang->line("detailed features"); ?></h2>
                            <p><?php echo $this->config->item('product_name').' '.$this->lang->line("is an app to provide you the facility to manage your contacts, send them bulk SMS/Email and birthday wishes."); ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                                <div class="qs-box relative mb50 pos-icon-left  wow fadeInUp" data-wow-delay="0.2s">
                                    <div class="qs-box-icon">
                                        <i class="fa fa-tachometer"></i>
                                    </div>
                                    <h4><?php echo $this->lang->line("Dashboard"); ?></h4>
                                    <p><?php echo $this->lang->line("Dashboard consists of recent activities of all users and admin's."); ?></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                                <div class="qs-box relative mb50 pos-icon-left  wow fadeInUp" data-wow-delay="0.2s">
                                    <div class="qs-box-icon">
                                        <i class="fa fa-user-plus"></i>
                                    </div>
                                    <h4><?php echo $this->lang->line("User management"); ?></h4>
                                    <p><?php echo $this->lang->line("You can add,edit or delete your users from Usermanagement option.");?></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                                <div class="qs-box relative mb50 pos-icon-left  wow fadeInUp" data-wow-delay="0.3s">
                                    <div class="qs-box-icon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <h4><?php echo $this->lang->line("SaaS"); ?></h4>
                                    <p><?php echo $this->lang->line("It's SAAS application. You can sell the service to your users.");?></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                                <div class="qs-box relative mb50 pos-icon-left  wow fadeInUp" data-wow-delay="0.2s">
                                    <div class="qs-box-icon">
                                        <i class="fa fa-briefcase"></i>  
                                    </div>
                                    <h4><?php echo $this->lang->line("Contact management"); ?></h4> 
                                    <p><?php echo $this->lang->line("You can create,edit,delete and also export/import your contacts.");?></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                                <div class="qs-box relative mb50 pos-icon-left wow fadeInUp" data-wow-delay="0.2s">
                                    <div class="qs-box-icon">
                                        <i class="fa fa-line-chart"></i>
                                    </div>
                                    <h4><?php echo $this->lang->line("SMS/Email gateway support"); ?></h4> 
                                    <p><?php echo $this->lang->line("It has support for Plivo, Twilio, Clickatell, Nexmo, Mandrill, Sendgrid, Mailgun etc.");?></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                                <div class="qs-box relative mb50 pos-icon-left wow fadeInUp" data-wow-delay="0.3s">
                                    <div class="qs-box-icon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <h4><?php echo $this->lang->line("SMS/Email template management"); ?></h4>
                                    <p><?php echo $this->lang->line("It will provide you to create SMS/Email Template.");?></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                                <div class="qs-box relative mb50 pos-icon-left wow fadeInUp" data-wow-delay="0.3s">
                                    <div class="qs-box-icon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <h4><?php echo $this->lang->line("Bulk SMS/Email sending"); ?></h4>
                                    <p><?php echo $this->lang->line("It has the facility to send bulk SMS/Email to contacts.");?></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                                <div class="qs-box relative mb50 pos-icon-left wow fadeInUp" data-wow-delay="0.3s">
                                    <div class="qs-box-icon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <h4><?php echo $this->lang->line("Scheduled SMS/Email sending"); ?></h4>
                                    <p><?php echo $this->lang->line("It has the facility to Send Scheduled SMS/Email.");?></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                                <div class="qs-box relative mb50 pos-icon-left wow fadeInUp" data-wow-delay="0.3s">
                                    <div class="qs-box-icon">
                                        <i class="fa fa-gift"></i>
                                    </div>
                                    <h4><?php echo $this->lang->line("Birthday wish SMS/Email"); ?></h4>
                                    <p><?php echo $this->lang->line("It has the facility to send birthday wished to the contacts on their birthday.");?></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                                <div class="qs-box relative mb50 pos-icon-left wow fadeInUp" data-wow-delay="0.3s">
                                    <div class="qs-box-icon">
                                        <i class="fa fa-bug"></i>
                                    </div>
                                    <h4><?php echo $this->lang->line("SMS/Email Report"); ?></h4>
                                    <p><?php echo $this->lang->line("You will be able to see the Created SMS/Email Campaigns Reports.");?></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                                <div class="qs-box relative mb50 pos-icon-left wow fadeInUp" data-wow-delay="0.3s">
                                    <div class="qs-box-icon">
                                        <i class="fa fa-plug"></i>
                                    </div>
                                    <h4><?php echo $this->lang->line("SSEM Native APIs"); ?></h4>
                                    <p><?php echo $this->lang->line("It has native API by which developers can integrate it’s facilities with another app.");?></p>
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                                <div class="qs-box relative mb50 pos-icon-left wow fadeInUp" data-wow-delay="0.3s">
                                    <div class="qs-box-icon">
                                        <i class="fa fa-language"></i>
                                    </div>
                                    <h4><?php echo $this->lang->line("Multilingual Support"); ?></h4>
                                    <p><?php echo $this->lang->line("It has built-in support for 11 different Languages.");?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <!--FEATURES AREA END-->
    

    <!--INTRO AREA-->
    <section class="intro-area section-padding relative">
        <div class="area-bg"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                    <div class="intro-image wow fadeIn text-center">
                        <h3 class="hidden">Just Used For Validation</h3>
                        <img src="<?php echo base_url();?>assets/site_new/img/mockups/home-two-promo-mockup.png" alt="" style="max-width: 82%;margin:0 auto;">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--INTRO AREA END-->

    <!--WORK AREA-->
    <section class="work-area section-padding" id="work">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-xs-12">
                    <div class="area-title text-center wow fadeIn">
                        <h2><?php echo $this->lang->line("Send SMS"); ?></h2>
                        <span class="icon-and-border"><i class="material-icons">phone_android</i></span>
                        <p><?php echo $this->lang->line("Sending SMS one of the important").' '.$this->config->item('product_name').'.'.' '.$this->lang->line("You can send Bulk/scheduled SMS to your contacts."); ?></p>
                    </div>
                </div>
            </div>
            <div class="row flex-v-center">
                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                    <div class="qs-box pos-icon-right mb100 wow fadeIn">
                        <div class="qs-box-icon">
                            <img src="<?php echo base_url();?>assets/site_new/img/icon/icon-1.png" alt="">
                        </div>
                        <h4><?php echo $this->lang->line("Set SMS API"); ?></h4>
                        <p><?php echo $this->lang->line("Set SMS API for sending SMS."); ?></p>
                    </div>
                    <div class="qs-box  pos-icon-right wow fadeIn xs-mb50">
                        <div class="qs-box-icon">
                            <img src="<?php echo base_url();?>assets/site_new/img/icon/icon-2.png" alt="">
                        </div>
                        <h4><?php echo $this->lang->line("Create SMS Template"); ?></h4>
                        <p><?php echo $this->lang->line("Create a SMS temaplate to use in sms campaign."); ?></p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-12 hidden-xs hidden-sm">
                    <div class="service-image text-center wow fadeIn xs-mb50">
                        <img src="<?php echo base_url();?>assets/site_new/img/mockups/home-two-work-mockup.png" alt="">
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 pull-left">
                    <div class="qs-box  pos-icon-left mb100 wow fadeIn">
                        <div class="qs-box-icon">
                            <img src="<?php echo base_url();?>assets/site_new/img/icon/icon-3.png" alt="">
                        </div>
                        <h4><?php echo $this->lang->line("Create & Send SMS"); ?></h4>
                        <p><?php echo $this->lang->line("Create & send your SMS to your contact."); ?></p>
                    </div>
                    <div class="qs-box pos-icon-left wow fadeIn">
                        <div class="qs-box-icon">
                            <img src="<?php echo base_url();?>assets/site_new/img/icon/icon-4.png" alt="">
                        </div>
                        <h4><?php echo $this->lang->line("SMS History"); ?></h4>
                        <p><?php echo $this->lang->line("See the history at SMS history."); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--WORK AREA END-->

    <!--ABOUT AREA-->
    <section class="about-area gray-bg section-padding" id="app">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-xs-12">
                    <div class="area-title text-center wow fadeIn">
                        <h2><?php echo $this->lang->line("About Our App"); ?></h2>
                        <span class="icon-and-border"><i class="material-icons">phone_android</i></span>
                        <p><?php echo $this->lang->line("World’s most powerful and Complete Smart SMS & Email manager Tool"); ?></p>
                    </div>
                </div>
            </div>
            <div class="row flex-v-center">
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <div class="about-content sm-mb50 sm-center">
                        <h4 class="mb30"><?php echo $this->config->item('product_name').' '."- ". $this->lang->line("Powerful and Complete Smart SMS & Email manager Tool"); ?></h4>
                        <p class="description"><?php echo $this->lang->line('You can manage your contacts, create SMS/Email template, send SMS/Email, schedule SMS/Email, wish your contacts’ birthday etc using Smart SMS & Email Manager (SSEM) in a smarter way.');?></p>
                        <p class="description"><?php echo $this->lang->line('SSEM has built-in support for world’s most popular SMS & Email gateways like Plivo, Twilio, Clickatell, Nexmo, Mandrill, Sendgrid, Mailgun etc.');?></p>
                        <p class="description"><?php echo $this->lang->line('It’s a multi-user SaaS application and designed in a way so that each user can have independent environment. Users will have their own SMS/Email gateways and will manage their own SMS/Email as well as bills.');?></p>
                        <p class="description"><?php echo $this->lang->line('Use awesome services in your own language. SSEM now has built-in support for 11 languages and you can add new language easily.');?></p>
                        <a href="#video" class="video-button mt30 inline-block"><i class="fa fa-play"></i> <?php echo $this->lang->line("Watch Promo Video"); ?></a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <div class="about-mockup center wow fadeIn xs-mt50">
                        <img src="<?php echo base_url();?>assets/site_new/img/mockups/home-two-about-mockup.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--ABOUT AREA END-->

    <!--PROMO AREA-->
    <section class="<?php if($this->config->item('display_video_block') == '0' || $this->config->item('promo_video') == '') echo 'hidden';?> promo-area relative section-padding" id="video">
        <div class="area-bg"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2 col-sm-12 col-xs-12">
                    <div class="area-title center white wow fadeIn">
                        <h2><?php echo $this->lang->line("Explore The Best Promo Video"); ?></h2>
                        <p><?php echo $this->lang->line("See the super promo video"); ?></p>
                    </div>
                </div>
            </div>
            <?php 
                $link = $this->config->item('promo_video');
                // $final = ltrim($link,'https://www.youtube.com/watch?v=');
                $final = trim(str_replace('https://www.youtube.com/watch?v=','',$link));
             ?>
            <div class="row">
                <div class="col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2 col-sm-12 col-xs-12">
                    <div class="promo-area-content center white wow fadeIn">
                        <div class="video-promo-slider">
                            <div class="single-video-promo-slide">
                                <img src="<?php echo base_url();?>assets/site_new/img/promo/video-promo-slide-1.png" alt="">
                                <div class="video-play-button">
                                    <button data-video-id="<?php echo $final; ?>" class="video-area-popup"><i class="fa fa-play-circle"></i></button>
                                </div>
                            </div>
                            <div class="single-video-promo-slide">
                                <img src="<?php echo base_url();?>assets/site_new/img/promo/video-promo-slide-1.png" alt="">
                                <div class="video-play-button">
                                    <button data-video-id="<?php echo $final; ?>" class="video-area-popup"><i class="fa fa-play-circle"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--PROMO AREA END-->

    <!--SCREENSHOT AREA-->
    <section class="screenshot-area section-padding" id="screenshot">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-xs-12">
                    <div class="area-title text-center wow fadeIn">
                        <h2><?php echo $this->lang->line("App Screenshots"); ?></h2>
                        <span class="icon-and-border"><i class="material-icons">phone_android</i></span>
                        <p><?php echo $this->lang->line("Here are some screenshots of").' '.$this->config->item('product_name').' '.$this->lang->line("See the amazing shots and enjoy."); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row flex-v-center">
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <div class="screenshot-slider-area wow fadeIn xs-mb50">
                        <div class="screenshot-slider-2">
                            <div class="single-screenshot">
                                <img src="<?php echo base_url("assets/site_new/img/screenshot/screenshot-1.jpg");?>" alt="">
                            </div>
                            <div class="single-screenshot">
                                <img src="<?php echo base_url("assets/site_new/img/screenshot/screenshot-2.jpg");?>" alt="">
                            </div>
                            <div class="single-screenshot">
                                <img src="<?php echo base_url("assets/site_new/img/screenshot/screenshot-3.jpg");?>" alt="">
                            </div>
                            <div class="single-screenshot">
                                <img src="<?php echo base_url("assets/site_new/img/screenshot/screenshot-4.jpg");?>" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                    <div class="screenshot-content xs-center sm-center xs-mt50 sm-mt50">
                        <h2><?php echo $this->lang->line("Awesome App"); ?></h2>
                        <p><?php echo $this->config->item('product_name').' '.$this->lang->line("- Smart SMS & Email manager is an app that can allow you to send Bulk/scheduled SMS/Email to your contacts. It also allow to send birthday wishes to your contacts. It has built-in support for Plivo, Twilio, Clickatell, Nexmo, Mandrill, Sendgrid, Mailgun etc which are most powerful SMS/Email gateway."); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--SCREENSHOT AREA END-->

    <!--DOWNLOAD AREA-->
    <section class="download-area section-padding relative white" id="download">
        <div class="area-bg" data-stellar-background-ratio="0.6"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                    <div class="download-content sm-center xs-center xs-mb50 xs-font wow fadeIn">
                        <h2><?php echo $this->lang->line("Get the greatest app !"); ?></h2>
                        <p><?php echo $this->config->item('product_name').' '.$this->lang->line("provides you trial package. So Click on the button and explore it."); ?></p>
                        	<a href="<?php echo site_url('home/sign_up'); ?>" class="download-button wow shake""><i class="fa fa-shopping-cart"></i><?php echo $this->lang->line("Free Trial"); ?> <span>SSEM</span></a>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4 col-sm-6 col-xs-12">
                    <div class="download-content sm-center xs-center wow fadeIn">
                        <h2><?php echo $this->lang->line("Amazing Prices"); ?></h2>
                        <p><?php echo $this->lang->line("Easy and Smart SMS & Email manager Tool. It offers you to use it at very reasonable price."); ?></p>
                        <a href="#pricing" class="download-button wow shake"><i class="fa fa-dollar"></i><?php echo $this->lang->line("Get the app"); ?> <span><?php echo $this->lang->line("Price Plans"); ?></span></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--DOWNLOAD AREA END-->

	<!--PRICING AREA-->
	<section class="price-area padding-100-70 sky-gray-bg" id="pricing">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-xs-12">
                    <div class="area-title text-center wow fadeIn">
                        <h2><?php echo $this->lang->line("Pricing"); ?> <span><?php echo $this->lang->line("Table"); ?></span></h2>
                        <span class="icon-and-border"><i class="material-icons">phone_android</i></span>
                        <p><?php echo $this->lang->line("Get the Very Smart SMS/Email manager tool with the built-in SMS/Email gateway support."); ?></p>
                    </div>
                </div>
            </div>

		<!-- starting of table row -->
             <div class="row">

                <div class="col-md-4 col-lg-4 col-lg-offset-4 col-md-offset-4 col-sm-6 col-xs-12">
                    <div class="single-price center wow fadeInUp" data-wow-delay="0.2s">
                        <div class="price-hidding">
                            <h3><?php echo $this->lang->line('get early access to'); ?> <b><?php echo $this->config->item('product_name'); ?></b></h3>
                        </div>
                        <div class="price-rate">
                        	<h2><sup>USD</sup> <?php echo $price; ?>
                                <sub> <?php echo $this->lang->line("/ Month - 1 User"); ?></sub>  
                            </h2>
                            
                        </div>

                        <div class="buy-now-button">
                            <a href="<?php echo site_url('home/sign_up'); ?>" class="read-more"><?php echo $this->lang->line('sign up'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of table row -->
        </div>
    </section>
    <!--PRICING AREA END-->

    <!--Review AREA-->
    <section class="<?php if($this->config->item('display_review_block') == '0') echo 'hidden';?> video-area section-padding style-two" id="team">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-xs-12">
                    <div class="area-title text-center wow fadeIn" style="visibility: visible; animation-name: fadeIn;">
                        <h2><span><?php echo $this->lang->line("Reviews"); ?></span></h2>
                        <span class="icon-and-border"><i class="material-icons">phone_android</i></span>
                    </div>
                </div>
            </div>
            <div class="row flex-v-center">
                <!-- Demo video section -->
                <?php 
                    $demo = $this->config->item('customer_review_video');
                    // $customer_review_video = ltrim($link,'https://www.youtube.com/watch?v=');
                    $customer_review_video = trim(str_replace('https://www.youtube.com/watch?v=','',$demo));
                ?>
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 <?php if($this->config->item('customer_review_video') == '') echo 'hidden';?>">
                    <div class="video-area-content wow fadeIn sm-mb50 xs-mb50">
                        <img src="<?php echo base_url();?>assets/site_new/img/video/review-bg.jpg" alt="">
                        <button data-video-id="<?php echo $customer_review_video; ?>" class="video-area-popup"><i class="fa fa-play"></i></button>
                        <h4 class="demo-title-area" style="text-align: center; font-weight: bold;border-radius: 60px;width: 40%;box-shadow: 2px 2px 2px #aaa, -1px 0 1px #aaa;position: relative;left: 170px;margin: 5px 0px; padding: 0 1px;"><?php echo $this->lang->line('Customer review Video'); ?></h4>
                    </div>
                </div>
                <!-- End of demo video section -->

                <div class="<?php if($this->config->item('customer_review_video') == '') echo 'col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2 col-sm-12 col-xs-12'; else echo 'col-md-6 col-lg-6 col-sm-12 col-xs-12';?>">
                    <div class="team-member-content wow fadeIn">
                        <div class="team-member-list team-slider">
	                        <?php 
                                $customerReview = $this->config->item('customer_review');
                                $ct=0;
							    foreach($customerReview as $singleReview) : 
                                $ct++;
                                $original = $singleReview[2];
                                $base     = base_url();

                                if (substr($original, 0, 4) != 'http') {
                                    $img = $base.$original;
                                } else {
                                   $img = $original;
                                }

                            ?>
                                <div class="single-team" style="height: 200px;">
                                    <div class="member-image">
                                        <img src="<?php echo $img; ?>" alt="reviewer">
                                    </div>
                                    <div class="name-and-designation">
                                        <h4><?php echo $singleReview[0]; ?></h4>
                                        <p><?php echo $singleReview[1]; ?></p>
                                        <p style="text-align: justify; font-weight: normal;">
                                            <?php 
                                                if(strlen($singleReview[3]) > 200 ) {
                                                    $str = substr($singleReview[3],0,180);
                                                    echo $str.". . ."."<a class='exe' type='button' data-toggle='modal' data-target=#myModal".$ct.">see more</a>";
                                                
                                                } else {
                                                    echo $str = $singleReview[3];
                                                }
                                                
                                            ?>
                                        </p>
                                    </div>
                                    <div class="member-details">
                                    </div>
                                </div>
	                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Review AREA END-->

    <!--CONTACT US AREA-->
    <section style="<?php if($this->config->item('display_review_block') == '0') echo 'background-color: #fff'; else echo 'background-color: #f5f4f4'; ?>" class="contact-area relative padding-100-50" id="contact">
        <div class="contact-form-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-xs-12">
                        <div class="area-title text-center wow fadeIn">
                            <h2><?php echo $this->lang->line('Contact Us');?></h2>
                            <span class="icon-and-border"><i class="material-icons">phone_android</i></span>
                            <p><?php echo $this->lang->line('Feel free to contact with us.'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                    	<div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                <div class="form-group" id="name-field">
                                    <?php 
										if($this->session->userdata('mail_sent') == 1) {
										echo "<div class='alert alert-success text-center'>".$this->lang->line("we have received your email. we will contact you through email as soon as possible")."</div>";
										$this->session->unset_userdata('mail_sent');
										}
									?>
                                </div>
                            </div>
                    	</div>
                        <div class="contact-form mb50 wow fadeIn">
                            <form action="<?php echo site_url("site/email_contact"); ?>" method="post">
                                <div class="row">
                                    <div class="col-md-5 col-lg-5 col-sm-12 col-xs-12">
                                        <div class="form-group" id="email-field">
                                            <div class="form-input">
                                                <input type="email" class="form-control" required id="email" <?php echo set_value("email"); ?> placeholder="<?php echo $this->lang->line("email");?>" name="email">
                                            </div>
                                            <span class="red"><?php echo form_error("email"); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-12 col-xs-12">
                                        <div class="form-group" id="phone-field">
                                            <div class="form-input">
                                                <input type="text" class="form-control" required id="subject" <?php echo set_value("subject"); ?> placeholder="<?php echo $this->lang->line("message subject");?>" name="subject">
                                            </div>
                                            <span class="red"><?php echo form_error("subject"); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-12 col-xs-12">
                                        <div class="form-group" id="message-field">
                                            <div class="form-input">
                                                <input type="number" class="form-control" step="1" required id="captcha" <?php echo set_value("captcha"); ?> placeholder="<?php echo $contact_num1. "+". $contact_num2." = ?"; ?>" name="captcha">
													<span class="red">
														<?php 
														if(form_error('captcha')) 
															echo form_error('captcha'); 
														else  
														{ 
															echo $this->session->userdata("contact_captcha_error"); 
															$this->session->unset_userdata("contact_captcha_error"); 
														} 
														?>
													</span>
                                            	</div>
                                            <span class="red"><?php echo form_error("message") ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                        <div class="form-group" id="message-field">
                                            <div class="form-input">
                                                <textarea class="form-control" rows="3" required id="message" <?php echo set_value("message"); ?> placeholder="<?php echo $this->lang->line("message");?>" name="message"></textarea>
                                            </div>
                                            <span class="red"><?php echo form_error("message") ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                        <div class="form-group center">
                                            <button type="submit"><?php echo $this->lang->line("Send Message");?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--CONTACT US AREA END-->

    <!--FOOER AREA-->
    <footer class="footer-area white relative">
        <div class="area-bg"></div>
        <div class="footer-bottom-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3 col-sm-12 col-xs-12">
                        <div class="footer-social-bookmark text-center section-padding wow fadeIn">
                            <div class="footer-logo mb50 hidden-xs">
                                <a href="#"><img src="<?php echo base_url();?>assets/images/logo.png" alt="logo"></a>
                            </div>
                            <p style=""><?php echo $this->lang->line("World’s Most Powerful And Complete Smart SMS & Email manager Tool"); ?></p>
                            <?php 
                                $facebook  = $this->config->item('facebook');
                                $twitter   = $this->config->item('twitter');
                                $linkedin  = $this->config->item('linkedin');
                                $reddit    = $this->config->item('reddit');
                                $pinterest = $this->config->item('pinterest');
                                $youtube   = $this->config->item('youtube');

                                if($facebook=='' && $twitter=='' && $linkedin=='' && $youtube=='') $cls='hidden';
                            ?>
                            <ul class="social-bookmark mt50 <?php if(isset($cls)) echo $cls; ?>">
                                <li <?php if($facebook=='') echo "class='hidden'"; ?>>
                                	<a title="Facebook" target="_blank" class="facebook" href="<?php echo $facebook; ?>"><i class="fa fa-facebook"></i>
                                	</a>
                                </li>
                                <li <?php if($twitter=='') echo "class='hidden'"; ?>>
                                	<a title="Twitter" target="_blank" class="twitter" href="<?php echo $twitter; ?>"><i class="fa fa-twitter"></i>
                                	</a>
                                </li>
                                <li <?php if($linkedin=='') echo "class='hidden'"; ?>>
                                	<a title="Linkedin" target="_blank" class="linkedin" href="<?php echo $linkedin; ?>"><i class="fa fa-linkedin"></i>
                                	</a>
                                </li>
                                <li <?php if($youtube=='') echo "class='hidden'"; ?>>
                                	<a title="Youtube" target="_blank" class="youtube" href="<?php echo $youtube; ?>"><i class="fa fa-youtube-play"></i>
                                	</a>
                                </li>
                                <li <?php if($reddit=='') echo "class='hidden'"; ?>>
                                    <a title="Reddit" target="_blank" class="reddit" href="<?php echo $reddit; ?>"><i class="fa fa-reddit"></i>
                                    </a>
                                </li>
                                <li <?php if($pinterest=='') echo "class='hidden'"; ?>>
                                    <a title="Pinterest" target="_blank" class="pinterest" href="<?php echo $pinterest; ?>"><i class="fa fa-pinterest"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <div class="footer-copyright text-center wow fadeIn" style="padding-bottom: 55px;">
                            <p>
                            	<?php echo $this->config->item("product_short_name")." ".$this->APP_VERSION; ?> | <?php echo $this->lang->line("Copyright"); ?> &copy; <a target="_blank" href="<?php echo site_url(); ?>"><?php echo $this->config->item("institute_address1"); ?></a></p>
                            <p class="text-center" style="font-size: 10px;">
                                <a href="<?php echo base_url('home/privacy_policy'); ?>" target="_blank"><?php echo $this->lang->line("Privacy Policy"); ?></a> | <a href="<?php echo base_url('home/terms_use'); ?>" target="_blank"><?php echo $this->lang->line("Terms of Service"); ?></a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </footer>
    <!-- COOKIES -->
    <?php if($this->session->userdata('allow_cookie')!='yes') : ?>
        <div class="text-center cookiealert">
            <div class="cookiealert-container">
                <a style="font-size: 16px; color:#fff;text-decoration: none;" href="<?php echo base_url('home/privacy_policy#cookie_policy');?>">
                    <?php echo $this->lang->line("This site requires cookies in order for us to provide proper service to you.");?>
                </a>
                <a type="button" href="#" style="color:#000;" class="btn btn-warning btn-sm acceptcookies" aria-label="Close">
                    <?php echo $this->lang->line("Got it !"); ?>
                </a>

            </div>
        </div>
    <?php endif; ?>
    <!-- /COOKIES -->
    <!--FOOER AREA END-->


    <!--====== SCRIPTS JS ======-->
    <script src="<?php echo base_url('assets/site_new/js/vendor/jquery-1.12.4.min.js');?>"></script>
    <script src="<?php echo base_url('assets/site_new/js/vendor/bootstrap.min.js');?>"></script>

    <!--====== PLUGINS JS ======-->
    <script src="<?php echo base_url('assets/site_new/js/vendor/jquery.easing.1.3.js');?>"></script>
    <script src="<?php echo base_url('assets/site_new/js/vendor/jquery-migrate-1.2.1.min.js');?>"></script>
    <script src="<?php echo base_url('assets/site_new/js/vendor/jquery.appear.js');?>"></script>
    <script src="<?php echo base_url('assets/site_new/js/owl.carousel.min.js');?>"></script>
    <script src="<?php echo base_url('assets/site_new/js/slick.min.js');?>"></script>
    <script src="<?php echo base_url('assets/site_new/js/stellar.js');?>"></script>
    <script src="<?php echo base_url('');?>assets/site_new/js/wow.min.js"></script>
    <script src="<?php echo base_url('assets/site_new/js/jquery-modal-video.min.js');?>"></script>
    <script src="<?php echo base_url('assets/site_new/js/stellarnav.min.js');?>"></script>
    <script src="<?php echo base_url('assets/site_new/js/contact-form.js');?>"></script>
    <script src="<?php echo base_url('');?>assets/site_new/js/jquery.ajaxchimp.js"></script>
    <script src="<?php echo base_url('assets/site_new/js/jquery.sticky.js');?>"></script>

    <!--===== ACTIVE JS=====-->
    <script src="<?php echo base_url();?>assets/site_new/js/main.js"></script>

    
</body>
</html>


<style type="text/css" media="screen">
    .red{color:red;}
</style>


<style>
    .exe { font-weight: bold; } 
    .exe:hover  { cursor: pointer; text-decoration: underline;  }
</style>


<script type="text/javascript">
    $(document).ready(function() {
        $(document.body).on('click', '.acceptcookies', function(event) {
            event.preventDefault();
            var base_url = '<?php echo base_url(); ?>';
            $('.cookiealert').hide();
            $.ajax({
                url: base_url+'home/allow_cookie',
                type: 'POST',
            })
        });
    });
</script>

<!-- Modal -->
<?php   
    $ct=0;
    foreach($customerReview as $singleReview) : 
        $ct++;
        $original = $singleReview[2];
        $base     = base_url();

        if (substr($original, 0, 4) != 'http') {
            $img = $base.$original;
        } else {
           $img = $original;
        }
?>

    <div id="myModal<?php echo $ct; ?>" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-weight: bold;"><?php echo $this->lang->line('Full Review'); ?></h4>
            </div>
            <div class="single-item" style="text-align: center; margin-top: 10px;">
                <div class="member-image">
                    <img class="img-circle img-thumbnail" src="<?php echo $img; ?>" alt="reviewer">
                </div>
                <div class="modal-body name-and-designation" style="margin-top: 10px;">
                    <h4><?php echo $singleReview[0]; ?></h4>
                    <p><?php echo $singleReview[1]; ?></p>
                    <p style="text-align: justify; font-style: normal; color: #000;padding:10px 20px;"><?php echo $singleReview[3]; ?></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
            </div>
        </div>

      </div>
    </div>
<?php endforeach; ?>
<!-- End of Modal -->

<style type="text/css">
    .add-970-90 img{width: 970px; height: 90px;}
    .add-300-600 img{width: 300px; height: 600px;}
    .add-320-100 img{width: 320px; height: 100px;}
    .add-300-250 img{width: 300px; height: 250px;}


    @media (max-width: 767px) { /* in xs device */
      .add-970-90,.add-300-600,.add-320-100,.add-300-250 {
        text-align: center !important;
      } 
      .add-970-90 img,.add-300-600 img,.add-320-100 img,.add-300-250 img{
        margin: 15px 0 !important;
      } 
      .footer-copyright{border-top:none !important;margin-top:20px;}
    }
    @media (min-width: 768px) and (max-width: 991px) { /* in sm device */
      .add-970-90,.add-300-600,.add-320-100,.add-300-250 {
        text-align: center !important;
      } 
      .add-970-90 img,.add-300-600 img,.add-320-100 img,.add-300-250 img{
        margin: 15px 0; !important;
      } 
      .footer-copyright{border-top:none !important;margin-top:20px;}
    }



    .form_holder {
        display: table;
        margin: 0px 0 3px 31px;
        font-size: 16px;
        background: none !important;
    }

    #website_name {
        background: #fff none repeat scroll 0 0;
        opacity: .8;
        border: 1px solid #ccc;
        border-right: none;
        border-radius: 50px 0 0 50px;
        display: inline-block;
        float: left;
        font-size: 16px;
        font-weight: 500;
        height: 44px;
        padding-left: 6%;
        text-align: center;
        transition: all 0.3s ease 0s;
        width: 400px;
        color: black !important;
        margin-top: 30px;
    }

    #submit {
        background: #f8971d none repeat scroll 0 0;
        opacity: .9;
        border: medium none;
        border-radius: 0 50px 50px 0;
        display: inline-block;
        font-size: 20px;
        font-weight: 400;
        height: 44px;
        line-height: 50px;
        padding-top: 10px;
        transition: all 0.3s ease 0s;
        width: 70px;
        margin-top: 30px;
        cursor: pointer;
        color: #fff;
    }

    #submit .fa-2x {
        font-size: 1.5em;
        position: relative;
        top: -11px;
    }

    .cookiealert
    {
        background: #000 !important;
        padding: 15px 0 !important;
        opacity: .7 !important;
        position: fixed !important;
        bottom:0 !important;
        left: 0 !important;
        z-index: 99999 !important;
        width: 100% !important;
    }

    .add-300-600 img,.add-300-250 img
    {
        border-radius: 15px;
        -moz-border-radius: 15px;
        -webkit-border-radius: 15px;
    }

    @media screen and (max-width: 640px) {
        #website_name {
            width: 200px;
            font-size: 8px;
            margin-left: -66px;
        }

        .form_holder {
            margin-left: 150px;
            /*margin-top: -20px;*/
        }
    }    

    @media only screen and (max-width: 400px) {
        #website_name {
            width: 170px;
            font-size: 8px;
            margin-left: -145px;
        }

        .form_holder {
            margin-left: 200px;
            /*margin-top: -20px;*/
        }
    }
</style>