<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="msapplication-tap-highlight" content="no">
    @yield('miscss')
    <link href="{{ asset('inner/main.css') }}" rel="stylesheet">
    <link href="{{ asset('inner/jquery.toast.min.css') }}" rel="stylesheet">
    <link href="{{ asset('inner/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('inner/buttons.dataTables.min.css') }}" rel="stylesheet">
    <style>
    ::placeholder {
        color:#d0d0d0!important;
    }
    </style>
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        
        @yield('topnav')

        <div class="ui-theme-settings">
            <button type="button" id="TooltipDemo" class="btn-open-options btn btn-warning">
                <i class="fa fa-cog fa-w-16 fa-spin fa-2x"></i>
            </button>
        </div> 
        
        <div class="app-main">
                    
            @yield('sidenav')

            <div class="app-main__outer">
            <div class="app-main__inner">
                
                @yield('content')

            </div>

            <div class="app-wrapper-footer">
                
                @yield('footer')

            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('inner/scripts/main.js') }} "></script>
<script src="{{ asset('inner/scripts/jquery-3.5.1.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('inner/scripts/loadingoverlay.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('inner/scripts/jquery.toast.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('inner/scripts/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('inner/scripts/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('inner/scripts/buttons.flash.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('inner/scripts/jszip.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('inner/scripts/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('inner/scripts/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ asset('inner/scripts/buttons.html5.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('inner/scripts/buttons.print.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('inner/scripts/jquery.countdown.min.js') }}"></script>
<div id="getting-started"></div>
<script type="text/javascript">
  $("#examwatch").countdown("2021/01/01", function(event) {
    $(this).text(
      event.strftime('%H hr %M min %S')
    );
  });
</script>
<script>  
        $(document).ready(function() {
            $('.reportable').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            } );
            $('.reportable2').DataTable();
        } );
        $(document).ready(function(){

            addQuestion = function(){
                $.LoadingOverlay("show");
                var data = $('form[name="addexQuest"]').serialize();
                $.ajax({
                    type:"post",
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{route('a_addexam_q')}}",
                    data: data,
                    success: function(res){
                        console.log(res);
                        showToast(res.message);
                        $.LoadingOverlay('hide');
                        document.getElementById("addexQuest").reset();
                        return;
                    },
                    error: function(xhr, status, err ){
                        console.log(xhr.responseJSON.message);
                        showToastDanger(xhr.responseJSON.message);
                        $.LoadingOverlay('hide');
                        return
                    }
                });
            }

            var regex = /^(.+?)(\d+)$/i;
            var cloneIndex = $(".clonables").length;
            function clone(){
                $(this).parents(".clonables").clone()
                    .appendTo("#exprs")
                    .attr("id", "clonables__" +  cloneIndex)
                    // .find("*")
                    .each(function() {
                        // set select IDs
                        $(this).find("select:eq(0)").attr("id", "identity__" + cloneIndex);
                        $(this).find("select:eq(1)").attr("id", "opt__" + cloneIndex);
                        $(this).find("select:eq(2)").attr("id", "iscorrect__" + cloneIndex);
                    })
                    .on('click', 'button.clone', clone)
                    .on('click', 'button.remove', remove);
                cloneIndex++;
            }
            function remove(){
                children = $(".clonables").length;
                console.log("it has " + children);
                if( children > 1 ){
                    $(this).parents(".clonables").remove();
                }else{

                }
            }
            $("button.clone").on("click", clone);
            $("button.remove").on("click", remove);
        });
        
        const showToast = function(text){
            $.toast({
                heading: 'Sucess',
                text: text,
                icon: 'info',
                bgColor: '#ed1d29',
                textColor: 'white',
                loader: false,
                position: 'top-right',
                hideAfter: 8000        
            });
        }
        const showToastDanger = function(text){
            $.toast({
                heading: 'Error',
                text: text,
                icon: 'error',
                bgColor: '#FF0000',
                textColor: 'white',
                loader: false,
                position: 'top-right',
                hideAfter: 8000        
            });
        }
    </script>
</body>
</html>
