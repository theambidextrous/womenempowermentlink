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
            <div>&nbsp;>&nbsp;Assignments</div>
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
                <h5 class="card-title">Available assignments
                    <span class="pull-right"> 
                        <!-- <button type="button" class="btn mr-2 mb-2 btn-info fundsmodal" data-toggle="modal" data-target="#fundsmodal"> <i class="pe-7s-plus text-white"></i> Enroll new course</button> -->
                    </span>
                </h5>
                <p>You have access to all assignments under this unit. You can submit your assignment any time before expiry.</p>
                <br>
                @if(count($assignments))
                @foreach( $assignments as $_assignment )
                @php($submission = App\LearnerAssignment::where('learner', Auth::user()->id)->where('assignment', $_assignment['id'])->first())
                @if(is_null($submission) )
                    @php($submission = [] )
                @else
                    @php( $submission = $submission->toArray() )
                @endif
                <div class="card-shadow-primary mb-3 card card-body border-primary">
                    <h5 class="card-title lesson-title">{{ucwords(strtolower($_assignment['title']))}}</h5>
                    <p>{{$_assignment['content']}}</p>
                    <p class="text-success">{{$_assignment['maxscore']}}mks</p>
                    @if(!count($submission))
                    <p class="alert alert-danger">You have not submitted this assignment</p>
                    <p class="text-success">Your Score: <b>0.0 mks</b></p>
                    <div class="form-row">
                        <div class="col-md-12">
                            <!-- submit form -->
                            <form class="form-inline" action="{{route('s_assignsubmit')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="col-sm-7 mr-2">
                                    <input type="hidden" name="unit" value="{{$this_unit['id']}}">
                                    <input type="hidden" name="assignment" value="{{$_assignment['id']}}">
                                    <input name="subfile" type="file" class="form-control-lg form-control" required>
                                </div>
                                <div class="col-sm-4">
                                    <button type="submit" class="btn mr-2 mb-2 pull-right btn-primary btn-reply btn-lg"> <i class="pe-7s-diskette text-white"> </i> Upload assignment</button>
                                </div>
                            </form>
                            <!-- end -->
                        </div>
                    </div>
                    @else
                    @if(!$submission['is_marked'])
                    <p class="alert alert-warning">Tutor is reviewing your submission</p>
                    @endif
                    <p class="text-success">Your Score: <b>{{$submission['score']}} mks</b></p>
                    @endif
                    <br>
                    <div class="form-row">
                        <div class="col-md-6">
                            <small class="opacity-6"><i class="fa fa-calendar-alt mr-1"></i>
                                {{date('h:i a', strtotime($_assignment['created_at']))}} | {{date('M jS, y', strtotime($_assignment['created_at']))}}
                            </small>
                        </div>
                    </div>
                   <!-- end -->
                </div>
                @endforeach
                @else
                <div class="card-shadow-primary mb-3 card card-body border-primary">
                    <h5 class="card-title lesson-title">Ooops!</h5>
                    <p>No assignments yet. Check back in a moment.</p>
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
