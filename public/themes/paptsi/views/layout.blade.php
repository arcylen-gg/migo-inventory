<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>{{ ucfirst($shop_info->shop_key) }} | {{ $page }}</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">

        <!-- GOOGLE FONT -->
        <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700" rel="stylesheet"> -->
        
        <!-- BOOTSTRAP -->
        <link rel="stylesheet" href="/themes/{{ $shop_theme }}/assets/bootstrap/css/bootstrap.min.css">

        <!-- FONT AWESOME -->
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/font-awesome/css/font-awesome.min.css">

        <!-- GLOBAL CSS -->
        <!-- <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/global.css">
        <link rel="stylesheet" type="text/css" href="/assets/front/css/loader.css"> -->
        {{-- Parallax --}}
        <!-- <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/parallax.css"> -->
        <!-- THEME COLOR -->

        <link href="/themes/{{ $shop_theme }}/css/{{ $shop_theme_color }}.css" rel="stylesheet" type="text/css">

        <!-- OTHER CSS -->
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/one.css">
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/style.css">

        <!-- <script src="/themes/{{ $shop_theme }}/assets/initializr/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script> -->

    </head>

    <body>
    
    <!-- Header -->
    <header id="home" class="welcome-hero-area">
      <div class="top-bar hidden-sm hidden-xs">
        <div class="container">
          <div class="row">
            <div class="col-sm-6">
              <ul class="list-unstyled list-inline">
                <li>
                  <p><i class="fa fa-envelope"></i><a href="mailto:support@sbtechnosoft.com">paptsi@gmail.com</a></p>
                </li>
                <li>
                  <p><i class="fa fa-phone"></i> Call us 02-833-7634</p>
                </li>
              </ul>
            </div>
            <div class="col-sm-6 text-right">
              <div class="top-header-wrapper">
                <ul>
                  <li><a href="javascript:void(0)"><i class="fa fa-facebook"></i></a></li>
                  <li><a href="javascript:void(0)"><i class="fa fa-twitter"></i></a></li>
                  <li><a href="javascript:void(0)"><i class="fa fa-linkedin"></i></a></li>
                  <li><a href="javascript:void(0)"><i class="fa fa-pinterest"></i></a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="header-top-area">
        <div class="container">
          <div class="row">
            <div class="col-md-3"> 
              <!-- START LOGO -->
              <!-- <div class="logo"> <a href="index-2.html">Prime</a> </div> -->

              <div class="logo"><a href="#"><img width="89%" src="/themes/{{ $shop_theme }}/img/logo.png"></a></div>
              
              <!-- END LOGO --> 
            </div>
            <div class="col-md-9"> 
              <!-- START MAIN MENU -->
              <nav class="navbar navbar-default"> 
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                  <!-- Logo --> </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                  <ul class="nav navbar-nav">
                    <li><a class="smoth-scroll" href="#home">Home</a></li>
                    <li><a class="smoth-scroll" href="#aboutus">About Us</a></li>
                    <li><a class="smoth-scroll" href="#services">Services</a></li>
                    <!-- <li><a class="smoth-scroll" href="#testimonials">Testimonials</a></li> -->
                    <!-- <li><a class="smoth-scroll" href="#pricing">Pricing</a></li> -->
                    <!-- <li><a class="smoth-scroll" href="#our-team">Our Team</a></li> -->
                    <li><a class="smoth-scroll" href="#contactus">Contact Us</a></li>
                  </ul>
                </div>
                <!-- /.navbar-collapse --> 
              </nav>
              <!-- END MAIN MENU --> 
            </div>
          </div>
        </div>
      </div>
      <!-- Banner Start -->
      <div class="banner-wrapper">
        <div class="container">
          <div class="banner-content">
            <div class="banner-content-table-cell">
              <div class="banner-logo-container"><img src="/themes/{{ $shop_theme }}/img/logo-inverted.png"></div>
              <h1>We Aim For The Best <span id="js-rotating">Services | Operations | Management</span></h1>
              <p>In the Industry of ports and Terminal Services</p>
              <a href="javascript:void(0)" class="btn btn-default">Learn More</a></div>
          </div>
        </div>
      </div>
      <!-- Banner End --> 
    </header>
    <!-- About Us Start -->
    <section id="aboutus" class="aboutus">
      <div class="container">
        <div class="section-title">
          <h2>About <span>Us</span></h2>
        </div>
        <div class="row">
          <div class="col-md-4 col-sm-6">
            <div class="about-details">
              <h4>About Experts</h4>
              <img src="images/about-img.jpg" alt="">
              <p>Philippine Archipelago Ports and Terminal Services Inc. aims to be a key player in the industry of port operation and management. We provide safe, clean and convenient port facilities and passenger terminal building.</p>
              <a class="readmore" href="#">Read More</a> </div>
          </div>
          <div class="col-md-4 col-sm-6">
            <div class="we-do">
              <h4>What We Do</h4>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer.</p>
              <ul>
                <li><i class="fa fa-check" aria-hidden="true"></i>Lorem Ipsum</li>
                <li><i class="fa fa-check" aria-hidden="true"></i>Lorem Ipsum</li>
                <li><i class="fa fa-check" aria-hidden="true"></i>Lorem Ipsum</li>
                <li><i class="fa fa-check" aria-hidden="true"></i>Lorem Ipsum</li>
                <li><i class="fa fa-check" aria-hidden="true"></i>Lorem Ipsum</li>
                <li><i class="fa fa-check" aria-hidden="true"></i>Lorem Ipsum</li>
              </ul>
            </div>
          </div>
          <div class="col-md-4 col-sm-12">
            <div class="skill">
              <h4>Our Progress</h4>
              <div class="skill-progress">
                <div>Lorem Ipsum</div>
                <div class="progress">
                  <div class="progress-bar html wow fadeInLeft" data-wow-delay="2s" role="progressbar" aria-valuenow="82" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span class="web-bar bar1 wow fadeInLeft" data-wow-delay="2s">82%</span> </div>
              <div class="skill-progress">
                <div>Lorem Ipsum</div>
                <div class="progress wow fadeInLeft" >
                  <div class="progress-bar css3 wow fadeInLeft" data-wow-delay="2s" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span class="web-bar bar2 wow fadeInLeft" data-wow-delay="2s">78%</span> </div>
              <div class="skill-progress">
                <div>Lorem Ipsum</div>
                <div class="progress">
                  <div class="progress-bar bootstrap wow fadeInLeft" data-wow-delay="2s" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span class="web-bar bar3 wow fadeInLeft" data-wow-delay="2s">85%</span> </div>
              <div class="skill-progress">
                <div>Lorem Ipsum</div>
                <div class="progress">
                  <div class="progress-bar photoshop wow fadeInLeft" data-wow-delay="2s" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span class="web-bar bar4 wow fadeInLeft" data-wow-delay="2s">65%</span> </div>
              <div class="skill-progress">
                <div>Lorem Ipsum</div>
                <div class="progress">
                  <div class="progress-bar jquery wow fadeInLeft" data-wow-delay="2s" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span class="web-bar bar5 wow fadeInLeft" data-wow-delay="2s">90%</span> </div>
              <div class="skill-progress">
                <div>Lorem Ipsum</div>
                <div class="progress">
                  <div class="progress-bar javascript wow fadeInLeft" data-wow-delay="2s" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span class="web-bar bar5 wow fadeInLeft" data-wow-delay="2s">95%</span> </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Services Start -->
    <section id="services" class="services">
      <div class="container">
        <div class="row">
          <div class="section-title">
            <h2>Our <span>Services</span></h2>
          </div>
        </div>
        <div class="row">
          <div class="service-callouts">
            <div class="col-sm-6 col-md-4">
              <div class="service-box"> <i class="fa fa-ship" aria-hidden="true"></i>
                <h3 class="subtitle">Lorem Ipsum</h3>
                <p>Lorem ipsum dolor sit amet, consectetur a elit, sed do eiu tempor incididunt ute labore etrt dolore.</p>
              </div>
            </div>
            <div class="col-sm-6 col-md-4">
              <div class="service-box"> <i class="fa fa-building-o" aria-hidden="true"></i>
                <h3 class="subtitle">Lorem Ipsum</h3>
                <p>Lorem ipsum dolor sit amet, consectetur a elit, sed do eiu tempor incididunt ute labore etrt dolore.</p>
              </div>
            </div>
            <div class="col-sm-6 col-md-4">
              <div class="service-box"> <i class="fa fa-handshake-o" aria-hidden="true"></i>
                <h3 class="subtitle">Lorem Ipsum</h3>
                <p>Lorem ipsum dolor sit amet, consectetur a elit, sed do eiu tempor incididunt ute labore etrt dolore.</p>
              </div>
            </div>
            <div class="col-sm-6 col-md-4">
              <div class="service-box"><i class="fa fa-crosshairs" aria-hidden="true"></i>
                <h3 class="subtitle">Lorem Ipsum</h3>
                <p>Lorem ipsum dolor sit amet, consectetur a elit, sed do eiu tempor incididunt ute labore etrt dolore.</p>
              </div>
            </div>
            <div class="col-sm-6 col-md-4">
              <div class="service-box"><i class="fa fa-line-chart" aria-hidden="true"></i>
                <h3 class="subtitle">Lorem Ipsum</h3>
                <p>Lorem ipsum dolor sit amet, consectetur a elit, sed do eiu tempor incididunt ute labore etrt dolore.</p>
              </div>
            </div>
            <div class="col-sm-6 col-md-4">
              <div class="service-box"><i class="fa fa-life-ring" aria-hidden="true"></i>
                <h3 class="subtitle">Lorem Ipsum</h3>
                <p>Lorem ipsum dolor sit amet, consectetur a elit, sed do eiu tempor incididunt ute labore etrt dolore.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Services End --> 
    <!-- Testimonials Wrapper Start -->
    <!-- <section id="testimonials" class="testimonials-wrapper white">
      <div class="container">
        <div class="row">
          <div class="section-title">
            <h2>People <span>Says</span></h2>
          </div>
        </div>
        <div id="myCarousel" class="carousel slide" data-ride="carousel"> 
          Testimonials Indicators
          <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
          </ol>
          Testimonials slides
          <div class="carousel-inner" role="listbox">
            <div class="item active">
              <div class="col-md-3">
                <div class="testimonial-img"> <img src="images/testimonials1.jpg" alt=""> <i class="fa fa-quote-left"></i> </div>
              </div>
              <div class="col-md-9">
                <div class="textimonial-text">
                  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                  <p class="client-name">Client Name</p>
                  <div class="testimonial-rating">
                    <p>Designation</p>
                    <ul>
                      <li><i class="fa fa-star"></i></li>
                      <li><i class="fa fa-star"></i></li>
                      <li><i class="fa fa-star"></i></li>
                      <li><i class="fa fa-star"></i></li>
                      <li><i class="fa fa-star-half-o"></i></li>
                    </ul>
                  </div>
                  <i class="fa fa-quote-right"></i> </div>
              </div>
            </div>
            <div class="item">
              <div class="col-md-3">
                <div class="testimonial-img"> <img src="images/testimonials2.jpg" alt=""> <i class="fa fa-quote-left"></i> </div>
              </div>
              <div class="col-md-9">
                <div class="textimonial-text">
                  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                  <p class="client-name">Client Name</p>
                  <div class="testimonial-rating">
                    <p>Designation</p>
                    <ul>
                      <li><i class="fa fa-star"></i></li>
                      <li><i class="fa fa-star"></i></li>
                      <li><i class="fa fa-star"></i></li>
                      <li><i class="fa fa-star"></i></li>
                      <li><i class="fa fa-star-half-o"></i></li>
                    </ul>
                  </div>
                  <i class="fa fa-quote-right"></i> </div>
              </div>
            </div>
            <div class="item">
              <div class="col-md-3">
                <div class="testimonial-img"> <img src="images/testimonials3.jpg" alt=""> <i class="fa fa-quote-left"></i> </div>
              </div>
              <div class="col-md-9">
                <div class="textimonial-text">
                  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                  <p class="client-name">Client Name</p>
                  <div class="testimonial-rating">
                    <p>Designation</p>
                    <ul>
                      <li><i class="fa fa-star"></i></li>
                      <li><i class="fa fa-star"></i></li>
                      <li><i class="fa fa-star"></i></li>
                      <li><i class="fa fa-star"></i></li>
                      <li><i class="fa fa-star-half-o"></i></li>
                    </ul>
                  </div>
                  <i class="fa fa-quote-right"></i> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section> -->
    <!-- Testimonials Wrapper End --> 
    <!-- Satisfied Wrapper start -->
    <div class="satisfied-wrapper" style="display: none;">
      <div class="container">
        <div class="col-sm-6 col-md-3">
          <div class="counter">
            <div class="icon"><span class="lnr lnr-users"></span></div>
            <div class="number animateNumber" data-num="3550"> <span>3550</span></div>
            <p>Websites Created</p>
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <div class="counter">
            <div class="icon"><span class="lnr lnr-clock"></span></div>
            <div class="number animateNumber" data-num="7200"> <span>7200</span></div>
            <p>Hours Worked</p>
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <div class="counter">
            <div class="icon"><span class="lnr lnr-laptop"></span></div>
            <div class="number animateNumber" data-num="1012"> <span>1012</span></div>
            <p>Projects Completed</p>
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <div class="counter">
            <div class="icon"><span class="lnr lnr-user"></span></div>
            <div class="number animateNumber" data-num="99"> <span>99</span></div>
            <p>Theme Experts</p>
          </div>
        </div>
      </div>
    </div>
    <!-- satisfied Wrapper start --> 
    <!-- Pricing Table Start -->
    <!-- <section id="pricing" class="pricing-table-wrapper">
      <div class="container">
        <div class="row">
          <div class="section-title">
            <h2>People <span>Says</span></h2>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <div class="pricing_item">
              <div class="pricebox clearfix">
                <div class="price_title">
                  <h3> Basic</h3>
                  <span class="ratings"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-half-full"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> </span> </div>
                <div class="price">
                  <p><strong>$19</strong><span class="month">/mo</span></p>
                </div>
              </div>
              <p class="availability">1GB Desk Space</p>
              <p class="availability">512 MB Memory</p>
              <p class="availability">Powerful Admin Panel</p>
              <p class="availability">100 Business Emails</p>
              <p class="availability">Unlimited Bandwidth</p>
              <p class="availability">8 Free Forks Every Months</p>
              <p class="availability">Free Software Installer</p>
              <a href="javascript:void(0)" class="readmore">GET IT NOW</a> </div>
          </div>
          end item
          
          <div class="col-sm-4">
            <div class="pricing_item active">
              <div class="pricebox clearfix">
                <div class="price_title">
                  <h3>Standard</h3>
                  <span class="ratings"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-half-full"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> </span> </div>
                <div class="price">
                  <p><strong>$39</strong><span class="month">/mo</span></p>
                </div>
              </div>
              <p class="availability">5GB Desk Space</p>
              <p class="availability">1GB Memory</p>
              <p class="availability">Powerful Admin Panel</p>
              <p class="availability">100 Business Emails</p>
              <p class="availability">Unlimited Bandwidth</p>
              <p class="availability">8 Free Forks Every Months</p>
              <p class="availability">Free Software Installer</p>
              <a href="javascript:void(0)" class="readmore">GET IT NOW</a> </div>
          </div>
          end item
          
          <div class="col-sm-4">
            <div class="pricing_item">
              <div class="pricebox clearfix">
                <div class="price_title">
                  <h3>Premium</h3>
                  <span class="ratings"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-half-full"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> </span> </div>
                <div class="price">
                  <p><strong>$69</strong><span class="month">/mo</span></p>
                </div>
              </div>
              <p class="availability">10GB Desk Space</p>
              <p class="availability">2GB Memory</p>
              <p class="availability">Powerful Admin Panel</p>
              <p class="availability">100 Business Emails</p>
              <p class="availability">Unlimited Bandwidth</p>
              <p class="availability">8 Free Forks Every Months</p>
              <p class="availability">Free Software Installer</p>
              <a href="javascript:void(0)" class="readmore">GET IT NOW</a> </div>
          </div>
          end item 
          
          end item 
        </div>
      </div>
    </section> -->
    <!-- Pricing Table Start -->
    <!-- <section id="our-team" class="our-team">
      <div class="container">
        <div class="row">
          <div class="section-title">
            <h2>Our <span>Team</span></h2>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 col-md-3">
            <div class="our-team-single">
              <div class="our-team-img"> <a href="javascript:void(0)"> <img src="images/out-team1.jpg" alt="" title=""> </a>
                <div class="team-overlay"></div>
                <div class="team-social">
                  <ul>
                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                    <li><a href="#"><i class="fa fa-skype"></i></a></li>
                  </ul>
                </div>
              </div>
              <div class="caption-text">
                <h3><a href="javascript:void(0)">Robin Williams</a></h3>
                <span>Founder &amp; CEO</span> </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-3">
            <div class="our-team-single">
              <div class="our-team-img"> <a href="javascript:void(0)"> <img src="images/out-team2.jpg" alt="" title=""> </a>
                <div class="team-overlay"></div>
                <div class="team-social">
                  <ul>
                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                    <li><a href="#"><i class="fa fa-skype"></i></a></li>
                  </ul>
                </div>
              </div>
              <div class="caption-text">
                <h3><a href="javascript:void(0)">Dave Young</a></h3>
                <span>Co-Founder</span> </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-3">
            <div class="our-team-single">
              <div class="our-team-img"> <a href="javascript:void(0)"> <img src="images/out-team3.jpg" alt="" title=""> </a>
                <div class="team-overlay"></div>
                <div class="team-social">
                  <ul>
                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                    <li><a href="#"><i class="fa fa-skype"></i></a></li>
                  </ul>
                </div>
              </div>
              <div class="caption-text">
                <h3><a href="javascript:void(0)">Mary Williams</a></h3>
                <span>Founder</span> </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-3">
            <div class="our-team-single">
              <div class="our-team-img"> <a href="javascript:void(0)"> <img src="images/out-team4.jpg" alt="" title=""> </a>
                <div class="team-overlay"></div>
                <div class="team-social">
                  <ul>
                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                    <li><a href="#"><i class="fa fa-skype"></i></a></li>
                  </ul>
                </div>
              </div>
              <div class="caption-text">
                <h3><a href="javascript:void(0)">Ruby Williams</a></h3>
                <span>Founder</span> </div>
            </div>
          </div>
        </div>
      </div>
    </section> -->
    <!-- Contact Us Start -->
    <section id="contactus" class="contactus">
      <div class="container">
      <div class="row">
          <div class="section-title">
            <h2>Contact <span>Us</span></h2>
          </div>
        </div>
      <div class="row">
      <div class="col-sm-8">
        <form id="contact-form" class="contact-form" method="post">
          <div class="row">
            <div class="col-sm-6"> <span>Name</span>
              <div class="form-group"> <i class="fa fa-user-o"></i>
                <input name="name" id="name" class="form-control" required type="text">
              </div>
            </div>
            <div class="col-sm-6"> <span>E-mail</span>
              <div class="form-group"> <i class="fa fa-envelope-o"></i>
                <input name="email" id="email" class="form-control" required type="email">
              </div>
            </div>
            <div class="col-sm-12"> <span>Phone</span>
              <div class="form-group"> <i class="fa fa-phone"></i>
                <input name="subject" id="subject" class="form-control" required type="text">
              </div>
            </div>
            <div class="col-sm-12"> <span>Message</span>
              <div class="form-group"> <i class="fa fa-comments-o"></i>
                <textarea rows="4" name="message" id="message" class="form-control" required></textarea>
              </div>
            </div>
          </div>
          <!-- / .row -->
          <button type="submit" class="readmore">Send Message</button>
        </form>
      </div>
      <div class="col-sm-4"> 
        <!-- contact address start-->
        <div class="contact-address text-center">
          <div class="contact-info">
            <div class="contact-icon"> <i class="fa fa-map-marker"></i> </div>
            <p>Unit 4B 4th Floor UNIOIL Center Bldg.  <span> Business Park Alyala Alabang </span> <span>Muntinlupa City PH</span></p>
            <p> <abbr title="Email">E:</abbr> paptsi@gmail.com <span> <abbr title="Phone">P:</abbr> 02-833-7634 </span> <!-- <span> <abbr title="Fax">F:</abbr> (012) 345-6789 </span> --></p>
          </div>
        </div>
      </div>
      <!-- contact address end--> 
    </div>
      </div>
    </section>
    <!-- Contact Us End --> 
    <!-- Google Map Start -->
    <div class="google-map">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1932.0173736412858!2d121.0236813759334!3d14.425160545116888!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d1ce5020a0cb%3A0x7ed15c029251ee8a!2sMadrigal+Business+Park!5e0!3m2!1sen!2sph!4v1506135550439" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
    </div>
    <!-- Google Map end --> 

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="/themes/{{ $shop_theme }}/assets/initializr/js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
    <script src="/themes/{{ $shop_theme }}/assets/initializr/js/vendor/bootstrap.min.js"></script>
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/match-height.js"></script>
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/fit-text.js"></script>
    <!-- <script type="text/javascript" src="/assets/front/js/jquery.keep-ratio.min.js"></script> -->

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/themes/{{ $shop_theme }}/assets/jquery/jquery.animateNumber.min.js"></script>
    <script src="/themes/{{ $shop_theme }}/assets/jquery/plugins.js"></script>
    <script src="/themes/{{ $shop_theme }}/assets/easing/jquery.easing.min.js"></script>
    <script src="/themes/{{ $shop_theme }}/assets/jquery/morphext.min.js"></script>
    <script src="/themes/{{ $shop_theme }}/assets/wow/wow.min.js"></script>
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/custom.js"></script>

    </body>

    <!-- FOOTER -->
    <footer id="bottom-footer" style="background-color: #ef8805; font-size: 13px;">
        <div class="container">
            <div class="bottom" style="color: #fff;">                           
                <div class="ftr-title">© 2017 Philippine Archipelago Ports and Terminal Services, INC. All Rights Reserved.</div>
                <div class="ftr-title-2" style="font-size: 10px;">Powered By: DIGIMA WEB SOLUTIONS, Inc.</div>
            </div>
        </div>
    </footer>
</html>
