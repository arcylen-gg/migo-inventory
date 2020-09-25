@extends('member.layout')
@section('content')
<form method="post">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Page <i class="fa fa-angle-double-right"></i> Content</span>
                    <small>
                    You can edit content of your website in this page.
                    </small>
                </h1>
                <button type="submit" class="panel-buttons btn btn-custom-primary pull-right">Submit</button>
            </div>
        </div>
    </div>
    <!-- NO PRODUCT YET -->
    <div class="panel panel-default panel-block panel-title-block panel-gray ">
        <ul class="nav nav-tabs">
            @foreach($page_info as $key => $_info)
            <li class="{{ $key == 'home' ? 'active' : '' }}"><a href="#{{ $key }}">{{ underscore2Camelcase($key) }}</a></li>
            @endforeach
            @if(isset($job_resume))
            <li><a href="#job_resume">Resume</a></li>
            @endif
            @if(isset($popular_tags))
            <li><a href="#popular_tags">Popular Tags</a></li>
            @endif
            <li><a href="#others">Others</a></li>
        </ul>
        <div class="panel panel-default panel-block panel-title-block panel-gray" style="margin-bottom: 0;">
            <div class="tab-content">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                @foreach($page_info as $key => $_info)
                    <div id="{{ $key }}" class="tab-pane fade {{ $key == 'home' ? 'in active' : '' }}">
                        <div class="clearfix" style="padding: 30px">
                            @foreach($_info as $keys => $info)
                            <div class="col-md-{{ $info->size }}" style="margin-bottom: 5px;">
                                <label>{{ $info->label }}</label>
                                <div>
                                    @if($info->type == "textbox")
                                    <div class="match-height">
                                        <input type="hidden" name="info[{{ $keys }}][type]" value="{{ $info->type }}">
                                        <input type="text" name="info[{{ $keys }}][value]" class="form-control" value="{{ $info->default }}">
                                    </div>
                                    @elseif($info->type == "gallery")
                                    <div>
                                        <input type="hidden" name="info[{{ $keys }}][type]" value="{{ $info->type }}">
                                        <input class="image-value" key="{{ $keys }}" type="hidden" name="info[{{ $keys }}][value]" value="{{ $info->default }}">
                                        <button type="button" class="btn btn-primary remove-image" key="{{ $keys }}">Remove Images</button>
                                        <div class="gallery-list image-gallery" key="{{ $keys }}">
                                            @if(is_serialized($info->default))
                                                @foreach(unserialize($info->default) as $value)
                                                <div>
                                                    <div class="img-holder">
                                                        <img style="object-fit: contain; object-position: center;" class="img-responsive" src="{{ $value }}">
                                                    </div>
                                                </div>
                                                @endforeach
                                            @else
                                            <div class="empty-notify"><i class="fa fa-image"></i> No Image Yet</div>
                                            @endif
                                        </div>
                                        <div class="slider-thumb" key="{{ $keys }}">
                                            @if(is_serialized($info->default))
                                                @foreach(unserialize($info->default) as $value)
                                                <div>
                                                    <div class="img-holder">
                                                        <img style="object-fit: contain; object-position: center;" class="img-responsive" src="{{ $value }}">
                                                    </div>
                                                </div>
                                                @endforeach
                                            @else
                                            @endif
                                        </div>
                                    </div>
                                    @elseif($info->type == "gallery_links")
                                    <div>
                                        <input type="hidden" class="type-content" key="{{ $keys }}" name="info[{{ $keys }}][type]" value="{{ $info->type }}">
                                        <button type="button" class="btn btn-primary remove-image" key="{{ $keys }}">Remove Images</button>
                                        <div class="gallery-list image-gallery link-css" key="{{ $keys }}">
                                            @if(is_serialized($info->default))
                                                @foreach(unserialize($info->default) as $serialize_key => $value)
                                                <div>
                                                    <div class="img-holder">
                                                        <img style="object-fit: contain; object-position: center;" class="img-responsive" src="{{ $value['image'] }}">
                                                        <input type="hidden" name="info[{{ $keys }}][value][{{ $serialize_key }}][image]" value="{{ $value['image'] }}">
                                                    </div>
                                                    <label>Link: </label>
                                                    <input onClick="event.stopImmediatePropagation()" type="text" class="form-control input-link" name="info[{{ $keys }}][value][{{ $serialize_key }}][link]" value="{{ $value['link'] }}">
                                                </div>
                                                @endforeach
                                            @else
                                            <div class="empty-notify"><i class="fa fa-image"></i> No Image Yet</div>
                                            @endif
                                        </div>
                                        <div class="slider-thumb" key="{{ $keys }}">
                                            @if(is_serialized($info->default))
                                                @foreach(unserialize($info->default) as $value)
                                                <div>
                                                    <div class="img-holder">
                                                        <img style="object-fit: contain; object-position: center;" class="img-responsive" src="{{ $value['image'] }}">
                                                    </div>
                                                </div>
                                                @endforeach
                                            @else
                                            @endif
                                        </div>
                                    </div>
                                    @elseif($info->type == "image")
                                    <div class="match-height">
                                        <input type="hidden" name="info[{{ $keys }}][type]" value="{{ $info->type }}">
                                        <input class="image-value" key="{{ $keys }}" type="hidden" name="info[{{ $keys }}][value]" value="{{ $info->default }}">
                                        <div class="gallery-list image-gallery image-gallery-single" key="{{ $keys }}">
                                            @if($info->default)
                                            <div>
                                                <div class="img-holder">
                                                    <img style="object-fit: contain; object-position: center;" class="img-responsive" src="{{ $info->default }}">
                                                </div>
                                            </div>
                                            @else
                                            <div class="empty-notify"><i class="fa fa-image"></i> No Image Yet</div>
                                            @endif
                                        </div>
                                    </div>
                                    @elseif($info->type == "post")
                                    <div class="match-height">
                                        <input type="hidden" name="info[{{ $keys }}][type]" value="{{ $info->type }}">
                                        <input class="post-value" key="{{ $keys }}" type="hidden" name="info[{{ $keys }}][value]" value="{{ $info->default }}">
                                        <button class="btn btn-primary popup" type="button" link="/member/page/content/post/{{ isset($info->limit) ? $info->limit : 'all' }}?key={{ $keys }}" size="lg">Manage Post</button>
                                    </div>
                                    @elseif($info->type == "collection")
                                    <div class="match-height">
                                        <input type="hidden" name="info[{{ $keys }}][type]" value="{{ $info->type }}">
                                        <select class="form-control select-collection" name="info[{{ $keys }}][value]">
                                            @foreach($_collection as $collection)
                                            <option value="{{ $collection->collection_id }}" {{ $collection->collection_id == $info->default ? "selected" : "" }}>{{ $collection->collection_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @elseif($info->type == "maintenance")
                                    <div class="match-height">
                                        <!-- <input type="hidden" name="info[{{ $keys }}][type]" value="{{ $info->type }}"> -->
                                        <!-- <input type="hidden" class="form-control maintenance-holder" key="{{ $keys }}" name="info[{{ $keys }}][value]"> -->
                                        <button class="btn btn-primary popup" size="lg" type="button" link="/member/page/content/maintenance?field={{ serialize($info->field) }}&key={{ $keys }}">Manage {{ $info->label }}</button>
                                        <div style="display: inline-block; margin-left: 5px;">This maintenance currently has <span class="maintenance-count" key="{{ $keys }}">{{ is_serialized($info->default) ? count(unserialize($info->default)) : 0 }}</span> data.</div>
                                    </div>
                                    @elseif($info->type == "brand")
                                        @if(count($_brand) > 0)
                                        <div class="match-height">
                                            <div class="row clearfix" style="margin-top: 15px; margin-bottom: 15px;">
                                            <input type="hidden" name="info[{{ $keys }}][type]" value="{{ $info->type }}">
                                                @foreach($_brand as $key_brand => $brand)
                                                    @if(isset(unserialize($info->default)[$brand['type_name']]))
                                                        <?php $brand_image = is_serialized($info->default) ? unserialize($info->default)[$brand['type_name']]["image"] : '/assets/front/img/default.jpg'; ?>
                                                        <?php $brand_link = is_serialized($info->default) ? unserialize($info->default)[$brand['type_name']]["link"] : '/assets/front/img/default.jpg'; ?>
                                                    @else
                                                        <?php $brand_image = '/assets/front/img/default.jpg'; ?>
                                                        <?php $brand_link = '/assets/front/img/default.jpg'; ?>
                                                    @endif
                                                    <div class="col-md-3">
                                                        <div style="margin-bottom: 7.5px; font-weight: 700;">{{ $brand['type_name'] }}</div>
                                                        <input class="image-value" key="{{ $keys }}-{{ $key_brand }}" type="hidden" name="info[{{ $keys }}][value][{{ $brand['type_name'] }}][image]" value="{{ $brand_image }}">
                                                        <div class="gallery-list image-gallery image-gallery-single" key="{{ $keys }}-{{ $key_brand }}">
                                                            @if($info->default)
                                                            <div>
                                                                <div class="img-holder">
                                                                    <img style="object-fit: contain; object-position: center;" class="img-responsive" src="{{ $brand_image }}">
                                                                </div>
                                                            </div>
                                                            @else
                                                            <div class="empty-notify"><i class="fa fa-image"></i> No Image Yet</div>
                                                            @endif
                                                        </div>
                                                        <div style="display: none;">
                                                            <input class="form-control" type="hidden" name="info[{{ $keys }}][value][{{ $brand['type_name'] }}][link]" placeholder="Brand Link" value="{{ $brand['type_name'] }}">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    @elseif($info->type == "tinymce")
                                    <div class="match-height">
                                        <input type="hidden" name="info[{{ $keys }}][type]" value="{{ $info->type }}">
                                        <textarea name="info[{{ $keys }}][value]" class="tinymce">{{ $info->default }}</textarea>
                                    </div>
                                    @else
                                    <div class="match-height">
                                        <input type="hidden" name="info[{{ $keys }}][type]" value="{{ $info->type }}">
                                        <textarea class="form-control" name="info[{{ $keys }}][value]">{{ $info->default }}</textarea>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                @if(isset($job_resume))
                <div id="job_resume" class="tab-pane fade">
                    <div class="job-resume">
                        <div class="clearfix" style="padding: 30px;">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Job Apply</th>
                                        <th>Job Introduction</th>
                                        <th>Job Resume</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($job_resume) && $job_resume && count($job_resume) > 0)
                                        @foreach($job_resume as $resume)
                                        <tr>
                                            <td>{{ $resume->job_apply }}</td>
                                            <td>{{ $resume->job_introduction }}</td>
                                            <td><a href="{{ URL::to($resume->job_resume) }}" target="_blank">View</a></td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
                @if(isset($popular_tags))
                <div id="popular_tags" class="tab-pane fade">
                    <div class="clearfix" style="padding: 30px">
                        <div class="col-md-12" style="margin-bottom: 5px;">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Search Count</th>
                                            <th>Keyword</th>
                                            <th class="text-center">Approved</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($popular_tags as $tag)
                                        <tr>
                                            <td>{{$tag->tag_id}}</td>
                                            <td>{{number_format($tag->count)}}</td>
                                            <td>{{$tag->keyword}}</td>
                                            <td class="text-center">
                                                <input type="checkbox" name="" onclick="approved_tag({{$tag->tag_id}})" {{$tag->tag_approved == 1 ? 'checked' : ''}}>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>                                
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div id="others" class="tab-pane fade">
                    <div class="clearfix" style="padding: 30px">
                        <div class="col-md-6" style="margin-bottom: 5px;">
                            <label>Company Name</label>
                            <div>
                                <div class="match-height">
                                    <input type="hidden" name="info[company_name][type]" value="{{ isset($company_info['company_name']) ? $company_info['company_name']->type : 'textbox' }}">
                                    <input type="text" name="info[company_name][value]" class="form-control" value="{{ isset($company_info['company_name']) ? $company_info['company_name']->value : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" style="margin-bottom: 5px;">
                            <label>Company Acronym</label>
                            <div>
                                <div class="match-height">
                                    <input type="hidden" name="info[company_acronym][type]" value="{{ isset($company_info['company_acronym']) ? $company_info['company_acronym']->type : 'textbox' }}">
                                    <input type="text" name="info[company_acronym][value]" maxlength="4" class="form-control" value="{{ isset($company_info['company_acronym']) ? $company_info['company_acronym']->value : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" style="margin-bottom: 5px;">
                            <label>Company Logo</label>
                            <div class="match-height">
                                <input type="hidden" name="info[company_logo][type]" value="{{ isset($company_info['company_logo']) ? $company_info['company_logo']->type : 'image' }}">
                                <input class="image-value" key="company_logo" type="hidden" name="info[company_logo][value]" value="{{ isset($company_info['company_logo']) ? $company_info['company_logo']->value : '' }}">
                                <div class="gallery-list image-gallery image-gallery-single" key="company_logo">
                                    @if(isset($company_info['company_logo']))
                                    <div>
                                        <div class="img-holder">
                                            <img class="img-responsive" src="{{ $company_info['company_logo']->value }}">
                                        </div>
                                    </div>
                                    @else
                                    <div class="empty-notify"><i class="fa fa-image"></i> No Image Yet</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" style="margin-bottom: 5px;">
                            <label>Receipt Logo</label>
                            <div class="match-height">
                                <input type="hidden" name="info[receipt_logo][type]" value="{{ isset($company_info['receipt_logo']) ? $company_info['receipt_logo']->type : 'image' }}">
                                <input class="image-value" key="receipt_logo" type="hidden" name="info[receipt_logo][value]" value="{{ isset($company_info['receipt_logo']) ? $company_info['receipt_logo']->value : '' }}">
                                <div class="gallery-list image-gallery image-gallery-single" key="receipt_logo">
                                    @if(isset($company_info['receipt_logo']))
                                    <div>
                                        <div class="img-holder">
                                            <img class="img-responsive" src="{{ $company_info['receipt_logo']->value }}">
                                        </div>
                                    </div>
                                    @else
                                    <div class="empty-notify"><i class="fa fa-image"></i> No Image Yet</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" style="margin-bottom: 5px;">
                            <label>Company Address</label>
                            <div>
                                <div class="match-height">
                                    <input type="hidden" name="info[company_address][type]" value="{{ isset($company_info['company_address']) ? $company_info['company_address']->type : 'textbox' }}">
                                    <input type="text" name="info[company_address][value]" class="form-control" value="{{ isset($company_info['company_address']) ? $company_info['company_address']->value : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" style="margin-bottom: 5px;">
                            <label>Company Email</label>
                            <div>
                                <div class="match-height">
                                    <input type="hidden" name="info[company_email][type]" value="{{ isset($company_info['company_email']) ? $company_info['company_email']->type : 'textbox' }}">
                                    <input type="text" name="info[company_email][value]" class="form-control" value="{{ isset($company_info['company_email']) ? $company_info['company_email']->value : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" style="margin-bottom: 5px;">
                            <label>Company Mobile</label>
                            <div>
                                <div class="match-height">
                                    <input type="hidden" name="info[company_mobile][type]" value="{{ isset($company_info['company_mobile']) ? $company_info['company_mobile']->type : 'textbox' }}">
                                    <input type="text" name="info[company_mobile][value]" class="form-control" value="{{ isset($company_info['company_mobile']) ? $company_info['company_mobile']->value : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" style="margin-bottom: 5px;">
                            <label>Company Business Hours</label>
                            <div>
                                <div class="match-height">
                                    <input type="hidden" name="info[company_hour][type]" value="{{ isset($company_info['company_hour']) ? $company_info['company_hour']->type : 'textbox' }}">
                                    <input type="text" name="info[company_hour][value]" class="form-control" value="{{ isset($company_info['company_hour']) ? $company_info['company_hour']->value : '  Monday - Friday at 9:00am - 6:00pm' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection


@section('css')
<link rel="stylesheet" type="text/css" href="/assets/member/css/page_content.css">
<link rel="stylesheet" type="text/css" href="/assets/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="/assets/slick/slick-theme.css">
<style type="text/css">
.slick-no-slide .slick-track {
    width: 100% !important;
    text-align: center;
    transform: translate3d(0px, 0px, 0px) !important;
}

.slick-no-slide .slick-slide {  
    float: none;
    display: inline-block;
}

.slick-no-slide .slick-list {
    padding: 0;
}

.mce-notification-warning
{
    display: none;
}

.slick-list
{
    height: auto !important;
}
</style>

@if(isset($job_resume))
<style type="text/css">

</style>
@endif

@endsection

@section('script')
<script type="text/javascript" src="/assets/slick/slick.js"></script>
<script type="text/javascript" src="/assets/member/js/page_content.js"></script>
<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
<script>tinymce.init({ 
    selector:'.tinymce',
    plugins: "lists",
    menubar: false,
    toolbar: "numlist bullist bold italic underline strikethrough"
 });</script>
@endsection