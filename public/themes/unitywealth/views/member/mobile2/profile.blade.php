<div data-page="profile" class="page">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left"><a href="/members" class="back link icon-only"><i class="icon icon-back"></i></a></div>
            <div class="center">Profile</div>
            <div class="right"><a href="#" data-popover=".popover-profile" class="link open-popover"><img class="menu-button" src="/themes/{{ $shop_theme }}/assets/mobile/img/menu.png"></a></div>
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
                        @if($profile->created_date)
                            <td>{{ date("F, d, Y", strtotime($profile->created_date)) }}</td>
                        @elseif($profile->created_at)
                            <td>{{ date("F, d, Y", strtotime($profile->created_at)) }}</td>
                        @elseif($profile->updated_at)
                            <td>{{ date("F, d, Y", strtotime($profile->updated_at)) }}</td>
                        @endif
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
    {{-- Basic Information Popup --}}
    <div class="popup popup-basic-info">
        <div class="content-block">
            <form action="/members/profile-update-info" method="POST" class="ajax-submit profile-update-form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="col-50">
                        <a href="#" class="button close-popup" style="border: 1px solid #5E341B; color: #5E341B; margin-left: auto;">&laquo; Back</a>
                    </div>
                    <div class="col-50">
                        <button type="submit" class="button active" style="background-color: #5E341B; color: #fff; margin-left: auto; width: 100%;">Save</button>
                    </div>
                </div>
                <div class="list-block">
                    <ul>
                        <li>
                            <div class="item-content">
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input type="text" name="first_name" placeholder="First Name" value="{{ $profile->first_name }}">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item-content">
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input type="text" name="middle_name" placeholder="Middle Name" value="{{ $profile->middle_name }}">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item-content">
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input type="text" name="last_name" placeholder="Last Name" value="{{ $profile->last_name }}">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item-content">
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input name="birthdate" type="date" placeholder="Birth Date" value="{{ date("Y", strtotime($profile->birthday)) }}-{{ date("m", strtotime($profile->birthday)) }}-{{ date("d", strtotime($profile->birthday)) }}">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item-content">
                                <div class="item-inner">
                                    <div class="item-input">
                                        <select name="country_id">
                                            <option value="" hidden>Select Country</option>
                                            @foreach($_country as $country)
                                                <option {{ $profile->country_id == $country->country_id ? "selected" : "" }} value="{{ $country->country_id }}">{{ $country->country_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item-content">
                                <div class="item-inner">
                                    <div class="item-input">
                                        <select name="customer_state">
                                            <option value="" hidden>Select Province</option>
                                            @foreach($_locale as $locale)
                                            <option value="{{ $locale->locale_id }}" {{ isset($profile_address->state_id) ? ($profile_address->state_id == $locale->locale_id ? "selected" : "") : "" }}>{{ $locale->locale_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item-content">
                                <div class="item-inner">
                                    <div class="item-input">
                                        <textarea type="text" name="customer_street" placeholder="Full Address">{{ $profile_address->customer_street }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </form>
        </div>
    </div>
    {{-- Reward Config Popup --}}
    <div class="popup popup-reward-config">
        <div class="content-block">
            <form action="/members/profile-update-reward" method="POST" class="ajax-submit reward-update-form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="col-50">
                        <a href="#" class="button close-popup" style="border: 1px solid #5E341B; color: #5E341B; margin-left: auto;">&laquo; Back</a>
                    </div>
                    <div class="col-50">
                        <button type="submit" class="button active" style="background-color: #5E341B; color: #fff; margin-left: auto; width: 100%;">Save</button>
                    </div>
                </div>
                <div class="list-block">
                    <ul>
                        <li>
                            <div class="item-content">
                                <div class="item-inner">
                                    <div class="item-input">
                                        <select name="downline_rule">
                                            <option value="auto" {{ $customer->downline_rule == "auto" ? "selected" : "" }}>AUTO PLACEMENT</option>
                                            <option value="manual" {{ $customer->downline_rule == "manual" ? "selected" : "" }}>MANUAL PLACEMENT</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </form>
        </div>
    </div>
    {{-- Profile Picture Popup --}}
    <div class="popup popup-profile-picture">
        <div class="content-block">
            <form action="/members/profile-update-picture" method="POST" class="ajax-submit profile-picture-form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="customer_id" value="{{ $profile->customer_id }}">
                <div class="row">
                    <div class="col-50">
                        <a href="#" class="button close-popup" style="border: 1px solid #5E341B; color: #5E341B; margin-left: auto;">&laquo; Back</a>
                    </div>
                    <div class="col-50">
                        <button type="submit" class="button active" style="background-color: #5E341B; color: #fff; margin-left: auto; width: 100%;">Save</button>
                    </div>
                </div>
                <div class="upload-profile-pic" style="text-align: center; margin-top: 25px;">
                    <div class="icon">
                        <img style="width: 161px; height: 121px; object-fit: cover; object-fit: cover;" class="img-upload" src="/themes/{{ $shop_theme }}/img/cloud.png">
                    </div>
                    <div class="name" style="color: #404040; font-weight: 500; font-size: 13px; margin: 25px 0; margin-bottom: 15px;">Choose New Profile Image</div>
                    <button class="btn btn-cloud" type="button" onClick="$('.upload-profile').trigger('click');" style="border: 0; background-color: #517df6; color: #fff; border-radius: 0; padding: 5px 50px; font-size: 13px; font-weight: 500;">Browse</button>
                    <input type="file" style="display: none;" class="upload-profile" name="profile_image">
                    <div class="file file-name" style="margin-top: 15px; color: #404040; font-size: 13px;">No File Selected</div>
                </div>
            </form>
        </div>
    </div>
    {{-- Password Popup --}}
    <div class="popup popup-password">
        <div class="content-block">
            <form action="/members/profile-update-password" method="POST" class="ajax-submit password-update-form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="col-50">
                        <a href="#" class="button close-popup" style="border: 1px solid #5E341B; color: #5E341B; margin-left: auto;">&laquo; Back</a>
                    </div>
                    <div class="col-50">
                        <button type="submit" class="button active" style="background-color: #5E341B; color: #fff; margin-left: auto; width: 100%;">Save</button>
                    </div>
                </div>
                <div class="list-block">
                    <ul>
                        <li>
                            <div class="item-content">
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input type="password" name="old_password" placeholder="Old Password">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item-content">
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input type="password" name="password" placeholder="Password">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item-content">
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input type="password" name="password_confirmation" placeholder="Confirm Password">
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </form>
        </div>
    </div>
</div>