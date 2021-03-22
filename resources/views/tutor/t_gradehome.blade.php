@extends('layouts.inner')


@section('topnav')
    @include('commons/topnav')
@endsection


@section('sidenav')
    @include('commons/sidenav_tutor')
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
.lab{
    font-weight:600;
}
.form-control {
    border: solid 0px;
    border-bottom: 1px solid #ced4da;
    border-radius: .2rem;
}
.form-grade{
    width: 150px!important;
    margin-right: 30px!important;
}
</style>
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-bookmarks text-info">
                </i>
            </div>
            @php( $unit_meta = App\Unit::find($this_unit['id']))
            <div><a href="{{route('t_home')}}">My Units </a> </div>
            <div>&nbsp;>&nbsp;{{ucwords(strtolower($this_unit['name']))}}</div>
            <div>&nbsp;>&nbsp;Grading</div>
        </div>
    </div>
</div>
<!-- manage course -->
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Unit Enrollments 
                    <span class="pull-right"> 
                    </span>
                </h5>
                <br>
                <!-- messges -->
                @if(Session::get('status') && Session::get('status') == 200)
                <div class="alert alert-success">{{Session::get('message')}}</div>
                @endif
                @if(Session::get('status') && Session::get('status') == 201)
                <div class="alert alert-danger">{{Session::get('message')}}</div>
                @endif
                <!-- recents -->
                <div class="main">
                    <div class="table-responsive">
                    <table class="mb-0 table table-sm reportable">
                        <thead>
                            <tr>
                                <th><small>Learner Name</small></th>
                                <th><small>Assessment Score</small></th>
                                <th><small>Exam Score</small></th>
                                <th><small>Final Grade</small></th>
                                <th><small>Action</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($this_unit_student))
                                @foreach( $this_unit_student as $_this_unit_student )
                                @php($perf = App\LearnerUnitPerformance::where('learner', $_this_unit_student['id'])->where('unit', $this_unit['id'])->first() )
                                @php($assess = 0)
                                @php($exam = 0)
                                @php($final = 0)
                                @if(!is_null($perf))
                                    @php($assess = $perf->assessment)
                                    @php($exam = $perf->exam)
                                    @php($final = $perf->final)
                                @endif
                                <tr>
                                    <td>{{$_this_unit_student['name']}}</td>
                                    <td>{{$assess}}</td> 
                                    <td>{{$exam}}</td>
                                    <td>{{$final}}</td>
                                    <td>
                                        <!-- grade form -->
                                        <form class="form-inline" action="{{route('t_gradeunit')}}" method="POST">
                                            @csrf
                                            @method('put')
                                            <div class="col-sm-3 mr-2">
                                                <label><small>Assessments</small></label>
                                                <input type="hidden" name="learner" value="{{$_this_unit_student['id']}}">
                                                <input type="hidden" name="unit" value="{{$this_unit['id']}}">
                                                <input placeholder="enter mark" name="assessment" type="number" min="0.5" step="0.1" class="form-control-lg form-control form-grade" value="{{$assess}}" required>
                                            </div>
                                            <div class="col-sm-3 ml-2">
                                                <label><small>Final Exam</small></label>
                                                <input placeholder="enter mark" name="exam" type="number" min="0.5" step="0.1" class="form-control-lg form-control form-grade" value="{{$exam}}" required>
                                            </div>
                                            <div class="col-sm-4">
                                                <button type="submit" class="btn mr-2 mb-2 pull-right btn-primary btn-reply btn-lg"> <i class="pe-7s-diskette text-white"> </i> Save grades</button>
                                            </div>
                                        </form>
                                        <!-- end -->
                                    </td>
                                </tr> 
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    </div>
                </div>
                <!-- end recents -->
            </div>
        </div>
    </div>
</div>
<!-- end -->
@endsection

@section('footer')
    @include('commons/footer')
@endsection
