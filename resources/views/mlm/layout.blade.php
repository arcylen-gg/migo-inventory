@if (File::exists(base_path('resources/views/mlm/layout/'.$shop_info->member_layout.'.blade.php')))
   @include('mlm.layout.'.$shop_info->member_layout.'')
@else
  @include('mlm.layout.default')
@endif
