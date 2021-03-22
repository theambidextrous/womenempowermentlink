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
                <i class="pe-7s-user text-info">
                </i>
            </div>
            <div>My Profile</div>
        </div>
    </div>
</div>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Personal Information</h5>
                <p>Name, email, phone etc.</p>
                <br>
                <!-- messges -->
                @if(Session::get('status') && Session::get('status') == 200)
                <div class="alert alert-success">{{Session::get('message')}}</div>
                @endif
                @if(Session::get('status') && Session::get('status') == 201)
                <div class="alert alert-danger">{{Session::get('message')}}</div>
                @endif
                <div class="modal-body">
                    <form action="{{route('t_profile_update')}}" method="post" id="newlesson" enctype="multipart/form-data">
                    @csrf
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label>Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="name" id="name" value="{{Auth::user()->name}}" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label>Email address</label>
                                    <div class="input-group">
                                        <input type="email" class="form-control" name="email" id="email" value="{{Auth::user()->email}}" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label>Phone number</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="phone" id="phone" value="{{Auth::user()->phone}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label>Gender</label>
                                    <div class="input-group">
                                        <select class="form-control" name="gender" id="gender" required>
                                            @if(Auth::user()->gender == 'FEMALE')
                                            <option value="FEMALE" selected>Female</option>
                                            <option value="MALE">Male</option>
                                            @elseif( Auth::user()->gender == 'MALE' )
                                            <option value="FEMALE">Female</option>
                                            <option value="MALE" selected>Male</option>
                                            @else
                                            <option value="nn">Select gender</option>
                                            <option value="FEMALE">Female</option>
                                            <option value="MALE">Male</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="position-relative form-group">
                                    <label>Address</label>
                                    <div class="input-group">
                                        <input type="text" value="{{Auth::user()->address}}" class="form-control" name="address" id="address" placeholder="address" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="mt-2 btn btn-primary">Update</button>
                    </form>
                </div>
                <!-- end recents -->
            </div>
        </div>
    </div>
</div>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Security</h5>
                <br>
                <!-- messges -->
                @if(Session::get('status') && Session::get('status') == 2000)
                <div class="alert alert-success">{{Session::get('message')}}</div>
                @endif
                @if(Session::get('status') && Session::get('status') == 2001)
                <div class="alert alert-danger">{{Session::get('message')}}</div>
                @endif
                <div class="modal-body">
                    <form action="{{route('t_pwd_change')}}" method="post" id="newlesson">
                    @csrf
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label>New Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="new password" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label>Confirm password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="confirm password" aria-describedby="inputGroupPrepend" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="mt-2 btn btn-primary">Change password</button>
                    </form>
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
