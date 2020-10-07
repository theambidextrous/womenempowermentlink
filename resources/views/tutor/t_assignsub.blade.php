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
            @php( $unit_meta = App\Unit::find($this_unit['id']))
            <div><a href="{{route('t_home')}}">My Units </a> </div>
            <div>&nbsp;>&nbsp;<a href="{{route('t_assignhome', ['unit' => $this_unit['id']])}}">Assignments</a></div>
            <div>&nbsp;>&nbsp;{{ucwords(strtolower($this_assignment['title']))}}</div>
            <div>&nbsp;>&nbsp;Submissions</div>
        </div>
    </div>
</div>
<!-- manage course -->
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Submissions in this Assignment
                    <span class="pull-right"> 
                        <!-- <button type="button" class="btn mr-2 mb-2 btn-info fundsmodal" data-toggle="modal" data-target="#fundsmodal"> <i class="pe-7s-plus text-white"></i> Add Assignment</button> -->
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
                                <th><small>Uploaded file</small></th>
                                <th><small>Marks</small></th>
                                <th><small>Grading</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($submissions))
                                @foreach( $submissions as $_submission )
                                @php($action = $this_unit['id'].'~'.$this_assignment['id'])
                                <tr>
                                    <td>{{App\User::find($_submission['learner'])->name }}</td>
                                    <td><a href="{{route('file_stream', ['file'=>$_submission['submission_file']])}}">View File</a></td>
                                    <td>{{$_submission['score']}}</td>
                                    <td>
                                    <!-- grade form -->
                                    <form class="form-inline" action="{{route('t_assigngrade')}}" method="POST">
                                        @csrf
                                        <div class="col-sm-8">
                                            <input type="hidden" name="id" value="{{$_submission['id']}}">
                                            <input type="hidden" name="hash" value="{{$action}}">
                                            <input placeholder="enter mark" name="score" type="text"class="form-control-lg form-control">
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="submit" class="btn mr-2 mb-2 btn-primary btn-reply"> <i class="pe-7s-diskette text-white"> </i> Save grade</button>
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
                <form action="{{route('t_addassign')}}" method="POST" id="newlesson" enctype="multipart/form-data">
                @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Title</label>
                                <div class="input-group">
                                    <input type="hidden" name="unit" value="{{$this_unit['id']}}">
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
