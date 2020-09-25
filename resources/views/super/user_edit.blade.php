<!-- Top Navbar-->
<div class="navbar">
   <div class="navbar-inner">
      <div class="left">
         <a href="#" class="back link">
            <i class="icon icon-back"></i>
            <span>Back</span>
         </a>
      </div>
      <div class="center sliding">Edit User ({{ $user->user_id }})</div>
      <div class="right">
         <a type="submit" href="javascript: $$('.button-for-register-1').click()" class="link">
            Update
         </a>
      </div>
   </div>
</div>
<div class="pages">
   <div data-page="user-edit" class="page">
      <div class="page-content">
         <form action="/super/user-edit" method="post" class="ajax-submit">
            {{ csrf_field() }}
            <div class="content-block-title">User Information</div>
            <div class="list-block">
               <ul>
                  <!-- FIRST NAME -->
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">First Name</div>
                           <div class="item-input">
                              <input name="first_name" type="text" placeholder="First Name" value="{{ $user->user_first_name }}">
                           </div>
                        </div>
                     </div>
                  </li>
                  <!-- LAST NAME -->
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Last Name</div>
                           <div class="item-input">
                              <input name="first_name" type="text" placeholder="Last Name" value="{{ $user->user_last_name }}">
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
                              <input name="first_name" type="text" placeholder="Contact" value="{{ $user->user_contact_number }}">
                           </div>
                        </div>
                     </div>
                  </li>
                  <!-- SELECT POSITION -->
                  <li>
                     <a href="#" class="item-link smart-select">
                        <!-- select -->
                        <select name="fruits">
                           <option value="0" selected>Developer</option>
                           @foreach($_position as $position)
                           <option {{ $user->user_level == $position->position_id ? 'selected' : '' }} value="{{ $position->position_id }}">{{ $position->position_name }}</option>
                           @endforeach
                        </select>
                        <div class="item-content">
                           <div class="item-inner">
                              <div class="item-title">Position</div>
                              <div class="item-after"></div>
                           </div>
                        </div>
                     </a>
                  </li>
               </ul>
            </div>

            <div class="content-block-title">Account</div>
            <div class="list-block">
               <ul>
                  <!-- E-MAIL -->
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Email</div>
                           <div class="item-input">
                              <input name="email" type="text" placeholder="Email" value="{{ $user->user_email }}">
                           </div>
                        </div>
                     </div>
                  </li>
                  <!-- PASSWORD -->
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Password</div>
                           <div class="item-input">
                              <input name="password" type="text" placeholder="Password" value="{{ $password }}">
                           </div>
                        </div>
                     </div>
                  </li>
               </ul>
            </div>
            <div class="content-block-title">Action List</div>
            <div class="list-block">
               <ul>
                  <li><a href="" class="item-link list-button">Archive</a></li>
                  <li><a href="" class="item-link list-button">Force Login</a></li>
               </ul>
            </div>
            <p style="text-align: right; display: none"><button class="button-for-register-1" type="submit">Update</button></p>
         </form>
      </div>
   </div>
</div>