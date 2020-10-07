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
