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
            @php( $unit_meta = App\Unit::find($lesson['unit']))
            <div><a href="{{route('a_home')}}">Courses </a> </div>
            
            <div>&nbsp;>&nbsp;<a href="{{route('a_coursehome',['id' => $unit_meta->course])}}">{{substr(ucwords(strtolower(App\Course::find($unit_meta->course)->name)), 0, 10)}}...</a></div>
            
            <div>&nbsp;>&nbsp;<a href="{{route('a_unithome',['id' => $unit_meta->id])}}">{{substr(ucwords(strtolower($unit_meta->name)), 0, 10)}}...</a></div>

            <div>&nbsp;>&nbsp;{{ucwords(strtolower($lesson['name']))}}</div>
        </div>
    </div>
</div>
<!-- manage course -->
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Manage Lesson
                    <span class="pull-right">
                    <form action="{{route('a_delunit', ['id' => $lesson['id']])}}" method="POST" id="delunit">
                    @csrf
                    @method('put')
                    <button type="submit" class="btn mr-2 mb-2 btn-danger fundsmodal"> <i class="pe-7s-plus text-white"></i> Delete Lesson</button>
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
                    <form action="{{route('a_editlesson', ['id' => $lesson['id']])}}" method="POST" id="newunit" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label class="lab">Lesson title</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="name" id="name" value="{{strtolower($lesson['name'])}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>
                            @if($lesson['live_link'] != 'not_applicable')
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label class="lab">Link to live class</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="live_link" id="live_link" value="{{$lesson['live_link']}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label class="lab">Live class date & time</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="live_time" id="live_time" value="{{strtolower($lesson['live_time'])}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($lesson['content'] != 'not_applicable')
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label class="lab">
                                        Lesson Content
                                        <a href="{{route('file_stream', ['file' => $lesson['content'] ])}}">View uploaded file </a>
                                    </label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" name="content" id="content"aria-describedby="inputGroupPrepend">
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label class="lab">Lesson brief</label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="description" id="description" aria-describedby="inputGroupPrepend" required>{{$lesson['description']}}</textarea>
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
@endsection

@section('footer')
    @include('commons/footer')
@endsection
<!-- Modal -->
