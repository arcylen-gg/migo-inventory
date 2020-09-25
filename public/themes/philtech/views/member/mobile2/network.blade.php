<div data-page="report" class="page">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left"><a href="index.html" class="back link icon-only"><i class="icon icon-back"></i></a></div>
            <div class="center">Network List</div>
            <div class="right"><a href="#" class="open-panel link icon-only"><i class="icon icon-bars"></i></a></div>
        </div>
    </div>
    <div class="page-content">
        <div class="report-view">
            <div class="select-holder">
                <div class="row">
                    <div class="col-50">
                        <div class="desc">List of network on your <strong>SOLID TREE</strong></div>
                    </div>
                    <div class="col-50">
                        <select>
                            @foreach($_slot as $slot)
                            <option value="{{ $slot->slot_no }}" {{ $slot->slot_no == request('slot_no') ? 'selected' : '' }}>{{ $slot->slot_no }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="data-table card">
                <table>
                    <thead>
                        <tr>
                            <th class="label-cell">LEVEL</th>
                            <th class="numeric-cell">SLOT COUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($_tree_level) > 0)
                            @foreach($_tree_level as $tree)
                            <tr>
                                <td class="label-cell">{!! $tree->ordinal_level !!}</td>
                                <td class="numeric-cell"><a onclick="action_load_link_to_modal('/members/network-slot?slot_no={{ request("slot_no") }}&level={{ $tree->sponsor_tree_level }}','lg')" href="javascript:"><b>{!! $tree->display_slot_count !!}</b></a></td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2" class="text-center"><b>{{ request("slot_no") }}</b> doesn't have any network yet.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>