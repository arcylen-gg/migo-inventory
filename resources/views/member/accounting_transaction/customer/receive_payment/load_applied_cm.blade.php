@if(isset($cmdata))
<div class="row">
    <div class="col-md-7 text-right digima-table-label">Applied Credits</div>
</div>
<br>
<div class="cm-row">
    @if(count($cmdata) > 0)
    @foreach($cmdata as $cm)
        <div class="row text-right cm-li">
            <div class="col-md-7 text-right">
                <input type="hidden" name="cm_id[]" value="{{$cm->cm_id}}">
                <i class="fa fa-trash remove-cm" style="color: red;cursor: pointer;"></i> &nbsp; &nbsp; &nbsp; {{$cm->transaction_refnum}}
            </div>
            <div class="col-md-5">
                <input type="text" class="text-right input-sm form-control cm-amount-applied compute-cm" name="cm_applied_amount[]" value="{{number_format($cm->cm_amount,2)}}">
            </div>
        </div>
    @endforeach
    @endif
</div>
<br>
<div class="row">

</div>
@endif