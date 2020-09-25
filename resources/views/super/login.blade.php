<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
      <meta name="apple-mobile-web-app-capable" content="yes">
      <meta name="apple-mobile-web-app-status-bar-style" content="black">
      <title>Digima Admin</title>
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
            <!-- START CONTENT -->
            <div style="margin-top: 30px;" class="login-screen-title">Digima Admin</div>
            <form action="/super/login" method="post" class="ajax-submit">
               {{ csrf_field() }}
               <div class="list-block">
                  <ul>
                     <li class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Username</div>
                           <div class="item-input">
                              <input type="text" name="username" placeholder="Your username">
                           </div>
                        </div>
                     </li>
                     <li class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Password</div>
                           <div class="item-input">
                              <input type="password" name="password" placeholder="Your password">
                           </div>
                        </div>
                     </li>
                  </ul>
               </div>
               <div class="list-block">
                  <ul>
                     <li><a href="javascript: $$('.button-for-login').click()" class="item-link list-button">Sign In</a></li>
                  </ul>
                  <div class="list-block-label" style="text-align: center;">
                     <p>This page is only for Administrator of<br><b>Digima Web Solutions, Inc.</b></p>
                  </div>
               </div>
               <input style="display: none;" type="submit" value="submit-data" class="button-for-login">
            </form>
         </div>
      </div>
   </div>
   <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
   <script type="text/javascript" src="/assets/mobile/framework7/dist/js/framework7.min.js"></script>
   <script type="text/javascript" src="/assets/admin/login.js"></script>
</body>
</html>