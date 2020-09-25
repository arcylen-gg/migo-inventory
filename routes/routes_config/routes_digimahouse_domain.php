<?php
if(($domain != "digimahouse.com" && $domain != "digimahouse.dev" && $domain != "digimatest.com") || (hasSubdomain() && $domain != "my168shop-primia.c9users.io"))
{
	Route::get('/', 'Shop\ShopHomeController@index');
	Route::get('/cart', 'Shop\ShopCartController@index');
	Route::get('/mini_cart', 'Shop\ShopCartController@mini_cart');
	Route::get('/cart/quick', 'Shop\ShopCartController@quick_cart');
	Route::get('/cart/add', 'Shop\ShopCartController@add_cart');
	Route::get('/cart/remove', 'Shop\ShopCartController@remove_cart');
	Route::get('/cart/update', 'Shop\ShopCartController@update_cart');
	Route::get('/cart/clear', 'Shop\ShopCartController@clear_cart');
	Route::get('/product', 'Shop\ShopProductController@index');
	Route::get('/product/view/{id}', 'Shop\ShopProductContentController@index');
	Route::get('/product/view2/{id}', 'Shop\ShopProductContent2Controller@index');
	Route::get('/product/variant', 'Shop\ShopProductContentController@variant');
	Route::get('/product/search', 'Shop\ShopProductContentController@search');
	Route::get('/about', 'Shop\ShopAboutController@index');
	Route::get('/contact', 'Shop\ShopContactController@index');
	Route::post('/contact', 'Shop\ShopContactController@contact_submit');
	Route::get('/contact/find_store', 'Shop\ShopContactController@find_store'); //EDWARD GUEVARRA
	Route::any('/career', 'Shop\ShopCareerController@index'); //EDWARD GUEVARRA
	Route::get('/career/success', 'Shop\ShopCareerController@success'); //EDWARD GUEVARRA
	Route::get('/how', 'Shop\ShopHowController@index'); //EDWARD GUEVARRA
	Route::get('/youwin', 'Shop\ShopYouWinController@index'); //EDWARD GUEVARRA

	Route::get('/events', 'Shop\ShopEventsController@index'); //EDWARD GUEVARRA
	Route::get('/events/view/{id}', 'Shop\ShopEventsController@view'); //EDWARD GUEVARRA
	Route::any('/events/view_details','Shop\ShopEventsController@view_details'); // ARCY
	Route::any('/events/event_reserve','Shop\ShopEventsController@event_reserve'); // ARCY
	Route::any('/events/event_reserve_submit','Shop\ShopEventsController@event_reserve_submit'); // ARCY

	Route::get('/policy', 'Shop\ShopPolicyController@index'); //EDWARD GUEVARRA
	Route::get('/testimony', 'Shop\ShopTestimonyController@index'); //EDWARD GUEVARRA
	Route::get('/runruno', 'Shop\ShopAboutController@runruno'); //EDWARD GUEVARRA
	Route::get('/news', 'Shop\ShopAboutController@news'); //EDWARD GUEVARRA
	Route::get('/email_payment', 'Shop\ShopAboutController@email_payment'); //EDWARD GUEVARRA
	Route::get('/contactus', 'Shop\ShopAboutController@contactus'); //EDWARD GUEVARRA
	Route::get('/jobs', 'Shop\ShopAboutController@jobs'); //EDWARD GUEVARRA
	Route::get('/job', 'Shop\ShopAboutController@job'); //EDWARD GUEVARRA
	Route::post('/job/submit', 'Shop\ShopAboutController@job_submit');
	Route::get('/term','Shop\ShopTermsController@index');

	Route::get('/promos', 'Shop\ShopAboutController@promos'); //MARK FIGS
	Route::get('/promo_view', 'Shop\ShopAboutController@promo_view'); //MARK FIGS
	Route::get('/history', 'Shop\ShopAboutController@history'); //MARK FIGS
	Route::get('/how_to_join', 'Shop\ShopAboutController@how_to_join'); //MARK FIGS
	Route::get('/3xcell_login', 'Shop\ShopAboutController@xcell_login'); //MARK FIGS
	Route::get('/3xcell_signup', 'Shop\ShopAboutController@xcell_signup'); //MARK FIGS
	Route::get('/about_red_fruit', 'Shop\ShopAboutController@about_red_fruit'); //MARK FIGS

	Route::get('/gallery', 'Shop\ShopGalleryController@gallery'); //MARK FIGS
	Route::get('/gallery_content/{id}', 'Shop\ShopGalleryController@gallery_content'); //MARK FIGS
	
	Route::get('/mycart', 'Shop\ShopMyCartController@MyCart'); //MARK FIGS
	Route::get('/item_checkout', 'Shop\ShopItemCheckoutController@item_checkout'); //MARK FIGS
	Route::get('/item_payment', 'Shop\ShopItemPaymentController@item_payment'); //MARK FIGS
	Route::get('/payment_success', 'Shop\ShopItemPaymentController@payment_success'); //MARK FIGS
	Route::get('/replicated', 'Shop\ShopAboutController@replicated'); //MARK FIGS
	Route::get('/terms_and_conditions', 'Shop\ShopAboutController@terms_and_conditions'); //MARK FIGS



	Route::any('/signin', 'Shop\ShopLoginController@signin'); //ROMMEL C.
	Route::any('/logout', 'Shop\ShopMemberController@logout');
	Route::any('/thank_you','Shop\ShopLoginController@thank_you');//PRESS RELEASE
	Route::post('/contactus', 'Shop\ShopContactController@contact_us');
	Route::any('/contactus/send', 'Shop\ShopMemberController@send_contact_us');
	Route::any('/demo/send', 	  'Shop\ShopMemberController@send_demo');
	Route::any('/sign_up', 'Shop\ShopRegisterController@press_signup'); //PRESS RELEASE
	Route::get('/pressuser', 'Shop\ShopMemberController@pressuser'); //PRESS RELEASE
	Route::get('/pressuser/mypressrelease/pressrelease/view/{pid}', 'Shop\ShopMemberController@pressuser_view'); //PRESS RELEASE
	Route::get('/pressuser/dashboard', 'Shop\ShopMemberController@pressuser_dashboard'); //PRESS RELEASE

	Route::any('/pressuser/pressrelease', 'Shop\ShopMemberController@pressuser_pressrelease'); //PRESS RELEASE
	Route::any('/pressuser/pressrelease/recipient', 'Shop\ShopMemberController@pressuser_pressrelease_recipient'); //PRESS RELEASE

	Route::any('/pressrelease/track/open', 'Shop\ShopMemberController@press_release_track_open');

	Route::any('/pressuser/pressrelease/recipient/search', 'Shop\ShopMemberController@pressuser_pressrelease_recipient_search'); //PRESS RELEASE
	Route::any('/pressuser/pressrelease/pr','Shop\ShopMemberController@send_pr');
	Route::any('/pressuser/mypressrelease', 'Shop\ShopMemberController@pressuser_my_pressrelease'); //PRESS RELEASE
	Route::any('/pressuser/pressrelease/delete_draft/{pid}','Shop\ShopMemberController@pressuser_delete_draft');
	Route::any('/pressuser/pressrelease/edit_draft/{pid}','Shop\ShopMemberController@pressuser_edit_draft');
	Route::any('/pressuser/pressrelease/draft','Shop\ShopMemberController@press_release_save_as_draft');
	Route::any('/pressuser/drafts','Shop\ShopMemberController@press_user_drafts');
	Route::any('/pressuser/image_upload', 'Shop\ShopMemberController@pressreleases_image_upload');//PRESS RELEASE
	Route::any('/pressuser/manage_user','Shop\ShopMemberController@press_user_manage_user');
	Route::get('/pressadmin', 'Shop\ShopMemberController@pressadmin'); //PRESS RELEASE
	Route::get('/pressadmin/dashboard', 'Shop\ShopMemberController@pressadmin_dashboard'); //PRESS RELEASE
	Route::get('/pressadmin/dashboard/view', 'Shop\ShopMemberController@pressadmin_dashboard_view'); //PRESS RELEASE
	Route::get('/pressadmin/mediacontacts', 'Shop\ShopMemberController@pressadmin_media_contacts'); //PRESS RELEASE
	Route::any('/pressadmin/manage_user/add_admin', 'Shop\ShopMemberController@manage_user_add_admin'); //PRESS RELEASE
	Route::any('/pressadmin/manage_user/delete_admin/{id}', 'Shop\ShopMemberController@manage_user_delete_admin'); //PRESS RELEASE
	Route::any('/pressadmin/manage_user', 'Shop\ShopMemberController@manage_user'); //PRESS RELEASE
	Route::get('/pressadmin/email',				'Shop\ShopMemberController@pressadmin_email'); //PRESS RELEASE
	Route::any('/pressadmin/manage_user_search',	'Shop\ShopMemberController@manage_user_search');//PRESS RELEASE
	Route::get('/pressadmin/edit_user/{id}','Shop\ShopMemberController@edit_user'); //PRESS RELEASE
	Route::any('/pressadmin/manage_user_edit','Shop\ShopMemberController@pressadmin_manage_user_edit'); //PRESS RELEASE
	Route::get('/pressadmin/delete_user/{id}','Shop\ShopMemberController@manage_user_delete_user'); //PRESS RELEASE
	Route::any('/pressadmin/manage_force_login/{id}','Shop\ShopMemberController@pressadmin_manage_force_login'); //PRESS RELEASE
	Route::get('/pressadmin/edit_admin/{id}','Shop\ShopMemberController@edit_admin'); //PRESS RELEASE
	Route::any('/pressadmin/manage_admin_edit','Shop\ShopMemberController@pressadmin_manage_admin_edit'); //PRESS RELEASE
	Route::any('/pressadmin/email_save','Shop\ShopMemberController@pressadmin_email_save'); //PRESS RELEASE
	Route::any('/pressadmin/email_edit/{id}','Shop\ShopMemberController@pressadmin_email_edit'); //PRESS RELEASE
	Route::get('/pressadmin/email_delete/{id}', 'Shop\ShopMemberController@email_delete'); //PRESS RELEASE
	Route::get('/pressuser/analytics', 'Shop\ShopMemberController@press_release_analytics');
	Route::get('/pressuser/analytics/view', 'Shop\ShopMemberController@press_release_analytics_campaign');
	Route::get('/pressuser/analytics/view/all', 'Shop\ShopMemberController@press_release_analytics_view_all');
	Route::get('/pressuser/media_contacts', 'Shop\ShopMemberController@pressuser_media_contacts');
	Route::any('/pressuser/media_contacts/add', 'Shop\ShopMemberController@pressuser_media_contacts_add');
	Route::any('/pressuser/media_contacts/delete/{id}', 'Shop\ShopMemberController@pressuser_media_contacts_delete');


	Route::post('/pressadmin/pressreleases/recipient', 'Shop\ShopMemberController@pressadmin_pressreleases_recipient'); //PRESS RELEASE
	Route::any('/pressadmin/pressreleases_addrecipient', 'Shop\ShopMemberController@pressadmin_pressrelease_addrecipient');//PRESS RELEASE
	Route::any('/pressadmin/pressreleases_deleterecipient/{id}', 'Shop\ShopMemberController@pressreleases_deleterecipient');//PRESS RELEASE
	Route::any('/pressadmin/pressreleases_edit_recipient/{id}', 'Shop\ShopMemberController@pressreleases_edit_recipient');//PRESS RELEASE
	Route::get('/newsroom',			'Shop\ShopNewsRoomController@index');//PRESS RELEASE
	Route::any('/newsroom/search',	'Shop\ShopNewsRoomController@newsroom_search');//PRESS RELEASE
	Route::any('/mediacontacts/search',	'Shop\ShopMemberController@mediacontacts_search');//PRESS RELEASE
	Route::get('/newsroom/view/{pid}','Shop\ShopNewsRoomController@news_room_view');//PRESS RELEASE

	Route::get('/pressuser/choose_recipient', 'Shop\ShopMemberController@pressuser_choose_recipient');
	// Route::get('/pressrelease/view','Shop\ShopMemberController@press_email');//PRESS RELEASE

	
	
	Route::get('/blog', 'Shop\ShopBlogController@index');
	Route::get('/blog/content', 'Shop\ShopBlogContentController@index');
	Route::get('/payment', 'Shop\ShopPaymentController@index');
	Route::get('/order_placed', 'Shop\ShopCheckoutController@order_placed');
	Route::get('/addto_cart', 'Shop\ShopCheckoutController@addtocart');
	Route::get('/file/{theme}/{type}/{filename}', 'Shop\Shop@file');
	/*Product search*/
	Route::get('/product_search', 'Shop\ShopSearchController@index');
	/*End Product search*/

	/* Checkout */
	Route::any('/checkout/login', 'Shop\ShopCheckoutLoginController@index');
	Route::get('/checkout', 'Shop\ShopCheckoutController@index');
	Route::get('/checkout/side', 'Shop\ShopCheckoutController@checkout_side');
	Route::get('/checkout/locale', 'Shop\ShopCheckoutController@locale');
	Route::get('/checkout/session', 'Shop\ShopCheckoutController@session');
	Route::post('/checkout', 'Shop\ShopCheckoutController@submit');
	Route::any('/checkout/payment', 'Shop\ShopCheckoutController@payment');
	Route::any('/checkout/payment/upload', 'Shop\ShopCheckoutController@payment_upload');
	Route::get('/checkout/method', 'Shop\ShopCheckoutController@update_method');
	/* End Checkout */

	/* Wishlist */
	Route::get('/wishlist/add/{id}', 'Shop\ShopWishlistController@add');
	Route::get('/wishlist/remove/{id}', 'Shop\ShopWishlistController@remove');

	/* Login E-commerce */
	Route::get('/account', 'Shop\ShopAccountController@index');
	Route::get('/account/order', 'Shop\ShopAccountController@order');
	Route::get('/account/wishlist', 'Shop\ShopAccountController@wishlist');
	Route::get('/account/settings', 'Shop\ShopAccountController@settings');
	Route::post('/account/settings', 'Shop\ShopAccountController@settings_submit');
	Route::get('/account/security', 'Shop\ShopAccountController@security');
	Route::post('/account/security', 'Shop\ShopAccountController@security_submit');
	Route::get('/account/invoice/{id}', 'Shop\ShopAccountController@invoice');
	Route::get('/account/logout', 'Shop\ShopAccountController@logout');
	Route::post('/account/login', 'Shop\ShopLoginController@submit');

	/*E-commerce registration*/
	Route::get('/account/register', 'Shop\ShopAccountController@account_register'); //Brain
	Route::post('/account/register', 'Mlm\MlmRegisterController@register_ecomm'); //Brain

	/* Others */
	Route::get('/partners', 'Shop\ShopPartnersController@index');
	Route::get('/partners_views', 'Shop\ShopPartnersController@partners_views');
	Route::get('/partner-filtering-location', 'Shop\ShopPartnersController@partnerFilterByLocation');
	Route::get('/legalities', 'Shop\ShopLegalitiesController@index');


	Route::get('/manual_checkout', 'Shop\ShopManualCheckout@index');
	Route::post('/manual_checkout', 'Shop\ShopManualCheckout@submit_proof');
	Route::get('/manual_checkout/success', 'Shop\ShopManualCheckout@success');

	/* Cart V2 */
	Route::get('/cartv2', 'Shop\ShopCart2Controller@index');
	Route::get('/cartv2/add', 'Shop\ShopCart2Controller@add_cart');
	Route::get('/cartv2/remove', 'Shop\ShopCart2Controller@remove_cart');
	Route::get('/cartv2/update', 'Shop\ShopCart2Controller@update_cart');
	Route::get('/cartv2/clear', 'Shop\ShopCart2Controller@clear_cart');
	Route::get('/cartv2/quantity', 'Shop\ShopCart2Controller@quantity_cart');
	Route::get("/cartv2/buy_kit_mobile/{id}", 'Shop\ShopCart2Controller@buy_kit_mobile');

	/* Return Policy */
	Route::get('/return_policy', 'Shop\ShopAboutController@return_policy'); 
	Route::get('/privacy_policy', 'Shop\ShopAboutController@privacy_policy');
	Route::get('/franchise_terms_and_condition', 'Shop\ShopAboutController@franchise_terms_and_condition');
	Route::get('/general_terms_and_condition', 'Shop\ShopAboutController@general_terms_and_condition'); 

	/* Alternative */
	Route::get('/product2', 'Shop\ShopProductController@product2'); //Carlo
}