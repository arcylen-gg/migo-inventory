<div class="table-responsive">
    <table class="table table-hover table-bordered table-striped table-condensed">
        <thead>
            <tr>
                <!-- <td class="col-md-2"></td> -->
                <th class="text-center">ID</th>
                <th class="text-center">Name</th>
                <th class="text-center">Due in fixed number of days</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($_terms as $terms)
            <tr>
                <td class="text-center">{{ $terms->terms_id }}</td>
                <td class="text-center">{{ $terms->terms_name}}</td>
                <td class="text-center">{{ $terms->terms_no_of_days}}</td>
                <td class="text-center">
                    <!-- ACTION BUTTON -->
                    @if($filter == "active")
                        <div class="btn-group">
                            <a class="btn btn-primary btn-grp-primary popup" href="javascript:" link="/member/maintenance/terms/terms?id={{$terms->terms_id}}" size="sm">Edit</a>
                            <!-- <a class="btn btn-primary btn-grp-primary popup" href="javascript:" link="" size="md"><span class="fa fa-trash"></span></a> -->
                        </div>
                    @else
                    <!-- <div class="btn-group">
                        <a class="btn btn-primary btn-grp-primary popup" link="" size="md">Restore</a>
                    </div> -->
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="text-center pull-right">
    {!!$_terms->render()!!}
</div>