@extends('layouts.inner')


@section('topnav')
    @include('commons/topnav')
@endsection


@section('sidenav')
    @include('commons/sidenav')
@endsection


@section('content')
<style>
.ios-button{
    border: solid 0px!important;
    border-bottom: solid 2px!important;
    border-right: solid 2px!important;
    border-radius: 13px!important;
    font-weight: 600;
}
.modal-header, .modal-footer {
    background: #fff!important;
    color: #ed1d29;
}
.modal-header {
    border-bottom: 1px solid #e9ecef!important;
}
.modal-content {
    background-color: transparent!important;
    border: 1px solid #e9ecef !important;
}
.modal-body {
    background-color: #fff!important;
    padding-left:50px;
    padding-right:50px;
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
.form-control[readonly] {
    background-color: #ffffff!important;
}
.form-control {
    border: solid 0px;
    border-bottom: 1px solid #ced4da;
    border-radius: .2rem;
}
.paying{
    font-size:18px!important;
    color:#d92550!important;
    border-color:#d92550!important;
    border-top: solid 0px!important;
    border-left: solid 0px!important;
    border-right: solid 0px!important;
    border-bottom: solid!important;
}
.paying > span,i{
    font-weight:500!important;
}
.paying > i{
    font-size:18px!important;
    color:#d92550!important;
    font-weight:500!important;
}
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0; 
}
input[type=number] {
    -moz-appearance:textfield; 
}
</style>
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-paper-plane text-primary">
                </i>
            </div>
            <div>Lipa Bills Instantly</div>
        </div>
    </div>
</div>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <!-- <h5 class="card-title">Grid Rows</h5> -->
                <form class="" name="lipabill" id="lipabill_form">
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <input name="sendtotill" id="sendtotill" placeholder="enter till no." type="number"  min="1" class="form-control">
                                <div class="invalid-feedback" id="invalid-till"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <input name="accountnumber" id="accountnumber" placeholder="enter account no." type="text" class="form-control">
                                <div class="invalid-feedback" id="invalid-account"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <input onkeyup="forex()" name="amountinkes" id="amountinkes" placeholder="bill amount in ksh." type="number" class="form-control" min="10">
                                <div class="invalid-feedback" id="invalid-amt"></div>
                                <!-- <small class="form-text text-muted">1 USD = 102.25</small> -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <button disabled class="mb-2 mr-2 btn-transition btn btn-outline-danger paying">
                                    <i class="pe-7s-credit"></i>
                                    <span id="chargable">$0.00</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="mt-2 btn btn-outline-primary ios-button" onclick="lipa('lipabill')">Pay Bill Now</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('commons/footer')
@endsection
<!-- USER SCRIPST -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
<!-- stripe elements -->
<script>
    $( document ).ready(function() {
        const elementFont = [
            cssSrc =['https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i'],
        ];
        const elementOptions =  {
            style: {
                base: {
                    iconColor: '#5777ba',
                    color: '#5777ba',
                    fontWeight: 500,
                    fontSize: '16px',
                    fontSmoothing: 'antialiased',
                    ':-webkit-autofill': {
                        color: '#5777ba',
                    },
                    '::placeholder': {
                        color: '#b4b5b6',
                    },
                },
                invalid: {
                iconColor: '#FF0000',
                color: '#FF0000',
                },
            },
            iconStyle:'solid',
        };
        var stripe = Stripe("{{Config::get('app.stripe_public_key')}}");
        var elements = stripe.elements({fonts:elementFont});
        var cardElement = elements.create('card', elementOptions);
        cardElement.mount('#card-collector-div');
        // cardElement.focus();
        /** */
        // $('.fundsmodal').trigger('click');
        /** get form */
        var cardholderName = document.getElementById('cardholder-name');
        var cardholderEmail = document.getElementById('cardholder-email');
        var cardholderPhone = document.getElementById('cardholder-phone');
        var cardButton = document.getElementById('card-button');
        var resultContainer = document.getElementById('card-result');
        cardButton.addEventListener('click', function(ev) {
            $.LoadingOverlay("show");
            stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                billing_details: {
                name: cardholderName.value,
                email: cardholderEmail.value,
                phone: cardholderPhone.value,
                },
            }).then(function(result) {
            if (result.error) {
                resultContainer.textContent = result.error.message;
                return;
            } else {
                resultContainer.textContent = "Created payment method: " + result.paymentMethod.id;
                var data = "payment_method=" + result.paymentMethod.id;
                $.ajax({
                    data:data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{route('addcard')}}",
                    type: "POST",
                    success: function(res){
                        console.log(res);
                        showToast(res.message);
                        $.LoadingOverlay("hide");
                        return;
                    },
                    error: function(xhr, textStatus, errorThrown){
                        console.log(textStatus, errorThrown);
                        console.log(JSON.stringify(xhr));
                        showToastDanger(errorThrown);
                        $.LoadingOverlay("hide");
                    }
                });
            }
            });
        });
    });
