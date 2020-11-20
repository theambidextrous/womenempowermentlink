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

class AdminController extends Controller
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
        return view('admin.a_home')->with([
            'courses' => $this->active_courses(),
            'counters' => $this->dashboard_counters(),
        ]);
    }
    public function a_coursehome($course_id)
    {
        return view('admin.a_coursehome')->with([
            'course' => $this->this_course($course_id),
            'units' => $this->active_course_units($course_id),
        ]);
    }
    public function a_addcourse(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'duration' => 'required|string',
        ]);
        if( $validator->fails() ){
            return redirect()->route('a_home')->with([
                'status' => 201,
                'courses' => $this->active_courses(),
                'message' => "Error creating course. missing required fields"
            ]);
        }
        $input = $req->all();
        $input['name'] = strtoupper($input['name']);
        Course::create($input);
        return redirect()->route('a_home')->with([
            'status' => 200,
            'courses' => $this->active_courses(),
            'message' => "Course Added"
        ]);
    }
    public function a_editcourse(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'duration' => 'required|string',
        ]);
        if( $validator->fails() ){
            return redirect()->route('a_coursehome', $id)->with([
                'status' => 2001,
                'message' => "missing required fields"
            ]);
        }
        $input = $req->all();
        $input['name'] = strtoupper($input['name']);
        Course::find($id)->update($input);
        return redirect()->route('a_coursehome', $id)->with([
            'status' => 2000,
            'message' => "Course Updated Successfully"
        ]);
    }
    public function a_delcourse($id)
    {
        if( Unit::where('course', $id)->count() > 0 )
        {
            return redirect()->route('a_coursehome', $id)->with([
                'status' => 2001,
                'message' => "Course could not be deleted because it has units."
            ]);
        }
        else 
        {
            Course::find($id)->update(['is_deleted' => true]);
            return redirect()->route('a_coursehome', $id)->with([
                'status' => 2000,
                'message' => "Course Deleted Successfully"
            ]);
        }
    }
    public function a_addunit(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'course' => 'required|string',
            'max_score' => 'required|string',
            'pass_score' => 'required|string',
        ]);
        $cid = $req->get('course');
        if( $validator->fails() ){
            return redirect()->route('a_coursehome', $cid)->with([
                'status' => 201,
                'message' => "missing required fields when creating unit"
            ]);
        }
        $input = $req->all();
        $input['name'] = strtoupper($input['name']);
        if( Unit::create($input) )
        {
            return redirect()->route('a_coursehome', $cid)->with([
                'status' => 200,
                'message' => "Unit created Successfully"
            ]);
        }
        return redirect()->route('a_coursehome', $cid)->with([
            'status' => 200,
            'message' => "Unit created Successfully"
        ]);
    }
    public function a_unithome($unitid)
    {
        return view('admin.a_unithome')->with([
            'unit' => $this->this_unit($unitid),
            'lessons' => $this->active_unit_lessons($unitid),
        ]);
    }
    public function a_uassign($unitid)
    {
        return view('admin.a_uassign')->with([
            'unit' => $this->this_unit($unitid),
            'assignments' => $this->active_unit_assignments($unitid),
        ]);
    }
    public function a_delassign($string){
        $meta = explode('~', $string);
        $unit = $meta[0];
        $id = $meta[1];
        if( LearnerAssignment::where('assignment', $id)->where('is_deleted', false)->count() > 0 )
        {
            return redirect()->route('a_uassign', $unit)->with([
                'status' => 201,
                'message' => "Assignment could not be dropped because some learners have already done it",
            ]);
        }
        Assignment::where('id', $id)->delete();
        return redirect()->route('a_uassign', $unit)->with([
            'status' => 200,
            'message' => "Assignment dropped!",
        ]);
    }
    public function a_addassign(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'unit' => 'required|string',
            'title' => 'required|string',
            'content' => 'required|string',
            'maxscore' => 'required|string',
        ]);
        $id = $req->get('unit');
        if( $validator->fails() ){
            return redirect()->route('a_uassign', $id)->with([
                'status' => 201,
                'message' => "missing required fields when creating assignment"
            ]);
        }
        $input = $req->all();
        $input['title'] = strtoupper($input['title']);
        $input['course'] = Unit::find($id)->course;
        $input['uploadedby'] = Auth::user()->id;
        Assignment::create($input);
        return redirect()->route('a_uassign', $id)->with([
            'status' => 200,
            'message' => "Assignment created Successfully"
        ]);
    }
    public function a_uexams($unitid)
    {
        return view('admin.a_uexams')->with([
            'unit' => $this->this_unit($unitid),
            'exams' => $this->active_unit_exams($unitid),
            'surveys' => $this->active_unit_surveys($unitid),
            'quizes' => $this->active_unit_quizes($unitid),
        ]);
    }
    public function a_addexam(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'type' => 'required|string',
            'unit' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'duration' => 'required|string',
            'maxscore' => 'required|string',
        ]);
        $id = $req->get('unit');
        $type = $req->get('type');
        if( $validator->fails() ){
            return redirect()->route('a_uexams', $id)->with([
                'status' => $this->exam_type_msg_code($type) .'1',
                'message' => "missing required fields when creating " . $this->exam_type_is($type)
            ]);
        }
        $input = $req->all();
        $input['title'] = strtoupper($input['title']);
        $input['course'] = Unit::find($id)->course;
        $input['uploadedby'] = Auth::user()->id;
        Exam::create($input);
        return redirect()->route('a_uexams', $id)->with([
            'status' => $this->exam_type_msg_code($type) .'0',
            'message' => ucwords($this->exam_type_is($type)) . " created Successfully"
        ]);
    }
    public function a_delexam($string){
        $meta = explode('~', $string);
        $unit = $meta[0];
        $id = $meta[1];
        if( Question::where('exam', $id)->where('is_deleted', false)->count() > 0 )
        {
            return redirect()->route('a_uexams', $unit)->with([
                'status' => 201,
                'message' => "Item could not be dropped because it has questions assigned",
            ]);
        }
        Exam::where('id', $id)->delete();
        return redirect()->route('a_uexams', $unit)->with([
            'status' => 200,
            'message' => "Item dropped!",
        ]);
    }
    public function a_delq($string){
        $meta = explode('~', $string);
        $unit = $meta[0];
        $exam = $meta[1];
        $question = $meta[2];
        if( Exam::find($exam)->is_active )
        {
            return redirect()->route('a_uexam_qhome', [ $unit, $exam])->with([
                'status' => 201,
                'message' => "Cannot drop a question on an exam that is In progress"
            ]);
        }
        Question::find($question)->delete();
        return redirect()->route('a_uexam_qhome', [ $unit, $exam])->with([
            'status' => 200,
            'message' => "Question dropped!"
        ]);
    }
    public function a_uexam_qhome($unit, $exam)
    {
        return view('admin.a_uexam_qhome')->with([
            'unit' => $this->this_unit($unit),
            'exam' => $this->this_exam($exam),
            'questions' => $this->active_exam_questions($exam),
        ]);
    }
    public function a_addexam_q(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'exam' => 'required|string',
            'unit' => 'required|string',
            'title' => 'required|string',
            'identity' => 'required|array',
            'opt' => 'required|array',
            'iscorrect' => 'required|array',
            'maxscore' => 'required|string',
        ]);
        if( $validator->fails() ){
            return response([
                'status' => 201,
                'message' => "Fill in all the required fields"
            ], 401);
        }
        if( Exam::find($req->get('exam'))->is_active )
        {
            return response([
                'status' => 205,
                'message' => "EXAM ALREADY IN PROGRESS. You are not allowed to manipulate active exams"
            ], 401);
        }
        $input = $req->all();
        if( count($input['identity']) == count($input['opt'])  && 
            count($input['opt']) == count($input['iscorrect']) && 
            count($input['iscorrect']) > 1 )
        {
            if( count($input['identity']) > count(array_unique($input['identity'])) )
            {
                return response([
                    'status' => 203,
                    'message' => "Options Identifiers should be unique, you have duplicates"
                ], 401);
            }
            if( count($input['opt']) > count(array_unique($input['opt'])) )
            {
                return response([
                    'status' => 203,
                    'message' => "Options values should be unique, you have duplicates"
                ], 401);
            }
            $yesyes = [];
            foreach( $input['iscorrect'] as $v ):
                if( $v == '11' )
                {
                    array_push($yesyes, 1);
                }
            endforeach;
            if( count($yesyes) > 1 )
            {
                return response([
                    'status' => 203,
                    'message' => "There should be only one CORRECT option"
                ], 401);
            }
            if(  count($yesyes) < 1  )
            {
                return response([
                    'status' => 203,
                    'message' => "There should be at least one CORRECT option"
                ], 401);
            }

            $options = [];
            $lp=0;
            foreach ($input['identity'] as $A ) {
                $entry = [
                    'Id' => $A,
                    'Option' => $input['opt'][$lp],
                    'isAnswer' => $input['iscorrect'][$lp]
                ];
                array_push($options, $entry);
                $lp++;
            }
            $input['title'] = strtoupper($input['title']);
            $input['options'] = json_encode($options);
            $input['uploadedby'] = Auth::user()->id;
            Question::create($input);
            return response([
                'status' => 200,
                'message' => "Question Added."
            ], 200);
        }
        else 
        {
            return response([
                'status' => 202,
                'message' => "All options fields must be equal in number"
            ], 401);
        }
        
    }
    public function a_act_exam(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'exam' => 'required|string',
            'unit' => 'required|string',
        ]);
        if( $validator->fails() ){
            return redirect()->route('a_uexam_qhome', [ $req->get('unit'), $req->get('exam')])->with([
                'status' => 201,
                'message' => "missing required fields when activating exam"
            ]);
        }
        Exam::find($req->get('exam'))->update([ 'is_active' => true ]);
        //notify users/push notification
        return redirect()->route('a_uexam_qhome', [ $req->get('unit'), $req->get('exam')])->with([
            'status' => 200,
            'message' => "Exam (".Exam::find($req->get('exam'))->title.") has been ctivated"
        ]);
    }
    public function a_editunit(Request $req, $unitid)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'pass_score' => 'required|string',
        ]);
        if( $validator->fails() ){
            return redirect()->route('a_unithome', $id)->with([
                'status' => 2001,
                'message' => "missing required fields when updating unit"
            ]);
        }
        $input = $req->all();
        $input['name'] = strtoupper($input['name']);
        Unit::find($unitid)->update($input);
        return redirect()->route('a_unithome', $unitid)->with([
            'status' => 2000,
            'message' => "Unit updated Successfully"
        ]);
    }
    public function a_delunit($id)
    {
        if( Lesson::where('unit', $id)->count() > 0 )
        {
            return redirect()->route('a_unithome', $id)->with([
                'status' => 2001,
                'message' => "Unit could not be deleted because it has lessons."
            ]);
        }
        else 
        {
            Unit::find($id)->update(['is_deleted' => true]);
            return redirect()->route('a_unithome', $id)->with([
                'status' => 2000,
                'message' => "Unit Deleted Successfully"
            ]);
        }
    }
    public function a_addlesson(Request $req)
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
            return redirect()->route('a_unithome', $unitid)->with([
                'status' => 201,
                'message' => "missing required fields when creating unit lesson"
            ]);
        }
        if( ! $req->hasfile('content') )
        {
            return redirect()->route('a_unithome', $unitid)->with([
                'status' => 201,
                'message' => "File field is required. Upload pdf or video not more than 20mb"
            ]);
        }
        $input = $req->all();
        $content = $req->file('content');
        $extension = $content->getClientOriginalExtension();
        $content_name = $file_uuid . '.' . $extension;
        if ( !$this->validLesson($extension) )
        {
            return redirect()->route('a_unithome', $unitid)->with([
                'status' => 201,
                'message' => "only pdf, mp4, avi, mkv & 3gp files allowed"
            ]);
        }
        Storage::disk('local')->putFileAs('cls/trt/content', $content, $content_name);
        $input['content'] = $content_name;
        $input['live_link'] = 'not_applicable';
        $input['live_time'] = 'not_applicable';
        $input['name'] = strtoupper($input['name']);
        Lesson::create($input);
        return redirect()->route('a_unithome', $unitid)->with([
            'status' => 200,
            'message' => "Lesson created Successfully"
        ]);
    }
    public function a_addlesson_live(Request $req)
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
            return redirect()->route('a_unithome', $unitid)->with([
                'status' => 201,
                'message' => "missing required fields when creating unit lesson"
            ]);
        }
        $input = $req->all();
        $input['content'] = 'not_applicable';
        $input['live_time'] = $input['live_time_a'].' '.$input['live_time_b'];
        $input['name'] = strtoupper($input['name']);
        Lesson::create($input);
        return redirect()->route('a_unithome', $unitid)->with([
            'status' => 200,
            'message' => "Lesson created Successfully"
        ]);
    }
    public function a_lessonhome($lessonid)
    {
        return view('admin.a_lessonhome')->with([
            'lesson' => $this->this_lesson($lessonid),
        ]);
    }
    public function a_editlesson(Request $req, $lessonid)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'live_link' => 'string',
            'live_time' => 'string',
        ]);
        if( $validator->fails() ){
            return redirect()->route('a_lessonhome', $lessonid)->with([
                'status' => 2001,
                'message' => "missing required fields when updating lesson"
            ]);
        }
        $input = $req->all();
        if( Lesson::find($lessonid)->content == 'not_applicable')
        {
            if( empty($input['live_link']) || empty($input['live_time']) )
            {
                return redirect()->route('a_lessonhome', $lessonid)->with([
                    'status' => 2001,
                    'message' => "missing required fields when updating lesson"
                ]);
            }
            $input['content'] = 'not_applicable';
            $input['name'] = strtoupper($input['name']);
            Lesson::find($lessonid)->update($input);
        }
        else 
        {
            $file_uuid = (string) Str::uuid();
            if( $req->hasfile('content') )
            {
                $content = $req->file('content');
                $content_name = $file_uuid . $content->getClientOriginalName();
                Storage::disk('local')->putFileAs('cls/trt/content', $content, $content_name);
                $input['content'] = $content_name;
            }
            $input['live_link'] = 'not_applicable';
            $input['live_time'] = 'not_applicable';
            $input['name'] = strtoupper($input['name']);
            Lesson::find($lessonid)->update($input);
        }
        return redirect()->route('a_lessonhome', $lessonid)->with([
            'status' => 2000,
            'message' => "Lesson updated Successfully"
        ]);
    }
    public function a_dellesson($lessonid)
    {
        Lesson::find($lessonid)->update(['is_deleted' => true]);
        return redirect()->route('a_lessonhome', $lessonid)->with([
            'status' => 2000,
            'message' => "Lesson Deleted Successfully"
        ]);
    }
    public function stream($file)
    {
        $filename = ('app/cls/trt/content/'.$file);
        return response()->download(storage_path($filename), null, [], null);
    }
    public function a_admins()
    {
        return view('admin.admins.a_admins')->with([
            'admins' => $this->this_active_admins(),
        ]);
    }
    public function a_add_admin(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
        ]);
        if( $validator->fails() ){
            return redirect()->route('a_admins')->with([
                'status' => 201,
                'message' => "missing required fields when creating admin"
            ]);
        }
        $input  = $req->all();
        if( User::where('email', $input['email'])->count() > 0 ){
            return redirect()->route('a_admins')->with([
                'status' => 201,
                'message' => "User with that email already exists"
            ]);
        }
        if( User::where('phone', $input['phone'])->count() > 0 ){
            return redirect()->route('a_admins')->with([
                'status' => 201,
                'message' => "User with that phone already exists"
            ]);
        }
        $input['password'] = 'WEL@2024';
        $input['is_admin'] = true;
        $input['is_teacher'] = false;
        $input['is_student'] = false;
        $input['is_active'] = true;
        $input['password'] = Hash::make($input['password']);
        User::create($input);
        return redirect()->route('a_admins')->with([
            'status' => 200,
            'message' => "Administrator added",
        ]);
    }
    public function a_tutors()
    {
        return view('admin.tutors.a_tutors')->with([
            'tutors' => $this->this_active_tutors(),
        ]);
    }
    public function a_add_tutor(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
        ]);
        if( $validator->fails() ){
            return redirect()->route('a_tutors')->with([
                'status' => 201,
                'message' => "missing required fields when creating tutor"
            ]);
        }
        $input  = $req->all();
        if( User::where('email', $input['email'])->count() > 0 ){
            return redirect()->route('a_tutors')->with([
                'status' => 201,
                'message' => "User with that email already exists"
            ]);
        }
        if( User::where('phone', $input['phone'])->count() > 0 ){
            return redirect()->route('a_tutors')->with([
                'status' => 201,
                'message' => "User with that phone already exists"
            ]);
        }
        $input['password'] = 'WEL@2024';
        $input['is_admin'] = false;
        $input['is_teacher'] = true;
        $input['is_student'] = false;
        $input['is_active'] = true;
        $input['password'] = Hash::make($input['password']);
        User::create($input);
        return redirect()->route('a_tutors')->with([
            'status' => 200,
            'message' => "Tutor added",
        ]);
    }
    public function a_tutorhome($id)
    {
        return view('admin.tutors.a_tutorhome')->with([
            'tutor' => $this->this_tutor($id),
            'units' => $this->this_tutor_units($id),
            'all_units' => $this->this_all_units($id),
        ]);
    }
    public function a_edittutor(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
        ]);
        if( $validator->fails() ){
            return redirect()->route('a_tutorhome', $id)->with([
                'status' => 2001,
                'message' => "missing required fields when updating tutor"
            ]);
        }
        $input  = $req->all();
        User::find($id)->update($input);
        return redirect()->route('a_tutorhome', $id)->with([
            'status' => 2000,
            'message' => "Tutor updated",
        ]);
    }
    public function a_assign_unit(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'unit' => 'required|array',
            'user' => 'required|string',
        ]);
        $id = $req->get('user');
        if( $validator->fails() ){
            return redirect()->route('a_tutorhome', $id)->with([
                'status' => 201,
                'message' => "missing required fields when assigning units"
            ]);
        }
        $input  = $req->all();
        if( count($input['unit']) )
        {
            foreach ($input['unit'] as $unit ) {
                $payload = ['unit' => $unit, 'tutor' => $id];
                TutorUnit::create($payload);
            }
        }
        return redirect()->route('a_tutorhome', $id)->with([
            'status' => 200,
            'message' => "Units assigned successfully!",
        ]);
    }
    public function a_drop_unit($string)
    {
        $meta = explode('~', $string);
        $tutor = $meta[0];
        $id = $meta[1];
        TutorUnit::where('id', $id)->delete();
        return redirect()->route('a_tutorhome', $tutor)->with([
            'status' => 200,
            'message' => "Unit dropped!",
        ]);
    }
    public function a_deltutor($id)
    {
        User::where('id', $id)->delete();
        return redirect()->route('a_tutors')->with([
            'status' => 200,
            'message' => "Tutor deleted!",
        ]);
    }

    /** =============== learners ============================= */
    public function a_learners()
    {
        return view('admin.learners.a_learners')->with([
            'learners' => $this->this_all_learners(),
        ]);
    }
    public function a_add_learner(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
        ]);
        if( $validator->fails() ){
            return redirect()->route('a_learners')->with([
                'status' => 201,
                'message' => "missing required fields when creating learner"
            ]);
        }
        $input  = $req->all();
        if( User::where('email', $input['email'])->count() > 0 ){
            return redirect()->route('a_learners')->with([
                'status' => 201,
                'message' => "User with that email already exists"
            ]);
        }
        if( User::where('phone', $input['phone'])->count() > 0 ){
            return redirect()->route('a_learners')->with([
                'status' => 201,
                'message' => "User with that phone already exists"
            ]);
        }
        $input['password'] = 'WEL@2024';
        $input['is_admin'] = false;
        $input['is_teacher'] = false;
        $input['is_student'] = true;
        $input['is_active'] = true;
        $input['password'] = Hash::make($input['password']);
        User::create($input);
        return redirect()->route('a_learners')->with([
            'status' => 200,
            'message' => "Learner added",
        ]);
    }
    public function a_learnerhome($id)
    {
        return view('admin.learners.a_learnerhome')->with([
            'learner' => $this->this_learner($id),
            'courses' => $this->this_learner_courses($id),
            'all_courses' => $this->this_all_courses($id),
        ]);
    }
    public function a_learnergrade($id)
    {
        return view('admin.learners.a_learnergrade')->with([
            'learner' => $this->this_learner($id),
            'enrolled_units' => $this->user_enrolled_units($id),
            'completed_units' => $this->user_completed_units($id),
            'pending_units' => $this->user_pending_units($id),
            'courses' => [],
        ]);
    }
    public function a_gradeunit(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'learner' => 'required|string',
            'unit' => 'required|string',
            'assessment' => 'required|string',
            'exam' => 'required|string',
        ]);
        $id = $req->get('learner');
        if( $validator->fails() ){
            return redirect()->route('a_learnergrade', $id)->with([
                'status' => 2001,
                'message' => "missing required fields when grading"
            ]);
        }
        $input  = $req->all();
        if( $input['assessment'] < 1 || $input['exam'] < 1 ){
            return redirect()->route('a_learnergrade', $id)->with([
                'status' => 2001,
                'message' => "Grades cannot be zero. enter valid values"
            ]);
        }
        if( $input['assessment'] > 40 || $input['exam'] > 60 ){
            return redirect()->route('a_learnergrade', $id)->with([
                'status' => 2001,
                'message' => "Grades cannot be more than the max grade. enter valid values"
            ]);
        }
        $final_grade = floor($input['assessment'] + $input['exam']);
        $unit_meta = Unit::find($input['unit']);
        $pass_score = floor($unit_meta->pass_score);
        $courseid = $unit_meta->course;
        if( LearnerCourse::where('course', $courseid)->where('learner', $id)->count() == 0 ){
            return redirect()->route('a_learnergrade', $id)->with([
                'status' => 2001,
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
            return redirect()->route('a_learnergrade', $id)->with([
                'status' => 2000,
                'message' => "Grades created successfully",
            ]);
        }else{
            $perform->assessment = $input['assessment'];
            $perform->exam = $input['exam'];
            $perform->final = $final_grade;
            $perform->is_passed = $is_passed;
            $perform->is_completed = $is_completed;
            $perform->save();
            return redirect()->route('a_learnergrade', $id)->with([
                'status' => 2000,
                'message' => "Grades for this unit have been updated successfully",
            ]);
        }

    }
    public function a_learnerperf($id)
    {
        return view('admin.learners.a_learnerperf')->with([
            'learner' => $this->this_learner($id),
            'assignments_done' => $this->this_learner_assignments($id),
            'exams_done' => $this->this_learner_exams($id),
        ]);
    }
    public function a_learnercoz($id)
    {
        return view('admin.learners.a_learnercoz')->with([
            'learner' => $this->this_learner($id),
            'courses' => $this->this_learner_courses($id),
            'all_courses' => $this->this_all_courses($id),
        ]);
    }
    public function a_editlearner(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
        ]);
        if( $validator->fails() ){
            return redirect()->route('a_learnerhome', $id)->with([
                'status' => 2001,
                'message' => "missing required fields when updating learner"
            ]);
        }
        $input  = $req->all();
        User::find($id)->update($input);
        return redirect()->route('a_learnerhome', $id)->with([
            'status' => 2000,
            'message' => "learner updated",
        ]);
    }
    public function a_enroll_course(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'course' => 'required|array',
            'learner' => 'required|string',
        ]);
        $id = $req->get('learner');
        if( $validator->fails() ){
            return redirect()->route('a_learnerhome', $id)->with([
                'status' => 201,
                'message' => "missing required fields when enrolling course"
            ]);
        }
        $input  = $req->all();
        if( count($input['course']) )
        {
            foreach ($input['course'] as $course ) {
                $payload = ['course' => $course, 'learner' => $id, 'is_completed' => false];
                LearnerCourse::create($payload);
            }
        }
        return redirect()->route('a_learnerhome', $id)->with([
            'status' => 200,
            'message' => "Course(s) enrolled successfully!",
        ]);
    }
    public function a_drop_course($string)
    {
        $meta = explode('~', $string);
        $learner = $meta[0];
        $id = $meta[1];
        $lcourse_meta = LearnerCourse::find($id);
        $lcount = LearnerUnitPerformance::where('course', $lcourse_meta->course)->where('learner', $learner)->count();
        if( $lcount > 0 )
        {
            return redirect()->route('a_learnerhome', $learner)->with([
                'status' => 201,
                'message' => "Cannot drop course because the learner has enrolled units on it!",
            ]);
        }
        LearnerCourse::where('id', $id)->delete();
        return redirect()->route('a_learnerhome', $learner)->with([
            'status' => 200,
            'message' => "Course dropped!",
        ]);
    }
    public function a_dellearner($id)
    {
        $has_course = LearnerCourse::where('learner', $id)->count();
        if( $has_course > 0 )
        {
            return redirect()->route('a_learnerhome', $id)->with([
                'status' => 2001,
                'message' => "Cannot delete learner because they have courses enrolled",
            ]);
        }
        User::where('id', $id)->delete();
        return redirect()->route('a_learners')->with([
            'status' => 200,
            'message' => "Learner deleted!",
        ]);
    }
    /** ==================== end learners ================================== */
    protected function active_unit_assignments($unit)
    {
        $c_ = Assignment::where('unit', $unit)->where('is_deleted', false)->orderBy('id', 'desc')->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function active_unit_exams($unit)
    {
        $c_ = Exam::where('unit', $unit)->where('type', 666666)->where('is_deleted', false)->orderBy('id', 'desc')->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function active_unit_surveys($unit)
    {
        $c_ = Exam::where('unit', $unit)->where('type', 555555)->where('is_deleted', false)->orderBy('id', 'desc')->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function active_unit_quizes($unit)
    {
        $c_ = Exam::where('unit', $unit)->where('type', 444444)->where('is_deleted', false)->orderBy('id', 'desc')->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function this_learner($id)
    {
        $c_ = User::find($id);
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function user_enrolled_units($learner)
    {
        $c_ = LearnerCourse::select(['course'])->where('is_deleted', false)->get();
        if(is_null($c_))
        {
            return [];
        }
        $course_ids = $c_->toArray();
        $res = Unit::whereIn('course', $course_ids)->orderBy('course', 'asc')->get();
        if(is_null($res))
        {
            return [];
        }
        return $res->toArray();
    }
    protected function user_completed_units($learner)
    {
        $c_ = LearnerUnitPerformance::select(['unit'])->where('learner', $learner)->get();
        if(is_null($c_))
        {
            return [];
        }
        $unit_ids = $c_->toArray();
        $res = Unit::whereIn('id', $unit_ids)->orderBy('course', 'asc')->get();
        if(is_null($res))
        {
            return [];
        }
        return $res->toArray();
    }
    protected function user_pending_units($learner)
    {
        $c_ = LearnerUnitPerformance::select(['unit'])->where('is_deleted', false)->get();
        if(is_null($c_))
        {
            return [];
        }
        $unit_ids = $c_->toArray();
        $res = Unit::whereNotIn('id', $unit_ids)->orderBy('course', 'asc')->get();
        if(is_null($res))
        {
            return [];
        }
        return $res->toArray();
    }
    protected function this_learner_courses($learner)
    {
        $c_ = LearnerCourse::where('learner', $learner)->where('is_deleted', false)->get();
        if(is_null($c_))
        {
            return [];
        }
        $data =  $c_->toArray();
        $rtn = [];
        foreach ($data as $_data) {
            $course_meta = Course::find($_data['course']);
            $_data['course_name'] = $course_meta->name;
            $_data['course_units'] = $this->active_course_units($_data['course']);
            array_push($rtn, $_data);
        }
        return $rtn;
    }
    protected function this_all_courses($learner)
    {
        $c_ = Course::whereNotIn('id', $this->learner_course_ids($learner))->where('is_deleted', false)->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function this_tutor($id)
    {
        $c_ = User::find($id);
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function learner_course_ids($learner)
    {
        $c_=LearnerCourse::select(['course'])->where('learner',$learner)->where('is_deleted',false)->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function tutor_unit_ids($tutor)
    {
        $c_ = TutorUnit::select(['unit'])->where('tutor', $tutor)->where('is_deleted', false)->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function this_tutor_units($id)
    {
        $c_ = TutorUnit::select(['id','unit'])->where('tutor', $id)->where('is_deleted', false)->get();
        if(is_null($c_))
        {
            return [];
        }
        $data =  $c_->toArray();
        $rtn = [];
        foreach ($data as $_data) {
            $unit_meta = Unit::find($_data['unit']);
            $course_name = Course::find($unit_meta->course)->name;
            $unit_name = $unit_meta->name;
            $_data['course'] = $course_name;
            $_data['unit_name'] = $unit_name;
            array_push($rtn, $_data);
        }
        return $rtn;
    }
    protected function this_all_units($tutor)
    {
        $c_ = Unit::whereNotIn('id', $this->tutor_unit_ids($tutor))->where('is_deleted', false)->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function this_all_learners()
    {
        $c_ = User::where('is_student', true)->where('is_active', true)->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function this_active_tutors()
    {
        $c_ = User::where('is_teacher', true)->where('is_active', true)->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function this_active_admins()
    {
        $c_ = User::where('is_admin', true)->where('is_active', true)->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function active_courses()
    {
        $c_ = Course::where('is_deleted', false)->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function deleted_courses()
    {
        $c_ = Course::where('is_deleted', true)->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function active_course_units($course_id)
    {
        $c_ = Unit::where('course', $course_id)->where('is_deleted' , false)->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function this_course($course_id)
    {
        $c_ = Course::find($course_id);
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
    protected function this_exam($examid)
    {
        $c_ = Exam::find($examid);
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function active_exam_questions($ex)
    {
        $c_ = Question::where('exam', $ex)->where('is_deleted', false)->orderBy('id', 'desc')->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function active_unit_lessons($unitid)
    {
        $c_ = Lesson::where('unit', $unitid)->where('is_deleted' , false)->orderBy('id', 'desc')->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function this_lesson($lessonid)
    {
        $c_ = Lesson::find($lessonid);
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function exam_types()
    {
        return [
            '666666' => 'Exam',
            '555555' => 'Surveys',
            '444444' => 'Quizes',
        ];
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
        if( $id == '444444' ){return 2000;}
    }
    protected function this_learner_assignments($user)
    {
        $c_ = LearnerAssignment::where('learner', $user)->where('is_deleted', false)->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function this_learner_exams($user)
    {
        $c_ = LearnerExam::where('learner', $user)->where('is_deleted', false)->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function validLesson($ext)
    {
        if( in_array($ext, ['pdf', 'mp4', 'avi', 'mkv', '3gp', 'mov']) )
        {
            return true;
        }
        return false;
    }
    protected function dashboard_counters()
    {
        $l = User::where('is_student', true)->where('is_active', true)->count();
        $c = Course::where('is_deleted', false)->count();
        $t =User::where('is_teacher', true)->where('is_active', true)->count();
        return [
            'l' => $l,
            'c' => $c,
            't' => $t
        ];
    }
}
