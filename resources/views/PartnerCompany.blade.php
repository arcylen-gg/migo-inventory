<!DOCTYPE html>
<html>
   <head>
      <title>Bootstrap Example</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="/bootstrap/css/style.css">
      <style>
      
      </style>
   </head>
   <body>
      <div id="flipkart-navbar">
         <div class="container">
            <div class="row row1">
               <h1 style="margin:0px;"><span class="largenav">Company</span></h1>
            </div>
            <div class="row row2">
               <div class="flipkart-navbar-search smallsearch col-sm-8 col-xs-11">
                  <div class="row">
                     <input class="flipkart-navbar-input col-xs-11" type="text" placeholder="Search for Products"  name="">
                     <button class="flipkart-navbar-button col-xs-1">
                     <svg width="15px" height="15px">
                     <path d="M11.618 9.897l4.224 4.212c.092.09.1.23.02.312l-1.464 1.46c-.08.08-.222.072-.314-.02L9.868 11.66M6.486 10.9c-2.42 0-4.38-1.955-4.38-4.367 0-2.413 1.96-4.37 4.38-4.37s4.38 1.957 4.38 4.37c0 2.412-1.96 4.368-4.38 4.368m0-10.834C2.904.066 0 2.96 0 6.533 0 10.105 2.904 13 6.486 13s6.487-2.895 6.487-6.467c0-3.572-2.905-6.467-6.487-6.467 "></path>
                  </svg>
                  </button>
               </div>
            </div>
            <div class="cart largenav col-sm-2">
               <a class="cart-button">
                  <svg class="cart-svg " width="16 " height="16 " viewBox="0 0 16 16 ">
                  <path d="M15.32 2.405H4.887C3 2.405 2.46.805 2.46.805L2.257.21C2.208.085 2.083 0 1.946 0H.336C.1 0-.064.24.024.46l.644 1.945L3.11 9.767c.047.137.175.23.32.23h8.418l-.493 1.958H3.768l.002.003c-.017 0-.033-.003-.05-.003-1.06 0-1.92.86-1.92 1.92s.86 1.92 1.92 1.92c.99 0 1.805-.75 1.91-1.712l5.55.076c.12.922.91 1.636 1.867 1.636 1.04 0 1.885-.844 1.885-1.885 0-.866-.584-1.593-1.38-1.814l2.423-8.832c.12-.433-.206-.86-.655-.86 " fill="#fff "></path>
               </svg> Link
               <span class="item-number ">0</span>
            </a>
         </div>
      </div>
      
   </div>
</div>
<div id="flipkart-navbar1">
      <div class="container-fluid">
      <ul >
           <li><a class="active" href="#home">Home</a></li>
           <li><a href="#news">News</a></li>
           <li><a href="#contact">Contact</a></li>
           <li><a href="#about">About</a></li>
         </ul>
       </div>  
      </div>

<div class="container">
   <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
         <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
         <li data-target="#myCarousel" data-slide-to="1"></li>
         <li data-target="#myCarousel" data-slide-to="2"></li>
      </ol>
      <!-- Wrapper for slides -->
      <div class="carousel-inner">
         <div class="item active">
            <img src="/uploads/dmsph-22/picture/1.jpg" alt="Los Angeles" style="width:100%;">
         </div>
         <div class="item">
            <img src="/uploads/dmsph-22/picture/2.jpg" alt="Chicago" style="width:100%;">
         </div>
         
         <div class="item">
            <img src="/uploads/dmsph-22/picture/3.jpg" alt="New york" style="width:100%;">
         </div>
      </div>
      <!-- Left and right controls -->
      <a class="left carousel-control" href="#myCarousel" data-slide="prev">
         <span class="glyphicon glyphicon-chevron-left"></span>
         <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#myCarousel" data-slide="next">
         <span class="glyphicon glyphicon-chevron-right"></span>
         <span class="sr-only">Next</span>
      </a>
   </div>
</div>
<div class="container">
   <table class="table table-bordered">
      <tr>
         <th class="text-center">Company Logo</th>
         <th class="text-center">Company Name</th>
         <th class="text-center">Company Owner Name</th>
         <th class="text-center">Contact Number</th>
         <th class="text-center">Company Address</th>
         <th class="text-center">Company Location</th>
         
      </tr>
      @foreach($_company_info as $company_info)
      <tr row="">
         <td class="logo-img">
            <img src="{{ $company_info->company_logo }}" class="img-thumbnail" alt="logo" width="100" height="70">
         </td>
         <td class="text-center">{{ $company_info->company_name }}</td>
         <td class="text-center">{{ $company_info->company_owner }}</td>
         <td class="text-center">{{ $company_info->company_contactnumber }}</td>
         <td class="text-center">{{ $company_info->company_address }}</td>
         <td class="text-center">{{ $company_info->company_location }}</td>
         
      </tr>
      @endforeach
      
   </table>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
function openNav() {
document.getElementById("mySidenav").style.width = "70%";
// document.getElementById("flipkart-navbar").style.width = "50%";
document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
}
function closeNav() {
document.getElementById("mySidenav").style.width = "0";
document.body.style.backgroundColor = "rgba(0,0,0,0)";
}
</script>
</html>