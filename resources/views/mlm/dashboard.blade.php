@if (File::exists(base_path('resources/views/mlm/layout_dashboard/'.$shop_info->member_layout.'.blade.php')))
   @include('mlm.layout_dashboard.'.$shop_info->member_layout.'')
@else
  @include('mlm.layout_dashboard.default')
@endif
