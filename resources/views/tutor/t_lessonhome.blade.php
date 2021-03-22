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
            <div>&nbsp;>&nbsp;Lesosns</div>
        </div>
    </div>
</div>
<!-- manage course -->
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Lessons in this Unit
                    <span class="pull-right"> 
                        <button type="button" class="btn mr-2 mb-2 btn-info fundsmodal" data-toggle="modal" data-target="#fundsmodal"> <i class="pe-7s-plus text-white"></i> Add Lesson</button>
                        <button type="button" class="btn mr-2 mb-2 btn-primary fundsmodal" data-toggle="modal" data-target="#liveless"> <i class="pe-7s-plus text-white"></i> Add Live Lesson</button>
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
                                <th><small>Content</small></th>
                                <th><small>Created</small></th>
                                <th><small>Action</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($this_unit_lessons))
                                @foreach( $this_unit_lessons as $_this_unit_lesson )
                                @php($action = $this_unit['id'].'~'.$_this_unit_lesson['id'])
                                <tr>
                                    <td>{{$_this_unit_lesson['name']}}</td>
                                    <td>{{$_this_unit_lesson['description']}}</td>
                                    <td>{{$_this_unit_lesson['content']}}</td>
                                    <td>{{date('M jS, 2020', strtotime($_this_unit_lesson['updated_at']))}}</td>
                                    <td><a href="{{route('t_droplesson', ['id' => $action])}}" class="mb-2 mr-2 btn-transition btn btn-outline-danger">Drop</a>
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
                <form action="{{route('t_addlesson')}}" method="POST" id="newlesson" enctype="multipart/form-data">
                @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <input type="hidden" name="unit" value="{{$this_unit['id']}}">
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
                <form action="{{route('t_addlesson_live')}}" method="POST" id="newlesson">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <input type="hidden" name="unit" value="{{$this_unit['id']}}">
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
