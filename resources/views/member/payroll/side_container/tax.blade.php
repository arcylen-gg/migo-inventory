<h4>Tax Table</h4>
<ul class="nav nav-tabs">

  @foreach($_period as $period)
  <li class="{{$period['status']}}"><a data-toggle="tab" href="#{{$period['category']}}">{{$period['category']}}</a></li>
  @endforeach
</ul>

<div class="tab-content tab-pane-div padding-top-10">
  @foreach($_period as $period)
  <div id="{{$period['category']}}" class="tab-pane fade in {{$period['status']}}">
    <form class="form-horizontal global-submit" role="form" action="/member/payroll/tax_table_list/tax_table_save" method="POST">
      <input type="hidden" name="payroll_tax_status_id" value="{{$period['payroll_tax_period_id']}}">
      <input type="hidden" name="_token" value="{{csrf_token()}}">
      <table class="table table-condensed table-bordered">
        <tr>
          <td></td>
          <td class="text-center">2</td>
          <td class="text-center">3</td>
          <td class="text-center">4</td>
          <td class="text-center">5</td>
          <td class="text-center">6</td>
          <td class="text-center">7</td>
          <td class="text-center">8</td>
        </tr>
        @foreach($period['data'] as $data)
        <tr>
          <td>
            <input type="text" name="tax_category[]" class="border-none width-100" readonly value="{{$data->tax_category}}">
          </td>
          <td>
            <input type="number" step="any" name="tax_first_range[]" class="border-none width-100 text-right" value="{{$data->tax_first_range}}">
          </td>
          <td>
            <input type="number" step="any" name="tax_second_range[]" class="border-none width-100 text-right" value="{{$data->tax_second_range}}">
          </td>
          <td>
            <input type="number" step="any" name="tax_third_range[]" class="border-none width-100 text-right" value="{{$data->tax_third_range}}">
          </td>
          <td>
            <input type="text" name="tax_fourth_range[]" class="border-none width-100 text-right" value="{{$data->tax_fourth_range}}">
          </td>
          <td>
            <input type="number" step="any" name="tax_fifth_range[]" class="border-none width-100 text-right" value="{{$data->tax_fifth_range}}">
          </td>
          <td>
            <input type="number" step="any" name="taxt_sixth_range[]" class="border-none width-100 text-right" value="{{$data->taxt_sixth_range}}">
          </td>
          <td>
            <input type="number" step="any" name="tax_seventh_range[]" class="border-none width-100 text-right" value="{{$data->tax_seventh_range}}"> 
          </td>
        </tr>
        @endforeach
       
      </table>
      <div class="form-group">
        <div class="col-md-12">
          <button class="btn btn-primary pull-right">Update</button>
        </div>
      </div>
    </form>
  </div>
  @endforeach
 
</div>