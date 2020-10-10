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
.course-title{
    color: #fff;
    background: #4267b4;
    font-size: 19px;
    font-weight: 300;
}
</style>
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-home text-info">
                </i>
            </div>
            <div>Learner Dashboard</div>
        </div>
    </div>
</div>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">My enrolled courses
                    <span class="pull-right"> 
                        <button type="button" class="btn mr-2 mb-2 btn-info fundsmodal" data-toggle="modal" data-target="#fundsmodal"> <i class="pe-7s-plus text-white"></i> Enroll new course</button>
                    </span>
                </h5>
                <p>My enrolled courses - As a learner, you have the capability to enroll to courses you want.</p>
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
                                <th>Course Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($mycourses))
                                @foreach( $mycourses as $_mycourse )
                                <tr>
                                    <td class="course-title">{{ucwords(strtolower($_mycourse['name']))}}</td>
                                </tr>
                                <tr><td>Course Units</td></tr>
                                <tr>
                                    <td>
                                    <!-- units -->
                                    <table class="mb-0 table table-sm reportable">
                                        <thead>
                                            <tr>
                                                <th>Unit Name</th>
                                                <th>Lessons</th>
                                                <th>Assignments</th>
                                                <th>Exams, quizes & surveys</th>
                                                <th>Grades</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php($units = App\Unit::where('course', $_mycourse['id'])->get())
                                        @if(!is_null($units))
                                        @php( $units = $units->toArray() )
                                        @if(count($units))
                                        @foreach( $units as $_unit )
                                        <tr>
                                            <td>{{ucwords(strtolower($_unit['name']))}}</td>
                                            <td><a href="{{route('s_lessonhome', ['unit' => $_unit['id'] ])}}" class="mb-2 mr-2 btn-transition btn btn-primary">Open Lessons</a></td>
                                            <td><a href="{{route('s_assignhome', ['unit' => $_unit['id'] ])}}" class="mb-2 mr-2 btn-transition btn btn-info">Open Assignments</a></td>
                                            <td><a href="{{route('s_examhome', ['unit' => $_unit['id'] ])}}" class="mb-2 mr-2 btn-transition btn btn-warning">Open Exams, Quizes & Surveys</a></td>
                                            <td><a href="#" class="mb-2 mr-2 btn-transition btn btn-success">Open Grades</a></td>
                                        </tr>
                                        @endforeach
                                        @endif
                                        @endif
                                        </tbody>
                                    </table>
                                    <!-- end units -->
                                    </td>
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
<!-- modal -->
<div class="modal fade" id="fundsmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">Enroll new course</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('s_enrollcourse')}}" method="POST" id="newlesson" enctype="multipart/form-data">
                @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Select course</label>
                                <div class="input-group">
                                    <select class="form-control" name="course" id="course" aria-describedby="inputGroupPrepend" required>
                                        @if(count($all_courses))
                                        <option value="nn">Select a course to enroll</option>
                                        @foreach( $all_courses as $_all_courses )
                                        <option value="{{$_all_courses['id']}}">{{ucwords(strtolower($_all_courses['name']))}}</option>
                                        @endforeach
                                        @else
                                        <option value="nn">There are no more courses to enroll</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 btn btn-primary">Enroll course </button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
