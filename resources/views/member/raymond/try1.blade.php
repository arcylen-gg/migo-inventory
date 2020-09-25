@extends('member.layout')
@section('content')
    <button class="btn btn-primary popup" link="/member/customer/modalcreatecustomer" size="lg">Button Label</button>


@endsection

@section('script')
    <script type="text/javascript">
        function submit_done_customer(data)
        {
           alert(data.customer_info.customer_id);

        }
    </script>
@endsection