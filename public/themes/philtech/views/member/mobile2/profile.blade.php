<div data-page="index" class="page">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left"><a href="index.html" class="back link icon-only"><i class="icon icon-back"></i></a></div>
            <div class="center">Profile</div>
            <div class="right"><a href="#" class="open-panel link icon-only"><i class="icon icon-bars"></i></a></div>
        </div>
    </div>
    <div class="page-content">
        <div class="profile-view">
            <div class="holder-info">
                <div class="profile-holder">
                    <div class="brown-cover"></div>
                    <div class="img">
                        <img style="width: 75px; height: 75px; object-fit: cover;" src="{{ $profile_image }}">
                    </div>
                    <div class="name">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                    <div class="sub">Member</div>
                </div>
                <div class="profile-info">
                    <div class="row no-gutter">
                        <div class="col-50">
                            <div class="holder green">
                                <div class="value">{{ $customer_summary["display_slot_count"] }}</div>
                                <div class="label">Slot Owned</div>
                            </div>
                        </div>
                        {{-- <div class="col-33">
                            <div class="holder orange">
                                <div class="value">30</div>
                                <div class="label">Direct Refferal</div>
                            </div>
                        </div> --}}
                        <div class="col-50">
                            <div class="holder blue">
                                <div class="value">{{ $wallet->display_current_wallet }}</div>
                                <div class="label">Current Wallet</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="profile-leads">
                <a href="javascript:">Leads Link&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right" aria-hidden="true"></i></a>
            </div> --}}
            <div class="profile-about">
                <div class="title">About Me</div>
                <table>
                    <tr>
                        <td><i class="fa fa-calendar" aria-hidden="true"></i> Date Joined</td>
                        <td>{{ $profile->created_date }}</td>
                    </tr>
                    <tr>
                        <td><i class="fa fa-map-marker" aria-hidden="true"></i> Location</td>
                        @if($profile_address)
                            <td>{{ $profile_address->customer_state }} {{ $profile_address->customer_city }} {{ $profile_address->customer_zipcode }} {{ $profile_address->customer_street }}</td>
                        @else
                            <td>No location set</td>
                        @endif
                    </tr>
                </table>
            </div>
            {{-- <div class="profile-about">
                <div class="title">New Referral</div>
                <table>
                    <tr>
                        <td>Mrs. Brown Phone</td>
                        <td>2017-03-08 09:20:33</td>
                    </tr>
                </table>
            </div> --}}
        </div>
    </div>
</div>