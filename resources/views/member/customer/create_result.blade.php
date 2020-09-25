<ul class="nav nav-tabs">
    <li><a href="#" class="nav-option" data-content="All Customers">All Customers</a></li>
    <li><a href="#" class="nav-option" data-content="Accept Marketing">Accept Marketing</a></li>
    <li><a href="#" class="nav-option" data-content="Tax Exempt">Tax Exempt</a></li>
    <li><a href="#" class="nav-option" data-content="Walk in">Walk in</a></li>
</ul>
<table class="table table-hover table-condensed">
 <thead>
     <th class="text-center">Name</th>
     <th class="text-center">Location</th>
     <th class="text-center">Orders</th>
     <th class="text-center">Last Orders</th>
     <th class="text-center">Total Spent</th>
 </thead>
 <tbody>
     @foreach($_customer as $key => $customer)
    <tr id="customer_tr_{{$customer['customer_id']}}">
        <td>
            <a href="#" data-content="{{$customer['customer_id']}}" class="a-customer" data-toggle="modal" data-target="#layoutModal">{{$customer['name']}}</a>
        </td>
        <td class="text-center">
            {{$customer['location']}}
        </td>
        <td class="text-center">
            {{$customer['totalorder']}}
        </td>
        <td class="text-center">
            <a href="#">{{$customer['lastorder'] == ''?'':'#'.$customer['lastorder']}}</a>
        </td>
        <td class="text-right">
            <span class="pull-left">PHP</span>{{number_format($customer['totalSpent'],2)}}
        </td>
    </tr>
     @endforeach
 </tbody>
</table>
<div class="form-group">
  <div class="col-md-12 text-center">
      {!!$paginate!!}
  </div>
</div>