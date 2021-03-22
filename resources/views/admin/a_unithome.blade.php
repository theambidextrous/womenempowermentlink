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
.lab{
    font-weight:600;
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
                <i class="pe-7s-bookmarks text-info">
                </i>
            </div>
            <div><a href="{{route('a_home')}}">Courses/Programmes </a> </div>
            <div>&nbsp;>&nbsp;<a href="{{route('a_coursehome',['id' => $unit['course']])}}">{{ucwords(strtolower(App\Course::find($unit['course'])->name))}}</a></div>
            <div>&nbsp;>&nbsp;{{ucwords(strtolower($unit['name']))}}</div>
        </div>
    </div>
    <!-- sub navigation -->
    <hr>
    <div class="page-title-heading">
        <h5 class="card-title">
            <span class="pull-right">
                <a href="{{route('a_unithome', ['id' => $unit['id']] )}}" class="btn mr-2 mb-2 btn-info fundsmodal">Manage Unit</a>
                <a href="{{route('a_uassign', ['id' => $unit['id']] )}}" class="btn mr-2 mb-2 btn-outline-info fundsmodal">Unit Assignments</a>
                <a href="{{route('a_uexams', ['id' => $unit['id']] )}}" class="btn mr-2 mb-2 btn-outline-info fundsmodal">Unit Quizes, Exams & Surveys</a>
            </span>
        </h5>
    </div>
    <!-- end subnavigation -->
</div>
<!-- manage course -->
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Manage Unit
                    <span class="pull-right">
                    <form action="{{route('a_delunit', ['id' => $unit['id']])}}" method="POST" id="delunit">
                    @csrf
                    @method('put')
                    <button type="submit" class="btn mr-2 mb-2 btn-danger fundsmodal"> <i class="pe-7s-plus text-white"></i> Delete Unit</button>
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
                    <form action="{{route('a_editunit', ['id' => $unit['id']])}}" method="POST" id="newunit">
                        @csrf
                        @method('put')
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label class="lab">Unit Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="name" id="name" value="{{strtolower($unit['name'])}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label class="lab">Unit Pass Score</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="pass_score" id="pass_score" value="{{$unit['pass_score']}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label class="lab">Unit Description</label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="description" id="description" aria-describedby="inputGroupPrepend" required>{{$unit['description']}}</textarea>
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
<!-- course units -->
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Manage Unit Lessons
                    <span class="pull-right"> 
                        <button type="button" class="btn mr-2 mb-2 btn-info fundsmodal" data-toggle="modal" data-target="#fundsmodal"> <i class="pe-7s-plus text-white"></i> Add Lesson</button>
                        <button type="button" class="btn mr-2 mb-2 btn-primary fundsmodal" data-toggle="modal" data-target="#liveless"> <i class="pe-7s-plus text-white"></i> Add Live Lesson</button>
                    </span>
                </h5>
                <br>
                <!-- messges -->
                @if(Session::get('status') && Session::get('status') == 200)
                <!-- @php( $units = Session::get('units')) -->
                <div class="alert alert-success">{{Session::get('message')}}</div>
                @endif
                @if(Session::get('status') && Session::get('status') == 201)
                <!-- @php( $units = Session::get('units')) -->
                <div class="alert alert-danger">{{Session::get('message')}}</div>
                @endif
                <!-- recents -->
                <div class="table-responsive">
                    <table class="mb-0 table table-sm">
                        <thead>
                            <tr>
                                <th><small>Title</small></th>
                                <th><small>Last Updated</small></th>
                                <th><small>Action</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($lessons))
                                @foreach( $lessons as $_lesson )
                                <tr>
                                    <td><a href="{{route('a_lessonhome',['id' => $_lesson['id']])}}" class="mb-2 mr-2 btn-transition btn btn-link-primary">{{$_lesson['name']}}</a></td>
                                    <td>{{date('M jS, 2020', strtotime($_lesson['updated_at']))}}</td>
                                    <td><a href="{{route('a_lessonhome',['id' => $_lesson['id']])}}" class="mb-2 mr-2 btn-transition btn btn-outline-primary">View Content</a>
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
<!-- end -->
@endsection

@section('footer')
    @include('commons/footer')
@endsection
<!-- Modal -->
<div class="modal fade" id="fundsmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">New Lesson</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('a_addlesson')}}" method="POST" id="newlesson" enctype="multipart/form-data">
                @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <input type="hidden" name="unit" value="{{$unit['id']}}">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="enter lesson title" aria-describedby="inputGroupPrepend" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <textarea class="form-control" name="description" id="description" placeholder="enter lesson brief" aria-describedby="inputGroupPrepend" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <input type="file" class="form-control" name="content" id="content" placeholder="enter zoom link" aria-describedby="inputGroupPrepend" required>
                                </div>
                                <small>* upload a file. pdf,docs,mp4,mkv & avi only</small>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 btn btn-primary">Add Lesson </button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!-- mmmm -->
<div class="modal fade" id="liveless" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">New Live Lesson</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('a_addlesson_live')}}" method="POST" id="newlesson">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <input type="hidden" name="unit" value="{{$unit['id']}}">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="enter lesson title" aria-describedby="inputGroupPrepend" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <textarea class="form-control" name="description" id="description" placeholder="enter lesson brief" aria-describedby="inputGroupPrepend" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="live_link" id="live_link" placeholder="enter zoom link" aria-describedby="inputGroupPrepend" required>
                                </div>
                                <small>* a link to the live zoom class or equivalent</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label>Class date</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="live_time_a" id="live_time_a" placeholder="Class date & time" aria-describedby="inputGroupPrepend" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Class time</label>
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <input type="time" class="form-control" name="live_time_b" id="live_time_b" placeholder="Class date & time" aria-describedby="inputGroupPrepend" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 btn btn-primary">Add Live Class </button>
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>
