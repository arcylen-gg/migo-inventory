@extends("layout")
@section("content")
<div class="container text-center">
<h4>Redirecting to Google.....</h4>
</div>
<a href="javascript:" class="g-signin2 hidden" id="click_google" data-onsuccess="onSignIn"></a>
@endsection
@section("script")
<script type="text/javascript" src="/assets/member/js/google_script/google_script_auth.js"></script>
@endsection