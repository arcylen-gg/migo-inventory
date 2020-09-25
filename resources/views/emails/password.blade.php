@if(isset($template))
    @include('emails.header', $template)
@endif
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title></title>
        <style></style>
    </head>
    <body>
        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
            <tr>
                <td align="center" valign="top">
                    <table border="0" cellpadding="20" cellspacing="0" width="600" id="emailContainer">
                        <tr>
                            <td align="left" valign="top">
                                    <div style="background-color: #557DA1; color: #ffffff; font-family: 'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size: 30px;font-weight: 300;line-height: 150%;margin: 0;text-align: left;padding: 36px 48px;display: block;">
                                            Thank you for your purchasing
                                    </div>
                                    <div style="padding: 48px;">
                                           <p> Your account has been created by purchasing to our site. Click <a href="{{ URL::to('/') }}">here</a> to login and change your password.</p>
                                            <p>Password : {{ $account_password }}</p>
                                    </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
@if(isset($template))
	<br>
    @include('emails.footer', $template)
@endif