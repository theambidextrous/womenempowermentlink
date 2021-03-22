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
.form-control {
    border: solid 0px;
    border-bottom: 1px solid #ced4da;
    border-radius: .2rem;
}
</style>
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-home text-info">
                </i>
            </div>
            <div>Tutor Dashboard</div>
        </div>
    </div>
</div>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">My units</h5>
                <p>My units - As a tutor, you have been assigned units to train. You are currently assigned the following units</p>
                <br>
                <!-- messges -->
                @if(Session::get('status') && Session::get('status') == 200)
                @php( $courses = Session::get('courses'))
                <div class="alert alert-success">{{Session::get('message')}}</div>
                @endif
                @if(Session::get('status') && Session::get('status') == 201)
                @php( $courses = Session::get('courses'))
                <div class="alert alert-danger">{{Session::get('message')}}</div>
                @endif
                <!-- recents -->
                <div class="table-responsive">
                    <table class="mb-0 table table-sm reportable">
                        <thead>
                            <tr>
                                <th><small>Unit</small></th>
                                <th><small>Course </small></th>
                                <th><small>Lessons</small></th>
                                <th><small>Assignments</small></th>
                                <th><small>Exams/Quizes</small></th>
                                <th><small>Grades</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($myunits))
                                @foreach( $myunits as $_myunit )
                                <tr>
                                    <td>{{ucwords(strtolower($_myunit['name']))}}</td>
                                    <td>{{ucwords(strtolower(App\Course::find($_myunit['course'])->name))}}</td>
                                    <td><a href="{{route('t_lessonhome', ['unit' => $_myunit['id'] ])}}" class="mb-2 mr-2 btn-transition btn btn-primary">Lessons</a></td>
                                    <td><a href="{{route('t_assignhome', ['unit' => $_myunit['id'] ])}}" class="mb-2 mr-2 btn-transition btn btn-info">Assignments</a></td>
                                    <td><a href="{{route('t_examhome', ['unit' => $_myunit['id'] ])}}" class="mb-2 mr-2 btn-transition btn btn-warning">Exams, Quizes & Surveys</a></td>
                                    <td><a href="{{route('t_gradehome', ['unit' => $_myunit['id'] ])}}" class="mb-2 mr-2 btn-transition btn btn-success">Grading</a></td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- end recents -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('commons/footer')
@endsection
