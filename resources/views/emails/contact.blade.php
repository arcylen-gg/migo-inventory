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
                            <td align="center" valign="top">
                            	<h2>Contact Details</h2>
                                <div><strong>Name:</strong> {{ $mail_first_name }} {{ $mail_last_name }}</div>
                                <div><strong>Phone:</strong> {{ $mail_phone_number }}</div>
                                <div><strong>Email:</strong> {{ $mail_email_address }}</div>
                                <div><strong>Subject:</strong> {{ $mail_subject }}</div>
                                <div><strong>Message:</strong> {{ $mail_message }}</div>
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