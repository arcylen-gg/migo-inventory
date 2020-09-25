<?php

namespace App\Globals\Dragonpay2;

class RequestForm
{
	public static function render($fieldValues, $paymentUrl)
	{
		echo "<form id='autosubmit' action='".$paymentUrl."' method='get'>";
		if (is_array($fieldValues) || is_object($fieldValues))
		{
			foreach ($fieldValues as $key => $val) {
			    echo "<input type='hidden' name='".ucfirst($key)."' value='".htmlspecialchars($val)."'>";
			}
		}
		echo "</form>";
		echo "
		<script type='text/javascript'>
		    function submitForm() {
		        document.getElementById('autosubmit').submit();
		    }
		    window.onload = submitForm;
		</script>

		";		

	}
	
	public static function render_post($fieldValues, $paymentUrl)
	{
		echo "<form id='autosubmit' action='".$paymentUrl."' method='post'>";
		if (is_array($fieldValues) || is_object($fieldValues))
		{
			foreach ($fieldValues as $key => $val) {
			    echo "<input type='hidden' name='".ucfirst($key)."' value='".htmlspecialchars($val)."'>";
			}
		}
		echo "</form>";
		echo "
		<script type='text/javascript'>
		    function submitForm() {
		        document.getElementById('autosubmit').submit();
		    }
		    window.onload = submitForm;
		</script>

		";		
	}
}
