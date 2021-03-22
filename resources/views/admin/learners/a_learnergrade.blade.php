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
            <div>&nbsp;>&nbsp;Grades & Grading</div>
        </div>
    </div>
    <!-- sub navigation -->
    <hr>
    <div class="page-title-heading">
        <h5 class="card-title">
            <span class="pull-right">
                <a href="{{route('a_learnerhome', ['id' => $learner['id']] )}}" class="btn mr-2 mb-2 btn-outline-info fundsmodal">Manage Learner</a>
                <a href="{{route('a_learnerperf', ['id' => $learner['id']] )}}" class="btn mr-2 mb-2 btn-outline-info fundsmodal">Performance</a>
                <a href="{{route('a_learnergrade', ['id' => $learner['id']] )}}" class="btn mr-2 mb-2 btn-info fundsmodal">Grades & Grading</a>
            </span>
        </h5>
    </div>
    <!-- end subnavigation -->
</div>
<!-- manage learner -->
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Grade Entries</h5>
                <br>
                <!-- messges -->
                @if(Session::get('status') && Session::get('status') == 2000)
                <div class="alert alert-success">{{Session::get('message')}}</div>
                @endif
                @if(Session::get('status') && Session::get('status') == 2001)
                <div class="alert alert-danger">{{Session::get('message')}}</div>
                @endif
                <!-- recents -->
                <div class="main">
                    <form action="{{route('a_gradeunit')}}" method="POST" id="newlearner">
                        @csrf
                        @method('put')
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label class="lab">Select Unit</label>
                                    <div class="input-group">
                                        <input type="hidden" value="{{$learner['id']}}" name="learner">
                                        <select class="form-control" name="unit" id="unit" aria-describedby="inputGroupPrepend" required>
                                            <option value="nn">Select unit to grade</option>
                                            @if(count($enrolled_units))
                                            @foreach($enrolled_units as $eu )
                                            <option value="{{$eu['id']}}">{{strtolower($eu['name'])}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label class="lab">Score in Assessments(max 40)</label>
                                    <div class="input-group">
                                        <input type="number" placeholder="e.g. 27" class="form-control" name="assessment" id="assessment" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label class="lab">Score in final exam(max 60)</label>
                                    <div class="input-group">
                                        <input type="number" placeholder="e.g. 41" class="form-control" name="exam" id="exam" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="mt-2 btn btn-primary">Grade Now </button>
                    </form>
                </div>
                <!-- end recents -->
            </div>
        </div>
    </div>
</div>
<!-- end -->
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Pending Units</h5>
                <br>
                <!-- messges -->
                @if(Session::get('status') && Session::get('status') == 200)
                <div class="alert alert-success">{{Session::get('message')}}</div>
                @endif
                @if(Session::get('status') && Session::get('status') == 201)
                <div class="alert alert-danger">{{Session::get('message')}}</div>
                @endif
                <!-- recents -->
                <div class="table-responsive">
                    <table class="mb-0 table table-sm">
                        <thead>
                            <tr>
                                <th><small>Unit Name</small></th>
                                <th><small>Assessments Score</small></th>
                                <th><small>Exam Score</small></th>
                                <th><small>Final Score</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($pending_units))
                                @foreach( $pending_units as $_pu )
                                @php( $perf = App\LearnerUnitPerformance::where('unit', $_pu['id'])->where('learner', $learner['id'])->first() )
                                <tr>
                                    <td>{{ ucwords(strtolower($_pu['name'])) }}</td>
                                    <td> 0/40</td>
                                    <td> 0/60</td>
                                    <td> 0/100</td>
                                </tr>
                                @endforeach
                            @else
                           <tr><td colspan="3"> <i>No no pending units for {{$learner['name']}}</i></td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- end recents -->
            </div>
        </div>
        <!-- completed units -->
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Completed Units</h5>
                <br>
                <!-- messges -->
                @if(Session::get('status') && Session::get('status') == 200)
                <div class="alert alert-success">{{Session::get('message')}}</div>
                @endif
                @if(Session::get('status') && Session::get('status') == 201)
                <div class="alert alert-danger">{{Session::get('message')}}</div>
                @endif
                <!-- recents -->
                <div class="table-responsive">
                    <table class="mb-0 table table-sm">
                        <thead>
                            <tr>
                                <th><small>Unit Name</small></th>
                                <th><small>Assessments Score</small></th>
                                <th><small>Exam Score</small></th>
                                <th><small>Final Score</small></th>
                                <th><small>Status</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($completed_units))
                                @foreach( $completed_units as $_cu )
                                @php( $perf = App\LearnerUnitPerformance::where('unit', $_cu['id'])->where('learner', $learner['id'])->first() )
                                @php( $status = '<a class="mb-2 mr-2 btn-transition btn btn-danger">Fail</a>')
                                @if( $perf->is_passed )
                                @php( $status = '<a class="mb-2 mr-2 btn-transition btn btn-success">Pass</a>')
                                @endif
                                <tr>
                                    <td>{{ ucwords(strtolower($_cu['name'])) }}</td>
                                    <td>{{ $perf->assessment }}/40</td>
                                    <td>{{ $perf->exam }}/60</td>
                                    <td>{{ $perf->final }}/100</td>
                                    <td>{!! $status !!}</td>
                                </tr>
                                @endforeach
                            @else
                           <tr><td colspan="3"> <i>No no completed units for {{$learner['name']}}</i></td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- end recents -->
            </div>
        </div>
        <!-- end complte -->
    </div>
</div>
@endsection

@section('footer')
    @include('commons/footer')
@endsection
