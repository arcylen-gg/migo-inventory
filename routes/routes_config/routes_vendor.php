<?php

/* MEMBER - VENDOR  */
Route::any('/member/vendor/list', 'Member\Vendor_ListController@index'); //GUILLERMO TABLIGAN
/* END MEMBER - VENDOR - GUILLERMO TABLIGAN */

/* MEMBER - DEVELOPER  */
Route::any('/member/developer/status', 'Member\Developer_StatusController@index'); //GUILLERMO TABLIGAN
Route::any('/member/developer/rematrix', 'Member\Developer_RematrixController@index'); //ERWIN GUEVARRA
Route::any('/member/developer/documentation', 'Member\Developer_DocumentationController@index'); //EVERYONE
/* END MEMBER - VENDOR - GUILLERMO TABLIGAN */