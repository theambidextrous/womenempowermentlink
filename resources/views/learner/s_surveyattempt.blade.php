@extends('layouts.exam_inner')

@section('topcss')
@php(date_default_timezone_set("Africa/Nairobi"))
<script>
window.onbeforeunload = function (e) {
e = e || window.event;

// For IE and Firefox prior to version 4
if (e) {
    e.returnValue = 'Sure?';
}

// For Safari
return 'Sure?';
};
</script>
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

#quiz {
  /* margin: -44px 50px 0px; */
  position: relative;
  width: calc(100% - 100px);
}

#quiz h1 {
  color: #FAFAFA;
  font-weight: 600;
  font-size: 36px;
  text-transform: uppercase;
  text-align: left;
  line-height: 44px;
}

#quiz button {
  /* float: left;
  margin: 8px 0px 0px 8px;
  padding: 4px 8px;
  background: #9ACFCC;
  color: #00403C;
  font-size: 14px;
  cursor: pointer;
  outline: none; */
}

#quiz button:hover {
  /* background: #36a39c;
  color: #FFF; */
}

#quiz button:disabled {
  opacity: 0.5;
  background: #9ACFCC;
  color: #00403C;
  cursor: default;
}

#question {
  padding: 20px;
  background: #FAFAFA;
  text-align: left;
}

#question h2 {
  margin-bottom: 16px;
  font-weight: 600;
  font-size: 20px;
}

#question input[type=radio] {
  display: none;
}

#question label {
  display: inline-block;
  margin: 4px;
  padding: 8px;
  border-radius:5px;
  background: #59d2e2;
  color: #4C3000;
  width: calc(50% - 8px);
  min-width: 50px;
  cursor: pointer;
}

#question label:hover {
  background: #3ab7ff;
}

#question input[type=radio]:checked + label {
    border-radius:5px;
    background: #ed1d29!important;
    color: #FAFAFA;
}

#quiz-results {
  display: flex;
  flex-direction: column;
  justify-content: center;
  position: absolute;
  top: 44px;
  left: 0px;
  background: #FAFAFA;
  width: 100%;
  height: calc(100% - 44px);
}

#quiz-results-message {
  display: block;
  color: #00403C;
  font-size: 20px;
  font-weight: bold;
}

#quiz-results-score {
  display: block;
  color: #31706c;
  font-size: 20px;
}

#quiz-results-score b {
  color: #00403C;
  font-weight: 600;
  font-size: 20px;
}

#quiz-retry-button {

  float: left;
  margin: 8px 0px 0px 8px;
  padding: 4px 8px;
  background: #9ACFCC;
  color: #00403C;
  font-size: 14px;
  cursor: pointer;
  outline: none;
  
}
.clockw{
    width: 259px!important;
    font-size: 24px!important;
    color: #fb7188!important;
}
</style>
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon clockw" id="xexamwatch">
                <!-- <i class="pe-7s-home text-info">
                </i> -->
            </div>
            <div>{{$this_exam['title']}}</div>
        </div>
    </div>
</div>

<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <p class="text-danger">Do not close this window until you have submitted your answers</p>
                <hr>
                <div class="main">
                    <!-- exam window -->
                    <div id="quiz">
                        <div id="question">
                            <h2>{{Session::get('current_index')}}). {{ucwords(strtolower($first_question['title']))}}</h2>
                            <input id="examid" type="hidden" name="examid" value="{{$this_exam['id']}}">
                            <input id="questionid" type="hidden" name="question" value="{{$first_question['id']}}">
                            <input id="scoreid" type="hidden" name="score" value="2">
                            @php( $options = json_decode($first_question['options'], true))
                            @if(count($options))
                            @php($_loop = 0 )
                            @foreach($options as $opt )
                            
                            <input id="choices-{{$_loop}}" type="radio" name="choice" value="{{$opt['Id']}}">
                            <label for="choices-{{$_loop}}">{{$opt['Id']}}). {{$opt['Option']}}</label>
                            @php($_loop++)
                            @endforeach
                            @endif
                        </div>
                        <button class="btn btn-warning" id="prev-question-button" onclick="getPrev()">Previous</button>
                        <button class="btn btn-info" id="next-question-button" onclick="getNext()">Next Question</button>
                        <button onclick="finishExam()" class="btn btn-danger pull-right" id="submit-button">Submit Survey</button>
                    </div>
                    <!-- end window -->
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
            getNext = function(){
                $.LoadingOverlay("show");
                var choice = $("input[name='choice']:checked").val();
                if( choice === undefined )
                {
                    showToastDanger('Kindly choose at least one answer');
                    $.LoadingOverlay("hide");
                    return;
                }
                var this_exam = $("#examid").val();
                var this_question = $("#questionid").val();
                var score = $("#scoreid").val();
                var data = "choice=" + choice + "&exam=" + this_exam + "&question=" + this_question + "&mark=" + score;
                $.ajax({
                    type:"post",
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{route('sp_next')}}",
                    data: data,
                    success: function(res){
                        console.log(res.data);
                        $.LoadingOverlay('hide');
                        $('#question').html(res.data);
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
            getPrev = function(){
                $.LoadingOverlay("show");
                var choice = $("input[name='choice']:checked").val();
                if( choice === undefined )
                {
                    showToastDanger('Kindly choose at least one answer');
                    $.LoadingOverlay("hide");
                    return;
                }
                var this_exam = $("#examid").val();
                var this_question = $("#questionid").val();
                var score = $("#scoreid").val();
                var data = "choice=" + choice + "&exam=" + this_exam + "&question=" + this_question + "&mark=" + score;
                $.ajax({
                    type:"post",
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{route('sp_prev')}}",
                    data: data,
                    success: function(res){
                        console.log(res.data);
                        $.LoadingOverlay('hide');
                        $('#question').html(res.data);
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
            finishExam = function(){
                $.LoadingOverlay("show");
                var this_exam = $("#examid").val();
                var data = "exam=" + this_exam;
                $.ajax({
                    type:"post",
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{route('sp_finish')}}",
                    data: data,
                    success: function(res){
                        console.log(res.data);
                        $.LoadingOverlay('hide');
                        window.location.href = res.goto;
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
