@extends('layouts.inner')


@section('topnav')
    @include('commons/topnav')
@endsection


@section('sidenav')
    @include('commons/sidenav_admin')
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
.course{
    background: #ed1d29;
    padding:15px!important;
    color: white;
}
.unitrow{
    background: antiquewhite;
    font-weight:bold;
}
.ico-center{
    font-size: 20px!important;
    vertical-align: middle!important;
    padding: 3px!important;
}
.ftf{
    font-weight:900;
}
</style>
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-users text-info">
                </i>
            </div>
            <div><a href="{{route('a_learners')}}">Learners </a> </div>
            <div>&nbsp;>&nbsp;<a href="{{route('a_learnerhome', ['id' => $learner['id']] )}}">{{ucwords(strtolower($learner['name']))}}
            </a></div>
            <div>&nbsp;>&nbsp;Performance</div>
        </div>
    </div>
    <!-- sub navigation -->
    <hr>
    <div class="page-title-heading">
        <h5 class="card-title">
            <span class="pull-right">
                <a href="{{route('a_learnerhome', ['id' => $learner['id']] )}}" class="btn mr-2 mb-2 btn-outline-info fundsmodal">Manage Learner</a>
                <a href="{{route('a_learnerperf', ['id' => $learner['id']] )}}" class="btn mr-2 mb-2 btn-info fundsmodal">Performance</a>
                <a href="{{route('a_learnergrade', ['id' => $learner['id']] )}}" class="btn mr-2 mb-2 btn-outline-info fundsmodal">Grades & Grading</a>
            </span>
        </h5>
    </div>
    <!-- end subnavigation -->
</div>
<!-- manage  -->
<ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
    <li class="nav-item">
        <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
            <span>Assignments Performance</span>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
            <span>Exams & Quizes Performance</span>
        </a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
    <!-- assign -->
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Assignments Performance</h5><br>
            <div class="table-responsive">
                <table class="mb-0 table table-sm">
                    <thead>
                        <tr>
                            <th><small>Assignment</small></th>
                            <th><small>Course/Unit</small></th>
                            <th><small>Score</small></th>
                            <th><small>Marking Status</small></th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($assignments_done))
                    @foreach ( $assignments_done as $_ad )
                    @php($assign_meta = App\Assignment::find($_ad['assignment']) )
                    @php( $acourse = App\Course::find($assign_meta->course) )
                    @php( $aunit = App\Unit::find($assign_meta->unit) )
                    @php( $mkstatus = '(<i class="pe-7s-close-circle text-danger ico-center"></i>)')
                    @if( $_ad['is_marked'])
                    @php($mkstatus = '(<i class="pe-7s-check text-info ico-center"></i>)')
                    @endif
                    <tr>
                        <td>{{ ucwords(strtolower($assign_meta->title)) }}</td>
                        <td>{!! ucwords(strtolower($acourse->name .' <b class="ftf">>></b> '.$aunit->name )) !!}</td>
                        <td>{{ $_ad['score'] }}</td>
                        <td>{!! $mkstatus !!}</td>
                    </tr>
                    @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- end -->
    </div>
    <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
        <!-- exam -->
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Exams & Quizes Performance</h5><br>
                <div class="table-responsive">
                    <table class="mb-0 table table-sm">
                        <thead>
                            <tr>
                                <th><small>Exam</small></th>
                                <th><small>Course/Unit</small></th>
                                <th><small>Score</small></th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(count($exams_done))
                        @foreach ( $exams_done as $_ed )
                        @php($exam_meta = App\Exam::find($_ed['exam']) )
                        @php( $ecourse = App\Course::find($exam_meta->course) )
                        @php( $eunit = App\Unit::find($exam_meta->unit) )
                        <tr>
                            <td>{{ ucwords(strtolower($exam_meta->title)) }}</td>
                            <td>{!! ucwords(strtolower($ecourse->name .' <b class="ftf">>></b> '.$eunit->name )) !!}</td>
                            <td>{{ $_ed['score'] }}</td>
                        </tr>
                        @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- end -->
    </div>
</div>

<!-- </div> -->
<!-- end -->

@endsection

@section('footer')
    @include('commons/footer')
@endsection
