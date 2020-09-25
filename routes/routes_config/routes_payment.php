<?php
/* Payment Integration with Dragon Pay */
Route::get('/payment/dragonpay', 'PaymentController@index');
Route::post('/payment/dragonpay', 'PaymentController@onSubmitPayment');
Route::any('/payment/dragonpay/postback', 'Shop\ShopPaymentFacilityController@dragonpay_postback');
Route::any('/payment/dragonpay/logs', 'Shop\ShopPaymentFacilityController@dragonpay_logs');
Route::get('/payment/dragonpay/return', 'Shop\ShopPaymentFacilityController@dragonpay_return');
/* End Dragon Pay */

/* Payment Integration with iPay88 */
Route::any("/payment/ipay88/response","Shop\ShopCheckoutController@ipay88_response");
Route::any("/payment/ipay88/backend","Shop\ShopCheckoutController@ipay88_backend");
/* End iPay88 */

/* Payment Integration with Paymaya */
Route::any('/payment/paymaya/webhook/success', 'Shop\ShopPaymentFacilityController@paymaya_webhook_success');
Route::any('/payment/paymaya/webhook/failure', 'Shop\ShopPaymentFacilityController@paymaya_webhook_failure');
Route::any('/payment/paymaya/webhook/cancel', 'Shop\ShopPaymentFacilityController@paymaya_webhook_cancel');
/* End Paymaya */