
<div class="background-border-container" >
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="search-container"  >
                <input type="text" placeholder="Search News" name="search_newsroom" id="search_newsroom">
                <button type="button" id="search_newsroom_btn" name="search_newsroom_btn" aria-hidden="true" her><i class="fa fa-search"></i></button>
            </div>
        </div>
    </div>
    
    <div class="main-container">
        @foreach ($pr as $prs)
        <div class="news-title-container" >
            <div class="title"><a href="/newsroom/view/{{$prs->pr_id}}">{{$prs->pr_headline}}</a></div>
        </div>  
        <div class="details-container">
            <a href="/newsroom/view/{{$prs->pr_id}}" style="color: black;text-decoration:none;">
            <p>{!!$prs->pr_content!!}</p>
            </a>
        </div>
        <div class="button-container">
          <button onclick="window.location.href='/newsroom/view/{{$prs->pr_id}}'">Read More</button>
        </div>
        @endforeach
    </div>
</div>

<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/search_news_room.css">

