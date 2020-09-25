<<<<<<< HEAD
<<<<<<< HEAD
      <!-- GLOBAL SUBMIT -->
      <div class="panel panel-default panel-block">
         <div class="list-group">
            <div class="list-group-item" id="getting-started">
               <h3 class="section-title"><b>GLOBAL SUBMIT</b></h3>
               <!-- USING HOW TO USE GLOBAL SUBMIT -->
               <p>
                  <h4>HOW TO USE GLOBAL SUBMIT</h4>
               </p>
               <pre>{{ $html_code_global_submit }}</pre>
               <p>
                  <div>LANGUAGE: HTML</div>
                  <div>FUNCTION: Once you add a class <span class="text-success text-bold">.global-submit</span> to a {{ '<form>' }}. It will automatically submit using ajax.</div>
                  <div>AUTHOR: Guillermo Tabligan</div>
               
               </p>
               <!-- CONTROLLER -->
               <hr>
               <p>
                  <h4>HANDLING SUBMIT IN THE CONTROLLER</h4>
               </p>
               <pre>{{ $html_code_global_submit_controller }}</pre>
               <p>
                  <div>LANGUAGE: PHP (LARAVEL)</div>
                  <div>FUNCTION: This is a templated code for the developer to use in his/her controller. The developer can also include addiitonal data on the response and he/she can use it on the callback.</div>
                  <div>AUTHOR: Guillermo Tabligan</div>
               </p>
               <!-- SUBMIT DONE CALLBACK -->
               <hr>
               <p>
                  <h4>CALL BACK AFTER SUBMIT</h4>
               </p>
               <pre>{{ $html_code_global_submit_done }}</pre>
               <p>
                  <div>LANGUAGE: JAVASCRIPT</div>
                  <div>FUNCTION: Use this to call additional functionalities once submission is done. You can check the response status and return data from the <span class="text-success text-bold">$response</span> in the previous subject.</div>
                  <div>AUTHOR: Guillermo Tabligan</div>
                  <div>
                     <span style="color: red;">ISSUES AND BUGS:</span>
                     <ul>
                        <li>If there is an error on form submition. The function will retry until the page is refreshed.</li>
                     </ul>
                  </div>
               </p>
=======
=======
>>>>>>> 136b08150bec7502341828c86aac2958aa3b79c5