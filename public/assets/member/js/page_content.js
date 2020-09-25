$.fn.unslick = function() 
{
    var _ = this;
    return _.each(function(index, element) 
    {

      if (element.slick) 
      {
        element.slick.destroy();
      }

    });
};

$('.nav-tabs a').click(function()
{
    $(this).tab('show');
})

$(document).ready(function()
{
    $(".match-height").matchHeight();
    load_all_slick();
    event_remove_slide();
    event_collection_droplist();

    $('.nav-tabs a').on('shown.bs.tab', function (e) 
    {
      // var target = $(e.target).attr("href") // activated tab
      // alert(target);
      
      $('.gallery-list').unbind("click");
      $('.gallery-list').bind("click", function(e)
      {
        e.stopImmediatePropagation();
      });
      $('.slick-prev').trigger('click');
      $('.gallery-list').unbind("click");

      $('.gallery-list').unslick();
      $('.gallery-list').each(function(e)
      {
        var key = $(this).attr("key");
        action_image_slick($('.gallery-list[key="'+key+'"]'), key);
      });
    });

    // $('.nav-tabs a').click(function(event) 
    // {
      
    // });
});
function load_all_slick()
{
  $('.image-gallery').each(function(e)
  {
      if (!$(this).hasClass("image-gallery-single")) 
      {
          var key = $(this).attr("key");

          $('.slider-thumb[key="'+key+'"]').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            asNavFor: '.image-gallery[key="'+key+'"]',
            dots: true,
            centerMode: true,
            focusOnSelect: true,
            pauseOnFocus: true,
            pauseOnHover: true,
          });

          $('.image-gallery[key="'+key+'"]').slick({
            infinite: true,
            // autoplay: true,
            autoplaySpeed: 2000,
            asNavFor: '.slider-thumb[key="'+key+'"]',
            pauseOnFocus: true,
            pauseOnHover: true,
          });
      }   
  });
}
function submit_selected_image_done(data) 
{ 
    var key = data.akey;
    append = [];
    var type = $('.type-content[key="'+key+'"]').val();
    var count = -1;

    $.each(data.image_data, function( index, value ) 
    {
        var image_path = value.image_path;

        if (type == "gallery_links") 
        {
          count++;

          append += '<div>'+
                        '<div class="img-holder">'+
                            '<img class="img-responsive" src="'+image_path+'">'+
                            '<input type="hidden" name="info['+key+'][value]['+count+'][image]" value="'+image_path+'">'+
                        '</div>'+
                        '<label class="hiderino">Link: </label>'+
                        '<input onClick="event.stopImmediatePropagation()" type="text" class="form-control hiderino" name="info['+key+'][value]['+count+'][link]">'+
                     '</div>';
        }
        else
        {
          append += '<div>'+
                          '<div class="img-holder">'+
                              '<img class="img-responsive" src="'+image_path+'">'+
                          '</div>'+
                       '</div>';
        }
    }); 

    if ($('.image-gallery[key="'+key+'"]').hasClass("slick-initialized")) 
    {
        $('.image-gallery[key="'+key+'"]').slick('unslick');
    }
    $('.image-gallery[key="'+key+'"] .empty-notify').remove();

    if (!$('.image-gallery[key="'+key+'"]').hasClass("image-gallery-single")) 
    {
        $('.image-gallery[key="'+key+'"]').empty().append(append);
        
        if ($('.slider-thumb[key="'+key+'"] .slick-slide').length) 
        {
            $('.slider-thumb[key="'+key+'"]').slick('removeSlide', null, null, true).append(append);
        }
        else
        {
            $('.slider-thumb[key="'+key+'"]').empty().append(append);
        }

        action_image_slick($('.image-gallery[key="'+key+'"]'), key);

        $('.image-value[key="'+key+'"]').val(allimg);
    }
    else
    {
        $('.image-gallery[key="'+key+'"]').empty().append(append);
        $('.slider-thumb[key="'+key+'"]').empty().append(append);

        $('.image-value[key="'+key+'"]').val(data.image_data[0].image_path);
    }

    $(".match-height").matchHeight();
    $(".slider-thumb .hiderino").remove();
}

function action_image_slick(x, y)
{
    if (x.hasClass("slick-initialized")) 
    {
        x.slick('unslick');
    }
    if ($('.slider-thumb[key="'+y+'"]').hasClass("slick-initialized")) 
    {
        $('.slider-thumb[key="'+y+'"]').slick('unslick');
    }

    allimg = [];

    $('.image-gallery[key="'+y+'"] .img-holder').each(function(e)
    {
        allimg.push($(this).not(".slick-cloned").children("img").attr("src"));
    });

    x.slick({
      infinite: true,
      // autoplay: true,
      autoplaySpeed: 2000,
      adaptiveHeight: true,
      asNavFor: '.slider-thumb[key="'+y+'"]',
      pauseOnFocus: true,
      pauseOnHover: true,
    });

    $('.slider-thumb[key="'+y+'"]').slick({
      slidesToShow: 5,
      slidesToScroll: 1,
      asNavFor: '.image-gallery[key="'+y+'"]',
      dots: true,
      centerMode: true,
      focusOnSelect: true,
      pauseOnFocus: true,
      pauseOnHover: true,
    });
}

function event_remove_slide()
{
    $("body").on("click", ".remove-image", function(e)
    {
        e.preventDefault();
        if (confirm("Are you sure?")) 
        {
            var key = $(e.currentTarget).attr("key");
            action_remove_slide(key);
        }
    });
}

function action_remove_slide(key)
{
    var append = '<div class="empty-notify"><i class="fa fa-image"></i> No Image Yet</div>';

    $('.image-gallery[key="'+key+'"]').empty().html(append);
    $('.slider-thumb[key="'+key+'"]').slick('removeSlide', null, null, true);

    $('.image-value[key="'+key+'"]').val("");
}

function event_collection_droplist()
{
  $('.select-collection').globalDropList(
  {  
    hasPopup                : "true",
    link                    : "/member/ecommerce/product/collection",
    link_size               : "lg",
    width                   : "100%",
    maxHeight               : "200px",
    placeholder             : "Search....",
    no_result_message       : "No result found!",
    onChangeValue           : function(){},
    onCreateNew             : function(){},
  })
}

function approved_tag(tag_id)
{
  $.ajax({
    url : '/member/page/content/update-tag',
    type : 'get',
    dataType : 'json',
    data : {tag_id : tag_id},
    success : function(data)
    {
      if(data.status == 'success')
      {
        toastr.success("Success");
      }
      else if(data.status == "error")
      {
        toastr.warning(data.status_message);
      }
    }
  });
}