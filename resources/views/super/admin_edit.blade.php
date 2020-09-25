<!-- Top Navbar-->
<div class="navbar">
   <div class="navbar-inner">
      <div class="left">
         <a href="#" class="back link">
            <i class="icon icon-back"></i>
            <span>Back</span>
         </a>
      </div>
      <div class="center sliding">Basic Details</div>
      <div class="right">
         <a type="submit" href="javascript: $$('.button-for-register-1').click()" class="link">
            <span>Update</span>
         </a>
      </div>
   </div>
</div>
<div class="pages">
   <div data-page="admin-edit" class="page">
      <div class="page-content">
         <form action="/super/admin/edit?admin_id={{ $admin_id }}" method="post" class="ajax-submit">
            {{ csrf_field() }}
            <div class="list-block">
               <ul>
                  <!-- Text inputs -->
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">First Name</div>
                           <div class="item-input">
                              <input value="{{ $admin->first_name }}" name="first_name" type="text" placeholder="First name">
                           </div>
                        </div>
                     </div>
                  </li>
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Last Name</div>
                           <div class="item-input">
                              <input value="{{ $admin->last_name }}" name="last_name" type="text" placeholder="Last name">
                           </div>
                        </div>
                     </div>
                  </li>
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Username</div>
                           <div class="item-input">
                              <input value="{{ $admin->username }}" autocomplete="off" name="username" type="text" placeholder="Username">
                           </div>
                        </div>
                     </div>
                  </li>

                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Password</div>
                           <div class="item-input">
                              <input value="{{ $admin_password }}" autocomplete="off" name="password" type="text" placeholder="Password">
                           </div>
                        </div>
                     </div>
                  </li>

                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Full Access</div>
                           <div class="item-input">
                              <label class="label-switch">
                                 <input {{ $admin->admin_type == "full" ? "checked" : "" }} name="full_access" type="checkbox">
                                 <div class="checkbox"></div>
                              </label>
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