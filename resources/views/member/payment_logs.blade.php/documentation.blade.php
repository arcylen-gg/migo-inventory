@extends('member.layout')
@section('content')
<div style="font-family: arial, tahoma, 'sans-serif'">
   <div class="panel panel-default panel-block panel-title-block" id="top">
      <div class="panel-heading">
         <div>
            <i class="fa fa-tags"></i>
            <h1>
            <span class="page-title">Digima House Documentations</span>
            <small>Rearrange the tree</small>
            </h1>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-3">
         <!-- GLOBAL POPUP -->
         <div class="side-nav">
            <div class="panel panel-default panel-block" style="background-color: #fff">
               <div class="list-group">
                  <div class="list-group-item" id="getting-started">
                     <div class="side-title text-bold doc-nav-title"><i class="fa fa-folder"></i> DOCUMENTATION NAVIGATION</div>
                     <div class="doc-nav-container"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-9">
         <!-- GLOBAL POPUP -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>1. Global Popup</b></h3>
                  <!-- USING JAVASCRIPT -->
                  <p>
                     <h4>USING JAVASCRIPT</h4>
                     <div class="gray-author">by Guillermo Tabligan (JAVASCRIPT)</div>
                  </p>
                  <pre>action_load_link_to_modal(url, size)</pre>
                  <p>
                     <div>This is a javascript code that can be called anywhere to load a link into a modal.</div>
                     <div>
                        PARAMETERS:
                        <ul>
                           <li>"URL" is the link of the page the developer would like to load in the modal form.</li>
                           <li>"SIZE" can <i>sm</i>,<i>md</i> or <i>lg</i>. This refers to the size of the form that the developer would like to load.</li>
                        </ul>
                     </div>
                     <div>
                        <span style="color: red;">ISSUES AND BUGS:</span>
                        <ul>
                           <li>(GT) Multiple modal can be open. Topup of other modal, another modal can be opened.</li>
                        </ul>
                     </div>
                  </p>
                  
                  <!-- USING HTML ATTRIBUTES AND CLASS -->
                  <hr>
                  <p>
                     <h4>USING HTML CLASS AND ATTRIBUTES</h4>
                     <div class="gray-author">by Guillermo Tabligan (HTML)</div>
                  </p>
                  <pre>{{ '<button class="btn btn-primary popup" link="link_here" size="lg">Button Label</button>' }}</pre>
                  <p>
                     <div>By adding class <span class="text-success text-bold">.popup</span> into any element. It calls a modal form automatically.</div>
                     <div>
                        ATTRIBUTES:
                        <ul>
                           <li>"LINK" is the link of the page the developer would like to load in the modal form.</li>
                           <li>"SIZE" can <i>sm</i>,<i>md</i> or <i>lg</i>. This refers to the size of the form that the developer would like to load.</li>
                        </ul>
                     </div>
                  </p>

                  <!-- MODAL POPUP HTML -->
                  <hr>
                  <p>
                     <h4>LOADING POPUP FORM DONE</h4>
                     <div class="gray-author">by Guillermo Tabligan (JAVASCRIPT)</div>
                  </p>
                  <pre>{{ $popup_code_submit_done }}</pre>
                  <p>
                     <div>Once the loading is done for the developer's URL, he/she might want to execute some script. The developer can use this function on his/her page in order to do execute a script after loading the page. The developer may need to filter the link since he/she might be using multiple popup modal on one page.</div>
                  </p>

                  <!-- MODAL POPUP HTML -->
                  <hr>
                  <p>
                     <h4>HTML TEMPLATE FOR POPUP MODAL FORM</h4>
                     <div class="gray-author">by Guillermo Tabligan (HTML)</div>
                  </p>
                  <pre>{{ $popup_code }}</pre>
                  <p>
                     <div>Use this template on the popup modal form as a template. The class in the form which is <span class="text-success text-bold">.global-submit</span> allows you to submit the form in ajax. A more detailed information for this will be discussed in another topic.</div>
                  </p>
               </div>
            </div>
         </div>

         <!-- GLOBAL SUBMIT -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>2. Global Submit</b></h3>
                  
                  <!-- USING HOW TO USE GLOBAL SUBMIT -->
                  <p>
                     <h4>HOW TO USE GLOBAL SUBMIT</h4>
                     <div class="gray-author">by Guillermo Tabligan (HTML)</div>
                  </p>
                  <pre>{{ $html_code_global_submit }}</pre>
                  <p>
                     <div>Once you add a class <span class="text-success text-bold">.global-submit</span> to a {{ '<form>' }}. It will automatically submit using ajax.</div>
                  </p>
                  <!-- CONTROLLER -->
                  <hr>
                  <p>
                     <h4>HANDLING SUBMIT IN THE CONTROLLER</h4>
                     <div class="gray-author">by Guillermo Tabligan (PHP/LARAVEL)</div>
                  </p>
                  <pre>{{ $html_code_global_submit_controller }}</pre>
                  <p>
                     <div>This is a templated code for the developer to use in his/her controller. The developer can also include addiitonal data on the response and he/she can use it on the callback.</div>
                  </p>
                  <!-- SUBMIT DONE CALLBACK -->
                  <hr>
                  <p>
                     <h4>CALL BACK AFTER SUBMIT</h4>
                     <div class="gray-author">by Guillermo Tabligan (JAVASCRIPT)</div>
                  </p>
                  <pre>{{ $html_code_global_submit_done }}</pre>
                  <p>
                     <div>Use this to call additional functionalities once submission is done. You can check the response status and return data from the <span class="text-success text-bold">$response</span> in the previous subject.</div>
                  </p>
                  <!-- ISSUES -->
                  <p>
                     <div class="red">ISSUES AND BUGS:</div>
                     <ul>
                        <li>(LJ) If there is an error on form submition. The function will retry until the page is refreshed.</li>
                     </ul>
                  </p>
               </div>
            </div>
         </div>

         <!-- TUTORIAL GROUP -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>3. Global Drop Down List plugin</b></h3>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <p>
                     <h4 class="subtitle">HOW TO USE DROP DOWN LIST</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (JAVASCRIPT)</div>
                  </p>

                  <pre>{{ '$(select).globalDropList();' }}</pre>
                  <p>
                     <div>By using the <span class="text-success text-bold">globalDropList()</span> function in any select type, it's automatically converts the select element into a custom one.</div>
                  </p>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">MODIFYING SOME CONFIGURATIONS IN PLUGINS</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (JAVASCRIPT)</div>
                  </p>

                  <pre>{{ $documentation_globalDropList_configuration }}</pre>
                  <p>
                     <div>This are the current default options that our plugin have. Here, you can enable or disable the 'add new' (popup) feautures of our plugin and also set some text.</div>
                  </p>
                  </p>

                  <pre>{{ '$.fn.globalDropList.defaults.addNewIcon = "fa fa-plus-bi"' }}</pre>
                  <p>
                     <div>We can also make the value of each option as GLOBAL, meaning, we don't need to set the 'Add New Icon' for each of the element. So if we declare this on our layout.blade, the default value for this will change</div>
                  </p>

                   <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">USING EVENTS</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (JAVASCRIPT)</div>
                  </p>

                  <pre>{{ $documentation_globalDropList_events }}</pre>
                  <p>
                     <div>You can also use an event</div>
                  </p>

                  <p>
                     <div></div>
                  </p>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">DESTROY AND REMAKE THE PLUGIN</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (JAVASCRIPT)</div>
                  </p>

                  <pre>{{ '$(select).globalDropList("destroy")' }}</pre>
                  <p>
                     <div>We can remove the plugin that we put into the element by putting a destroy parameter in our plugin function. If we want to reload our plugin we can use the code <span class="text-success text-bold">$(select).myDropList("destroy").myDropList();</span></div>
                  </p>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">RELOAD THE PLUGIN CONTENT WITHOUT DESTROYING</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (JAVASCRIPT)</div>
                  </p>

                  <pre>{{ '$(select).globalDropList("reload")' }}</pre>
                  <p>
                     <div>This will update the current content of the plugin if you made some changes</div>
                  </p>

                  <pre>{{ $documentation_globalDropList_reload }}</pre>
                  <p>
                     <div>You can  use this set of code to update the select element ( usable when you are using ajax to add an option to select)</div>
                  </p>
                  <hr>
                  <p>
                     <h4 class="subtitle">MAKE THE PLUGIN DISABLED AND ENABLED</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (JAVASCRIPT)</div>
                  </p>

                  <pre>{{ '$(select).globalDropList("disabled")' }}</pre>
                  <pre>{{ '$(select).globalDropList("enabled")' }}</pre>
                  <p>
                     <div>This will disabled and enabled the select</div>
                  </p>
                  <!-- ISSUES -->
                  <p>
                     <!-- <div class="red">ISSUES AND BUGS:</div>
                     <ul>
                     </ul> -->
                  </p>
               </div>
            </div>
         </div>

         <!-- TUTORIAL GROUP -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>4. Settings of Members</b></h3>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <p>
                     <h4 class="subtitle">A. Adding Settings</h4>
                     <div class="gray-author">by Luke Glenn Jordan (PHP/LARAVEL/HTML)</div>
                  </p>

                  
                  <p><div>In order to add settings. The developer needs to navigation on <c>/resources/views/member/settings/settings.blade.php</c> and create a new field on this page. </div></p>
                  <p><div>The developer must create a div with a class="setting" and id="{name/key of your settings}</div></p>
                  <p>Two inputs are also needed name="settings_key" you can mark it hidden just put name of the setting in the value of the input.</p>
                  <p>name="settings_value" will serve as the value of the setting</p>
                  <pre>{{$documentation_member_settings}}</pre>
                  <p><div>If you have array/variable in php you want to use, Navigate to :</div></p>
                  <pre>App\Http\Controllers\Member\SettingsController   setup()</pre>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">B. Calling Settings Form</h4>
                     <div class="gray-author">by Luke Glenn Jordan (HTML)</div>
                  </p>

                  <pre>{{'<a href="javascript:" class="show_settings" settings_key="currency">Show settings</a>'}}</pre>
                  <p>
                  <p><div>By using the class "show_settings" and settings_key="{name/key of your settings}", the developer can call the settings form to update the settings.</div></p>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">C. Retrieving Settings</h4>
                     <div class="gray-author">by Luke Glenn Jordan (PHP/Laravel)</div>
                  </p>
                  Dependencies:
                  <pre>{{ 'App\Globals\Settings' }}</pre>
                  <p>
                  <p><div>To retrieve a setting, the developer must call the function:</div></p>
                  <pre>Settings::get_settings_php($settings_key)</pre>
                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>settings_key (string) <i>this is the key that was set when the developer created a field in the settings form.</i></li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>Error
                           <ul>
                              <li>response_status (string) <i>"success" or "error"</i></li>
                              <li>status_message (string) <i>contains the error message</i></li>
                           </ul>
                        </li>
                        <li>Success
                           <ul>
                              <li>response_status (string) <i>"success" or "error"</i></li>
                              <li>settings_key (string) <i> the key/name of the settings</i></li>
                              <li>settings_value (string) <i>the value of the settings</i></li>
                           </ul>
                        </li>
                        
                     </ul>
                  </p>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">C. Updating Settings</h4>
                     <div class="gray-author">by Luke Glenn Jordan (PHP/Laravel)</div>
                  </p>
                  <p>
                  Dependencies:
                  <pre>{{ 'App\Globals\Settings' }}</pre>
                  <p>
                  <p><div>To update a setting, the developer must call the function:</div></p>
                  <pre>Settings::update_settings($settings_key, $settings_value)</pre>
                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>settings_key (string) <i>this is the key that was set when the developer created a field in the settings form.</i></li>
                        <li>settings_value (string) <i>the new value of the setting</i></li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>Error
                           <ul>
                              <li>response_status (string) <i>"success" or "error"</i></li>
                              <li>status_message (string) <i>contains the error message</i></li>
                           </ul>
                        </li>
                        <li>Success
                           <ul>
                              <li>response_status (string) <i>"success" or "error"</i></li>
                              <li>settings_key (string) <i> the key/name of the settings</i></li>
                              <li>settings_value (string) <i>the value of the settings</i></li>
                           </ul>
                        </li>
                        
                     </ul>
                  </p>
               </div>
            </div>
         </div>

         <!-- CUSTOMER FORM -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>5. Image Upload Form</b></h3>
                  <!-- USING HOW TO USE UPLOAD GALLERY -->
                  <a class="pull-right btn btn-success image-gallery image-gallery-single" key="1" href="">Click Me to Test</a>
                  <p>
                     <h4>HOW TO USE UPLOAD GALLERY</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (HTML)</div>
                  </p>
                 
                  <pre>{{$documentation_image_gallery}}</pre>
                  <p>
                     <div>Once the developer adds a class <span class="text-success text-bold">.image-gallery</span> to any tag. It will automatically show the image gallery. By Default, selection of image is multiple however for limiting image selection to one, just add a class <span class="text-success text-bold">.image-gallery-single</span> to limit the gallery</div>
                     <div>
                        ATTRIBUTE(S):
                        <ul>
                           <li>"KEY" is a unique key for the tags (useful for multi image gallery in one page)</li>
                        </ul>
                     </div>
                  </p>

                  <!-- SELECT IMAGE CLICKED -->
                  <hr>
                  <p>
                     <h4>SUBMIT SELECTED IMAGE</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (JAVASCRIPT)</div>
                  </p>
                 
                  <pre>{{ $documentation_submit_image_done }}</pre>
                  <p>
                     <div>Use this to call additional functionalities once submission is done. Use <span class="text-success text-bold">data</span> for return response.</div>
                 </p>
                  <p>
                     <div>
                        Returned data are as follows:
                        <ul>
                           <li>"DATA" contains array of information of the selected image.</li>
                           <ul>
                              <li>column_data_here</li>
                              <li>column_data_here</li>
                              <li>column_data_here</li>
                           </ul>
                        </ul>
                     </div>
                     <div>
                        <span style="color: red;">ISSUES AND BUGS:</span>
                        <ul>
                           <li>(GT) It should be specified what are the <i>array/fields</i> inside the returned data.</li>
                        </ul>
                     </div>
                  </p>
               </div>
            </div>
         </div>

         <!-- IMAGE UPLOAD GALLLERY -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>6. Customer Form</b></h3>
                  <!-- CREATING NEW CUSTOMERS -->
                  <p>
                     <h4>CREATING NEW CUSTOMER</h4>
                     <div class="gray-author">by Jimar Zape (HTML)</div>
                  </p>

                  <pre>{{ '<button class="btn btn-primary popup" link="/member/customer/modalcreatecustomer">Button Label</button>' }}</pre>
                  <p>
                  <p>
                     <div>To use the <i>create customer form</i> for new customers. You need to use the <span class="text-success text-bold">.popup</span> class and use a specific link which is <span class="text-success text-bold">/member/customer/modalcreatecustomer</span>.</div>
                  </p>
                  <!-- EDITING CUSTOMER INFO -->
                  <hr>
                  <p>
                      <h4>CREATING NEW CUSTOMER (CALLBACK)</h4>
                     <div class="gray-author">by Jimar Zape (HTML)</div>
                  </p>
                 
                  <pre>{{ 'function submit_done_customer(data) { }' }}</pre>
                  <p>
                     <div>This triggers after saving the customer information in the database.</div>
                     <p>
                        <div>The following data are returned after submission:</div>
                        <ul>
                           <li>customer_info <i>"Customer Basic Information"</i></li>
                           <ul>
                              <li>customer_id</li>
                              <li>shop_id</li>
                              <li>country_id</li>
                              <li>title_name</li>
                              <li>first_name</li>
                              <li>middle_name</li>
                              <li>last_name</li>
                              <li>suffix_name</li>
                              <li>email</li>
                              <li>b_day</li>
                              <li>customer_phone</li>
                              <li>customer_mobile</li>
                              <li>customer_fax</li>
                              <li>customer_display_name</li>
                           </ul>
                           <li>customer_address <i>"Customer Address Information"</i></li>
                           <ul>
                              <li>0 <i>"Shipping Address"</i></li>
                              <ul>
                                 <li>customer_state</li>
                                 <li>customer_city</li>
                                 <li>customer_zipcode</li>
                                 <li>customer_street</li>
                              </ul> 
                              <li>1 <i>"Billing Address"</i></li>
                              <ul>
                                 <li>customer_state</li>
                                 <li>customer_city</li>
                                 <li>customer_zipcode</li>
                                 <li>customer_street</li>
                              </ul> 
                           </ul>
                        </ul>
                     </p>
                     <div>
                        <span style="color: red;">ISSUES AND BUGS:</span>
                        <ul>
                           <li>(GT) Don't return all customer information especially the "password" even if it is encrypted. Return only those who are stated in this documentation.</li>

                        </ul>
                     </div>
                  </p>



                  <!-- EDITING CUSTOMER INFO -->
                  <hr>
                  <p>
                     <h4>EDITING CUSTOMER INFORMATION</h4>
                     <div class="gray-author">by Jimar Zape (HTML)</div>
                  </p>
                  <pre>{{ '<button class="btn btn-primary popup" link="/member/customer/customeredit/{customer_id}">Button Label</button>' }}</pre>
                  <p>
                     <div>To use the <i>edit customer form</i>. You need to use the <span class="text-success text-bold">.popup</span> class and use a specific link which is <span class="text-success text-bold">/member/customer/customeredit/{customer_id}</span>. Do not forget to replace <span class="text-success text-bold">{customer_id}</span> with the customer's real id.</div>
                     <div>
                        <span style="color: red;">ISSUES AND BUGS:</span>
                        <ul>
                           <li>(GT) Do not use encryption when submitting data. Raw <span class="text-success text-bold">{customer_id}</span> is already enough.</li>
                        </ul>
                     </div>
                  </p>
               </div>
            </div>
         </div>

         <!-- TUTORIAL GROUP -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>7. Item Form</b></h3>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <p>
                     <h4>ADDING NEW ITEMS</h4>
                     <div class="gray-author">by Erwin Guevarra and Arcy Guetierrez (HTML)</div>
                  </p>

                  <pre>{{ '<button class="btn btn-primary popup" link="/member/item/add" size="lg">Add Item</button>' }}</pre>
                  <p>
                  <p>
                     <div>To use the <i>create item form</i> for new items. You need to use the <span class="text-success text-bold">.popup</span> class and use a specific link which is <span class="text-success text-bold">/member/item/add</span>.</div>
                     <div>
                        <span style="color: red;">ISSUES AND BUGS:</span>
                        <ul>
                           <li>(GT) We need a <b>callback</b> after adding new items.</li>
                        </ul>
                     </div>
                  </p>
                  <!-- EDITING ITEM INFO -->
                  <hr>
                  <p>
                     <h4>EDITING ITEM INFORMATION</h4>
                     <div class="gray-author">by Erwin Guevarra and Arcy Guetierrez (HTML)</div>
                  </p>
                 
                  <pre>{{ '<a link="/member/item/edit/{item_id}" size="lg" href="javascript:" class="popup">Edit</a>' }}</pre>
                  <p>
                     <div>To use the <i>edit customer form</i>. You need to use the <span class="text-success text-bold">.popup</span> class and use a specific link which is <span class="text-success text-bold">/member/item/edit/{item_id}</span>. Do not forget to replace <span class="text-success text-bold">{item_id}</span> with the item's real id.</div>
                  </p>
               </div>
            </div>
         </div>

         <!-- TUTORIAL GROUP -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>8. Item Category Form</b></h3>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <p>
                     <h4>ADDING NEW ITEMS</h4>
                     <div class="gray-author">by Jimar Zape (HTML)</div>
                  </p>

                  <pre>{{ '<button class="btn btn-primary popup" link="/member/item/add" size="lg">Add Item</button>' }}</pre>
                  <p>
                  <p>
                     <div>To use the <i>create item form</i> for new items. You need to use the <span class="text-success text-bold">.popup</span> class and use a specific link which is <span class="text-success text-bold">/member/item/add</span>.</div>
                  </p>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">EDITING ITEM INFORMATION</h4>
                     <div class="gray-author">by Jimar Zape (HTML)</div>
                  </p>
                 
                  <pre>{{ '<a link="/member/item/edit/{item_id}" size="lg" href="javascript:" class="popup">Edit</a>' }}</pre>
                  <p>
                     <div>To use the <i>edit customer form</i>. You need to use the <span class="text-success text-bold">.popup</span> class and use a specific link which is <span class="text-success text-bold">/member/item/edit/{item_id}</span>. Do not forget to replace <span class="text-success text-bold">{item_id}</span> with the item's real id.</div>
                  </p>
               </div>
            </div>
         </div>

         <!-- TUTORIAL GROUP -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>9. Shipping Calculation</b></h3>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <p>
                     <h4 class="subtitle">HOW TO USE SHIPPING CALCULATION</h4>
                     <div class="gray-author">by <span class="red">No Developer Yet</span> (PHP/Laravel)</div>
                  </p>

                  <pre>{{ 'UNDER DEVELOPMENT' }}</pre>
                  <p>
                  <p><div>Allows the developer to compute the shipping fee.</div></p>
                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>shipping_id (int) <i>"ID of Shipping Company"</i></li>
                        <li>product_weight_unit (string) <i>"default is kg"</i></li>
                        <li>product_weight (double)</li>
                        <li>product_size_unit (string) <i>"default is inch"</i></li>
                        <li>product_length (double)</li>
                        <li>product_width (double)</li>
                        <li>product_height (double)</li>
                        <li>location_from (string) <i>"manila or province"</i></li>
                        <li>location_to (string) <i>"manila or province"</i></li>
                        <li>item_of_value (boolean)</li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>shipping_fee (double) <i>"Shipping Cost"</i></li>
                     </ul>
                  </p>
               </div>
            </div>
         </div>

         <!-- TUTORIAL GROUP -->
         @include('member.developer.mlm.index')
         <!-- TUTORIAL GROUP -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>11. Warehouse Control</b></h3>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <p>
                     <h4 class="subtitle">A. GET WAREHOUSE INFORMATION</h4>
                     <div class="gray-author">by Jimar Zape (PHP/Laravel)</div>
                  </p>

                  <p>
                     <div>
                        Dependencies:
                        <div><pre>{{ 'use App\Globals\Warehouse;' }}</pre></div>
                     </div>
                  </p>
    
                  <p>
                     <div>This method returns full warehouse information including the stocks of each product on the warehouse.</div>
                  </p>

                  <p>
                     <pre>{{ 'Warehouse::select_item_warehouse_single($warehouse_id,$return_type);' }}</pre>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>warehouse_id (int) <i>ID of Warehouse</i></li>
                        <li>return_type (string) <i>"array" or "json"</i></li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>warehouse_information (array) <i>These are information regarding the warehouse.</i></li>
                        <ul>
                           <li>warehouse_id (int)</li>
                           <li>warehouse_name (string)</li>
                        </ul>
                        <li>warehouse_products (array) <i>These are list of products and their corresponding stocks.</i></li>
                        <ul>
                           <li>0</li>
                           <ul>
                              <li>product_id (int)</li>
                              <li>product_name (string)</li>
                              <li>product_sku (string)</li>
                              <li>product_warehouse_stocks (int)</li>
                              <li>product_reorder_point (int)</li>
                           </ul>
                           <li>1</li>
                           <ul>
                              <li>product_id (int)</li>
                              <li>product_name (string)</li>
                              <li>product_sku (string)</li>
                              <li>product_warehouse_stocks (int)</li>
                              <li>product_reorder_point (int)</li>
                           </ul>
                        </ul>
                     </ul>
                  </p>

                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">B. CHECK INVENTORY ON WAREHOUSE BY PRODUCT</h4>
                     <div class="gray-author">by Jimar Zape (PHP/Laravel)</div>
                  </p>

                  <pre>{{ 'FOR TESTING' }}</pre>

                  <p>
                     <div>This method allows the developer to check how many product is available in a warehouse.</div>
                  </p>

                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>warehouse_id (int) <i>"ID of Warehouse"</i></li>
                        <li>product_id (int) <i>"ID of Product"</i></li>
                        <li>return_type (string) <i>"array or json"</i></li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>product_id (int)</li>
                        <li>product_name (string)</li>
                        <li>product_sku (string)</li>
                        <li>product_warehouse_stocks (int)</li>
                     </ul>
                  </p>

                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">C. INVENTORY TRANSFER SINGLE</h4>
                     <div class="gray-author">by Jimar Zape (PHP/Laravel)</div>
                  </p>

                  <pre>{{ 'Warehouse:inventory_transfer_single($warehouse_source_id, $warehouse_destination_id, $product_id, $quantity, $return_type);' }}</pre>

                  <p>
                     <div>Allows the developer to transfer from one warehouse to another. Everytime inventory is transferred, two <i>inventory slip</i> is generated. The first <i>inventory slip</i> is the consumption on the source warehosue and the second one is the slip for the warehouse who received it.</div>
                  </p>

                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>warehouse_source_id (int) <i>"ID of Warehouse"</i></li>
                        <li>warehouse_destination_id (int) <i>"ID of Warehouse"</i></li>
                        <li>product_id (int) <i>"ID of Product"</i></li>
                        <li>quantity <i>"Number of quantity being transferred"</i></li>
                        <li>return_type (array) <i>"json or array"</i></li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>status (string) <i>"success or error"</i></li>
                        <li>status_message (string) <i>"contains information if status is error"</i></li>
                        <li>inventory_slip (array)</li>
                        <ul>
                           <li>inventory_slip_id_source (int) <i>"Inventory Slip of Source Warehouse"</i></li>
                           <li>inventory_slip_id_destination (int) <i>"Inventory Slip of Source Warehouse"</i></li>
                        </ul>
                     </ul>
                  </p>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">D. INVENTORY TRANSFER BULK</h4>
                     <div class="gray-author">by Jimar Zape (PHP/Laravel)</div>
                  </p>

                  <pre>{{ 'Warehouse::inventory_transfer_bulk($warehouse_source_id, $warehouse_destination_id, $warehouse_transfer, $remarks, $return_type);' }}</pre>

                  <p>
                     <div>Allows the developer to transfer from one warehouse to another. Everytime inventory is transferred, two <i>inventory slip</i> is generated. The first <i>inventory slip</i> is the consumption on the source warehosue and the second one is the slip for the warehouse who received it.</div>
                  </p>

                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>warehouse_source_id (int) <i>"ID of Warehouse"</i></li>
                        <li>warehouse_destination_id (int) <i>"ID of Warehouse"</i></li>
                        <li>warehouse_transfer (array) <i>"Contains Transfer Information"</i></li>
                        <ul>
                           <li>0</li>
                           <ul>
                              <li>product_id (int) <i>"ID of Product"</i></li>
                              <li>quantity <i>"Number of quantity being transferred"</i></li>
                           </ul>
                           <li>1</li>
                           <ul>
                              <li>product_id (int) <i>"ID of Product"</i></li>
                              <li>quantity <i>"Number of quantity being transferred"</i></li>
                           </ul>
                        </ul>
                        <li>return_type (array) <i>"json or array"</i></li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>status (string) <i>"success or error"</i></li>
                        <li>status_message (string) <i>"contains information if status is error"</i></li>
                        <li>inventory_slip (array)</li>
                        <ul>
                           <li>inventory_slip_id_source (int) <i>"Inventory Slip of Source Warehouse"</i></li>
                           <li>inventory_slip_id_destination (int) <i>"Inventory Slip of Source Warehouse"</i></li>
                        </ul>
                     </ul>
                  </p>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">E. INVENTORY REFILL</h4>
                     <div class="gray-author">by Jimar Zape (PHP/Laravel)</div>
                  </p>
                  <pre>{{ 'Warehouse::inventory_refill($warehouse_id, $warehouse_reason_refill, $warehouse_refill_source, $warehouse_remarks, $warehouse_refill_product, $return_type);' }}</pre>
                  <p>
                     <div>Allows the developer to refill stocks if specific inventory. Everytime inventory is refilled, an inventory slip is also generated on the process.</div>
                  </p>

                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>warehouse_id (int) <i>"ID of Warehouse"</i></li>
                        <li>warehouse_reason_refill (string) <i>"State source of this refill in words."</i></li>
                        <li>warehouse_refill_source (int) <i>"State source of this refill in ID."</i></li>
                        <li>warehouse_remarks (string) <i>"Contains Refill Comments or Notes"</i></li>
                        <li>warehouse_refill_product (array) <i>"Contains Refill Information"</i></li>
                        <ul>
                           <li>0</li>
                           <ul>
                              <li>product_id (int) <i>"ID of Product"</i></li>
                              <li>quantity <i>"Number of quantity being refilled."</i></li>
                           </ul>
                           <li>1</li>
                           <ul>
                              <li>product_id (int) <i>"ID of Product"</i></li>
                              <li>quantity <i>"Number of quantity being refilled."</i></li>
                           </ul>
                        </ul>
                        <li>return_type (array) <i>"json or array"</i></li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>status (string) <i>"success or error"</i></li>
                        <li>status_message (string) <i>"contains information if status is error"</i></li>
                        <li>inventory_slip_id (int)</li>
                     </ul>
                  </p>

                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">F. INVENTORY CONSUME</h4>
                     <div class="gray-author">by Jimar Zape (PHP/Laravel)</div>
                  </p>

                  <pre>{{ 'Warehouse::inventory_consume($warehouse_id, $warehouse_consume_remarks, $warehouse_consume_product, $warehouse_consumer_id, $warehouse_consume_reason, $return_type);' }}</pre>
                  <p>
                     <div>Allows the developer to consume invetory of a warehouse. Parameter <c>reason</c> is required and important.</div>
                  </p>

                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>warehouse_id (int) <i>"ID of Warehouse"</i></li>
                        <li>warehouse_consume_remarks (text) <i>"Notes or reminder inputted by the user."</i></li>
                        <li>warehouse_consume_product (array) <i>"Contains Product to be consume Information"</i></li>
                        <li>warehouse_consumer_id (int) <i>"eg: customer_id"</i></li>
                        <li>warehouse_consume_reason (string) <i>"State reason for consuming stocks. eg: customer"</i></li>
                        <ul>
                           <li>0</li>
                           <ul>
                              <li>product_id (int) <i>"ID of Product"</i></li>
                              <li>quantity <i>"Number of quantity being refilled."</i></li>
                           </ul>
                           <li>1</li>
                           <ul>
                              <li>product_id (int) <i>"ID of Product"</i></li>
                              <li>quantity <i>"Number of quantity being refilled."</i></li>
                           </ul>
                        </ul>
                        <li>return_type (array) <i>"json or array"</i></li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>status (string) <i>"success or error"</i></li>
                        <li>status_message (string) <i>"contains information if status is error"</i></li>
                        <li>inventory_slip_is (int)</li>
                     </ul>
                  </p>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">G. GET TRANSFER WAREHOUSE INFORMATION</h4>
                     <div class="gray-author">by Jimar Zape (PHP/Laravel)</div>
                  </p>

                  <pre>{{ 'Warehouse::get_transfer_warehouse_information($warehouse_from_id, $warehouse_to_id, $return_type);' }}</pre>
                  <p>
                     <div>This method returns full warehouse transfer information including the stocks of each product on both warehouse.</div>
                  </p>

                  <p>
                  <div>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>warehouse_from_id (int) <i>"ID of Warehouse (from)"</i></li>
                        <li>warehouse_to_id (int) <i>"ID of Warehouse (to)"</i></li>
                        <li>return_type (string) <i>"array" or "json"</i></li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>warehouse_products (array) <i>These are list of products on both warehouse and their corresponding stocks.</i></li>
                        <ul>
                           <li>0</li>
                           <ul>
                              <li>product_id (int) <i>"ID of Product"</i></li>
                              <li>product_name (string) <i>"Name of product"</i></li>
                              <li>product_sku (string) </li>
                              <li>product_source_qty (int) <i>"Quantity of products from the source warehouse"</i></li>
                              <li>product_current_qty (int) <i>"Current Quantity of products"</i></li>
                              <li>product_reorder_point (int) <i>"Reorder Point of the receiver of the stocks"</i></li>
                           </ul>
                           <li>1</li>
                           <ul>
                              <li>product_id (int) <i>"ID of Product"</i></li>
                              <li>product_name (string) <i>"Name of product"</i></li>
                              <li>product_sku (string) </li>
                              <li>product_source_qty (int) <i>"Quantity of products from the source warehouse"</i></li>
                              <li>product_current_qty (int) <i>"Current Quantity of products"</i></li>
                              <li>product_reorder_point (int) <i>"Reorder Point of the receiver of the stocks"</i></li>
                           </ul>
                        </ul>
                     </ul>
                  </p>
               </div>
            </div>
         </div>  
         <!-- TUTORIAL GROUP -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>12. Cart Control</b></h3>
                  <p>
                     <div>
                        Dependencies:
                        <div><pre>{{ 'use App\Globals\Cart;' }}</pre></div>
                     </div>
                  </p>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <p>
                     <h4 class="subtitle">A. ADDING ITEM TO CART USING A SIMPLE HTML</h4>
                     <div class="gray-author">by <span class="red">No Developer Yet</span> (HTML)</div>
                  </p>

                  <pre>{{ 'UNDER DEVELOPMENT' }}</pre>
                  <p>
                  <p>
                     <div>Allows the developer to add a cart by adding a simple class into a tag.</div>
                  </p>

                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">B. ADDING ITEM TO CART USING PHP</h4>
                     <div class="gray-author">by <span class="red">No Developer Yet</span> (PHP/Laravel)</div>
                  </p>
                  <p>
                     <div>
                        Use shop id,product id and quantity.
                        <div><pre>{{ 'Cart::add_to_cart($product_id,$quantity);' }}</pre></div>
                     </div>
                  </p>
                  <p>
                   <div>Allows the developer to add a cart by calling a method in PHP. This method checks stocks of product in the designated warehouse for e-commerce.</div>
                  </p>
                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>product_id (int) <i>ID of Warehouse</i></li>
                        <li>quantity (int) <i>Number of products that would ba added to cart.</i></li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>status (string) <i>"success" or "error"</i></li>
                        <li>status_message (string) <i>contains information if status is error</i></li>
                     </ul>

                  </p>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">C. SETTING CUSTOMER INFORMATION TO CART</h4>
                     <div class="gray-author">by <span class="red">No Developer Yet</span> (PHP/Laravel)</div>
                  </p>

                  <pre>{{ 'Cart::customer_settings($customer_id,$customer_information,$customer_shipping_address,$customer_billing_address,$customer_payment_method,$customer_payment_proof);' }}</pre>
                  <p>
                  <p>
                   <div>Allows the developer to set data of customer in the cart.</div>
                  </p>

                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>customer_id (int) <i>Customer ID (set to '0' [zero] if customer doesn't have account and set to 'null' if no update)</i></li>
                        <li>customer_information (array) <i>List of Information (set to 'null' if no update)</i></li>
                        <ul>
                           <li>first_name (string)</li>
                           <li>middle_name (string)</li>
                           <li>last_name (string)</li>
                           <li>email (string)</li>
                           <li>company (string)</li>
                           <li>birthday (date)</li>
                        </ul>
                        <li>customer_shipping_address (array) <i>Billing Address Information (set to 'null' if no update)</i></li>
                        <ul>
                           <li>state</li>
                           <li>city</li>
                           <li>zip</li>
                           <li>street</li>
                           <li>address</li>
                        </ul>
                        <li>customer_billing_address (array) <i>Shipping Address Informatione (set to 'null' if no update)</i></li>
                        <ul>
                           <li>state</li>
                           <li>city</li>
                           <li>zip</li>
                           <li>street</li>
                           <li>address</li>
                        </ul>
                        <li>customer_payment_method (string) <i>can be 'paypal', 'cod', 'credit card', etc (set to 'null' if no update)</i></li>
                        <li>customer_payment_proof (int) <i>this is only needed if proof can be uploaded as a payment (take note that "tbl_payment_proof" contains the information regarding the proof of payment)</i></li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>status (string) <i>"success" or "error"</i></li>
                        <li>status_message (string) <i>contains information if status is error</i></li>
                     </ul>
                  </p>

                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">D. GENERATING DISCOUNT COUPON</h4>
                     <div class="gray-author">by <span class="red">No Developer Yet</span> (PHP/Laravel)</div>
                  </p>

                  <pre>{{ 'Cart::use_coupon_code($coupon_code);' }}</pre>
                  <p>
                  <p>
                   <div>Allows the developer to apply a discount in the transaction.</div>
                  </p>

                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>coupon_code (string) <i>this is a code in "tbl_coupon_code" generated for discount</i></li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>status (string) <i>"success" or "error"</i></li>
                        <li>status_message (string) <i>contains information if status is error</i></li>
                     </ul>
                  </p>


                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">E. APPLYING DISCOUNT COUPON</h4>
                     <div class="gray-author">by <span class="red">No Developer Yet</span> (PHP/Laravel)</div>
                  </p>
                  Type = "fixed" or "percentage";
                  <pre>{{ 'Cart::generate_coupon_code($word_limit,$price,$type);' }}</pre>
                  <p>
                  <p>
                   <div>Allows the developer to apply a discount in the transaction.</div>
                  </p>

                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>coupon_code (string) <i>this is a code in "tbl_coupon_code" generated for discount</i></li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>status (string) <i>"success" or "error"</i></li>
                        <li>status_message (string) <i>contains information if status is error</i></li>
                     </ul>
                  </p>

                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">F. REMOVING ITEM TO CART</h4>
                     <div class="gray-author">by <span class="red">No Developer Yet</span> (PHP/Laravel)</div>
                  </p>
                  <pre>{{ 'Cart::delete_product($product_id);' }}</pre>
                  <p>
                  <p>
                     <div>Calling this method allows the developer to remove an item from a cart.</div>
                  </p>

                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>product_id (int) <i>Primary Key ID of the Product</i></li>
                     </ul>
                  </p>

                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">G. GETTING CART INFORMATION</h4>
                     <div class="gray-author">by <span class="red">No Developer Yet</span> (PHP/Laravel)</div>
                  </p>

                  <pre>{{ 'Cart::get_cart()' }}</pre>
                  <p>
                  <p>
                     <div>Using this method, the developer will have access to the overall information of the cart.</div>
                  </p>

                  <p>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>cart_customer_information (array) <i>This contains information of customer.</i></li>
                        <li>cart_shipping_information (array) <i>This contains information regarding the shipping address.</i></li>
                        <li>cart_billing_information (array) <i>This contains information regarding the billing address.</i></li>
                        <li>cart_item_information (array) <i>This contains information regarding the products inside the cart.</i></li>
                        <ul>
                           <li>product_id (int)</li>
                           <li>product_name (string)</li>
                           <li>product_sku (string)</li>
                           <li>product_stocks (int)</li>
                           <li>product_ecommerce_price (double)</li>
                           <li>product_discounted (string) <i>'fixed', 'percentage' or 'no_discount' ('no_discount' is the default)</i></li>
                           <li>product_discounted_value (double) <i>This is the value for the discount.</i></li>
                           <li>product_discounted_remark (string) <i>E.G "20% Discount for Year End Sale dated October 24, 2016 up to December 31, 2016"</i></li>
                           <li>product_current_price (double) <i>This is the price less discount if there is any.</i></li>
                        </ul>
                        <li>cart_sale_information (array) <i>This contains information regarding the current sales.</i></li>
                        <ul>
                           <li>total_item_price (double) <i>Total Price of all the items.</i></li>
                           <li>total_shipping (double) <i>Shipping fee computed based on the shipping address.</i></li>
                           <li>total_coupon_discount (double) <i>Total discount based on coupon.</i></li>
                           <li>total_overall_price (double) <i>Total price the customer has to pay.</i></li>
                        </ul>
                     </ul>
                  </p>

                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">H. PROCESSING CART</h4>
                     <div class="gray-author">by <span class="red">No Developer Yet</span> (PHP/Laravel)</div>
                  </p>

                  <pre>{{ 'UNDER DEVELOPMENT' }}</pre>
                  <p>
                  <p>
                     <div>This method allows the developer to process the data into cart. The cart will be converted into an order.</div>
                  </p>
                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>customer_paid (boolean) <i>"true" or "false" - set to true if customer is already paid ('false' is the default).</i></li>
                     </ul>
                  </p>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">H. SHOWING POPUP CART</h4>
                     <div class="gray-author">by <span class="red">No Developer Yet</span> (HTML)</div>
                  </p>

                  <pre>{{ 'UNDER DEVELOPMENT' }}</pre>
                  <p>
                  <p>
                     <div>Allows the developer to view the cart just be adding a simple class.</div>
                  </p>
               </div>
            </div>
         </div>  


         <!-- TUTORIAL GROUP -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>13. Post Form</b></h3>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <p>
                     <h4 class="subtitle">CREATING NEW POST</h4>
                     <div class="gray-author">by Edward Guevarra (HTML)</div>
                  </p>

                  <pre>{{ 'UNDER DEVELOPMENT' }}</pre>
                  <p>
                  <p>
                     <div>Allows the developer to add new post on the system.</div>
                  </p>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">CREATING NEW POST (CALLBACK)</h4>
                     <div class="gray-author">by <span class="red">No Developer Yet</span> (HTML)</div>
                  </p>

                  <pre>{{ 'UNDER DEVELOPMENT' }}</pre>
                  <p>
                  <p>
                     <div>This triggers after submitting the newly created post.</div>
                  </p>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">EDIT POST</h4>
                     <div class="gray-author">by <span class="red">No Developer Yet</span> (HTML)</div>
                  </p>

                  <pre>{{ 'UNDER DEVELOPMENT' }}</pre>
                  <p>
                  <p>
                     <div>Allows the developer to add new post on the system.</div>
                  </p>
               </div>
            </div>
         </div>


         <!-- TUTORIAL GROUP -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>14. Accounting Control</b></h3>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <p>
                     <h4 class="subtitle">1. GET LIST OF ALL ACCOUNT TITLE</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (PHP/Laravel)</div>
                  </p>

                  <pre>{{ 'Accounting::getAllAccount()' }}</pre>
                  <pre>{{ 'Accounting::getAllAccount("all","1","bank","null")' }}</pre>
                  <p>
                  <!-- <p><div>Allows the developer to compute the shipping fee.</div></p> -->
                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>filter (string) <i>(Default: all) can be "all", "active" or "inactive"</i></li>
                        <li>account_id (string) <i>(Default: null) Using this will allow the developer to get 'Sub Account' from specific Sub Title.</i></li>
                        <li>type (string) <i>(Default: null)</i></li>
                        <li>search (string) <i>(Default: null) If there is specific account name / number.</i></li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>0</li>
                        <ul>
                           <li>account_id (int)</li>
                           <li>account_number (string)</li>
                           <li>account_name (string)</li>
                           <li>account_type (string)</li>
                           <li>account_description (text)</li>
                           <li>account_parent_id (int) <i>NULL if there is no parent</i></li>
                           <li>account_sublevel (int)</li>
                           <li>account_balance (float)</li>
                           <li>is_sub_account (1 or 0)</li>
                           <li>account_new_balance</li>
                           <li>sub_account (array) <i>NULL if there is no sub-account</i></li>
                           <ul>
                              <li>0</li>
                              <ul>
                                 <li>account_id (int)</li>
                                 <li>account_number (string)</li>
                                 <li>account_name (string)</li>
                                 <li>account_type (string)</li>
                                 <li>account_description (text)</li>
                                 <li>account_parent_id (int) <i>NULL if there is no parent</i></li>
                                 <li>account_sublevel (int)</li>
                                 <li>account_balance (float)</li>
                                 <li>is_sub_account (1 or 0)</li>
                                 <li>account_new_balance</li>
                                 <li>sub_account (array) <i>NULL if there is no sub-account</i></li>
                              </ul>
                              <li>1</li>
                              <ul>
                                 <li>account_id (int)</li>
                                 <li>account_number (string)</li>
                                 <li>account_name (string)</li>
                                 <li>account_type (string)</li>
                                 <li>account_description (text)</li>
                                 <li>account_parent_id (int) <i>NULL if there is no parent</i></li>
                                 <li>account_sublevel (int)</li>
                                 <li>account_balance (float)</li>
                                 <li>is_sub_account (1 or 0)</li>
                                 <li>account_new_balance</li>
                                 <li>sub_account (array) <i>NULL if there is no sub-account</i></li>
                              </ul>
                           </ul>
                        </ul>
                        <li>1</li>
                        <ul>
                           <li>account_id (int)</li>
                           <li>account_number (string)</li>
                           <li>account_name (string)</li>
                           <li>account_type (string)</li>
                           <li>account_description (text)</li>
                           <li>account_parent_id (int) <i>NULL if there is no parent</i></li>
                           <li>account_sublevel (int)</li>
                           <li>account_balance (float)</li>
                           <li>is_sub_account (1 or 0)</li>
                           <li>account_new_balance</li>
                           <li>sub_account (array) <i>NULL if there is no sub-account</i></li>
                           <ul>
                              <li>0</li>
                              <ul>
                                 <li>account_id (int)</li>
                                 <li>account_number (string)</li>
                                 <li>account_name (string)</li>
                                 <li>account_type (string)</li>
                                 <li>account_description (text)</li>
                                 <li>account_parent_id (int) <i>NULL if there is no parent</i></li>
                                 <li>account_sublevel (int)</li>
                                 <li>account_balance (float)</li>
                                 <li>is_sub_account (1 or 0)</li>
                                 <li>account_new_balance</li>
                                 <li>sub_account (array) <i>NULL if there is no sub-account</i></li>
                              </ul>
                              <li>1</li>
                              <ul>
                                 <li>account_id (int)</li>
                                 <li>account_number (string)</li>
                                 <li>account_name (string)</li>
                                 <li>account_type (string)</li>
                                 <li>account_description (text)</li>
                                 <li>account_parent_id (int) <i>NULL if there is no parent</i></li>
                                 <li>account_sublevel (int)</li>
                                 <li>account_balance (float)</li>
                                 <li>is_sub_account (1 or 0)</li>
                                 <li>account_new_balance</li>
                                 <li>sub_account (array) <i>NULL if there is no sub-account</i></li>
                              </ul>
                           </ul>
                        </ul>
                     </ul>
                  </p>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">2. GET INFORMATION OF SPECIFIC ACCOUNT TITLE</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (PHP/Laravel)</div>
                  </p>
                  <pre>{{ 'Accounting::getAccount(1)' }}</pre>
                  <p>
                  <!-- <p><div>Allows the developer to compute the shipping fee.</div></p> -->
                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>account_id (int) <span class="blue"> 'required' </span><i>ID of Account Title</i></li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>account_id (int)</li>
                        <li>account_name (string)</li>
                        <li>account_type (string)</li>
                        <li>account_description (text)</li>
                        <li>account_balance (double)</li>
                     </ul>
                  </p>

                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">3. GET ACCOUNT TITLE FOR SPECIFIC ITEM</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (PHP/Laravel)</div>
                  </p>
                  
                  <pre>{{ 'Accounting::getItemAccount(1)' }}</pre>
                  <p>
                  <p><div>Allows the developer to compute the shipping fee.</div></p>
                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>item_id (int) <span class="blue"> 'required' </span><i>ID of Item</i></li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>income (array)</li>
                        <ul>
                           <li>account_id (int)</li>
                           <li>account_name (string)</li>
                           <li>account_type (string)</li>
                           <li>account_description (text)</li>
                        </ul>
                        <li>asset (array)</li>
                        <ul>
                           <li>account_id (int)</li>
                           <li>account_name (string)</li>
                           <li>account_type (string)</li>
                           <li>account_description (text)</li>
                        </ul>
                        <li>cost_of_good_sold (array)</li>
                        <ul>
                           <li>account_id (int)</li>
                           <li>account_name (string)</li>
                           <li>account_type (string)</li>
                           <li>account_description (text)</li>
                        </ul>
                        <!-- <li>account_receivable (array)</li>
                        <ul>
                           <li>account_id (int)</li>
                           <li>account_name (string)</li>
                           <li>account_type (string)</li>
                           <li>account_description (text)</li>
                        </ul>
                        <li>account_payable (array)</li>
                        <ul>
                           <li>account_id (int)</li>
                           <li>account_name (string)</li>
                           <li>account_type (string)</li>
                           <li>account_description (text)</li>
                        </ul> -->
                     </ul>
                  </p>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">4. ADDING JOURNAL ENTRIES</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (PHP/Laravel)</div>
                  </p>
                  <pre>@include('member.developer.documentation_journal_entry_data')</pre>
                  <p>
                  <p><div>Sample Data.</div></p>
                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>entry (array) <span class="blue"> 'required' </span></li>
                        <ul>
                           <li>reference_module (string) <span class="blue"> 'required' </span> <i>type of module of the transaction</i></li>
                           <ul>  
                              <li>mlm-product-repurchase</li>
                              <li>product-order</li>
                              <li>sales-receipt</li>
                              <li>invoice</li>
                              <li>receive-payment</li>
                              <li>bill-payment</li>
                              <li>write-check</li>
                              <li>bill</li>
                              <li>debit-memo</li>
                              <li>credit-memo</li>
                              <li>deposit</li>
                           </ul>
                           <li>reference_id (int) <span class="blue"> 'required' </span> <i>reference id of the transaction</i></li>
                           <li>name_id (int) <i>Customer or Vendor ID (use only if the transaction can either use for customer or vendor)</i></li>
                           <li>name_reference (string) <i>"customer" or "vendor" (use only if the transaction can either use for customer or vendor)</i></li>
                           <li>vatable (double) <i>If the transaction has vat</i></li>
                           <li>discount (double) <i>If the transaction has a discount as whole</i></li>
                           <li>ewt (double) <i>If the transaction has ewt</i></li>
                           <li>account_id (int) <i>If there is a specified account id for the main account (account that is selected in the transaction | overwrite the default value of accounts receivable or payable </i></li>
                           <li>total (double) <span class="blue"> 'required' </span> <i>Entry amount</i></li>
                        </ul>
                        <li>entry_data (array) <span class="blue"> 'required' </span></li>
                        <ul>
                           <li>0</li>
                           <ul>
                              <li>item_id (int) or account_id (int) <span class="blue"> 'required' </span> <i>Id of the item or Id of the account</i></li>
                              <li>vatable (double) <i>If the transaction has vat</i></li>
                              <li>discount (double) <i>If the transaction has a discount per item</i></li>
                              <li>entry_amount (double) <span class="blue"> 'required' </span> <i>Amount per item</i></li>
                              <li>entry_description (string) <i>Description</i></li>
                           </ul>
                        </ul>
                        <li>remarks (string) <!-- <span class="blue"> 'required' </span> --><i>Remarks for whole journal entry</i></li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>void</i></li>
                     </ul>
                  </p>

                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <hr>
                  <p>
                     <h4 class="subtitle">5. ADDING MANUAL JOURNAL ENTRIES</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (PHP/Laravel)</div>
                  </p>
                  <pre>@include('member.developer.documentation_manual_journal_entry_data')</pre>
                  <p>
                  <p><div>Sample Data.</div></p>
                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>entry (array) <span class="blue"> 'required' </span></li>
                        <ul>
                           <li>entry_date (string) <span class="blue"> 'required' </span> <i>date of the transaction journal</i></li>
                           <li>je_id (int) <span class="blue"> 'required' </span> <i>reference id of the transaction if exist (nullable)</i></li>
                        </ul>
                        <li>entry_data (array) <span class="blue"> 'required' </span></li>
                        <ul>
                           <li>0</li>
                           <ul>
                              <li>account_id (int)  <span class="blue"> 'required' </span> <i>Id of the account</i></li>
                              <li>type (double) <span class="blue"> 'required' </span> <i>"Credit" or "Debit"</i></li>
                              <li>entry_amount (double) <span class="blue"> 'required' </span> <i>Amount per item</i></li>
                              <li>name_id (string) <i>Id of the Customer or Vendor if available</i></li>
                              <li>name_reference (string) <i>"vendor" or "customer"</i></li>
                           </ul>
                        </ul>
                        <li>remarks (string) <!-- <span class="blue"> 'required' </span> --><i>Remarks for whole journal entry</i></li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>void</i></li>
                     </ul>
                  </p>
               </div>
            </div>
         </div>

         <!-- TUTORIAL GROUP -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>15. Invoice Control</b></h3>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <p>
                     <h4 class="subtitle">Creating Invoice</h4>
                     <div class="gray-author">by Bryan Kier R. Aradanas (PHP/Laravel)</div>
                  </p>

                  <pre>{{ $documentation_create_invoice }}</pre>
                  <p>
                  <p><div>Allows the developer to compute the shipping fee.</div></p>
                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>customer_information (array) <i>"Information regarding the customer"</i></li>
                        <ul>
                           <li>customer_id</li>
                           <li>customer_email</li>
                        </ul>
                        <li>invoice_information (array) <i>"Information regarding the customer"</i></li>
                        <ul>
                           <li>invoice_terms_id</li>
                           <li>invoice_date</li>
                           <li>invoice_due</li>
                           <li>billing_address</li>
                        </ul>
                        <li>item_information</li>
                        <ul>
                           <li>0</li>
                           <ul>
                              <li>item_sercive_date</li>
                              <li>item_id</li>
                              <li>item_description</li>
                              <li>quantity</li>
                              <li>rate</li>
                              <li>discount</li>
                              <li>discount_remark</li>
                              <li>amount</li>
                           </ul>
                            <li>1</li>
                           <ul>
                              <li>item_service_date</li>
                              <li>item_id</li>
                              <li>item_description</li>
                              <li>quantity</li>
                              <li>rate</li>
                              <li>discount</li>
                              <li>discount_remark</li>
                              <li>amount</li>
                           </ul>
                        </ul>
                        <li>total_information</li>
                        <ul>
                           <li>total_subtotal_price</li>
                           <li>ewt</li>
                           <!-- <li>total_addons (aray)</li>
                           <ul>
                              <li>0</li>
                              <ul>
                                 <li>label (string) <i>E.G: VAT, EWT</i></li>
                                 <li>value (double)</li>
                              </ul>
                               <li>1</li>
                              <ul>
                                 <li>label (string)</li>
                                 <li>value (double)</li>
                              </ul>
                           </ul> -->
                           <li>total_discount_type</li>
                           <li>total_discount_value</li>
                           <li>taxable</li>
                           <li>total_overall_price</li>
                        </ul>
                        <li>total_other_information</li>
                        <ul>
                           <li>customer_message</li>
                           <li>invoice_memo</li>
                        </ul>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>shipping_fee (double) <i>"Shipping Cost"</i></li>
                     </ul>
                  </p>
               </div>
            </div>
         </div>

         <!-- TUTORIAL GROUP -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>16. Select - Drop Down (Sample)</b></h3>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <p>
                     <h4>FOR CUSTOMER</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (MIXED)</div>
                  </p>
                  <select class="drop-down-customer">
                      @include("member.load_ajax_data.load_customer")
                  </select>
                  </br>
                  <pre>{{ $dropdown_customer }}</pre>
                  <p>
                  <p>
                     <div>For Ajax Request, use url : <span class="text-success text-bold">/member/customer/load_customer</span>. Return type : <span class="text-success text-bold">{{'<option />'}}</span></div>
                  </p>

                  <hr>

                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <p>
                     <h4>FOR ITEM</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (MIXED)</div>
                  </p>
                  <select class="drop-down-item">
                      @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                  </select>
                  </br>
                  <pre>{{ $dropdown_item }}</pre>
                  <p>
                  <p>
                     <div>For Ajax Request, use url : <span class="text-success text-bold">/member/item/load_item_category</span>. Return type : <span class="text-success text-bold">{{'<option />'}}</span></div>
                  </p>

                  <hr>

                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <p>
                     <h4>FOR CHART OF ACOUNTS</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (MIXED)</div>
                  </p>
                  <select class="drop-down-coa">
                      @include("member.load_ajax_data.load_chart_account", ['add_search' => "", 'item_type' => ['Income','Other Income']])
                  </select>
                  </br>
                  <pre>{{ $dropdown_coa }}</pre>
                  <p>
                  <p>
                     <div>For Ajax Request, use url : <span class="text-success text-bold">/member/accounting/load_coa</span>. Return type : <span class="text-success text-bold">{{'<option />'}}</span></div>
                  </p>

                  <hr>

                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <p>
                     <h4>FOR CATEGORY</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (MIXED)</div>
                  </p>
                  <select class="drop-down-category">
                      @include("member.load_ajax_data.load_category", ['add_search' => ""])
                  </select>
                  </br>
                  <pre>{{ $dropdown_category }}</pre>
                  <p>
                  <p>
                     <div>For Ajax Request, use url : <span class="text-success text-bold">/member/item/load_category</span>. Return type : <span class="text-success text-bold">{{'<option />'}}</span></div>
                  </p>

                   <hr>
                   
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <p>
                     <h4>FOR VENDOR</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (MIXED)</div>
                  </p>
                  <select class="drop-down-vendor">
                      @include("member.load_ajax_data.load_vendor")
                  </select>
                  </br>
                  <pre>{{ $dropdown_vendor }}</pre>
                  <p>
                  <p>
                     <div>For Ajax Request, use url : <span class="text-success text-bold">/member/item/load_vendor</span>. Return type : <span class="text-success text-bold">{{'<option />'}}</span></div>
                  </p>
                 
               </div>
            </div>
         </div>

         <!-- TUTORIAL GROUP -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>17. Ecommerce Product Controller</b></h3>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <p>
                     <h4 class="subtitle">Get all products</h4>
                     <div class="gray-author">by Bryan Kier R. Aradanas (PHP/Laravel)</div>
                  </p>

                  <pre>{{ 'Ecom_Product::getAllProduct();' }}</pre>
                  <p>
                  <p><div>Allows to get all product.</div></p>
                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>Category (string) <i>"--- "</i></li>
                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>0</li>
                        <ul>
                           <li>eprod_id (int)</li>
                           <li>eprod_name (string)</li>
                           <li>eprod_type (string)</li>
                           <li>eprod_description (string)</li>
                           <li>variant (array)</li>
                           <ul>
                              <li>0</li>
                              <ul>
                                 <li>evariant_id (int)</li>
                                 <li>evariant_prod_id (int)</li>
                                 <li>evariant_item_id (int)</li>
                                 <li>evariant_item_label (string)</li>
                                 <li>evariant_description (string)</li>
                                 <li>evariant_price (float)</li>
                                 <li>date_created (datetime)</li>
                                 <li>date_visible (datetime)</li>
                                 <li>visible (int)</li>
                                 <li>options (array) <i>Not Defined if single variation</i></li>
                                 <ul>
                                    <li>option_name (key) => option_value (string) </li>
                                    <li>option_name (key) => option_value (string) </li>
                                 </ul>
                              </ul>
                              <li>1</li>
                              <ul>
                                 <li>evariant_id (int)</li>
                                 <li>evariant_prod_id (int)</li>
                                 <li>evariant_item_id (int)</li>
                                 <li>evariant_item_label (string)</li>
                                 <li>evariant_description (string)</li>
                                 <li>evariant_price (float)</li>
                                 <li>date_created (datetime)</li>
                                 <li>date_visible (datetime)</li>
                                 <li>visible (int)</li>
                                 <li>options (array) <i>Not Defined if single variation</i></li>
                                 <ul>
                                    <li>option_name (key) => option_value (string) </li>
                                    <li>option_name (key) => option_value (string) </li>
                                 </ul>
                              </ul>
                           </ul>
                        </ul>
                        <li>1</li>
                        <ul>
                           <li>eprod_id (int)</li>
                           <li>eprod_name (string)</li>
                           <li>eprod_type (string)</li>
                           <li>eprod_description (string)</li>
                           <li>variant (array)</li>
                           <ul>
                              <li>0</li>
                              <ul>
                                 <li>evariant_id (int)</li>
                                 <li>evariant_prod_id (int)</li>
                                 <li>evariant_item_id (int)</li>
                                 <li>evariant_item_label (string)</li>
                                 <li>evariant_description (string)</li>
                                 <li>evariant_price (float)</li>
                                 <li>date_created (datetime)</li>
                                 <li>date_visible (datetime)</li>
                                 <li>visible (int)</li>
                              </ul>
                           </ul>
                        <ul>
                     </ul>
                  </p>
               </div>
            </div>
         </div>

         <!-- TUTORIAL GROUP -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>18. User Access Level</b></h3>
                  <!-- TUTORIAL GROUP - SUBJECT -->
                  <p>
                     <h4 class="subtitle">Check if the user has a access</h4>
                     <div class="gray-author">by Bryan Kier R. Aradanas (PHP/Laravel)</div>
                  </p>

                  <pre>{{ 'Utilities::checkAccess($page_code, $access_name)' }}</pre>
                  <p>
                  <p><div>Allows to check if the user logged on has an access.</div></p>
                  <p>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>Page Code (string) <span class="blue">required</span> <i>"(Code of the page)"</i></li>
                        <li>Access Name (string) <span class="blue">required</span> <i>"(Type or name of access)"</i></li>

                     </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>1 <i>Has access</i></li>
                        <li>0 <i>Do not have access</i></li>
                     </ul>
                  </p>
               </div>
            </div>
         </div>


         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>19. Unit of Measurement Control</b></h3>
                  <!-- 1 -->
                  <p>
                     <h4 class="subtitle">A. GET ALL BASED UNIT OF MEASUREMENT</h4>
                     <div class="gray-author">by Arcylen Gutierrez (PHP/Laravel)</div>
                  </p>

                  <p>
                     <div>
                        Dependencies:
                        <div><pre>{{ 'use App\Globals\UnitMeasurement;' }}</pre></div>
                     </div>
                  </p>
    
                  <p>
                     <div>This method returns all based unit and its abbreviation.</div>
                  </p>
                  <p>
                     <pre>{{ 'UnitMeasurement::load_um();' }}</pre>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>um_id (int) <i>The ID of UM.</i></li>
                        <li>um_name (string) <i>The name of the Unit of Measurement.</i></li>
                        <li>multi_abbrev (string) <i>The abbreviation used for the based UM.</i></li>
                     </ul>
                  </p>
                  <!-- 2 -->

                    <p>
                     <h4 class="subtitle">B. GET ALL BASED AND RELATED UNIT OF MEASUREMENT</h4>
                     <div class="gray-author">by Arcylen Gutierrez (PHP/Laravel)</div>
                  </p>
    
                  <p>
                     <div>This method returns all based and its related Unit of Measurement.</div>
                  </p>

                  <p>
                     <pre>{{ 'UnitMeasurement::load_um_multi();' }}</pre>
                     <div><c>Returned information</c> are as follows:</div>
                     <i>Unit of Measurement (array)</i>
                     <ul>
                        <li>multi_id (int) <i>The ID of UM.</i></li>
                        <li>multi_name (string) <i>The name of the Related UM.</i></li>
                        <li>multi_abbrev (string) <i>The abbreviation used for the Related UM.</i></li>
                        <li>unit_qty (int) <i>The Quantity of Related UM.</i></li>
                     </ul>
                  </p>
                  <!-- 3 -->
                  <p>
                     <h4 class="subtitle">C. GET ALL RELATED UNIT OF MEASUREMENT</h4>
                     <div class="gray-author">by Arcylen Gutierrez (PHP/Laravel)</div>
                  </p>
    
                  <p>
                     <div>This method returns all related unit and abbreviation under one based Unit of Measurement.</div>
                  </p>

                  <p>
                     <pre>{{ 'UnitMeasurement::load_one_um($um_id);' }}</pre>
                     <div><c>Parameters</c> are as follows:</div>
                        <ul>
                           <li>um_id (int) <i>ID of BASED Unit of Measurement</i></li>
                        </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <i>Unit of Measurement (array)</i>
                     <ul>
                        <li>multi_id (int) <i>The ID of UM.</i></li>
                        <li>multi_name (string) <i>The name of the Related UM.</i></li>
                        <li>multi_abbrev (string) <i>The abbreviation used for the Related UM.</i></li>
                        <li>unit_qty (int) <i>The Quantity of Related UM.</i></li>
                     </ul>
                  </p>
                  <!-- 4 -->
                  <p>
                     <h4 class="subtitle">D. GET DETAILS IN ONE UNIT OF MEASUREMENT</h4>
                     <div class="gray-author">by Arcylen Gutierrez (PHP/Laravel)</div>
                  </p>
    
                  <p>
                     <div>This method returns the details of the selected UM.</div>
                  </p>

                  <p>
                     <pre>{{ 'UnitMeasurement::um_info($multi_id);' }}</pre>
                     <div><c>Parameters</c> are as follows:</div>
                        <ul>
                           <li>multi_id (int) <i>ID of UM</i></li>
                        </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>multi_id (int) <i>The ID of UM.</i></li>
                        <li>multi_name (string) <i>The name of the Related UM.</i></li>
                        <li>multi_abbrev (string) <i>The abbreviation used for the Related UM.</i></li>
                        <li>unit_qty (int) <i>The Quantity of Related UM.</i></li>
                     </ul>
                  </p>
                  <!-- 5 -->
                  <p>
                     <h4 class="subtitle">E. GET ALL <strong>RELATED</strong> UM based IN ONE <strong>RELATED</strong> UNIT OF MEASUREMENT</h4>
                     <div class="gray-author">by Arcylen Gutierrez (PHP/Laravel)</div>
                  </p>
    
                  <p>
                     <div>This method returns the related UM in one selected related UM.</div>
                  </p>

                  <p>
                     <pre>{{ 'UnitMeasurement::select_um_array($multi_id, $return);' }}</pre>
                     <div><c>Parameters</c> are as follows:</div>
                        <ul>
                           <li>multi_id (int) <i>ID of Selected UM</i></li>
                           <li>return (string) <i>array or json</i></li>
                        </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <i>Unit of Measurement Array</i>
                     <ul>
                        <li>multi_id (int) <i>The ID of UM.</i></li>
                        <li>multi_name (string) <i>The name of the Related UM.</i></li>
                        <li>multi_abbrev (string) <i>The abbreviation used for the Related UM.</i></li>
                        <li>unit_qty (int) <i>The Quantity of Related UM.</i></li>
                     </ul>
                  </p>
                  <!-- 6 -->
                  <p>
                     <h4 class="subtitle">F. CONVERT QUANTITY TO ITS BASED UM AND SELECTED UM</h4>
                     <div class="gray-author">by Arcylen Gutierrez (PHP/Laravel)</div>
                  </p>
    
                  <p>
                     <div>This method convert the quantity into string based on its UM</div>
                  </p>

                  <p>
                     <pre>{{ 'UnitMeasurement::um_view($qty, $um_based_id, $um_issued_id);' }}</pre>
                     <div><c>Parameters</c> are as follows:</div>
                        <ul>
                           <li>qty (int) <i>Total quantity of an item per piece</i></li>
                           <li>um_based_id (int) <i>The ID of its based UM (Tbl_item:item_measurement_id)</i></li>
                           <li>um_issued_id (int) <i>The ID of um where you want it to convert</i></li>
                        </ul>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>{{ '$return = UnitMeasurement::um_view($qty, $um_based_id, $um_issued_id);' }} (string) <i>The result of conversion.</i></li>
                     </ul>
                  </p>

               </div>
            </div>
         </div>  
         <!-- audit trail -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>20. Audit Trail</b></h3>
                  <!-- 1 -->
                  <p>
                     <h4 class="subtitle">A. Insert Audit History</h4>
                     <div class="gray-author">by Arcylen Gutierrez (PHP/Laravel)</div>
                  </p>

                  <p>
                     <div>
                        Dependencies:
                        <div><pre>{{ 'use App\Globals\AuditTrail;' }}</pre></div>
                     </div>
                  </p>
    
                  <p>
                     <div>This method insert log in every transaction.</div>
                  </p>
                  <p>
                     <pre>{{'AuditTrail::record_logs($action, $source, $source_id, $old_data, $new_data);' }}</pre>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>Action (string) <span class="blue">required</span> <i>"Action (Added, Edited, Logged In and etc.)"</i></li>
                        <li>Source (string) <span class="blue"></span> <i>"Type of transaction (invoice, purchase and etc.)"</i></li>
                        <li>Source ID (integer) <span class="blue"></span> <i>"ID of transaction"</i></li>
                        <li>Old Data (text) <span class="blue"></span> <i>"Serialized Array of old data"</i></li>
                        <li>New Data (text) <span class="blue"></span> <i>"Serialized Array of new data"</i></li>
                     </ul>
                  </p>


                  <p>
                     <h4 class="subtitle">B. GET DETAILS IN AUDIT HISTORY</h4>
                     <div class="gray-author">by Arcylen Gutierrez (PHP/Laravel)</div>
                  </p>
    
                  <p>
                     <div>This method returns the details Audit history.</div>
                  </p>

                  <p>
                     <pre>{{ 'AuditTrail::getAudit_data();' }}</pre>
                     <div><c>Returned information</c> are as follows:</div>
                     <ul>
                        <li>created_at (datetime) <i>DateTime of transaction.</i></li>
                        <li>user (string) <i>User Name</i></li>
                        <li>action (string) <i>The action made by the user</i></li>
                        <li>transaction_txt (string) <i>The source and source_id of the transaction.</i></li>
                        <li>transaction_client (string) <i>The transaction client (customer, vendor and etc.)</i></li>
                        <li>transaction_amount (double) <i>The amount affected in the transaction</i></li>
                     </ul>
                  </p>
               </div>
            </div>
         </div>  
         <!-- audit trail end -->

         <!-- TUTORIAL GROUP -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>21. Standard Transaction Types</b></h3>
                  <!-- 1 -->
                  <p>
                     <h4 class="subtitle">Transaction Type Name</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (DATABASE)</div>
                  </p>
                  <p>
                     <ul>
                        <li>Under Development</li>
                     </ul>
                  </p>
               </div>
            </div>
         </div>  

         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>22. Email Content</b></h3>
                  <!-- 1 -->
                  <p>
                     <h4 class="subtitle">A. Change Email Content</h4>
                     <div class="gray-author">by Arcylen Gutierrez (PHP/Laravel)</div>
                  </p>

                  <p>
                     <div>
                        Dependencies:
                        <div><pre>{{ 'use App\Globals\EmailContent;' }}</pre></div>
                     </div>
                  </p>
    
                  <p>
                     <div>This method returns the email content to be send.</div>
                  </p>
                  <p>
                     <pre>{{'EmailContent::email_txt_replace($content_key, $change_content);' }}</pre>
                     <div><c>Parameters</c> are as follows:</div>
                     <ul>
                        <li>Content Key (string) <i>Email Content to be change</i></li>
                        <li>Change Content (array) <i></i></li> 
                        <ul>
                           <div>0</div>
                           <li>txt_to_be_replace (string)</li>
                           <li>txt_to_replace (string)</li>
                        </ul>
                        <ul>
                           <div>1</div>
                           <li>txt_to_be_replace (string)</li>
                           <li>txt_to_replace (string)</li>
                        </ul>
                     </ul>
                  </p>
               </div>
            </div>
         </div>  

         <!-- TUTORIAL GROUP -->
         <div class="panel panel-default panel-block">
            <div class="list-group">
               <div class="list-group-item" id="getting-started">
                  <h3 class="section-title"><b>23. Global Paginate</b></h3>
                  <!-- 1 -->
                  <p>
                     <h4 class="subtitle">INCLUDE JAVASCRIPT</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (Javascipt)</div>
                  </p>
                  <p>
                     <div>
                        <div><pre>{{ '<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>' }}</pre></div>
                     </div>
                  </p>
                  <div>Include this to your file</div>
<!--                   <div>
                     ATTRIBUTES:
                     <ul>
                        <li>"LINK" is the link of the page the developer would like to load in the modal form.</li>
                        <li>"SIZE" can <i>sm</i>,<i>md</i> or <i>lg</i>. This refers to the size of the form that the developer would like to load.</li>
                     </ul>
                  </div> -->

                  <hr>

                  <p>
                     <h4 class="subtitle">USING HTML CLASS AND ATTRIBUTES</h4>
                     <div class="gray-author">by Bryan Kier Aradanas (Javascipt)</div>
                  </p>
                  <p>
                     <div>
                        <div><pre>{{ $documentation_paginate_html }}</pre></div>
                     </div>
                  </p>
                  <div>Put all the contents in a div with a unique <span class="text-success text-bold">id</span> attribute. And the div inside div with a class of <span class="text-success text-bold">.load-data</span><div>
                     <div>
                        ATTRIBUTES:
                        <ul>
                           <li>"CLASS : load-data" div container for the pagination</li>
                           <li>"TARGET" specify the target div id for reloading the content.</li>
                        </ul>
                     </div>
                  <div> You can also add a url query on the ajax request, Just add it as an attribute in the container. Ex : <span class="text-success text-bold">{{ '<div class="load-data" target="data-value" filter="status" orderby="asc">' }}</span> , the result of ajax request is ___?filter=status&&orderby=asc  </div>

               </div>
            </div>
         </div>  

      </div>
   </div>
</div>

@endsection
<style type="text/css">
   c
   {
      color: #758F27;
      font-weight: bold;
   }
   i
   {
      color: gray;
   }
   .gray-author
   {
      color: #aaa;
      margin-top: -10px;
   }
   .red
   {
      color: red;
   }
   .side-nav.sticky
   {
      position: fixed;
      top: 25px;
   }
   .doc-nav-title
   {
      font-size: 14px;
   }
   .doc-nav
   {
      display: block;
      font-size: 14px;
      cursor: pointer;
      color: #777;
      margin-left: 20px;
   }
   .doc-nav:hover
   {
      color: #000;
   }

</style>
@section('script')
<script type="text/javascript">
$(document).ready(function()
{
   var tops = $(window).scrollTop();
   var width = $(".side-nav").width();
   $(".wrapper").scroll(function()
   {
      var tops = $(".wrapper").scrollTop();

      if(tops > 145)
      {

         $(".side-nav").addClass("sticky");
         $(".side-nav").width(width);
      }
      else
      {
         $(".side-nav").removeClass("sticky");
      }
   });

   //NAVIGATION CREATION

   $(".section-title").each(function(key)
   {
      var title = $(this).text();
      var rand = getRandomizer(1, 999999);
      $(this).attr("id", rand);
      $append = '<a href="#' + rand + '" class="doc-nav"> ' + $(this).text() + '</a>';

      $(".doc-nav-container").append($append);
   });

})


function getRandomizer(bottom, top)
{
   return Math.floor( Math.random() * ( 1 + top - bottom ) ) + bottom;
}
</script>

<script type="text/javascript">
    $(".drop-down-customer").globalDropList(
        {
            link: '/member/customer/modalcreatecustomer',
            link_size: 'lg',
            placeholder: 'Customer'
        });
    $(".drop-down-coa").globalDropList(
        {
            link: '/member/accounting/chart_of_account/popup/add',
            link_size: 'md',
            placeholder: 'Chart of Account'
        });

    $(".drop-down-category").globalDropList(
        {
            link: '/member/item/category/modal_create_category',
            link_size: 'md',
            placeholder: 'Category'
        });
    $(".drop-down-item").globalDropList(
         {
             link: '/member/item/add',
             link_size: 'lg',
             maxHeight: "309px",
             placeholder: 'Item'
         });
    $(".drop-down-vendor").globalDropList(
         {
             link: '/member/vendor/add',
             link_size: 'lg',
             placeholder: 'Vendor'
         });
</script>
@endsection