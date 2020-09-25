var googleUser = {};
var startApp = function() 
{
    gapi.load('auth2', function()
    {
      // Retrieve the singleton for the GoogleAuth library and set up the client.
      auth2 = gapi.auth2.init(
      {
        client_id: $('.google_app_id').val(),
        cookiepolicy: 'single_host_origin',
        // Request scopes in addition to 'profile' and 'email'
        scope: 'profile email'
      });
      attachSignin(document.getElementById('customBtn'));
    });
  };
   function attachSignin(element) 
   {
    // console.log(element.id);
    auth2.attachClickHandler(element, {},
        function(googleUser) 
        {
        	sign_in(googleUser);
          // document.getElementById('name').innerText = "Signed in: " +
          //     googleUser.getBasicProfile().getName();
        },
        function(error) 
        {
          console.log(JSON.stringify(error, undefined, 2));
        });
  }
function sign_in(googleUser)
{
	// console.log(googleUser);
    // Useful data for your client-side scripts:
    var profile = googleUser.getBasicProfile();

    // The ID token you need to pass to your backend:
    var id_token = googleUser.getAuthResponse().id_token;
    // console.log("ID Token: " + id_token);
	// location.href = '/members/login-google-submit';

	var data = [];
	data['access_token'] = id_token;
	data['id'] = profile.getId();
	data['first_name'] = profile.getGivenName();
	data['last_name'] = profile.getFamilyName();
	data['email'] = profile.getEmail();

	push_data = JSON.stringify(data);
	$.ajax({
		url : '/members/login-google-submit',
		type : 'POST',
		dataType : 'json',
		data : { id : data['id'],first_name : data['first_name'],access_token : data['access_token'],last_name : data['last_name'],email : data['email'], _token : $('#_token').val()},
		success : function(res)
		{
			if(res == 'success')
			{
				location.href = "/members";
			}
		}
	});
}
