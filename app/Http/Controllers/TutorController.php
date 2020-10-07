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
use Validator;
use Storage;
use Config;

class TutorController extends Controller
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
        return view('tutor.t_home')->with([
            'myunits' => $this->my_active_units(),
        ]);
    }
    public function t_profile()
    {
        return view('tutor.t_profile')->with([
            'myunits' => [],
        ]);
    }
    public function t_profile_update(Request $req)
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
            return redirect()->route('t_profile')->with([
                'status' => 201,
                'message' => "missing required fields when updating profile"
            ]);
        }
        $input = $req->all();
        User::find(Auth::user()->id)->update($input);
        return redirect()->route('t_profile')->with([
            'status' => 200,
            'message' => "Profile info updated successfully"
        ]);
    }
    public function t_pwd_change(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'password' => 'required|string',
            'cpassword' => 'required|string',
        ]);
        if( $validator->fails() )
        {
            return redirect()->route('t_profile')->with([
                'status' => 2001,
                'message' => "missing required fields when updating password"
            ]);
        }
        $input = $req->all();
        if( strlen($input['password']) < 8 )
        {
            return redirect()->route('t_profile')->with([
                'status' => 2001,
                'message' => "Error. Passwords must be at least 8 characters"
            ]);
        }
        if( $input['password'] != $input['cpassword'] )
        {
            return redirect()->route('t_profile')->with([
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
    public function t_lessonhome($unitid)
    {
        return view('tutor.t_lessonhome')->with([
            'this_unit' => $this->this_unit($unitid),
            'this_unit_lessons' => $this->this_unit_lessons($unitid),
        ]);
    }
    public function t_addlesson(Request $req)
    {
        $file_uuid = (string) Str::uuid();
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'unit' => 'required|string',
        ]);
        $unitid = $req->get('unit');
        if( $validator->fails() )
        {
            return redirect()->route('t_lessonhome', $unitid)->with([
                'status' => 201,
                'message' => "missing required fields when creating unit lesson"
            ]);
        }
        if( ! $req->hasfile('content') )
        {
            return redirect()->route('t_lessonhome', $unitid)->with([
                'status' => 201,
                'message' => "upload a valid content file e.g. pdf, mp4, avi, mkv, 3gp"
            ]);
        }
        $input = $req->all();
        $content = $req->file('content');
        $content_name = $file_uuid . $content->getClientOriginalName();
        Storage::disk('local')->putFileAs('cls/trt/content', $content, $content_name);
        $input['content'] = $content_name;
        $input['live_link'] = 'not_applicable';
        $input['live_time'] = 'not_applicable';
        $input['name'] = strtoupper($input['name']);
        Lesson::create($input);
        return redirect()->route('t_lessonhome', $unitid)->with([
            'status' => 200,
            'message' => "Lesson created Successfully"
        ]);
    }
    public function t_addlesson_live(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'unit' => 'required|string',
            'live_link' => 'required|string',
            'live_time_a' => 'required|string',
            'live_time_b' => 'required|string',
        ]);
        $unitid = $req->get('unit');
        if( $validator->fails() ){
            return redirect()->route('t_lessonhome', $unitid)->with([
                'status' => 201,
                'message' => "missing required fields when creating unit lesson"
            ]);
        }
        $input = $req->all();
        $input['content'] = 'not_applicable';
        $input['live_time'] = $input['live_time_a'].' '.$input['live_time_b'];
        $input['name'] = strtoupper($input['name']);
        Lesson::create($input);
        return redirect()->route('t_lessonhome', $unitid)->with([
            'status' => 200,
            'message' => "Lesson created Successfully"
        ]);
    }
    public function t_droplesson($string)
    {
        $meta = explode('~', $string);
        $unit = $meta[0];
        $lesson = $meta[1];
        Lesson::find($lesson)->update(['is_deleted' => true]);
        return redirect()->route('t_lessonhome', $unit)->with([
            'status' => 200,
            'message' => "Lesson dropped!",
        ]);
    }
    public function t_assignhome($unitid)
    {
        return view('tutor.t_assignhome')->with([
            'this_unit' => $this->this_unit($unitid),
            'this_unit_assign' => $this->this_unit_assign($unitid),
        ]);
    }
    public function t_addassign(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'unit' => 'required|string',
            'title' => 'required|string',
            'content' => 'required|string',
            'maxscore' => 'required|string',
        ]);
        $id = $req->get('unit');
        if( $validator->fails() ){
            return redirect()->route('t_assignhome', $id)->with([
                'status' => 201,
                'message' => "missing required fields when creating assignment"
            ]);
        }
        $input = $req->all();
        $input['title'] = strtoupper($input['title']);
        $input['course'] = Unit::find($id)->course;
        $input['uploadedby'] = Auth::user()->id;
        Assignment::create($input);
        return redirect()->route('t_assignhome', $id)->with([
            'status' => 200,
            'message' => "Assignment created Successfully"
        ]);
    }
    public function t_dropassign($string)
    {
        $meta = explode('~', $string);
        $unit = $meta[0];
        $assign = $meta[1];
        if( LearnerAssignment::where('assignment', $assign)->where('is_deleted', false)->count() > 0 )
        {
            return redirect()->route('t_assignhome', $unit)->with([
                'status' => 201,
                'message' => "Assigment could not be dropped because some learners have already submitted it!",
            ]);
        }
        Assignment::find($assign)->update(['is_deleted' => true]);
        return redirect()->route('t_assignhome', $unit)->with([
            'status' => 200,
            'message' => "Assigment dropped!",
        ]);
    }
    public function t_assignsub($hash)
    {
        $meta = explode('~', $hash);
        $unitid = $meta[0];
        $assign = $meta[1];
        return view('tutor.t_assignsub')->with([
            'this_unit' => $this->this_unit($unitid),
            'this_assignment' => $this->this_assign($assign),
            'submissions' => $this->this_assign_subs($assign),
        ]);
    }
    public function t_assigngrade(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'id' => 'required|string',
            'hash' => 'required|string',
            'score' => 'required|string',
        ]);
        $hash = $req->get('hash');
        $meta = explode('~', $hash);
        $assign_id = $meta[1];
        if( $validator->fails() ){
            return redirect()->route('t_assignsub', $hash)->with([
                'status' => 201,
                'message' => "missing required fields when grading assignment"
            ]);
        }
        $input = $req->all();
        $max_score = Assignment::find($assign_id)->maxscore;
        $input['score'] = floatval($input['score']);
        if( $input['score'] > $max_score )
        {
            return redirect()->route('t_assignsub', $hash)->with([
                'status' => 201,
                'message' => "Grade cannot be more than Assignment max score"
            ]);
        }
        if( $input['score'] < 1 )
        {
            return redirect()->route('t_assignsub', $hash)->with([
                'status' => 201,
                'message' => "Grade cannot be less than zero"
            ]);
        }
        $data = [];
        $data['is_marked'] = true;
        $data['score'] = $input['score'];
        $data['markedby'] = Auth::user()->id;
        LearnerAssignment::find($input['id'])->update($data);
        return redirect()->route('t_assignsub', $hash)->with([
            'status' => 200,
            'message' => "Assignment graded Successfully"
        ]);
    }
    public function t_examhome($unitid)
    {
        return view('tutor.t_examhome')->with([
            'this_unit' => $this->this_unit($unitid),
            'this_unit_exam' => $this->this_unit_exam($unitid),
        ]);
    }
    public function t_addexam(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'type' => 'required|string',
            'unit' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'maxscore' => 'required|string',
        ]);
        $id = $req->get('unit');
        $type = $req->get('type');
        if( $validator->fails() ){
            return redirect()->route('t_examhome', $id)->with([
                'status' => $this->exam_type_msg_code($type) .'1',
                'message' => "missing required fields when creating " . $this->exam_type_is($type)
            ]);
        }
        $input = $req->all();
        $input['title'] = strtoupper($input['title']);
        $input['course'] = Unit::find($id)->course;
        $input['uploadedby'] = Auth::user()->id;
        Exam::create($input);
        return redirect()->route('t_examhome', $id)->with([
            'status' => $this->exam_type_msg_code($type) .'0',
            'message' => ucwords($this->exam_type_is($type)) . " created Successfully"
        ]);
    }
    public function t_delexam($string){
        $meta = explode('~', $string);
        $unit = $meta[0];
        $id = $meta[1];
        if( Question::where('exam', $id)->where('is_deleted', false)->count() > 0 )
        {
            return redirect()->route('t_examhome', $unit)->with([
                'status' => 201,
                'message' => "Item could not be dropped because it has questions assigned",
            ]);
        }
        Exam::where('id', $id)->delete();
        return redirect()->route('t_examhome', $unit)->with([
            'status' => 200,
            'message' => "Item dropped!",
        ]);
    }
    public function t_expreview($hash)
    {
        $meta = explode('~', $hash);
        $unitid = $meta[0];
        $exam = $meta[1];
        return view('tutor.t_expreview')->with([
            'this_unit' => $this->this_unit($unitid),
            'this_exam' => $this->this_exam($exam),
            'questions' => $this->this_exam_questions($exam),
        ]);
    }
    public function t_act_exam(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'exam' => 'required|string',
            'unit' => 'required|string',
        ]);
        $hash = $req->get('unit') . '~' . $req->get('exam');
        if( $validator->fails() ){
            return redirect()->route('t_expreview', $hash)->with([
                'status' => 201,
                'message' => "missing required fields when activating"
            ]);
        }
        Exam::find($req->get('exam'))->update([ 'is_active' => true ]);
        //notify users/push notification
        return redirect()->route('t_expreview', $hash)->with([
            'status' => 200,
            'message' => Exam::find($req->get('exam'))->title." has been ctivated"
        ]);
    }
    public function t_delq($string){
        $meta = explode('~', $string);
        $unit = $meta[0];
        $exam = $meta[1];
        $question = $meta[2];
        $hash = $unit . '~' . $exam;
        if( Exam::find($exam)->is_active )
        {
            return redirect()->route('t_expreview', $hash)->with([
                'status' => 201,
                'message' => "Cannot drop a question on an exam that is In progress"
            ]);
        }
        Question::find($question)->delete();
        return redirect()->route('t_expreview', $hash)->with([
            'status' => 200,
            'message' => "Question dropped!"
        ]);
    }
    public function t_gradehome($unitid)
    {
        return view('tutor.t_gradehome')->with([
            'this_unit' => $this->this_unit($unitid),
            'this_unit_student' => $this->this_unit_student($unitid),
        ]);
    }
    public function t_gradeunit(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'learner' => 'required|string',
            'unit' => 'required|string',
            'assessment' => 'required|string',
            'exam' => 'required|string',
        ]);
        $id = $req->get('unit');
        if( $validator->fails() ){
            return redirect()->route('t_gradehome', $id)->with([
                'status' => 201,
                'message' => "missing required fields when grading"
            ]);
        }
        $input  = $req->all();
        if( $input['assessment'] < 1 || $input['exam'] < 1 ){
            return redirect()->route('t_gradehome', $id)->with([
                'status' => 201,
                'message' => "Grades cannot be zero. enter valid values"
            ]);
        }
        if( $input['assessment'] > 40 || $input['exam'] > 60 ){
            return redirect()->route('t_gradehome', $id)->with([
                'status' => 201,
                'message' => "Grades cannot be more than the max allowed grade. enter valid values"
            ]);
        }
        $final_grade = floatval($input['assessment'] + $input['exam']);
        $unit_meta = Unit::find($input['unit']);
        $pass_score = floor($unit_meta->pass_score);
        $courseid = $unit_meta->course;
        if( LearnerCourse::where('course', $courseid)->where('learner', $input['learner'])->count() == 0 ){
            return redirect()->route('t_gradehome', $id)->with([
                'status' => 201,
                'message' => "Learner not enrolled to this course"
            ]);
        }
        $is_completed = $is_passed = false;
        if( $final_grade > $pass_score ){
            $is_completed = $is_passed = true;
        }
        $perform = LearnerUnitPerformance::where('learner', $input['learner'])
            ->where('unit', $input['unit'])
            ->where('course', $courseid)->first();
        if(is_null( $perform ))
        {
            $input['course'] = $courseid;
            $input['final'] = $final_grade;
            $input['checkedby'] = Auth::user()->id;
            $input['is_completed'] = $is_completed;
            $input['is_passed'] = $is_passed;
            LearnerUnitPerformance::create($input);
            return redirect()->route('t_gradehome', $id)->with([
                'status' => 200,
                'message' => "Graded successfully",
            ]);
        }else{
            $perform->assessment = $input['assessment'];
            $perform->exam = $input['exam'];
            $perform->final = $final_grade;
            $perform->is_passed = $is_passed;
            $perform->is_completed = $is_completed;
            $perform->save();
            return redirect()->route('t_gradehome', $id)->with([
                'status' => 200,
                'message' => "Grades for this unit have been updated successfully",
            ]);
        }

    }
    protected function exam_type_is($id)
    {
        if( $id == '666666' ){return 'exam';}
        if( $id == '555555' ){return 'survey';}
        if( $id == '444444' ){return 'quiz';}
    }
    protected function exam_type_msg_code($id)
    {
        if( $id == '666666' ){return 20;}
        if( $id == '555555' ){return 200;}
        if( $id == '444444' ){return 20;}
    }
    protected function this_unit_student($id)
    {
        $unit_meta = Unit::find($id);
        if( is_null($unit_meta) )
        {
            return [];
        }
        $course = $unit_meta->course;
        $students = LearnerCourse::select('learner')->where('course', $course)->get();
        if( is_null($students) )
        {
            return [];
        }
        $students = $students->toArray();

        $rtn = User::whereIn('id', $students)->where('is_active', true)->orderBy('id', 'desc')->get();
        if(is_null($rtn))
        {
            return [];
        }
        return $rtn->toArray();
    }
    protected function this_unit_exam($id)
    {
        $rtn = Exam::where('unit', $id)->whereIn('type', ['666666', '555555', '444444'])->where('is_deleted', false)->orderBy('id', 'desc')->get();
        if(is_null($rtn))
        {
            return [];
        }
        return $rtn->toArray();
    }
    protected function this_unit_assign($id)
    {
        $rtn = Assignment::where('unit', $id)->where('is_deleted', false)->orderBy('id', 'desc')->get();
        if(is_null($rtn))
        {
            return [];
        }
        return $rtn->toArray();
    }
    protected function this_unit_lessons($id)
    {
        $rtn = Lesson::where('unit', $id)->where('is_deleted', false)->orderBy('id', 'desc')->get();
        if(is_null($rtn))
        {
            return [];
        }
        return $rtn->toArray();
    }
    protected function this_assign_subs($id)
    {
        $rtn = LearnerAssignment::where('assignment', $id)->where('is_deleted', false)->orderBy('id', 'desc')->get();
        if(is_null($rtn))
        {
            return [];
        }
        return $rtn->toArray();
    }
    protected function this_assign($id)
    {
        $rtn = Assignment::find($id);
        if(is_null($rtn))
        {
            return [];
        }
        return $rtn->toArray();
    }
    protected function this_unit($id)
    {
        $rtn = Unit::find($id);
        if(is_null($rtn))
        {
            return [];
        }
        return $rtn->toArray();
    }
    protected function this_exam($id)
    {
        $rtn = Exam::find($id);
        if(is_null($rtn))
        {
            return [];
        }
        return $rtn->toArray();
    }
    protected function this_exam_questions($id)
    {
        $rtn = Question::where('exam', $id)->where('is_deleted', false)->orderBy('id', 'desc')->get();
        if(is_null($rtn))
        {
            return [];
        }
        return $rtn->toArray();
    }
    protected function my_active_units()
    {
        $me = Auth::user()->id;
        $c_ = TutorUnit::select('unit')->where('tutor', $me)->where('is_deleted', false)->get();
        if(is_null($c_))
        {
            return [];
        }
        $rtn = Unit::whereIn('id', $c_->toArray())->get();
        if(is_null($rtn))
        {
            return [];
        }
        return $rtn->toArray();
    }
}
