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
    background: #4267b4!important;
}
.modal-header {
    border-bottom: 1px solid #4267b4!important;
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
    background-color: #4267b4;
    border: 1px solid #4267b4;
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
            <div>&nbsp;>&nbsp;Exams, Quizes & Surveys</div>
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
                <h5 class="card-title">Available Exams & Quizes
                    <span class="pull-right"> 
                        <!-- <button type="button" class="btn mr-2 mb-2 btn-info fundsmodal" data-toggle="modal" data-target="#fundsmodal"> <i class="pe-7s-plus text-white"></i> Enroll new course</button> -->
                    </span>
                </h5>
                <p>You have access to all active exams and quizes under this unit. You can attempt them any time before expiration.</p>
                <div class="main">
                    <div class="table-responsive">
                    <table class="mb-0 table table-sm reportable2">
                        <thead>
                            <tr>
                                <th><small>Title</small></th>
                                <th><small>Description</small></th>
                                <th><small>Marks</small></th>
                                <th><small>Created</small></th>
                                <th><small>Action</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($this_unit_exam))
                                @foreach( $this_unit_exam as $_this_unit_exam )
                                @if( $_this_unit_exam['type'] != '555555' )
                                @php($action = $this_unit['id'].'~'.$_this_unit_exam['id'])
                                <tr>
                                    <td>{{$_this_unit_exam['title']}}</td>
                                    <td>{{$_this_unit_exam['description']}}</td>
                                    <td>{{$_this_unit_exam['maxscore']}}mks</td>
                                    <td>{{date('M jS, 2020', strtotime($_this_unit_exam['updated_at']))}}</td>
                                    <td>
                                         <a href="{{route('s_examattempt', [ 'exam' => $_this_unit_exam['id'], 'unit' => $this_unit['id'] ])}}" class="mb-2 mr-2 btn-transition btn btn-outline-info">Attempt</a>
                                    </td>
                                </tr>
                                @endif
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
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Available Surveys
                    <span class="pull-right"> 
                        <!-- <button type="button" class="btn mr-2 mb-2 btn-info fundsmodal" data-toggle="modal" data-target="#fundsmodal"> <i class="pe-7s-plus text-white"></i> Enroll new course</button> -->
                    </span>
                </h5>
                <p>You have access to all active surveys under this unit. You can attempt them any time before expiration.</p>
                <div class="main">
                    <div class="table-responsive">
                    <table class="mb-0 table table-sm reportable2">
                        <thead>
                            <tr>
                                <th><small>Title</small></th>
                                <th><small>Description</small></th>
                                <th><small>Marks</small></th>
                                <th><small>Created</small></th>
                                <th><small>Action</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($this_unit_exam))
                                @foreach( $this_unit_exam as $_this_unit_exam )
                                @if( $_this_unit_exam['type'] == '555555' )
                                @php($action = $this_unit['id'].'~'.$_this_unit_exam['id'])
                                <tr>
                                    <td>{{$_this_unit_exam['title']}}</td>
                                    <td>{{$_this_unit_exam['description']}}</td>
                                    <td>{{$_this_unit_exam['maxscore']}}mks</td>
                                    <td>{{date('M jS, 2020', strtotime($_this_unit_exam['updated_at']))}}</td>
                                    <td>
                                         <a href="{{route('s_examattempt', [ 'exam' => $_this_unit_exam['id'], 'unit' => $this_unit['id'] ])}}" class="mb-2 mr-2 btn-transition btn btn-outline-info">Answer Survey</a>
                                    </td>
                                </tr>
                                @endif
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
