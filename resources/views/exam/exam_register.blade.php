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
            <span>Start</span>
            <i class="icon icon-next"></i>
         </a>
      </div>
   </div>
</div>
<div class="pages">
   <div data-page="register-step1" class="page">
      <div class="page-content">
         <form action="/exam/register" method="post" class="ajax-submit">
            {{ csrf_field() }}
            <div class="list-block">
               <ul>
                  <!-- Text inputs -->
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">First Name</div>
                           <div class="item-input">
                              <input name="first_name" type="text" placeholder="First name">
                           </div>
                        </div>
                     </div>
                  </li>
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Last Name</div>
                           <div class="item-input">
                              <input name="last_name" type="text" placeholder="Last name">
                           </div>
                        </div>
                     </div>
                  </li>
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">E-mail</div>
                           <div class="item-input">
                              <input name="email" type="email" placeholder="E-mail">
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
                  <!-- GENDER -->
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Gender</div>
                           <div class="item-input">
                              <select name="gender">
                                 <option>Male</option>
                                 <option>Female</option>
                              </select>
                           </div>
                        </div>
                     </div>
                  </li>
                  <!-- GENDER -->
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Civil Status</div>
                           <div class="item-input">
                              <select name="civil_status">
                                 <option>Single</option>
                                 <option>Married</option>
                                 <option>Divorced</option>
                                 <option>Widowed</option>
                              </select>
                           </div>
                        </div>
                     </div>
                  </li>
                  <!-- BIRTHDAY -->
                  <li>
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Birthday</div>
                           <div class="item-input">
                              <input name="birthday" type="date" placeholder="Birth day" value="2014-04-30">
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
                  <!-- TEXTAREA -->
                  <li class="align-top">
                     <div class="item-content">
                        <div class="item-inner">
                           <div class="item-title label">Work<br>Experience</div>
                           <div class="item-input">
                              <textarea name="work_experience" placeholder="Previous Work (Leave blank if no previous work experience)"></textarea>
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