


@foreach($partnerResult as $partnerResultItem)

  <div class="col-md-3 col-sm-6" style="padding: 4px;">
     <div class="partners-div clearfix match-height" style="height: auto; padding-bottom: 15px;">
        <div class="partner-header">
           <img class="match-height" style="object-fit: contain;" src="{{ $partnerResultItem->company_logo }}" width="100%">
        </div>
        <div class="partner-body">
           <div style="text-align: center; padding: 30px 0px 30px 0px; font-weight: 700;">{{ $partnerResultItem->company_name }}
           </div>
           <div id="company-address">
              <div class="col-xs-2"><i class="fa fa-map-marker" aria-hidden="true" id="element"></i></div>
              <div class="col-xs-10" ><p> {{ $partnerResultItem ->company_address }} </p></div>
           </div>
           <div>
              <div class="col-xs-2"><i class="fa fa-phone" aria-hidden="true" id="element2"></i></div>
              <div class="col-xs-10">
                 <p> {{ $partnerResultItem ->company_contactnumber }}</p>
              </div>
           </div>
        </div>
     </div>
  </div>

@endforeach