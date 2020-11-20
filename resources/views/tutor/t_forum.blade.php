@extends('layouts.inner')


@section('miscss')
<link href="{{ asset('inner/main.timeline.css') }}" rel="stylesheet">
@endsection

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
.form-control {
    border: solid 0px;
    border-bottom: 1px solid #ced4da;
    border-radius: .2rem;
}
.forum-title{
    text-transform: none!important;
    color: rgb(99 195 255)!important;
    font-weight: 500!important;
    font-size: 24px!important;
}
.reply{
    text-transform: none!important;
    color: rgba(13,27,62,0.7)!important;
    font-weight: 400!important;
    font-size: .88rem!important;
}
.cbox{
    background: aliceblue;
    padding: 10px;
    border-radius: 15px;
}
.chat{
    background: #ffffff!important;
}
.forum-box{
    -webkit-box-shadow: 0px 2px 5px -1px rgba(0,0,0,0.32);
    -moz-box-shadow: 0px 2px 5px -1px rgba(0,0,0,0.32);
    box-shadow: 0px 2px 5px -1px rgba(0,0,0,0.32);
    border-radius: 15px;
}
.btn-reply{
    top: 18;
}
.form-reply{
    /* min-width: 100%; */
}
</style>
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-chat text-info">
                </i>
            </div>
            <div>Forums - Course Discussions</div>
        </div>
    </div>
</div>
<!-- content -->
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">My Timeline
                    <span class="pull-right"> 
                        <button type="button" class="btn mr-2 mb-2 btn-primary fundsmodal" data-toggle="modal" data-target="#forummodal"> <i class="pe-7s-plus text-white"></i> Post in Forum </button>
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
                <!-- timeline -->
                <!-- one forum -->
                @if( count($payload) )
                @foreach( $payload as $_payload )
                <div class="card-body forum-box">
                    <h5 class="card-title forum-title">{{ucwords(strtolower($_payload['forum']))}}</h5>
                    <small class="opacity-6"><i class="fa fa-calendar-alt mr-1"></i>
                    {{date('h:i a', strtotime($_payload['created_at']))}} | {{date('M jS, y', strtotime($_payload['created_at']))}} | <i class="fa fa-user-alt mr-1"></i> {{App\User::find($_payload['uploadedby'])->name}} | <i class="fa fa-book mr-1"></i> {{App\Course::find($_payload['course'])->name}}</small>
                    @php($replies = App\ForumReply::where('forum', $_payload['id'])->get()->toArray() )
                    <!-- forums conversations -->
                    <div class="chat-wrapper cbox">
                        @if(count($replies))
                        @foreach( $replies as $_rep )
                        <div class="chat-box-wrapper">
                            <div>
                                <div class="avatar-icon-wrapper mr-1">
                                    <div class="avatar-icon avatar-icon-lg rounded" style="border:solid 0px;">
                                        <img src="{{asset('inner/images/avatars/1.png')}}" alt="">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="chat-box chat">{{$_rep['reply']}}</div>
                                <small class="opacity-6"><i class="fa fa-calendar-alt mr-1"></i>
                                    {{date('h:i a', strtotime($_rep['created_at']))}} | {{date('M jS, y', strtotime($_rep['created_at']))}} | <i class="fa fa-user-alt mr-1"></i> {{App\User::find($_rep['uploadedby'])->name}}
                                </small>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <p>No replies yet</p>
                        @endif
                    </div>
                    <!-- end conversations -->
                    <!-- reply form -->
                    <div class="app-inner-layout__bottom-pane d-block text-center">
                        <div class="mb-0 position-relative row form-group">
                            <form class="form-inline form-reply" action="{{route('t_add_freply')}}" method="POST">
                                @csrf
                                <div class="col-sm-8">
                                    <input type="hidden" name="forum" value="{{$_payload['id']}}">
                                    <input placeholder="Write a reply" name="reply" type="text"class="form-control-lg form-control">
                                </div>
                                <div class="col-sm-4">
                                    <button type="submit" class="btn mr-2 mb-2 btn-primary btn-reply"> <i class="pe-7s-chat text-white"> </i> Reply</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- end reply form -->
                </div><br>
                @endforeach
                @else
                <h4>No forum posts yet</h4>
                @endif
                <!-- end one forum -->
                <!-- end -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('commons/footer')
@endsection
<!-- Modal -->
<div class="modal fade" id="forummodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">New Forum Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('t_add_forum')}}" method="POST" id="newcourse" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <select class="form-control" name="unit" id="unit" placeholder="Full name" aria-describedby="inputGroupPrepend" required>
                                    <option value="nn">Select Course Unit</option>
                                    @if(count($units))
                                    @foreach( $units as $_unit )
                                    <option value="{{$_unit['id']}}">{{ucwords(strtolower($_unit['name']))}}</option>
                                    @endforeach
                                    @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <textarea class="form-control" name="forum" id="forum" placeholder="Type your post/question here" aria-describedby="inputGroupPrepend" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Attach Screenshot, Image etc</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" name="ffile" id="ffile" aria-describedby="inputGroupPrepend"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 btn btn-primary">Post Now </button>
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>
