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
                <a href="{{route('a_uassign', ['id' => $unit['id']] )}}" class="btn mr-2 mb-2 btn-outline-info fundsmodal">Unit Assignments</a>
                <a href="{{route('a_uexams', ['id' => $unit['id']] )}}" class="btn mr-2 mb-2 btn-info fundsmodal">Unit Quizes, Exams & Surveys</a>
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
                <h5 class="card-title">Unit Exams
                    <span class="pull-right"> 
                        <button type="button" class="btn mr-2 mb-2 btn-info fundsmodal" data-toggle="modal" data-target="#fundsmodal"> <i class="pe-7s-plus text-white"></i> Add New Exam</button>
                    </span>
                </h5>
                <p>Exams - This is a very important part of the portal as far as learner assessments are concerned. Use this section to create final/main exams. Min. max should be 60.</p>
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
                                <th><small>#</small></th>
                                <th><small>Title</small></th>
                                <!-- <th><small>Description</small></th> -->
                                <th><small>Max Score</small></th>
                                <th><small>Actions</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($exams))
                                @php($li = 1)
                                @foreach( $exams as $_exam )
                                @php( $action = $unit['id'].'~'.$_exam['id'] )
                                <tr>
                                    <td>{{$li}}</td>
                                   <td>{{ucwords(strtolower($_exam['title']))}}</td>
                                   <!-- <td>{{substr($_exam['description'], 0, 70)}}...</td> -->
                                   <td>{{$_exam['maxscore']}}</td>
                                   <td>
                                        <a href="{{route('a_uexam_qhome', ['unit' => $unit['id'],'exam' => $_exam['id'] ])}}">Preview Questions</a> | 
                                        <a href="{{route('a_delexam', ['id' => $action ])}}">Drop</a>
                                   </td>
                                </tr>
                                @php($li++)
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
<!-- Quizes -->
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Unit Quizes
                    <span class="pull-right"> 
                        <button type="button" class="btn mr-2 mb-2 btn-success fundsmodal" data-toggle="modal" data-target="#quizmodal"> <i class="pe-7s-plus text-white"></i> Add New Quiz</button>
                    </span>
                </h5>
                <p>Quizes - same as exams but meant to serve as minor assessments before final end of programme/course exams. Use this section to manage quizes</p>
                <br>
                <!-- messges -->
                @if(Session::get('status') && Session::get('status') == 20000)
                <!-- @php( $units = Session::get('units')) -->
                <div class="alert alert-success">{{Session::get('message')}}</div>
                @endif
                @if(Session::get('status') && Session::get('status') == 20001)
                <!-- @php( $units = Session::get('units')) -->
                <div class="alert alert-danger">{{Session::get('message')}}</div>
                @endif
                <!-- recents -->
                <div class="table-responsive">
                    <table class="mb-0 table table-sm">
                        <thead>
                            <tr>
                                <th><small>#</small></th>
                                <th><small>Title</small></th>
                                <!-- <th><small>Description</small></th> -->
                                <th><small>Max Score</small></th>
                                <th><small>Actions</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($quizes))
                                @php($lili = 1)
                                @foreach( $quizes as $_quiz )
                                @php( $action = $unit['id'].'~'.$_quiz['id'] )
                                <tr>
                                    <td>{{$lili}}</td>
                                   <td>{{ucwords(strtolower($_quiz['title']))}}</td>
                                   <!-- <td>{{substr($_quiz['description'], 0, 70)}}...</td> -->
                                   <td>{{$_quiz['maxscore']}}</td>
                                   <td>
                                        <a href="{{route('a_uexam_qhome', ['unit' => $unit['id'],'exam' => $_quiz['id'] ])}}">Preview Questions</a> | 
                                        <a href="{{route('a_delexam', ['id' => $action ])}}">Drop</a>
                                   </td>
                                </tr>
                                @php($lili++)
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
<!-- Surveys -->
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Course/Unit Evaluation Surveys
                    <span class="pull-right"> 
                        <button type="button" class="btn mr-2 mb-2 btn-primary fundsmodal" data-toggle="modal" data-target="#surveymodal"> <i class="pe-7s-plus text-white"></i> Add New Survey</button>
                    </span>
                </h5>
                <p>Surveys - For the purposes of gathering feedbacks from learners, we use closed-ended simple surveys. Use this section to manage surveys</p>
                <br>
                <!-- messges -->
                @if(Session::get('status') && Session::get('status') == 2000)
                <!-- @php( $units = Session::get('units')) -->
                <div class="alert alert-success">{{Session::get('message')}}</div>
                @endif
                @if(Session::get('status') && Session::get('status') == 2001)
                <!-- @php( $units = Session::get('units')) -->
                <div class="alert alert-danger">{{Session::get('message')}}</div>
                @endif
                <!-- recents -->
                <div class="table-responsive">
                    <table class="mb-0 table table-sm">
                        <thead>
                            <tr>
                                <th><small>#</small></th>
                                <th><small>Title</small></th>
                                <!-- <th><small>Description</small></th> -->
                                <th><small>Actions</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($surveys))
                                @php($lff = 1)
                                @foreach( $surveys as $_survey )
                                @php( $action = $unit['id'].'~'.$_survey['id'] )
                                <tr>
                                    <td>{{$lff}}</td>
                                   <td>{{ucwords(strtolower($_survey['title']))}}</td>
                                   <td>
                                        <a href="{{route('a_uexam_qhome', ['unit' => $unit['id'],'exam' => $_survey['id'] ])}}">Preview Questions</a> | 
                                        <a href="{{route('a_delexam', ['id' => $action ])}}">Drop</a>
                                   </td>
                                </tr>
                                @php($lff++)
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
                <h5 class="modal-title text-white" id="exampleModalLabel">New Exam</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('a_addexam')}}" method="POST" id="newlesson" enctype="multipart/form-data">
                @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Title</label>
                                <div class="input-group">
                                    <input type="hidden" name="type" value="666666">
                                    <input type="hidden" name="unit" value="{{$unit['id']}}">
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
                <form action="{{route('a_addexam')}}" method="POST" id="newlesson" enctype="multipart/form-data">
                @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Title</label>
                                <div class="input-group">
                                    <input type="hidden" name="type" value="444444">
                                    <input type="hidden" name="unit" value="{{$unit['id']}}">
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
                <form action="{{route('a_addexam')}}" method="POST" id="newlesson" enctype="multipart/form-data">
                @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Title</label>
                                <div class="input-group">
                                    <input type="hidden" name="type" value="555555">
                                    <input type="hidden" name="unit" value="{{$unit['id']}}">
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
