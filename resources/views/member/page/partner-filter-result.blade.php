<div class="table-responsive">
                    <table class="table table-condensed post-table">
                        <thead style="text-transform: uppercase">
                            <tr>
                                <th class="text-center">Company Logo</th>
                                <th class="text-center">Company Name</th>
                                <th class="text-center">Company Owner Name</th>
                                <th class="text-center">Contact Number</th>
                                <th class="text-center">Company Address</th>
                                <th class="text-center">Company Location</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($partnerResult as $partnerResultItem)
                            <tr row="">
                                <td class="text-center">
                                    <img src="{{ $partnerResultItem->company_logo }}" class="img-thumbnail" alt="Cinque Terre" width="100" height="70">
                                </td>
                                <td class="text-center">{{ $partnerResultItem->company_name }}</td>
                                <td class="text-center">{{ $partnerResultItem->company_owner }}</td>
                                <td class="text-center">{{ $partnerResultItem->company_contactnumber }}</td>
                                <td class="text-center">{{ $partnerResultItem->company_address }}</td>
                                <td class="text-center">{{ $partnerResultItem->company_location }}</td>
                                <td class="text-center">
                                    <!-- ACTION BUTTON -->
                                    
                                    <div class="dropdown">
                                        <button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
                                        <span class="caret"></span></button>
                                        <ul class="dropdown-menu dropdown-menu-custom">
                                            <li ><a href="/member/page/partnerview/edit/{{ $partnerResultItem->id }}" ><i class="icon-fixed-width icon-pencil"></i>&nbsp;&nbsp;&nbsp;&nbsp;Edit</a></li>
                                            <li><a href="/member/page/partnerview/delete/{{ $partnerResultItem->id }}" ><i class="icon-fixed-width icon-trash"></i>&nbsp;&nbsp;Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>