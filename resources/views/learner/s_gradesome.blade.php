@extends('layouts.exam_inner')

@section('topcss')
<link href="{{ asset('inner/main.css') }}" rel="stylesheet">
    <link href="{{ asset('inner/jquery.toast.min.css') }}" rel="stylesheet">
    <link href="{{ asset('inner/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('inner/buttons.dataTables.min.css') }}" rel="stylesheet">
    <style>
    ::placeholder {
        color:#d0d0d0!important;
    }
    </style>
@endsection
@section('topnav')
    @include('commons/topnav')
@endsection


@section('sidenav')
    @include('commons/sidenav_learner')
@endsection


@section('content')
<style>
.modal-header, .modal-footer {
    background: #ed1d29!important;
}
.modal-header {
    border-bottom: 1px solid #ed1d29!important;
}
.input-group-text-custom {
    display: flex;
    align-items: center;
    padding: .375rem .75rem;
    margin-bottom: 0;
    font-size: .88rem;
    font-weight: 400;
    line-height: 1.5;
    color: #ffffff;
    text-align: center;
    white-space: nowrap;
    background-color: #ed1d29;
    border: 1px solid #ed1d29;
    border-radius: .25rem;
}
.form-control {
    border: solid 0px;
    border-bottom: 1px solid #ced4da;
    border-radius: .2rem;
}
.lesson-title{
    text-transform: none!important;
    color: rgb(81 209 224)!important;
    font-weight: 400!important;
    font-size: 18px!important;
}
.has-padding{
    margin-top:40px;
}
.l-live{
    background: aliceblue;
}
</style>
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-home text-info">
                </i>
            </div>
            <div><a href="{{route('s_home')}}">My Dashboard </a> </div>
            <div>&nbsp;>&nbsp;{{ucwords(strtolower($this_unit['name']))}}</div>
            <div>&nbsp;>&nbsp;Grades</div>
        </div>
    </div>
</div>

<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <!-- messges -->
                @if(Session::get('status') && Session::get('status') == 200)
                <div class="alert alert-success">{{Session::get('message')}}</div>
                @endif
                @if(Session::get('status') && Session::get('status') == 201)
                <div class="alert alert-danger">{{Session::get('message')}}</div>
                @endif
                <h5 class="card-title">Unit Performance</h5>
                <p>This section contains your performance(graded) in this unit.</p>
                <div class="main">
                    <div class="table-responsive">
                    <table class="mb-0 table table-sm reportable">
                        <thead>
                            <tr>
                                <th><small>Unit Name</small></th>
                                <th><small>Assessments</small></th>
                                <th><small>Final Paper</small></th>
                                <th><small>Overoll Score</small></th>
                                <th><small>Status</small></th>
                                <th><small>Progress</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($this_unit_grade))
                                @foreach( $this_unit_grade as $_this_unit_grade )
                                @php($p = 'failed')
                                @php( $c = 'Pending')
                                @if($_this_unit_grade['is_passed'])
                                @php( $p = 'passed')
                                @endif
                                @if($_this_unit_grade['is_completed'])
                                @php( $c = 'Completed')
                                @endif
                                <tr>
                                    <td>{{ucwords(strtolower($this_unit['name']))}}</td>
                                    <td>{{$_this_unit_grade['assessment']}}/40</td>
                                    <td>{{$_this_unit_grade['exam']}}/60</td>
                                    <td>{{$_this_unit_grade['final']}}%</td>
                                    <td>{{$p}}</td>
                                    <td>{{$c}}</td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('commons/footer')
@endsection

@section('bottomscript')
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
            loadTest = function(exam, ename, duration){
                $.LoadingOverlay("show");
                $('#exam').val(exam);
                $('#examduration').text(duration + "minutes");
                $('#examtitle').text(ename);
                $('#startexam_btn').trigger('click');
                $.LoadingOverlay("hide");
            }
            loadSurvey = function(exam, ename, duration){
                $.LoadingOverlay("show");
                $('#survey').val(exam);
                $('#surveyduration').text(duration + "minutes");
                $('#surveytitle').text(ename);
                $('#startsurvey_btn').trigger('click');
                $.LoadingOverlay("hide");
            }
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
    @endsection
    <!-- modal -->
<button id="startexam_btn" style="display:none;" data-toggle="modal" data-target="#startexam"></button>
<div class="modal fade" id="startexam" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="examtitle">START EXAM</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('s_exama_anow')}}" method="POST" id="newlesson" enctype="multipart/form-data">
                @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <input type="hidden" name="exam" id="exam"/>
                                <input type="hidden" name="unit" id="unit" value="{{$this_unit['id']}}"/>
                                <p>Please read the following instructions before starting the exam. Once you start, you will not be allowed to stop until the exam is finished.</p>
                                <ul>
                                <li>You will have <b><span id="examduration"></span></b> to complete your exam</li>
                                <li>Do not open multiple tabs, this will automatically submit your exam</li>
                                <li>Do not refresh the page, doing so will automatically submit your exam even if you have not finisheds</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 btn btn-primary">START EXAM NOW</button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!-- survey -->
<button id="startsurvey_btn" style="display:none;" data-toggle="modal" data-target="#startsurvey"></button>
<div class="modal fade" id="startsurvey" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="surveytitle">START SURVEY</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('s_survey_anow')}}" method="POST" id="newlesson" enctype="multipart/form-data">
                @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <input type="hidden" name="exam" id="survey"/>
                                <input type="hidden" name="unit" id="unit" value="{{$this_unit['id']}}"/>
                                <p>Thank you for taking time to answer this survey. Your feedback is highly appreciated.</p>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 btn btn-primary">START SURVEY NOW</button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


