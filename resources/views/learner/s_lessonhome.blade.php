@extends('layouts.inner')


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
            <div>&nbsp;>&nbsp;Lesosns</div>
        </div>
    </div>
</div>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Available lessons
                    <span class="pull-right"> 
                        <!-- <button type="button" class="btn mr-2 mb-2 btn-info fundsmodal" data-toggle="modal" data-target="#fundsmodal"> <i class="pe-7s-plus text-white"></i> Enroll new course</button> -->
                    </span>
                </h5>
                <p>You have access to all lessons under this unit. There are live lessons which are scheduled at specific dates and time with access links under them.</p>
                <br>
                @if(count($lessons))
                @foreach( $lessons as $_lesson )
                @php($class_ = '')
                @if($_lesson['content'] == 'not_applicable')
                @php($class_ = 'l-live')
                @endif
                <div class="card-shadow-primary mb-3 card card-body border-primary {{$class_}}">
                    <h5 class="card-title lesson-title">{{ucwords(strtolower($_lesson['name']))}}</h5>
                    <p>{{$_lesson['description']}}</p>
                    @if($_lesson['content'] == 'not_applicable')
                   <!-- live class -->
                   <p class="text-success">Type: <small>Live class</small></p>
                   <p class="text-success">Date & Time: <small>{{date('M jS, y', strtotime($_lesson['live_time']))}} {{date('h:i a', strtotime($_lesson['live_time']))}}</small></p>
                   <div class="form-row">
                        <div class="col-md-6">
                        <br>
                            <small class="opacity-6"><i class="fa fa-calendar-alt mr-1"></i>
                                {{date('h:i a', strtotime($_lesson['created_at']))}} | {{date('M jS, y', strtotime($_lesson['created_at']))}}
                            </small>
                        </div>
                        <div class="col-md-6">
                            <a download target="_blank" href="{{$_lesson['live_link']}}" class="mb-2 mr-2 btn-transition btn btn-primary"> <i class="pe-7s-video"> </i> Join live Class</a>
                        </div>
                    </div>
                   <!-- end -->
                    @else
                    <!-- recorded class -->
                   <p class="text-success">Type: <small>Recorded/File upload</small></p>
                    @if(preg_match('/^.*\.(mp4|mov|mpg|mpeg|wmv|mkv)$/i', $_lesson['content']))
                        <!-- video lesson -->
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="{{route('file_stream', ['file' => $_lesson['content']])}}" allowfullscreen></iframe>
                                </div>
                            </div>
                            <div class="col-md-6 has-padding">
                            <br>
                                <small class="opacity-6"><i class="fa fa-calendar-alt mr-1"></i>
                                    {{date('h:i a', strtotime($_lesson['created_at']))}} | {{date('M jS, y', strtotime($_lesson['created_at']))}}
                                </small>
                            </div>
                            <div class="col-md-6 has-padding">
                                <a download target="_blank" href="{{route('file_stream', ['file' => $_lesson['content']])}}" class="mb-2 mr-2 btn-transition btn btn-primary"> <i class="pe-7s-cloud-download"> </i> Download Video</a>
                            </div>
                        </div>
                        <!-- end video -->
                    @else
                        <!-- file -->
                        <div class="form-row">
                            <div class="col-md-6">
                            <br>
                                <small class="opacity-6"><i class="fa fa-calendar-alt mr-1"></i>
                                    {{date('h:i a', strtotime($_lesson['created_at']))}} | {{date('M jS, y', strtotime($_lesson['created_at']))}}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <a download target="_blank" href="{{route('file_stream', ['file' => $_lesson['content']])}}" class="mb-2 mr-2 btn-transition btn btn-primary"> <i class="pe-7s-cloud-download"> </i> Download File</a>
                            </div>
                        </div>
                        <!-- end file -->
                    @endif
                   <!-- end -->
                    @endif
                </div>
                @endforeach
                @else
                <div class="card-shadow-primary mb-3 card card-body border-primary">
                    <h5 class="card-title lesson-title">Ooops!</h5>
                    <p>No lessons yet. Check back in a moment.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('commons/footer')
@endsection
