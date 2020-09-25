<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
      <meta name="apple-mobile-web-app-capable" content="yes">
      <meta name="apple-mobile-web-app-status-bar-style" content="black">
      <title>Digima Web Solutions, Inc.</title>
      <!-- Path to Framework7 Library CSS-->
      <link rel="stylesheet" href="/assets/mobile/framework7/dist/css/framework7.ios.min.css">
      <!-- Path to your custom app styles-->
      <link rel="stylesheet" href="/assets/exam/css/exam.css">
   </head>
   <body>
      <div class="statusbar-overlay"></div>
      <div class="panel-overlay"></div>
      <div class="panel panel-left panel-reveal">
         <div class="content-block">
            <p>Left Panel Navigation Here</p>
         </div>
      </div>
      <div class="panel panel-right panel-cover">
         <div class="content-block">
            <p>Right panel content goes here</p>
         </div>
      </div>
      <div class="views">
         <div class="view view-main">
            <div class="navbar">
               <div data-page="index" class="navbar-inner">
                  <div class="center sliding">Digima Web Solutions, Inc.</div>
               </div>
               <div data-page="about" class="navbar-inner cached">
                  <div class="left sliding"><a href="#" class="back link"> <i class="icon icon-back"></i><span>Back</span></a></div>
                  <div class="center sliding">About Us</div>
               </div>
               <div data-page="services" class="navbar-inner cached">
                  <div class="left sliding"><a href="#" class="back link"> <i class="icon icon-back"></i><span>Back</span></a></div>
                  <div class="center sliding">Services</div>
               </div>
               <div data-page="form" class="navbar-inner cached">
                  <div class="left sliding"><a href="#" class="back link"> <i class="icon icon-back"></i><span>Back</span></a></div>
                  <div class="center sliding">Form</div>
               </div>
            </div>
            <div class="pages navbar-through toolbar-through">
               <div data-page="index" class="page">
                  <div class="page-content">
                     <!-- PAGE CONTENT START -->
                     <div class="landing-page">
                        <div class="content-block-title page-intro"><b>Applicant</b><br> Evaluation Examination</div>
                        
                        <div class="content-block">
                           <div class="landing-message">
                              <div>Good day, applicant. Make sure you are ready and available for the next few hours before you take the exam. Exam will take an avarage time of <b>1 hour</b> and re-take of the exam will not be allowed.<br><br>Every questions will have a corresponding time.</div>
                           </div>
                        </div>

                        <div class="content-block">
                           <p><a href="/exam/register" class="item-link button button-round active start-exam-button">PROCEED</a></p>  
                        </div>
                     </div>
                     <!-- PAGE CONTENT END -->
                  </div>
               </div>
            </div>
         </div>
      </div>
      <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
      <script type="text/javascript" src="/assets/mobile/framework7/dist/js/framework7.min.js"></script>
      <script type="text/javascript" src="/assets/exam/exam.js"></script>
   </body>
</html>

