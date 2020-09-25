<div class="form-horizontal">
    <div class="form-group">
       <label for="" class="col-md-2">Contact</label>
       <div class="col-md-10">
           <div class="input-group width-100">
                <span class="input-group-btn width-30">
                    <select class="form-control" id="update_category">
                        <option value="email" {{$contact->category == 'email'?'selected="seleected"':''}}>email</option>
                        <option value="number" {{$contact->category == 'number'?'selected="seleected"':''}}>number</option>
                    </select>
                </span>
                <input type="text"  id="update_contact" value="{{$contact->contact}}" placeholder="Your contact here" class="form-control">
            </div>
       </div>
   </div>
</div>