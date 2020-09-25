@section('content')
@extends('layout')
<div class="container-fluid">
   <div class="events">
      <div style="font-size: 4.2rem;
    line-height: 1.19;
    border-bottom: 0.1rem solid rgba(0,0,0,0.15);
    padding-bottom: 2rem;
    padding-top: 1.2rem;
    margin-bottom: 20px;
    font-weight: 300;">{{ $post->post_title }}</div>
      <table style="width: 100%; table-layout: fixed;">
         <tbody>
            <tr>
               <td class="main-event" style="overflow: auto;">
                  <div class="img">
                     <img src="{{ $post->post_image }}">
                  </div>
                  <div class="desc">{!! $post->post_content !!}</div>
               </td>
               <td class="side-event">
                  @if(count($_related) > 1)
                  <?php $i = 0; ?>
                  @foreach($_related as $related)
                     @if($post->post_id != $related->post_id)
                        @if($i == 0)
                        <div class="holder first" onClick="location.href='/events/view/{{ $related->main_id }}'" style="cursor: pointer;">
                           <div class="img">
                              <img class="cova" src="{{ $related->post_image }}">
                           </div>
                           <div class="side-content">{{ $related->post_title }}</div>
                        </div>
                        @else
                        <table class="holder" onClick="location.href='/events/view/{{ $related->main_id }}'" style="cursor: pointer;">
                           <tbody>
                              <tr>
                                 <td class="img">
                                    <img style="height: 75px; width: 100%; object-fit: cover;" src="{{ $related->post_image }}">
                                 </td>
                                 <td class="side-content">{{ $related->post_title }}</td>
                              </tr>
                           </tbody>
                        </table>
                        @endif
                        <?php $i++; ?>
                     @endif
                  @endforeach
                  @else
                  <div class="holder text-center">No related Events</div>
                  @endif
               </td>
            </tr>
            <tr>
               <td class="text-center" colspan="2">
                  <button type="button" onClick="location.href='/events'" class="btn back">&laquo; Go Back</button>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
</div>
@endsection
@section('css')
<link rel="stylesheet" href="resources/assets/frontend/css/events-view.css?version=1">
<style type="text/css">
@media screen and (max-width: 991px)
{
   table tbody, table tr
   {
      display: block;
   }
}
.cova
{
   object-fit: cover !important;
   max-width: 100%;
}
</style>
@endsection
@section('script')
<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
<script type="text/javascript" src="resources/assets/frontend/js/events.js"></script>
@endsection