@extends("press_user.member")
@section("pressview")
<div class="background-container">
    <div class="pressview">
       <div class="drafts-holder-container">
         <div class="title-container"></div>
            <div class="title-container">
                @foreach($_user as $user_name)
              <div class="col-md-2">First Name:</div>
              <div class="col-md-4">
              
                 <input type="text" id="" name="" class="form-control" value="{{$user_name->user_first_name}}" readonly>
              </div>
              
              <div class="col-md-2">Last Name:</div>
              <div class="col-md-4">
                 <input type="text" id="" name="" class="form-control"  value="{{$user_name->user_last_name}}" readonly>
              </div>
               <div class="col-md-2">Company Name:</div>
              <div class="col-md-4">
                 <input type="text" id="" name="" class="form-control"  value="{{$user_name->user_company_name}}" readonly>
              </div>
               <div class="col-md-2">Email:</div>
              <div class="col-md-4">
                 <input type="text" id="" name="" class="form-control"  value="{{$user_name->user_email}}" readonly>
              </div>

              <div class="col-md-8">
                    <button type="button" formaction="">Insert Comapany Image</button>
               </div>

              @endforeach
            </div>
        </div>
    </div>
</div>
<style>
.Title {
 
  color: white;
  padding: 10px;
} 
</style>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/press_user_drafts.css">
@endsection

@section("script")

@endsection