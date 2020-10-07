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
            <div>&nbsp;>&nbsp;{{ucwords(strtolower($this_unit['name']))}}</div>
            <div>&nbsp;>&nbsp;Exams & Quizes</div>
        </div>
    </div>
</div>
<!-- manage course -->
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Exams & Quizes in this Unit
                    <span class="pull-right"> 
                        <button type="button" class="btn mr-2 mb-2 btn-info fundsmodal" data-toggle="modal" data-target="#fundsmodal"> <i class="pe-7s-plus text-white"></i> Add Exam</button>
                        <button type="button" class="btn mr-2 mb-2 btn-primary fundsmodal" data-toggle="modal" data-target="#quizmodal"> <i class="pe-7s-plus text-white"></i> Add Quiz</button>
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
                                <th><small>Title</small></th>
                                <th><small>Description</small></th>
                                <th><small>Marks</small></th>
                                <th><small>Created</small></th>
                                <th><small>Action</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($this_unit_exam))
                                @foreach( $this_unit_exam as $_this_unit_exam )
                                @if( $_this_unit_exam['type'] != '555555' )
                                @php($action = $this_unit['id'].'~'.$_this_unit_exam['id'])
                                <tr>
                                    <td>{{$_this_unit_exam['title']}}</td>
                                    <td>{{$_this_unit_exam['description']}}</td>
                                    <td>{{$_this_unit_exam['maxscore']}}mks</td>
                                    <td>{{date('M jS, 2020', strtotime($_this_unit_exam['updated_at']))}}</td>
                                    <td>
                                         <a href="{{route('t_expreview', ['hash' => $action])}}" class="mb-2 mr-2 btn-transition btn btn-outline-info">Preview</a>
                                        <a href="{{route('t_delexam', ['id' => $action])}}" class="mb-2 mr-2 btn-transition btn btn-outline-danger">Drop</a> 
                                    </td>
                                </tr>
                                @endif
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
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Surveys & Course Evaluations in this Unit
                    <span class="pull-right"> 
                        <button type="button" class="btn mr-2 mb-2 btn-info fundsmodal" data-toggle="modal" data-target="#surveymodal"> <i class="pe-7s-plus text-white"></i> Add Survey</button>
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
                    <div class="table-responsive">
                    <table class="mb-0 table table-sm reportable">
                        <thead>
                            <tr>
                                <th><small>Title</small></th>
                                <th><small>Description</small></th>
                                <th><small>Created</small></th>
                                <th><small>Action</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($this_unit_exam))
                                @foreach( $this_unit_exam as $_this_unit_survey )
                                @if( $_this_unit_survey['type'] == '555555' )
                                @php($action = $this_unit['id'].'~'.$_this_unit_survey['id'])
                                <tr>
                                    <td>{{$_this_unit_survey['title']}}</td>
                                    <td>{{$_this_unit_survey['description']}}</td>
                                    <td>{{date('M jS, 2020', strtotime($_this_unit_survey['updated_at']))}}</td>
                                    <td>
                                         <a href="{{route('t_expreview', ['hash' => $action])}}" class="mb-2 mr-2 btn-transition btn btn-outline-info">Preview</a>
                                        <a href="{{route('t_dropassign', ['id' => $action])}}" class="mb-2 mr-2 btn-transition btn btn-outline-danger">Drop</a> 
                                    </td>
                                </tr>
                                @endif
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
@endsection

@section('footer')
    @include('commons/footer')
@endsection
<!-- Modal -->
<div class="modal fade" id="fundsmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">New Exam</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('t_addexam')}}" method="POST" id="newlesson" enctype="multipart/form-data">
                @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Title</label>
                                <div class="input-group">
                                    <input type="hidden" name="type" value="666666">
                                    <input type="hidden" name="unit" value="{{$this_unit['id']}}">
                                    <input type="text" class="form-control" name="title" id="title" placeholder="End of course exam 2020" aria-describedby="inputGroupPrepend" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Description</label>
                                <div class="input-group">
                                    <textarea class="form-control" name="description" id="description" placeholder="Description of the above exam" aria-describedby="inputGroupPrepend" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Total mark Count</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="maxscore" id="maxscore" placeholder="60" aria-describedby="inputGroupPrepend" required>
                                </div>
                                <small>* Total sum of marks on all questions in this exam</small>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 btn btn-primary">Add Exam </button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!-- mmmm -->
<!-- Modal -->
<div class="modal fade" id="quizmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">New Quiz</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('t_addexam')}}" method="POST" id="newlesson" enctype="multipart/form-data">
                @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Title</label>
                                <div class="input-group">
                                    <input type="hidden" name="type" value="444444">
                                    <input type="hidden" name="unit" value="{{$this_unit['id']}}">
                                    <input type="text" class="form-control" name="title" id="title" placeholder="CAT (II) of 2020" aria-describedby="inputGroupPrepend" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Description</label>
                                <div class="input-group">
                                    <textarea class="form-control" name="description" id="description" placeholder="Description of the above quiz" aria-describedby="inputGroupPrepend" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Total mark Count</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="maxscore" id="maxscore" placeholder="10" aria-describedby="inputGroupPrepend" required>
                                </div>
                                <small>* Total sum of marks on all questions in this quiz</small>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 btn btn-primary">Add Quiz </button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!-- surveys -->
<div class="modal fade" id="surveymodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">New Survey</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('t_addexam')}}" method="POST" id="newlesson" enctype="multipart/form-data">
                @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Title</label>
                                <div class="input-group">
                                    <input type="hidden" name="type" value="555555">
                                    <input type="hidden" name="unit" value="{{$this_unit['id']}}">
                                    <input type="hidden" name="maxscore" value="2" id="maxscore">
                                    <input type="text" class="form-control" name="title" id="title" placeholder="course evaluation survey 1" aria-describedby="inputGroupPrepend" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Description</label>
                                <div class="input-group">
                                    <textarea class="form-control" name="description" id="description" placeholder="Description of the above survey" aria-describedby="inputGroupPrepend" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 btn btn-primary">Add Survey </button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
