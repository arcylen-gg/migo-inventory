  <div class="col-md-6 col-lg-4">
        <div class="panel panel-default panel-block">
            <div class="list-group">
                <div class="list-group-item">
                    <h4 class="section-title"><img src="/assets/mlm/img/news-icon.png"> <span>NEWS & ANNOUNCEMENTS</span></h4>
                    <div class="news-container">
                        @foreach($_post as $key => $post)
                            @if($key == 0)
                            <div class="holder">
                                <a href="/mlm/news/{{ $post->post_id }}">
                                    <div class="img">
                                        <img src="{{ $post->post_image }}">
                                    </div>
                                    <div class="title">{{ $post->post_title }}</div>
                                    <div class="desc">{{ substr($post->post_excerpt, 0, 100) }} ...</div>
                                    <div class="date">{{ date("M. j, Y", strtotime($post->post_date)) }}</div>
                                </a>
                            </div>
                            @else
                            <div class="holder">
                                <a href="/mlm/news/{{ $post->post_id }}">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td class="td-img">
                                                    <div class="img">
                                                        <img src="{{ $post->post_image }}">
                                                    </div>
                                                </td>
                                                <td class="td-text">
                                                    <div class="title">{{ $post->post_title }}</div>
                                                    <div class="desc">{{ substr($post->post_excerpt, 0, 100) }} ...</div>
                                                    <div class="date">{{ date("M. j, Y", strtotime($post->post_date)) }}</div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </a>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>