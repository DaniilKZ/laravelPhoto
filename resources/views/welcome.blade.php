<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel - Search image Unsplash</title>

        <link rel="icon" type="image/png" href="{{ asset('/img/favicon.png') }}" />
 
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/style.css') }}" rel="stylesheet">

        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css" />

    </head>
<body>

<form action="{{ action('SearchController@store') }}" method="GET">
    {{ csrf_field() }}
    <div class="search_block">
        <input type="text" name="searchvalue" value="" placeholder="Что вы ищите?" id="searchvalue">
        <input type="submit" value="Найти"  id="seacrh">
    </div>
</form> 

<div class="image_block"></div>
<div id="loader" class="sk-folding-cube"><div class="sk-cube1 sk-cube"></div><div class="sk-cube2 sk-cube"></div><div class="sk-cube4 sk-cube"></div><div class="sk-cube3 sk-cube"></div></div>

<script  src="https://code.jquery.com/jquery-3.3.1.min.js"  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="  crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js"></script>
<script type="text/javascript">     
    window.addEventListener("load", () => {
      document.getElementById("loader").style.display = "none"; 
    });    

    $('form').on('submit',function(e){ 
        e.preventDefault(); 
        $(".image_block").html("");
        sendAjax(); 
    });     
    

    $(document).on('click', '.load', function(e){   
        var $more = $(document).find(".load").attr("data-page");
        sendAjax($more); 
    });
      
    function sendAjax($more = 1) {

                document.getElementById("loader").style.display = "block";

                $searchvalue = $("#searchvalue").val()
                $.ajax({ 
                    url: '{{URL::to('search')}}', 
                    dataType: 'json',
                    data:{ 'searchvalue':$searchvalue, 'more': $more },
                    success:function(data){    
                        if (data.response != null) {
                            var array_size = data.response.length;  

                            for ( var i = 0;  i <= array_size-1; ) { 

                                $(".image_block").append(
                                  '<div class="image">'+'<a data-fancybox href="'+data.response[i].urls.full+'"><img src="'+data.response[i].urls.small+'" ></a>\
                                    <div class="social-share">\
                                    <p>Поделиться:\
                                          <a href="https://vk.com/share.php?url='+data.response[i].links.html+'" class="social-vk" target="_blank">\
                                            <i class="fa fa-vk" aria-hidden="true"></i>\
                                          </a>\
                                          <a href="https://www.facebook.com/sharer.php?u='+data.response[i].links.html+'&amp;t='+data.response[i].description+'" class="social-fb" target="_blank">\
                                            <i class="fa fa-facebook" aria-hidden="true"></i>\
                                          </a>\
                                          <a href="https://connect.ok.ru/offer?url='+data.response[i].links.html+'&title='+data.response[i].description+'&imageUrl='+data.response[i].urls.small+'" class="social-ok" target="_blank">\
                                            <i class="fa fa-odnoklassniki" aria-hidden="true"></i>\
                                          </a>\
                                          <a href="https://twitter.com/share?url='+data.response[i].links.html+'&amp;text='+data.response[i].description+'&amp;hashtags=my_hashtag" class="social-tw" target="_blank">\
                                            <i class="fa fa-twitter" aria-hidden="true"></i>\
                                          </a>\
                                          <a href="https://plus.google.com/share?url='+data.response[i].links.html+'" onclick="javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;" class="social-gp">\
                                            <i class="fa fa-google-plus" aria-hidden="true"></i>\
                                          </a> </p>\
                                    </div></div>'); 
                                i++;
                            }
                            $(".load").remove();  
                             $more++; 
                            $("body").append('<button class="load" data-page="'+$more+'">Загрузить еще</button>'); 
                        }else{
                            alert(data.error);
                        }

                    }, 
                    complete: function(){
                        document.getElementById("loader").style.display = "none";
                    },
                    error: function(data){ 
                        console.log(data);      
                        document.getElementById("loader").style.display = "none";
                    }  
                    
                }); 
    }   
    $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } }); 
</script> 
    </body>
</html>
