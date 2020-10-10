@extends('layouts.inner')


@section('topnav')
    @include('commons/topnav')
@endsection


@section('sidenav')
    @include('commons/sidenav_learner')
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
.lesson-title{
    text-transform: none!important;
    color: rgb(81 209 224)!important;
    font-weight: 400!important;
    font-size: 18px!important;
}
.has-padding{
    margin-top:40px;
}
.l-live{
    background: aliceblue;
}

#quiz {
  /* margin: -44px 50px 0px; */
  position: relative;
  width: calc(100% - 100px);
}

#quiz h1 {
  color: #FAFAFA;
  font-weight: 600;
  font-size: 36px;
  text-transform: uppercase;
  text-align: left;
  line-height: 44px;
}

#quiz button {
  /* float: left;
  margin: 8px 0px 0px 8px;
  padding: 4px 8px;
  background: #9ACFCC;
  color: #00403C;
  font-size: 14px;
  cursor: pointer;
  outline: none; */
}

#quiz button:hover {
  /* background: #36a39c;
  color: #FFF; */
}

#quiz button:disabled {
  opacity: 0.5;
  background: #9ACFCC;
  color: #00403C;
  cursor: default;
}

#question {
  padding: 20px;
  background: #FAFAFA;
  text-align: left;
}

#question h2 {
  margin-bottom: 16px;
  font-weight: 600;
  font-size: 20px;
}

#question input[type=radio] {
  display: none;
}

#question label {
  display: inline-block;
  margin: 4px;
  padding: 8px;
  border-radius:5px;
  background: #59d2e2;
  color: #4C3000;
  width: calc(50% - 8px);
  min-width: 50px;
  cursor: pointer;
}

#question label:hover {
  background: #3ab7ff;
}

#question input[type=radio]:checked + label {
    border-radius:5px;
    background: #4267b4!important;
    color: #FAFAFA;
}

#quiz-results {
  display: flex;
  flex-direction: column;
  justify-content: center;
  position: absolute;
  top: 44px;
  left: 0px;
  background: #FAFAFA;
  width: 100%;
  height: calc(100% - 44px);
}

#quiz-results-message {
  display: block;
  color: #00403C;
  font-size: 20px;
  font-weight: bold;
}

#quiz-results-score {
  display: block;
  color: #31706c;
  font-size: 20px;
}

#quiz-results-score b {
  color: #00403C;
  font-weight: 600;
  font-size: 20px;
}

#quiz-retry-button {

  float: left;
  margin: 8px 0px 0px 8px;
  padding: 4px 8px;
  background: #9ACFCC;
  color: #00403C;
  font-size: 14px;
  cursor: pointer;
  outline: none;
  
}
.clockw{
    width: 259px!important;
    font-size: 24px!important;
    color: #fb7188!important;
}
</style>
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon clockw" id="examwatch">
                <!-- <i class="pe-7s-home text-info">
                </i> -->
            </div>
            <div><a href="#">Exam in progress:</a> {{$this_exam['title']}}</div>
        </div>
    </div>
</div>

<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <p class="text-danger">Do not close this window until you have submitted your answers</p>
                <hr>
                <div class="main">
                    <!-- exam window -->
                    <div id="quiz">
                        <div id="question">
                            <h2>How many wheels are there on a tricycle?</h2>
                            <input id="choices-0" type="radio" name="choices" value="choices-0">
                            <label for="choices-0">Three</label>
                            <input id="choices-1" type="radio" name="choices" value="choices-1">
                            <label for="choices-1">One</label>
                            <input id="choices-2" type="radio" name="choices" value="choices-2">
                            <label for="choices-2">Two</label>
                            <input id="choices-3" type="radio" name="choices" value="choices-3">
                            <label for="choices-3">Four</label>
                        </div>
                        <button class="btn btn-warning" id="prev-question-button">Previous</button>
                        <button class="btn btn-info" id="next-question-button">Next Question</button>
                        <button class="btn btn-danger pull-right" id="submit-button">Finish Exam</button>
                        <div id="quiz-results" style="display: none;">
                            <p id="quiz-results-message"></p>
                            <p id="quiz-results-score"></p>
                            <button id="quiz-retry-button">Retry</button>
                        </div>
                    </div>
                    <!-- end window -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('commons/footer')
@endsection