</script>
<!-- end stripe elements -->
<script>
    const lipa = function(form){
        var till = $('#lipabill_form').find('input[name="sendtotill"]').val();
        if(till.length === 0){
            $('#invalid-till').text('Enter till number');
            $('#invalid-till').show();
            return;
        }
        var account = $('#lipabill_form').find('input[name="accountnumber"]').val();
        if(account.length === 0){
            $('#invalid-account').text('Enter account number');
            $('#invalid-account').show();
            return;
        }
        var amountinkes = $('#lipabill_form').find('input[name="amountinkes"]').val();
        if(amountinkes.length === 0){
            $('#invalid-amt').text('Enter bill amount in KES');
            $('#invalid-amt').show();
            return;
        }
        $.LoadingOverlay("show");
        var data = $('form[name="lipabill"]').serialize();
        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"post",
            url:"{{route('lipabill')}}",
            data:data,
            success: function(res){
                console.log(res);
                if( res.status === 201 ){
                    showToastDanger(res.message);
                    $.LoadingOverlay("hide");
                    $('.fundsmodal').trigger('click');
                    return;
                }
                if( res.status !== 200 ){
                    showToastDanger(res.message);
                    $.LoadingOverlay("hide");
                    return;
                }
                if( res.status === 200 ){
                    showToast(res.message);
                    $.LoadingOverlay("hide");
                    setTimeout(() => {
                        // location.reload();
                    }, 3000);
                    return;
                }
            },
            error: function(xhr, textStatus, errorThrown){
                console.log(xhr);
                showToastDanger(errorThrown);
                $.LoadingOverlay("hide");
            }
        });
    }
    forex = function (){
        var amount = $('#amountinkes').val();
        var rate = {{Config::get("app.forex_rate")}};
        var cnv = amount/rate;
        $('#chargable').text('$' + (cnv).toFixed(2));
    }
    const showToast = function(text){
        $.toast({
            heading: 'Sucess',
            text: text,
            icon: 'info',
            bgColor: '#ed1d29',
            textColor: 'white',
            loader: false,
            position: 'top-right',
            hideAfter: 5000        
        });
    }
    const showToastDanger = function(text){
        $.toast({
            heading: 'Error',
            text: text,
            icon: 'error',
            bgColor: '#FF0000',
            textColor: 'white',
            loader: false,
            position: 'top-right',
            hideAfter: 3000        
        });
    }
</script>
<!-- END SCRIPTS -->
<!-- modals -->
<!-- 1. add card -->
<!-- Modal -->
<button style="display:none;" type="button" class="btn mr-2 mb-2 btn-primary fundsmodal" data-toggle="modal" data-target="#fundsmodal"></button>
<div class="modal fade" id="fundsmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Card Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <p class="mb-0">We noticed you do not have any source of funds on your account</p><br> -->
                <form class="" name="addcard_form" id="addcard_form">
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <input id="cardholder-name" class="form-control" type="hidden" value="{{Auth::user()->name}}">
                                    <input id="cardholder-email" class="form-control" type="hidden" value="{{Auth::user()->email}}">
                                    <input id="cardholder-phone" class="form-control" type="hidden" value="{{Auth::user()->phone}}">
                                </div>
                            </div>
                            <div class="position-relative form-group">
                                <div id="card-collector-div"></div>
                            </div>
                        </div>
                    </div>
                    <div id="card-result"></div>
                    <button type="button" id="card-button" class="mt-2 btn btn-outline-primary ios-button">Complete Payment</button>
                </form>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
