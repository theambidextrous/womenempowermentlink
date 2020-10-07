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
            <div>&nbsp;>&nbsp;<a href="{{route('a_coursehome',['id' => $unit['course']])}}">{{ucwords(strtolower(App\Course::find($unit['course'])->name))}}</a></div>
            <div>&nbsp;>&nbsp;{{ucwords(strtolower($unit['name']))}}</div>
        </div>
    </div>
    <!-- sub navigation -->
    <hr>
    <div class="page-title-heading">
        <h5 class="card-title">
            <span class="pull-right">
                <a href="{{route('a_unithome', ['id' => $unit['id']] )}}" class="btn mr-2 mb-2 btn-outline-info fundsmodal">Manage Unit</a>
                <a href="{{route('a_uassign', ['id' => $unit['id']] )}}" class="btn mr-2 mb-2 btn-info fundsmodal">Unit Assignments</a>
                <a href="{{route('a_uexams', ['id' => $unit['id']] )}}" class="btn mr-2 mb-2 btn-outline-info fundsmodal">Unit Quizes, Exams & Surveys</a>
            </span>
        </h5>
    </div>
    <!-- end subnavigation -->
</div>
<!-- course units -->
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Unit Assignments
                    <span class="pull-right"> 
                        <button type="button" class="btn mr-2 mb-2 btn-info fundsmodal" data-toggle="modal" data-target="#fundsmodal"> <i class="pe-7s-plus text-white"></i> Add Assignment</button>
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
                                <th><small>Content</small></th>
                                <th><small>Max Score</small></th>
                                <th><small>Actions</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($assignments))
                                @foreach( $assignments as $_assignment )
                                @php( $action = $unit['id'].'~'.$_assignment['id'] )
                                <tr>
                                   <td>{{ucwords(strtolower($_assignment['title']))}}</td>
                                   <td>{{$_assignment['content']}}</td>
                                   <td>{{$_assignment['maxscore']}}</td>
                                   <td><a href="{{route('a_delassign', ['id' => $action ])}}">Drop</a></td>
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
                <h5 class="modal-title text-white" id="exampleModalLabel">New Assignment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('a_addassign')}}" method="POST" id="newlesson" enctype="multipart/form-data">
                @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Title</label>
                                <div class="input-group">
                                    <input type="hidden" name="unit" value="{{$unit['id']}}">
                                    <input type="text" class="form-control" name="title" id="title" placeholder="Assignment 1 2020" aria-describedby="inputGroupPrepend" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Assignment</label>
                                <div class="input-group">
                                    <textarea class="form-control" name="content" id="content" placeholder="What are some of the requirements to be an MCA in kenya?" aria-describedby="inputGroupPrepend" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Assignment Max score</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="maxscore" id="maxscore" placeholder="20" aria-describedby="inputGroupPrepend" required>
                                </div>
                                <small>* maximum score if learner was to get all correct</small>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 btn btn-primary">Add Assignment </button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!-- mmmm -->
