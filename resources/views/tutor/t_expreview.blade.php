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
            <div>&nbsp;>&nbsp;<a href="{{route('t_examhome', ['unit' => $this_unit['id']])}}">Exams, Quizes & Surveys</a></div>
            <div>&nbsp;>&nbsp;{{ucwords(strtolower($this_exam['title']))}}</div>
            <div>&nbsp;>&nbsp;Preview</div>
        </div>
    </div>
</div>
<!-- manage course -->
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Questions Preview
                    <span class="pull-right"> 
                        @if( $this_exam['is_active'])
                        <a href="#" class="btn mr-2 mb-2 btn-danger"> <i class="pe-7s-disk text-white"></i> 
                        Is Active
                        </a>
                        @else
                        <button type="button" class="btn mr-2 mb-2 btn-success" data-toggle="modal" data-target="#examsmodal"> <i class="pe-7s-disk text-white"></i> Activate</button>
                        @endif
                        <button type="button" class="btn mr-2 mb-2 btn-info fundsmodal" data-toggle="modal" data-target="#fundsmodal"> <i class="pe-7s-plus text-white"></i> Add Question</button>
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
                        <table class="mb-0 table table-sm">
                            <thead>
                                <tr>
                                    <th><small>#</small></th>
                                    <th><small>Title</small></th>
                                    <th><small>Options</small></th>
                                    <th><small>Max Score</small></th>
                                    <th><small>Actions</small></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($questions))
                                    @php($li = 1)
                                    @foreach( $questions as $_question )
                                    @php( $action = $this_unit['id'].'~'.$this_exam['id'].'~'.$_question['id'] )
                                    @php($options = json_decode($_question['options'], true))
                                    <tr>
                                        <td>{{$li}}</td>
                                        <td>{{ucwords(strtolower($_question['title']))}}</td>
                                        <td>
                                            @if(count($options))
                                                @foreach( $options as $opt )
                                                @php($is_ans = '(<i class="pe-7s-check text-info"></i>)')
                                                @if( $opt['isAnswer'] == '00')
                                                @php($is_ans = '<i></i>')
                                                @endif
                                                {{$opt['Id']}}). {{$opt['Option']}} {!! $is_ans !!} <br>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>{{$_question['maxscore']}}</td>
                                        <td><a href="{{route('t_delq', ['id' => $action ])}}">Drop</a></td>
                                    </tr>
                                    @php($li++)
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">Add Question</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="addexQuest" id="addexQuest" enctype="multipart/form-data">
                @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Title</label>
                                <div class="input-group">
                                    <input type="hidden" name="exam" value="{{$this_exam['id']}}">
                                    <input type="hidden" name="unit" value="{{$this_unit['id']}}">
                                    <input type="text" class="form-control" name="title" id="title" placeholder="Which of the following is true" aria-describedby="inputGroupPrepend" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div id="exprs">
                                    <label for="BoxType" class="col-form-label">Answer Options</label>
                                    <div class="form-group row clonables" id="clonables__0">
                                        <div class="col-md-4">
                                            <label class="label"><small><i>Option Identifier</i></small></label>
                                            <select id="identity__0" class="partner form-control rounded_form_control" name="identity[]">
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="D">D</option>
                                                <option value="E">E</option>
                                                <option value="F">F</option>
                                                <option value="G">G</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="label"><small><i>Option value</i></small></label>
                                            <input type="text" id="opt__0" name="opt[]" class="form-control rounded_form_control" placeholder="All MCAs are men">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="label"><small><i>Is correct answer</i></small></label>
                                            <select id="iscorrect__0" name="iscorrect[]" class="form-control rounded_form_control">
                                                <option value="00">No</option>
                                                <option value="11">Yes</option>
                                            </select>
                                        </div>
                                        <button type="button" class="clone btn btn-link btn-admin-link"><i class="fas fa-plus"></i> Add another</button> 
                                        <button type="button" class="remove btn btn-link btn-admin-link"><i class="fas fa-trash"></i>  Remove this</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Marks</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="maxscore" id="maxscore" placeholder="2 marks" aria-describedby="inputGroupPrepend" required>
                                </div>
                                <small>* Total marks the question holds on the exam/quiz</small>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addQuestion()" class="mt-2 btn btn-primary">Add Question </button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="examsmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">Activate Exam/Quiz/Survey</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('t_act_exam')}}" method="post" enctype="multipart/form-data">
                @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <input type="hidden" name="unit" value="{{$this_unit['id']}}"/>
                            <input type="hidden" name="exam" value="{{$this_exam['id']}}"/>
                             <span style="white-space:normal;line-height:18px;text-transform:none;" class="badge badge-warning">
                                Activating Exams, Quizes or surveys renders them visible to learners. <br>
                                This cannot be reversed. 
                                <br>You will not be able to add or remove questions on this exam/quiz/survey</span>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 btn btn-danger">Activate Now</button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

