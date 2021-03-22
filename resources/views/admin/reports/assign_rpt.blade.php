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
                <i class="pe-7s-server text-info">
                </i>
            </div>
            <div>Reports - Assignments</div>
        </div>
    </div>
</div>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">
                    <span class="pull-right"> 
                        <!-- <button type="button" class="btn mr-2 mb-2 btn-primary fundsmodal" data-toggle="modal" data-target="#fundsmodal"> <i class="pe-7s-plus text-white"></i> Add Learner</button> -->
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
                <div class="table-responsive">
                    <table class="mb-0 table table-sm reportable wrap">
                        <thead>
                            <tr>
                                <th><small>Title</small></th>
                                <th><small>Assignment</small></th>
                                <th><small>Course</small></th>
                                <th><small>Unit</small></th>
                                <th><small>Tutor</small></th>
                                <th><small>Max. Score</small></th>
                                <th><small>Date</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($payload))
                                @foreach( $payload as $_payload )
                                <tr>
                                    <td>{{ ucwords(strtolower($_payload['title'])) }}</td>
                                    <td>{{$_payload['content']}}</td>
                                    <td>{{ ucwords(strtolower(App\Course::find($_payload['course'])->name)) }}</td>
                                    <td>{{ ucwords(strtolower(App\Unit::find($_payload['unit'])->name)) }}</td>
                                    <td>{{ App\User::find($_payload['uploadedby'])->name }}</td>
                                    <td>{{$_payload['maxscore']}}</td>
                                    <td>{{date('M jS, 2020', strtotime($_payload['updated_at']))}}</td>
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
@endsection

@section('footer')
    @include('commons/footer')
@endsection
<!-- Modal -->
<div class="modal fade" id="fundsmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">Enroll New Learner</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('a_add_learner')}}" method="POST" id="newcourse">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Full name" aria-describedby="inputGroupPrepend" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="email" id="email" placeholder="email address" aria-describedby="inputGroupPrepend" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder="phone number" aria-describedby="inputGroupPrepend" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 btn btn-primary">Enroll learner </button>
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>
