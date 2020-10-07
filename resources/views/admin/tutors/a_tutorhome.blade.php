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
                <i class="pe-7s-users text-info">
                </i>
            </div>
            <div><a href="{{route('a_tutors')}}">Tutors </a> </div>
            <div>&nbsp;>&nbsp;{{ucwords(strtolower($tutor['name']))}}</div>
        </div>
    </div>
</div>
<!-- manage tutor -->
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Manage Tutor
                    <span class="pull-right">
                    <form action="{{route('a_deltutor', ['id' => $tutor['id']])}}" method="POST" id="deltutor">
                    @csrf
                    @method('put')
                    <button type="submit" class="btn mr-2 mb-2 btn-danger fundsmodal"> <i class="pe-7s-plus text-white"></i> Delete tutor</button>
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
                    <form action="{{route('a_edittutor', ['id' => $tutor['id']])}}" method="POST" id="newtutor">
                        @csrf
                        @method('put')
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label class="lab">Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="name" id="name" value="{{strtolower($tutor['name'])}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label class="lab">Email</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="email" id="email" value="{{strtolower($tutor['email'])}}" aria-describedby="inputGroupPrepend" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label class="lab">Phone Number</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="phone" id="phone" value="{{strtolower($tutor['phone'])}}" aria-describedby="inputGroupPrepend" required>
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
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">
                    Units assigned to this tutor
                    <span class="pull-right"> 
                        <button type="button" class="btn mr-2 mb-2 btn-primary fundsmodal" data-toggle="modal" data-target="#fundsmodal"> <i class="pe-7s-plus text-white">
                </i> Assign Units to Tutor</button>
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
                    <table class="mb-0 table table-sm">
                        <thead>
                            <tr>
                                <th><small>Course Name</small></th>
                                <th><small>Unit Name</small></th>
                                <th><small>Drop</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($units))
                                @foreach( $units as $_unit )
                                <tr>
                                    <td>{{ucwords(strtolower($_unit['course']))}}</td>
                                    <td>{{ucwords(strtolower($_unit['unit_name']))}}</td>
                                    <td><a href="{{route('a_drop_unit',['id' => $tutor['id'].'~'.$_unit['id'] ])}}" class="mb-2 mr-2 btn-transition btn btn-outline-danger">Drop unit</a>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                           <tr><td colspan="3"> <i>No units assigned yet</i></td></tr>
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
                <h5 class="modal-title text-white" id="exampleModalLabel">Assign to Tutor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('a_assign_unit')}}" method="POST" id="newcourse">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <input type="hidden" name="user" value="{{$tutor['id']}}"/>
                                    <select class="form-control" multiple name="unit[]" id="unit" aria-describedby="inputGroupPrepend" required>
                                    @if(count($all_units))
                                        @foreach( $all_units as $_au)
                                        <option value="{{$_au['id']}}">{{ucwords(strtolower($_au['name']))}}</option>
                                        @endforeach
                                    @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 btn btn-primary">Assign to Tutor </button>
                </form>
            </div>
            <div class="modal-footer">
               
            </div>
        </div>
    </div>
</div>
