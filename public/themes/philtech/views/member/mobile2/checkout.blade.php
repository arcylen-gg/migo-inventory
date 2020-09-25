<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
      <meta name="mobile-web-app-capable" content="yes">
      <meta name="apple-mobile-web-app-capable" content="yes">
      <meta name="apple-mobile-web-app-status-bar-style" content="black">
      <meta name="theme-color" content="#2196f3">
      <title>Brown</title>
      <link rel="stylesheet" href="/themes/{{ $shop_theme }}/assets/mobile/framework7/dist/css/framework7.material.css">
      <link rel="stylesheet" href="/themes/{{ $shop_theme }}/assets/mobile/framework7/dist/css/framework7.material.colors.css">
      <link href="http://fonts.googleapis.com/css?family=Roboto:400,300,500,700" rel="stylesheet" type="text/css">
      <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700" rel="stylesheet"> 
      <link rel="stylesheet" href="/themes/{{ $shop_theme }}/assets/mobile/framework7/kitchen-sink-material/css/material-icons.css">
      <link rel="stylesheet" href="/themes/{{ $shop_theme }}/assets/mobile/framework7/kitchen-sink-material/css/kitchen-sink.css">
      {{-- FONT AWESOME --}}
      <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/font-awesome/css/font-awesome.min.css">
      <!-- Brown Custom Icon -->
      <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/brown-icon/styles.css">
      <!-- GLOBAL CSS -->
      <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/mobile/css/global.css">
   </head>
   <body>
      <div class="statusbar-overlay"></div>
      <div class="panel-overlay"></div>
      <div class="panel panel-left panel-cover">
         <div class="view navbar-fixed">
            <div class="pages">
               <div data-page="panel-left" class="page">
                  <div class="navbar sidebar-left">
                     <div class="navbar-inner">
                        <div class="title-holder">
                           <div class="title">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                           <div class="sub">Member</div>
                        </div>
                        <div class="right">
                           <div class="dot"></div>
                        </div>
                     </div>
                  </div>
                  <div class="page-content sidebar-left">
                     <div class="image-profile">
                        <img style="border-radius: 100%;" src="{{ $profile_image }}">
                     </div>
                     <div class="list-block">
                        <ul>
                           <li>
                              <a href="/members" class="item-link close-panel">
                                 <div class="item-content">
                                    <div class="item-media"><i class="icon brown-icon-dashboard"></i></div>
                                    <div class="item-inner">
                                       <div class="item-title">Dashboard</div>
                                    </div>
                                 </div>
                              </a>
                           </li>
                           @if($mlm_member)
                           <li>
                              <a href="/members/profile" class="item-link close-panel">
                                 <div class="item-content">
                                    <div class="item-media"><i class="icon brown-icon-profile"></i></div>
                                    <div class="item-inner">
                                       <div class="item-title">Profile</div>
                                    </div>
                                 </div>
                              </a>
                           </li>
                           {{-- <li>
                              <a href="forms.html" class="item-link close-panel">
                                 <div class="item-content">
                                    <div class="item-media"><i class="icon brown-icon-flow-tree"></i></div>
                                    <div class="item-inner">
                                       <div class="item-title">Genealogy</div>
                                    </div>
                                 </div>
                              </a>
                           </li> --}}
                           <li>
                              <a href="/members/report" class="item-link close-panel">
                                 <div class="item-content">
                                    <div class="item-media"><i class="icon brown-icon-bar-chart"></i></div>
                                    <div class="item-inner">
                                       <div class="item-title">Reports</div>
                                    </div>
                                 </div>
                              </a>
                           </li>
                           <li>
                              <a href="/members/network" class="item-link close-panel">
                                 <div class="item-content">
                                    <div class="item-media"><i class="icon brown-icon-flow-tree"></i></div>
                                    <div class="item-inner">
                                       <div class="item-title">Network List</div>
                                    </div>
                                 </div>
                              </a>
                           </li>
                           <li>
                              <a href="/members/wallet-encashment" class="item-link close-panel">
                                 <div class="item-content">
                                    <div class="item-media"><i class="icon brown-icon-wallet"></i></div>
                                    <div class="item-inner">
                                       <div class="item-title">Wallet</div>
                                    </div>
                                 </div>
                              </a>
                           </li>
                           @endif
                           <li>
                              <a href="javascript:" onClick="location.href='/members/logout'" class="item-link close-panel">
                                 <div class="item-content">
                                    <div class="item-media"></div>
                                    <div class="item-inner">
                                       <div class="item-title">Logout</div>
                                    </div>
                                 </div>
                              </a>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="panel panel-right panel-reveal">
         <div class="view view-right">
            <div class="pages navbar-fixed">
               <div data-page="panel-right1" class="page">
                  <div class="navbar">
                     <div class="navbar-inner">
                        <div class="center">Right Panel</div>
                     </div>
                  </div>
                  <div class="page-content">
                     <div class="content-block">
                        <p>This is a right side panel. You can close it by clicking outsite or on this link:<a href="#" class="close-panel">close me</a>. You can put here anything, even another isolated view, try it:</p>
                     </div>
                     <div class="list-block">
                        <ul>
                           <li>
                              <a href="panel-right2.html" class="item-link">
                                 <div class="item-content">
                                    <div class="item-inner">
                                       <div class="item-title">Right panel page 2</div>
                                    </div>
                                 </div>
                              </a>
                           </li>
                           <li>
                              <a href="panel-right3.html" class="item-link">
                                 <div class="item-content">
                                    <div class="item-inner">
                                       <div class="item-title">Right panel page 3</div>
                                    </div>
                                 </div>
                              </a>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="views">
         <div class="view view-main">
            <div class="pages navbar-fixed">
               <div data-page="index" class="page">
                  <div class="navbar">
                     <div class="navbar-inner">
                        <div class="left"><a href="#" class="open-panel link icon-only"><i class="icon icon-bars"></i></a></div>
                        <div class="left">Checkout</div>
                        {{-- <div class="right">
                           <div class="text">3</div>
                           <img src="/themes/{{ $shop_theme }}/assets/mobile/img/notification.png">
                        </div> --}}
                     </div>
                  </div>
                  <div class="page-content">
                    <form method="post">
                    {{ csrf_field() }}
                      <div class="checkout-view">
                          <div class="holder">
                              <div class="content-block-title">Fill Up Delivery Information</div>
                              <div class="list-block" style="margin-bottom: 0;">
                                <ul>
                                    <!-- Text inputs -->
                                    <li>
                                        <div class="item-content">
                                            <div class="item-inner">
                                                <div class="item-title label">Province</div>
                                                <div class="item-input">
                                                    <select name="customer_state">
                                                      @foreach($_locale as $locale)
            											               <option value="{{ $locale->locale_name }}">{{ $locale->locale_name }}</option>
            											         @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="item-content">
                                            <div class="item-inner">
                                                <div class="item-title label">Complete Shipping Address</div>
                                                <div class="item-input">
                                                    <textarea required="required" name="customer_street" placeholder="Type your complete address here"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                          </div>
                          <div class="holder">
                              <!-- CART SUMMARY -->
    							<div class="cart-summary">
    								<div class="top-title row no-gutter clearfix">
    									<div class="col-33">
    										<div class="per-title" style="border-bottom: 2px solid #63b944;">Product</div>									
    									</div>
    									<div class="col-33">
    										<div class="per-title" style="border-bottom: 2px solid #ef5525;">Quantity</div>
    									</div>
    									<div class="col-33">
    										<div class="per-title" style="border-bottom: 2px solid #6075f7;">Price</div>
    									</div>
    								</div>
    								@if($cart)
    								
    									@foreach($cart["_item"] as $c)
    									<!-- PER ITEM SUMMARY -->
    									<div class="per-summary-content row no-gutter clearfix">
    										<div class="col-33">
    											<div class="per-summary-details">{{ $c->item_name }}</div>
    										</div>
    										<div class="col-33">
    											<div class="per-summary-details">{{ $c->quantity }}</div>
    										</div>
    										<div class="col-33">
    											<div class="per-summary-details">{{ $c->display_subtotal }}</div>
    										</div>
    									</div>
    									@endforeach
    									
    									<!-- SUMMARY TOTAL CONTAINER -->
    									<div class="total-container row no-gutter clearfix">
    										<!-- SUBTOTAL -->
    										<!--<div class="col-md-6">-->
    										<!--	<div class="left-detail">Subtotal</div>-->
    										<!--</div>-->
    										<!--<div class="col-md-6">-->
    										<!--	<div class="right-detail">{{ $cart["_total"]->display_total }}</div>-->
    										<!--</div>-->
    										<!-- TOTAL -->
    										<div class="col-50">
    											<div class="left-detail">Total</div>
    										</div>
    										<div class="col-50">
    											<div class="right-detail">{{ $cart["_total"]->display_grand_total }}</div>
    										</div>
    									</div>
    									<!-- SHIPPING FEE -->
    									<div class="shipping-fee">Shipping Fee is included</div>
    								@else
    									<div class="text-center" style="padding: 50px;">CART IS EMPTY</div>
    								@endif
    							
    							</div>
                          </div>
                          <div class="holder">
                              <!-- PAYMENT OPTION -->
							<div class="payment-option" style="margin-top: 0;">
								<div class="top-title">How do you want to pay?</div>
								<div class="option">
									<div class="form-input">
										<select name="method" required="required">
											<option value="" hidden>Select Payment Method</option>
											@foreach($_payment as $payment)
												<option value="{{ $payment->link_reference_name }}">{{ $payment->method_name }}</option>
											@endforeach
										</select>	
									</div>
								</div>
								<div class="button-container">
									<button type="submit" class="button-proceed" id="proceed">Proceed</button>
								</div>
							</div>
                          </div>
                      </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="popup demo-popup">
         <div class="view navbar-fixed">
            <div class="pages">
               <div class="page">
                  <div class="navbar">
                     <div class="navbar-inner">
                        <div class="center">Popup Title</div>
                        <div class="right"><a href="#" class="link close-popup">Done</a></div>
                     </div>
                  </div>
                  <div class="page-content">
                     <div class="content-block">
                        <p>Here comes popup. You can put here anything, even independent view with its own navigation. Also not, that by default popup looks a bit different on iPhone/iPod and iPad, on iPhone it is fullscreen.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse faucibus mauris leo, eu bibendum neque congue non. Ut leo mauris, eleifend eu commodo a, egestas ac urna. Maecenas in lacus faucibus, viverra ipsum pulvinar, molestie arcu. Etiam lacinia venenatis dignissim. Suspendisse non nisl semper tellus malesuada suscipit eu et eros. Nulla eu enim quis quam elementum vulputate. Mauris ornare consequat nunc viverra pellentesque. Aenean semper eu massa sit amet aliquam. Integer et neque sed libero mollis elementum at vitae ligula. Vestibulum pharetra sed libero sed porttitor. Suspendisse a faucibus lectus.</p>
                        <p>Duis ut mauris sollicitudin, venenatis nisi sed, luctus ligula. Phasellus blandit nisl ut lorem semper pharetra. Nullam tortor nibh, suscipit in consequat vel, feugiat sed quam. Nam risus libero, auctor vel tristique ac, malesuada ut ante. Sed molestie, est in eleifend sagittis, leo tortor ullamcorper erat, at vulputate eros sapien nec libero. Mauris dapibus laoreet nibh quis bibendum. Fusce dolor sem, suscipit in iaculis id, pharetra at urna. Pellentesque tempor congue massa quis faucibus. Vestibulum nunc eros, convallis blandit dui sit amet, gravida adipiscing libero.</p>
                        <p>Morbi posuere ipsum nisl, accumsan tincidunt nibh lobortis sit amet. Proin felis lorem, dictum vel nulla quis, lobortis dignissim nunc. Pellentesque dapibus urna ut imperdiet mattis. Proin purus diam, accumsan ut mollis ac, vulputate nec metus. Etiam at risus neque. Fusce tincidunt, risus in faucibus lobortis, diam mi blandit nunc, quis molestie dolor tellus ac enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse interdum turpis a velit vestibulum pharetra. Vivamus blandit dapibus cursus. Aenean lorem augue, vehicula in eleifend ut, imperdiet quis felis.</p>
                        <p>Duis non erat vel lacus consectetur ultricies. Sed non velit dolor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel varius mi, a tristique ante. Vivamus eget nibh ac elit tempor bibendum sit amet vitae velit. Proin sit amet dapibus nunc, non porta tellus. Fusce interdum vulputate imperdiet. Sed faucibus metus at pharetra fringilla. Fusce mattis orci et massa congue, eget dapibus ante rhoncus. Morbi semper sed tellus vel dignissim. Cras vestibulum, sapien in suscipit tincidunt, lectus mi sodales purus, at egestas ligula dui vel erat. Etiam cursus neque eu lectus eleifend accumsan vitae non leo. Aliquam scelerisque nisl sed lacus suscipit, ac consectetur sapien volutpat. Etiam nulla diam, accumsan ut enim vel, hendrerit venenatis sem. Vestibulum convallis justo vitae pharetra consequat. Mauris sollicitudin ac quam non congue.</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="popover popover-menu">
         <div class="popover-inner">
            <div class="list-block">
               <ul>
                  <li><a href="modals.html" class="list-button item-link">Modals</a></li>
                  <li><a href="popover.html" class="list-button item-link">Popover</a></li>
                  <li><a href="tabs.html" class="list-button item-link">Tabs</a></li>
                  <li><a href="panels.html" class="list-button item-link">Side Panels</a></li>
                  <li><a href="list-view.html" class="list-button item-link">List View</a></li>
                  <li><a href="forms.html" class="list-button item-link">Forms</a></li>
               </ul>
            </div>
         </div>
      </div>
      <div class="popover popover-music">
         <div class="popover-inner">
            <div class="list-block media-list">
               <ul>
                  <li>
                     <a href="#" class="item-link item-content">
                        <div class="item-media"><img src="http://lorempixel.com/88/88/people/1" width="44"></div>
                        <div class="item-inner">
                           <div class="item-title-row">
                              <div class="item-title">Yellow Submarine</div>
                           </div>
                           <div class="item-subtitle">Beatles</div>
                        </div>
                     </a>
                  </li>
                  <li>
                     <a href="#" class="item-link item-content">
                        <div class="item-media"><img src="http://lorempixel.com/88/88/people/2" width="44"></div>
                        <div class="item-inner">
                           <div class="item-title-row">
                              <div class="item-title">Don't Stop Me Now</div>
                           </div>
                           <div class="item-subtitle">Queen</div>
                        </div>
                     </a>
                  </li>
                  <li>
                     <a href="#" class="item-link item-content">
                        <div class="item-media"><img src="http://lorempixel.com/88/88/people/3" width="44"></div>
                        <div class="item-inner">
                           <div class="item-title-row">
                              <div class="item-title">Billie Jean</div>
                           </div>
                           <div class="item-subtitle">Michael Jackson</div>
                        </div>
                     </a>
                  </li>
               </ul>
            </div>
         </div>
      </div>
      <div class="popover popover-contacts">
         <div class="popover-inner">
            <div class="list-block media-list">
               <ul>
                  <li class="item-content">
                     <div class="item-media"><img src="http://lorempixel.com/88/88/people/1" width="40"></div>
                     <div class="item-inner">
                        <div class="item-title-row">
                           <div class="item-title">Ali Connors</div>
                        </div>
                     </div>
                  </li>
                  <li class="item-content">
                     <div class="item-media"><img src="http://lorempixel.com/88/88/people/2" width="40"></div>
                     <div class="item-inner">
                        <div class="item-title-row">
                           <div class="item-title">Sandra Adams</div>
                        </div>
                     </div>
                  </li>
                  <li class="item-content">
                     <div class="item-media"><img src="http://lorempixel.com/88/88/people/3" width="40"></div>
                     <div class="item-inner">
                        <div class="item-title-row">
                           <div class="item-title">Trevor Hansen</div>
                        </div>
                     </div>
                  </li>
                  <li>
                     <a href="#" class="item-link item-content bg-purple color-white ripple-white">
                        <div class="item-media"><i class="icon material-icons">mode_edit</i></div>
                        <div class="item-inner">
                           <div class="item-title-row">
                              <div class="item-title">Add New</div>
                           </div>
                        </div>
                     </a>
                  </li>
               </ul>
            </div>
         </div>
      </div>
      <div class="login-screen">
         <div class="view">
            <div class="page">
               <div class="page-content login-screen-content">
                  <div class="login-screen-title">Framework7</div>
                  <form>
                     <div class="list-block inputs-list">
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
                     <div class="content-block"><a href="#" class="button button-big">Sign In</a></div>
                     <div class="content-block">
                        <div class="list-block-label">Some text about login information.<br>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <div class="picker-modal picker-modal-demo">
         <div class="toolbar">
            <div class="toolbar-inner">
               <div class="left"></div>
               <div class="right"><a href="#" class="link close-picker">Done</a></div>
            </div>
         </div>
         <div class="picker-modal-inner">
            <div class="content-block">Integer mollis nulla id nibh elementum finibus. Maecenas eget fermentum ipsum. Sed sagittis condimentum nisl at tempus. Duis lacus libero, laoreet vitae ligula a, aliquet eleifend sapien. Nullam sodales viverra sodales.</div>
         </div>
      </div>
      <!-- JQUERY -->
      <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
      <script type="text/javascript" src="/themes/{{ $shop_theme }}/assets/mobile/framework7/dist/js/framework7.js"></script>
      <script type="text/javascript" src="/themes/{{ $shop_theme }}/assets/mobile/framework7/kitchen-sink-material/js/kitchen-sink.js"></script>
      {{-- EXTERNAL JS --}}
      <script type="text/javascript" src='/assets/chartjs/Chart.bundle.min.js'></script>
      <!-- GLOBAL JS -->
      <script type="text/javascript" src="/themes/{{ $shop_theme }}/assets/mobile/js/global.js"></script>
   </body>
</html>