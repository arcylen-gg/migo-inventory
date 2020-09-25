<!-- Top Navbar-->
<div class="navbar">
   <div class="navbar-inner">
      <div class="left">
         <a href="#" class="back link">
            <i class="icon icon-back"></i>
            <span>Back</span>
         </a>
      </div>
      <div class="center sliding">Add Client</div>
      <div class="right">
         <a type="submit" href="javascript: $$('.button-for-register-1').click()" class="link">
            <span>Save</span>
         </a>
      </div>
   </div>
</div>
<div class="pages">

   <div data-page="register-step1" class="page">
      <div class="page-content">
         <form action="/super/client/add" method="post" class="ajax-submit">
            {{ csrf_field() }}
            <div class="content-block-title">Account Detail</div>
            <div class="list-block">
               <ul>
                  <!-- Text inputs -->
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">First Name</div>
                           <div class="item-input">
                              <input name="first_name" type="text" placeholder="First Name">
                           </div>
                        </div>
                     </div>
                  </li>
                  <!-- Text inputs -->
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Last Name</div>
                           <div class="item-input">
                              <input name="last_name" type="text" placeholder="Last Name">
                           </div>
                        </div>
                     </div>
                  </li>
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">E-Mail</div>
                           <div class="item-input">
                              <input name="user_email" type="text" placeholder="Company E-Mail">
                           </div>
                        </div>
                     </div>
                  </li>
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Password</div>
                           <div class="item-input">
                              <input name="password" type="password" placeholder="Password">
                           </div>
                        </div>
                     </div>
                  </li>
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Confirm</div>
                           <div class="item-input">
                              <input name="password_confirmation" type="password" placeholder="Confirm Password">
                           </div>
                        </div>
                     </div>
                  </li>
               </ul>
            </div>

            <div class="content-block-title">Business Information</div>
            <div class="list-block">
               <ul>
                  <!-- Text inputs -->
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Key</div>
                           <div class="item-input">
                              <input name="shop_key" type="text" placeholder="Business Key">
                           </div>
                        </div>
                     </div>
                  </li>
                  <!-- CONTACT -->
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Contact</div>
                           <div class="item-input">
                              <input name="contact" type="text" placeholder="Mobile Number">
                           </div>
                        </div>
                     </div>
                  </li>
                  <!-- COUNTRY -->
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Country</div>
                           <div class="item-input">
                              <select name="country">
                                 @foreach($_country as $country)
                                    <option {{ $country->country_name == "Philippines" ? 'selected' : '' }} value="{{ $country->country_id }}">{{ $country->country_name }}</option>
                                 @endforeach
                              </select>
                           </div>
                        </div>
                     </div>
                  </li>
                  <!-- TEXTAREA -->
                  <li class="align-top">
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Complete<br>Address</div>
                           <div class="item-input">
                              <textarea name="complete_address" placeholder="Enter Address Here"></textarea>
                           </div>
                        </div>
                     </div>
                  </li>
               </ul>
            </div>
            <p style="text-align: right; display: none"><button class="button-for-register-1" type="submit">SAVE DETAILS</button></p>
         </form>
      </div>
   </div>
</div>