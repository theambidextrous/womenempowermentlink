<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\User;
use App\Course;
use App\TutorUnit;
use App\Unit;
use App\LearnerCourse;
use App\Lesson;
use App\LearnerUnitPerformance;
use App\Assignment;
use App\LearnerAssignment;
use App\Exam;
use App\Question;
use App\LearnerExam;
use App\ExamProgress;
use App\Forum;
use App\ForumReply;
use Validator;
use Storage;
use Config;

class LearnerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('learner.s_home')->with([
            'mycourses' => $this->my_active_courses(),
            'all_courses' => $this->all_courses(),
        ]);
    }
    public function s_profile()
    {
        return view('learner.s_profile')->with([
            'mycourses' => [],
        ]);
    }
    public function s_profile_update(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
            'gender' => 'required|string|not_in:nn',
            'address' => 'required|string',
        ]);
        if( $validator->fails() )
        {
            return redirect()->route('s_profile')->with([
                'status' => 201,
                'message' => "missing required fields when updating profile"
            ]);
        }
        $input = $req->all();
        User::find(Auth::user()->id)->update($input);
        return redirect()->route('s_profile')->with([
            'status' => 200,
            'message' => "Profile info updated successfully"
        ]);
    }
    public function s_pwd_change(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'password' => 'required|string',
            'cpassword' => 'required|string',
        ]);
        if( $validator->fails() )
        {
            return redirect()->route('s_profile')->with([
                'status' => 2001,
                'message' => "missing required fields when updating password"
            ]);
        }
        $input = $req->all();
        if( strlen($input['password']) < 8 )
        {
            return redirect()->route('s_profile')->with([
                'status' => 2001,
                'message' => "Error. Passwords must be at least 8 characters"
            ]);
        }
        if( $input['password'] != $input['cpassword'] )
        {
            return redirect()->route('s_profile')->with([
                'status' => 2001,
                'message' => "Error. Passwords do not match"
            ]);
        }
        $payload = [];
        $payload['password'] = Hash::make($input['password']);
        User::find(Auth::user()->id)->update($payload);
        Auth::logout();
        return redirect()->route('login')->with([
            'status' => 2000,
            'message' => "Password updated successfully"
        ]);
    }
    public function s_enrollcourse(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'course' => 'required|string|not_in:nn',
        ]);
        if( $validator->fails() )
        {
            return redirect()->route('s_home')->with([
                'status' => 201,
                'message' => "missing required fields when enrolling course"
            ]);
        }
        $input = $req->all();
        $input['learner'] = Auth::user()->id;
        LearnerCourse::create($input);
        return redirect()->route('s_home')->with([
            'status' => 200,
            'message' => "Enrolled successfully"
        ]);
    }
    public function s_lessonhome($unitid)
    {
        return view('learner.s_lessonhome')->with([
            'this_unit' => $this->this_unit($unitid),
            'lessons' => $this->this_unit_lessons($unitid),
        ]);
    }
    public function s_assignhome($unitid)
    {
        return view('learner.s_assignhome')->with([
            'this_unit' => $this->this_unit($unitid),
            'assignments' => $this->this_unit_assignments($unitid),
        ]);
    }
    public function s_assignsubmit(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'unit' => 'required|string',
            'assignment' => 'required|string',
        ]);
        $unitid = $req->get('unit');
        if( $validator->fails() ){
            return redirect()->route('s_assignhome', $unitid)->with([
                'status' => 201,
                'message' => "missing required fields submitting assignment"
            ]);
        }
        $input = $req->all();
        $file_uuid = (string) Str::uuid();
        if( !$req->hasfile('subfile') )
        {
            return redirect()->route('s_assignhome', $unitid)->with([
                'status' => 201,
                'message' => "Upload a valid assignment file"
            ]);
        }
        $content = $req->file('subfile');
        $content_name = $file_uuid . $content->getClientOriginalName();
        Storage::disk('local')->putFileAs('cls/trt/content', $content, $content_name);
        $input['submission_file'] = $content_name;
        $input['learner'] = Auth::user()->id;
        $input['markedby'] = 0;
        $input['score'] = 0.0;
        LearnerAssignment::create($input);
        return redirect()->route('s_assignhome', $unitid)->with([
            'status' => 200,
            'message' => "Assignment uploaded Successfully"
        ]);
    }
    public function s_gradesome($unitid)
    {
        return view('learner.s_gradesome')->with([
            'this_unit' => $this->this_unit($unitid),
            'this_unit_grade' => $this->this_unit_grades($unitid),
        ]);
    }
    public function s_examhome($unitid)
    {
        return view('learner.s_examhome')->with([
            'this_unit' => $this->this_unit($unitid),
            'this_unit_exam' => $this->this_unit_exams($unitid),
        ]);
    }

    public function s_exama_anow(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'unit' => 'required|string',
            'exam' => 'required|string',
        ]);
        $unitid = $req->get('unit');
        if( $validator->fails() ){
            return redirect()->route('s_examhome', $unitid)->with([
                'status' => 201,
                'message' => "missing required fields when attempting exam"
            ]);
        }
        $input = $req->all();
        /** create exam attempt */
        $exam = $input['exam'];
        $questions = $this->pull_exam_questions($exam);
        $_the_input = [];
        $_the_input['exam'] = $exam;
        $_the_input['student'] = Auth::user()->id;
        try{
            if( is_array($questions) )
            {
                $index = 1;
                foreach( $questions as $_question ):
                    $this->has_attempted($exam, $_question['id']);
                    $_the_input['question_id'] = $_question['id'];
                    $_the_input['q_index'] = $index;
                    $_the_input['correct'] = $this->extract_correct($_question['options']);
                    $_the_input['selected'] = null;
                    // $_the_input['maxscore'] = $_question['maxscore'];
                    ExamProgress::create($_the_input);
                    $index++;
                endforeach;
            }
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            return redirect()->route('s_examhome', $unitid)->with([
                'status' => 201,
                'message' => "you have already done this exam."
            ]);
        }
        $first_q = $questions[0];
        $max_index = $this->exam_q_count($exam);
        Session::put('first_q', $first_q);
        Session::put('current_index', 1);
        Session::put('prev_index', $max_index);
        Session::put('next_index', 2);
        if( $max_index == 1 )
        {
            Session::put('next_index', $max_index);
        }
        return redirect()->route('s_examattempt', [$input['exam'], $input['unit']])->with([
            'status' => 200,
            'message' => "all the best"
        ]);
    }
    public function s_survey_anow(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'unit' => 'required|string',
            'exam' => 'required|string',
        ]);
        $unitid = $req->get('unit');
        if( $validator->fails() ){
            return redirect()->route('s_examhome', $unitid)->with([
                'status' => 201,
                'message' => "missing required fields when attempting survey"
            ]);
        }
        $input = $req->all();
        /** create exam attempt */
        $exam = $input['exam'];
        $questions = $this->pull_exam_questions($exam);
        $_the_input = [];
        $_the_input['exam'] = $exam;
        $_the_input['student'] = Auth::user()->id;
        try{
            if( is_array($questions) )
            {
                $index = 1;
                foreach( $questions as $_question ):
                    $this->has_attempted($exam, $_question['id']);
                    $_the_input['question_id'] = $_question['id'];
                    $_the_input['q_index'] = $index;
                    $_the_input['correct'] = $this->extract_correct($_question['options']);
                    $_the_input['selected'] = null;
                    // $_the_input['maxscore'] = $_question['maxscore'];
                    ExamProgress::create($_the_input);
                    $index++;
                endforeach;
            }
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            return redirect()->route('s_examhome', $unitid)->with([
                'status' => 201,
                'message' => "you have already answered this survey."
            ]);
        }
        $first_q = $questions[0];
        $max_index = $this->exam_q_count($exam);
        Session::put('first_q', $first_q);
        Session::put('current_index', 1);
        Session::put('prev_index', $max_index);
        Session::put('next_index', 2);
        if( $max_index == 1 )
        {
            Session::put('next_index', $max_index);
        }
        return redirect()->route('s_surveyattempt', [$input['exam'], $input['unit']])->with([
            'status' => 200,
            'message' => "all the best"
        ]); 
    }
    public function s_examattempt($exam, $unitid)
    {
        return view('learner.s_examattempt')->with([
            'this_unit' => $this->this_unit($unitid),
            'this_exam' => $this->this_exam($exam),
            'first_question' => Session::get('first_q'),
        ]);
    }
    public function s_surveyattempt($exam, $unitid)
    {
        return view('learner.s_surveyattempt')->with([
            'this_unit' => $this->this_unit($unitid),
            'this_exam' => $this->this_exam($exam),
            'first_question' => Session::get('first_q'),
        ]);
    }
    /** exam progress monitor functions */
    public function ep_next(Request $req)
    {
        //var data = "choice=" + choice + "&exam=" + this_exam + "&question=" + this_question + "&mark=" + score;
        $validator = Validator::make($req->all(), [
            'choice' => 'required|string',
            'exam' => 'required|string',
            'question' => 'required|string',
            'mark' => 'required|string',
        ]);
        if( $validator->fails() ){
            return response([
                'status' => 201,
                'message' => "Please select at least 1 answer"
            ], 401);
        }
        $input = $req->all();
        $max_index = $this->exam_q_count($input['exam']);
        $this->score_user_choice($input['exam'], $input['question'], $input['choice'], $input['mark']);
        /** indices */
        $cur_index = Session::get('next_index');
        $prev_index = Session::get('current_index');
        $next_index = $cur_index + 1;
        if( $cur_index == $max_index )
        {
            $next_index = 1;
        }
        if( $cur_index == 1 )
        {
            $prev_index = $max_index;
        }
        /** reset indices */
        Session::put('current_index', $cur_index);
        Session::put('prev_index', $prev_index);
        Session::put('next_index', $next_index);
        $next_q  = $this->next_question($input['exam'], $cur_index);
        return response([
            'status' => 200,
            'message' => "next question",
            'data' => $next_q
        ], 200);
    }
    public function ep_prev(Request $req)
    {
        //var data = "choice=" + choice + "&exam=" + this_exam + "&question=" + this_question + "&mark=" + score;
        $validator = Validator::make($req->all(), [
            'choice' => 'required|string|not_in:undefined',
            'exam' => 'required|string',
            'question' => 'required|string',
            'mark' => 'required|string',
        ]);
        if( $validator->fails() ){
            return response([
                'status' => 201,
                'message' => "Please select at least 1 answer"
            ], 401);
        }
        $input = $req->all();
        $max_index = $this->exam_q_count($input['exam']);
        $this->score_user_choice($input['exam'], $input['question'], $input['choice'], $input['mark']);
        /** indices */
        $cur_index = Session::get('prev_index');
        $next_index = Session::get('current_index');
        $prev_index = $cur_index - 1;
        if( $cur_index == $max_index )
        {
            $prev_index = 1;
        }
        if( $cur_index == 1 )
        {
            $prev_index = $max_index;
        }
        /** reset indices */
        Session::put('current_index', $cur_index);
        Session::put('prev_index', $prev_index);
        Session::put('next_index', $next_index);
        $next_q  = $this->next_question($input['exam'], $cur_index);
        return response([
            'status' => 200,
            'message' => "next question",
            'data' => $next_q
        ], 200);
    }
    public function ep_finish(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'exam' => 'required|string',
        ]);
        if( $validator->fails() ){
            return response([
                'status' => 201,
                'message' => "Exam session ended a while ago"
            ], 401);
        }
        $exam = $req->get('exam');
        $unit = Exam::find($exam)->unit;
        $goto = route('s_examhome', ['unit' => $unit]);
        $score = ExamProgress::where('exam', $exam)->where('student', Auth::user()->id)->get()->sum('maxscore');
        $payload = [];
        $payload['learner'] = Auth::user()->id;
        $payload['exam'] = $exam;
        $payload['learner_answer'] = 'in logs';
        $payload['markedby'] = 0;
        $payload['score'] = $score;
        $payload['is_deleted'] = false;
        $payload['is_marked'] = true;
        LearnerExam::create($payload);
        Session::remove('current_index');
        Session::remove('prev_index');
        Session::remove('next_index');
        return response([
            'status' => 200,
            'message' => "exam completed",
            'goto' => $goto
        ], 200);
    }
    /** survey progress monitor functions */
    public function sp_next(Request $req)
    {
        //var data = "choice=" + choice + "&exam=" + this_exam + "&question=" + this_question + "&mark=" + score;
        $validator = Validator::make($req->all(), [
            'choice' => 'required|string',
            'exam' => 'required|string',
            'question' => 'required|string',
            'mark' => 'required|string',
        ]);
        if( $validator->fails() ){
            return response([
                'status' => 201,
                'message' => "Please select at least 1 answer"
            ], 401);
        }
        $input = $req->all();
        $max_index = $this->exam_q_count($input['exam']);
        $this->score_user_choice($input['exam'], $input['question'], $input['choice'], $input['mark']);
        /** indices */
        $cur_index = Session::get('next_index');
        $prev_index = Session::get('current_index');
        $next_index = $cur_index + 1;
        if( $cur_index == $max_index )
        {
            $next_index = 1;
        }
        if( $cur_index == 1 )
        {
            $prev_index = $max_index;
        }
        /** reset indices */
        Session::put('current_index', $cur_index);
        Session::put('prev_index', $prev_index);
        Session::put('next_index', $next_index);
        $next_q  = $this->next_question($input['exam'], $cur_index);
        return response([
            'status' => 200,
            'message' => "next question",
            'data' => $next_q
        ], 200);
    }
    public function sp_prev(Request $req)
    {
        //var data = "choice=" + choice + "&exam=" + this_exam + "&question=" + this_question + "&mark=" + score;
        $validator = Validator::make($req->all(), [
            'choice' => 'required|string|not_in:undefined',
            'exam' => 'required|string',
            'question' => 'required|string',
            'mark' => 'required|string',
        ]);
        if( $validator->fails() ){
            return response([
                'status' => 201,
                'message' => "Please select at least 1 answer"
            ], 401);
        }
        $input = $req->all();
        $max_index = $this->exam_q_count($input['exam']);
        $this->score_user_choice($input['exam'], $input['question'], $input['choice'], $input['mark']);
        /** indices */
        $cur_index = Session::get('prev_index');
        $next_index = Session::get('current_index');
        $prev_index = $cur_index - 1;
        if( $cur_index == $max_index )
        {
            $prev_index = 1;
        }
        if( $cur_index == 1 )
        {
            $prev_index = $max_index;
        }
        /** reset indices */
        Session::put('current_index', $cur_index);
        Session::put('prev_index', $prev_index);
        Session::put('next_index', $next_index);
        $next_q  = $this->next_question($input['exam'], $cur_index);
        return response([
            'status' => 200,
            'message' => "next question",
            'data' => $next_q
        ], 200);
    }
    public function sp_finish(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'exam' => 'required|string',
        ]);
        if( $validator->fails() ){
            return response([
                'status' => 201,
                'message' => "Exam session ended a while ago"
            ], 401);
        }
        $exam = $req->get('exam');
        $unit = Exam::find($exam)->unit;
        $goto = route('s_examhome', ['unit' => $unit]);
        $score = ExamProgress::where('exam', $exam)->where('student', Auth::user()->id)->get()->sum('maxscore');
        $payload = [];
        $payload['learner'] = Auth::user()->id;
        $payload['exam'] = $exam;
        $payload['learner_answer'] = 'in logs';
        $payload['markedby'] = 0;
        $payload['score'] = $score;
        $payload['is_deleted'] = false;
        $payload['is_marked'] = true;
        LearnerExam::create($payload);
        Session::remove('current_index');
        Session::remove('prev_index');
        Session::remove('next_index');
        return response([
            'status' => 200,
            'message' => "exam completed",
            'goto' => $goto
        ], 200);
    }
    /*** FORUM */
    public function s_forum()
    {
        return view('learner.s_forum')->with([
            'payload' => $this->all_forum(),
            'units' => $this->all_units(),
        ]);
    }
    public function s_add_forum(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'unit' => 'required|string|not_in:nn',
            'forum' => 'required|string',
        ]);
        if( $validator->fails() ){
            return redirect()->route('s_forum')->with([
                'status' => 201,
                'message' => "Error creating forum Post. missing required fields"
            ]);
        }
        $input = $req->all();
        if( $req->hasFile('ffile') )
        {
            $file_uuid = (string) Str::uuid();
            $content = $req->file('ffile');
            $content_name = $file_uuid . $content->getClientOriginalName();
            $explode_image = explode('.', $content_name);
            $extension = end($explode_image);
            if ( ! in_array($extension, $this->allowed_img()) )
            {
                return redirect()->route('s_forum')->with([
                    'status' => 201,
                    'message' => "Error creating forum Post. Invalid file upload. Use a valid image or photo"
                ]);
            }
            Storage::disk('local')->putFileAs('cls/trt/content', $content, $content_name);
            $input['ffile'] = $content_name;
        }
        $input['uploadedby'] = Auth::user()->id;
        $input['course'] = Unit::find($input['unit'])->course;
        Forum::create($input);
        return redirect()->route('s_forum')->with([
            'status' => 200,
            'message' => "Forum Posted Successfully!"
        ]);
    }
    public function s_add_freply(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'reply' => 'required|string',
            'forum' => 'required|string',
        ]);
        if( $validator->fails() ){
            return redirect()->route('s_forum')->with([
                'status' => 201,
                'message' => "Error creating forum reply. missing required fields"
            ]);
        }
        $input = $req->all();
        $input['uploadedby'] = Auth::user()->id;
        ForumReply::create($input);
        return redirect()->route('s_forum')->with([
            'status' => 200,
            'message' => "Replied Successfully!"
        ]);
    }
    protected function all_forum()
    {
        $c_ = Forum::where('is_deleted', false)->orderBy('created_at', 'desc')->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function all_units()
    {
        $c_ = Unit::where('is_deleted', false)->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function allowed_img()
    {
        return ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg','tif', 'tiff', 'wbmp'];
    }
    protected function next_question($exam, $index)
    {
        $meta = ExamProgress::where('exam', $exam)->where('student', Auth::user()->id)->where('q_index', $index)->first();
        if(is_null($meta))
        {
            return redirect()->route('s_home')->with([
                'status' => 201,
                'message' => "Unknown error occured when automarking your exam"
            ]); 
        }
        $q_meta = Question::find($meta->question_id);
        if(is_null($q_meta))
        {
            return redirect()->route('s_home')->with([
                'status' => 201,
                'message' => "Unknown error occured when fetching your exam. Contact admins"
            ]); 
        }
        $q_meta = $q_meta->toArray();
        $options = json_decode($q_meta['options'], true);
        $_radio = '';
        if(count($options))
        {
            $_loop = 0;
            foreach($options as $opt ):
                if( $meta->selected == $opt['Id'])
                {
                    $_radio .= '
                    <input checked id="choices-'.$_loop.'" type="radio" name="choice" value="'.$opt['Id'].'"><label for="choices-'.$_loop.'">'.$opt['Id'].'). '.$opt['Option'].'</label>';
                }
                else 
                {
                    $_radio .= '
                    <input id="choices-'.$_loop.'" type="radio" name="choice" value="'.$opt['Id'].'">
                    <label for="choices-'.$_loop.'">'.$opt['Id'].'). '.$opt['Option'].'</label>';
                }
                $_loop++;
            endforeach;
        }
        $_html = '
        <h2>
        '.$index.'). '.ucwords(strtolower($q_meta['title'])).' ('.$q_meta['maxscore'].'marks)</h2>
        <input id="examid" type="hidden" name="examid" value="'.$exam.'">
        <input id="questionid" type="hidden" name="question" value="'.$q_meta['id'].'">
        <input id="scoreid" type="hidden" name="score" value="'.$q_meta['maxscore'].'">'
        .$_radio;
        return $_html;

    }
    protected function this_unit_grades($unitid)
    {
        $_c = LearnerUnitPerformance::where('unit', $unitid)->where('learner', Auth::user()->id)->get();
        if( is_null($_c) )
        {
            return [];
        }
        return $_c->toArray();
    }
    protected function exam_q_count($exam)
    {
        $rtn = Question::where('exam', $exam)->where('is_deleted', false)->count();
        return $rtn;
    }
    protected function score_user_choice($exam, $q, $choice, $marks)
    {
        $meta = ExamProgress::where('exam', $exam)->where('student', Auth::user()->id)->where('question_id', $q)->first();
        if(is_null($meta))
        {
            return redirect()->route('s_home')->with([
                'status' => 201,
                'message' => "You tried to access an exam that is not well set."
            ]); 
        }
        if( strtoupper($choice) == strtoupper($meta->correct))
        {
            $meta->selected = $choice;
            $meta->maxscore = $marks;
            $meta->is_locked = true;
        }
        else
        {
            $meta->selected = $choice;
            $meta->maxscore = '0';
            $meta->is_locked = true;
        }
        $meta->save();
        return true;
    }
    protected function has_attempted($exam, $q)
    {
        $count = ExamProgress::where('exam', $exam)->where('student', Auth::user()->id)->where('question_id', $q)->count();
        if( $count > 0 )
        {
            return redirect()->route('s_home')->with([
                'status' => 201,
                'message' => "You tried to access an exam that you already attempted"
            ]); 
        }
    }
    protected function extract_correct($json)
    {
        $options = json_decode($json, true);
        foreach( $options as $opt ){
            if( $opt['isAnswer'] == '11' )
            {
                return $opt['Id'];
            }
        }
    }
    protected function pull_exam_questions($exam)
    {
        $q = Question::where('exam', $exam)->where('is_deleted', false)->get();
        if(is_null($q))
        {
            return redirect()->route('s_home')->with([
                'status' => 201,
                'message' => "You tried to access an exam that is not fully set. Please reach out to the administrator for further assistance"
            ]); 
        }
        return $q->toArray();
    }
    protected function this_exam($id)
    {
        $c_ = Exam::find($id);
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function this_unit_exams($unitid)
    {
        $c_ = Exam::where('unit', $unitid)->where('is_deleted', false)->where('is_active', true)->orderBy('id', 'desc')->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function this_unit_assignments($unitid)
    {
        $c_ = Assignment::where('unit', $unitid)->where('is_deleted', false)->orderBy('id', 'desc')->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function this_unit($unitid)
    {
        $c_ = Unit::find($unitid);
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function this_unit_lessons($unitid)
    {
        $c_ = Lesson::where('unit', $unitid)->where('is_deleted', false)->orderBy('live_time', 'asc')->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function all_courses()
    {
        $me = Auth::user()->id;
        $c_ = LearnerCourse::select('course')->where('learner', $me)->where('is_deleted', false)->get();
        if(is_null($c_))
        {
            return [];
        }
        $rtn = Course::whereNotIn('id', $c_->toArray())->orderBy('id', 'desc')->get();
        if(is_null($rtn))
        {
            return [];
        }
        return $rtn->toArray();
    }
    protected function my_active_courses()
    {
        $me = Auth::user()->id;
        $c_ = LearnerCourse::select('course')->where('learner', $me)->where('is_deleted', false)->get();
        if(is_null($c_))
        {
            return [];
        }
        $rtn = Course::whereIn('id', $c_->toArray())->orderBy('id', 'desc')->get();
        if(is_null($rtn))
        {
            return [];
        }
        return $rtn->toArray();
    }
}
