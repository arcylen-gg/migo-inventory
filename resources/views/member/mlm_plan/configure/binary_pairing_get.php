<td colspan="4">
    <div class="col-md-4">
        <label for="membership_name">Membership Name</label>
        <input type="text" class="form-control"  name="membership_name" value="{{$membership->membership_name}}" disabled>
    </div>
    <div class="col-md-4">
        <label for="membership_name">Max Pair Per Cycle</label>
        <input type="text" class="form-control"  name="max_pair_cycle" value="{{$membership->membership_name}}" disabled>
    </div>
    <div class="col-md-12 table-responsive">
        <table class="table table-codensed">
            <thead>
                <tr>
                    <th>Point (LEFT)</th>
                    <th>Point (RIGHT)</th>
                    <th>Bonus</th>
                    <th class="opt-col"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>1</td>
                    <td>100</td>
                    <td>><a href="javascript:">Save</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</td>