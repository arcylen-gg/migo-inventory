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
                                <h2>Dear {{ $customer_full_name }}</h2>
                            	<h3>Payment Details:</h3>
                                <p style="padding-left: 20px; white-space: pre-wrap;">{{ $payment_detail }}</p>
                                <p>Click <a style="cursor: pointer;" href="{{ URL::to('checkout/payment/upload') }}?id={{ $order_id }}">here</a> to complete transaction by uploading your proof of payment.</p>
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