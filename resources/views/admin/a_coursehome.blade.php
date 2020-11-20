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
            <div>&nbsp;>&nbsp;{{ucwords(strtolower($course['name']))}}</div>
        </div>
    </div>
</div>
<!-- manage course -->
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Manage Course
                    <span class="pull-right">
                    <form action="{{route('a_delcourse', ['id' => $course['id']])}}" method="POST" id="delcourse">
                    @csrf
                    @method('put')
                    <button type="submit" class="btn mr-2 mb-2 btn-danger fundsmodal"> <i class="pe-7s-plus text-white"></i> Delete Course</button>
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
                    <form action="{{route('a_editcourse', ['id' => $course['id']])}}" method="POST" id="newcourse">
                        @csrf
                        @method('put')
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label class="lab">Course Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="name" id="name" value="{{strtolower($course['name'])}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label class="lab">Course Duration</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="duration" id="duration" value="{{$course['duration']}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label class="lab">Course Description</label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="description" id="description" aria-describedby="inputGroupPrepend" required>{{$course['description']}}</textarea>
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
                <h5 class="card-title">Manage Units
                    <span class="pull-right"> 
                        <button type="button" class="btn mr-2 mb-2 btn-primary fundsmodal" data-toggle="modal" data-target="#fundsmodal"> <i class="pe-7s-plus text-white"></i> Add Unit</button>
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
                <!-- {{print_r($units)}} -->
                    <table class="mb-0 table table-sm">
                        <thead>
                            <tr>
                                <th><small>Name</small></th>
                                <th><small>Max Score</small></th>
                                <th><small>Pass Score</small></th>
                                <th><small>Last Updated</small></th>
                                <th><small>Action</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!is_null($units))
                                @foreach( $units as $_unit )
                                <tr>
                                    <td><a href="{{route('a_unithome',['id' => $_unit['id']])}}" class="mb-2 mr-2 btn-transition btn btn-link-primary">{{$_unit['name']}}</a></td>
                                    <td>{{$_unit['max_score']}}%</td>
                                    <td>{{$_unit['pass_score']}}%</td>
                                    <td>{{date('M jS, 2020', strtotime($_unit['updated_at']))}}</td>
                                    <td><a href="{{route('a_unithome',['id' => $_unit['id']])}}" class="mb-2 mr-2 btn-transition btn btn-outline-primary">Open Lessons</a>
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
                <h5 class="modal-title text-white" id="exampleModalLabel">Create New Unit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('a_addunit')}}" method="POST" id="newunit">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <input type="hidden" name="course" value="{{$course['id']}}">
                                    <input type="hidden" name="max_score" value="100">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="enter unit name" aria-describedby="inputGroupPrepend" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <textarea class="form-control" name="description" id="description" placeholder="enter course description" aria-describedby="inputGroupPrepend" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <input type="number" class="form-control" name="pass_score" id="pass_score" placeholder="enter pass score" aria-describedby="inputGroupPrepend" required>
                                </div>
                                <small>* a score considered a pass for this unit e.g. 40</small>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 btn btn-primary">Add Unit </button>
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>
