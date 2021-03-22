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
            <div>&nbsp;>&nbsp;{{ucwords(strtolower($learner['name']))}}</div>
        </div>
    </div>
    <!-- sub navigation -->
    <hr>
    <div class="page-title-heading">
        <h5 class="card-title">
            <span class="pull-right">
                <a href="{{route('a_learnerhome', ['id' => $learner['id']] )}}" class="btn mr-2 mb-2 btn-info fundsmodal">Manage Learner</a>
                <a href="{{route('a_learnerperf', ['id' => $learner['id']] )}}" class="btn mr-2 mb-2 btn-outline-info fundsmodal">Performance</a>
                <a href="{{route('a_learnergrade', ['id' => $learner['id']] )}}" class="btn mr-2 mb-2 btn-outline-info fundsmodal">Grades & Grading</a>
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
                <h5 class="card-title">Manage learner
                    <span class="pull-right">
                    <form action="{{route('a_dellearner', ['id' => $learner['id']])}}" method="POST" id="dellearner">
                    @csrf
                    @method('put')
                    <button type="submit" class="btn mr-2 mb-2 btn-danger fundsmodal"> <i class="pe-7s-plus text-white"></i> Delete learner</button>
                    </form> 
                    </span>
                </h5>
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
                    <form action="{{route('a_editlearner', ['id' => $learner['id']])}}" method="POST" id="newlearner">
                        @csrf
                        @method('put')
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label class="lab">Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="name" id="name" value="{{strtolower($learner['name'])}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label class="lab">Email</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="email" id="email" value="{{strtolower($learner['email'])}}" aria-describedby="inputGroupPrepend" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label class="lab">Phone Number</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="phone" id="phone" value="{{strtolower($learner['phone'])}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label class="lab">Gender</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="gender" id="gender" value="{{strtolower($learner['gender'])}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label class="lab">Special Needs</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="special_needs" id="special_needs" value="{{strtolower($learner['special_needs'])}}" aria-describedby="inputGroupPrepend">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label class="lab">County</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="county" id="county" value="{{strtolower($learner['county'])}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label class="lab">Constituency</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="constituency" id="constituency" value="{{strtolower($learner['constituency'])}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label class="lab">Ward</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="ward" id="ward" value="{{strtolower($learner['ward'])}}" aria-describedby="inputGroupPrepend">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label class="lab">Date Enrolled</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="created_at" id="created_at" value="{{date('m/d/Y', strtotime($learner['created_at']))}}" aria-describedby="inputGroupPrepend" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="mt-2 btn btn-primary">Save changes </button>
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
                <h5 class="card-title">
                    Course Enrollments
                    <span class="pull-right"> 
                        <button type="button" class="btn mr-2 mb-2 btn-primary fundsmodal" data-toggle="modal" data-target="#fundsmodal"> <i class="pe-7s-plus text-white">
                </i> Enroll learner</button>
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
                <div class="table-responsive">
                    <table class="mb-0 table table-sm">
                        <thead>
                            <tr>
                                <th><small>Course Name</small></th>
                                <th><small>Completed Status</small></th>
                                <th><small>Drop</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($courses))
                                @foreach( $courses as $_course )
                                    @php( $compl = 'Pending')
                                    @if($_course['is_completed'])
                                    @php( $compl = 'Completed')
                                    @endif
                                <tr class="course">
                                    <td class="courserow">{{ucwords(strtolower($_course['course_name']))}}</td>
                                    <td class="courserow">{{$compl}}</td>
                                    <td class="courserow"><a href="{{route('a_drop_course',['id' => $learner['id'].'~'.$_course['id'] ])}}" class="mb-2 mr-2 btn-transition btn btn-danger">Drop course</a>
                                    </td>
                                </tr>
                                <tr class="unitrow">
                                    <td colspan="3">Course Units - <small>Enrolled automatically</small></td>
                                </tr>
                                <tr>
                                <td  colspan="3">
                                <!-- inner table -->
                                    <table>
                                        <tbody>
                                        @if(count($_course['course_units']))
                                            @php($count = 1)
                                            @foreach( $_course['course_units'] as $_c_unit )
                                                <tr>
                                                    <td>({{$count}}) {{strtolower($_c_unit['name'])}}</td>
                                                </tr>
                                                @php($count++)
                                            @endforeach
                                        @else
                                        <tr><td> <i>No units for this course yet</i></td></tr>
                                        @endif
                                        <tbody>
                                    </table>
                                <!-- end inner -->
                                </td>
                                </tr>
                                @endforeach
                            @else
                           <tr><td colspan="3"> <i>No courses enrolled yet</i></td></tr>
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
<!-- Modal -->
<div class="modal fade" id="fundsmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">Enroll learner to a Course</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('a_enroll_course')}}" method="POST" id="newcourse">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <input type="hidden" name="learner" value="{{$learner['id']}}"/>
                                    <select class="form-control" multiple name="course[]" id="course" aria-describedby="inputGroupPrepend" required>
                                    @if(count($all_courses))
                                        @foreach( $all_courses as $_au)
                                        <option value="{{$_au['id']}}">{{ucwords(strtolower($_au['name']))}}</option>
                                        @endforeach
                                    @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 btn btn-primary">Enroll Now </button>
                </form>
            </div>
            <div class="modal-footer">
               
            </div>
        </div>
    </div>
</div>
