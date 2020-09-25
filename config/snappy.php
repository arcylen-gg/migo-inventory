<?php
// 'binary'  => base_path('vendor/h4cc/wkhtmltopdf-i386/bin/wkhtmltopdf-i386')
// palit mo to dun sa
// 'binary' => '"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf"'
return array(


   'pdf' => array(
       'enabled' => true,
       'binary'  => base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64'),
       'timeout' => false,
       'options' => array(),
       'env'     => array(),
   ),
   'image' => array(
       'enabled' => true,
       'binary'  => base_path('vendor/h4cc/wkhtmltoimage-amd64/bin/wkhtmltoimage-amd64'),
       'timeout' => false,
       'options' => array(),
       'env'     => array(),
   ),


);
