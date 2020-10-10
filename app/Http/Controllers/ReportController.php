<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\User;
use App\Course;
use App\Unit;
use App\Assignment;
use App\Exam;
use App\Forum;
use App\ForumReply;
use Validator;
use Storage;
use Config;

class ReportController extends Controller
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
    public function assign_rpt()
    {
        return view('admin.reports.assign_rpt')->with([
            'payload' => $this->all_assignment(),
        ]);
    }
    public function exam_rpt()
    {
        return view('admin.reports.exam_rpt')->with([
            'payload' => $this->all_exam(),
        ]);
    }
    public function survey_rpt()
    {
        return view('admin.reports.survey_rpt')->with([
            'payload' => $this->all_survey(),
        ]);
    }
    public function forum_rpt()
    {
        return view('admin.reports.forum_rpt')->with([
            'payload' => $this->all_forum(),
            'units' => $this->all_units(),
        ]);
    }
    public function a_add_forum(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'unit' => 'required|string|not_in:nn',
            'forum' => 'required|string',
        ]);
        if( $validator->fails() ){
            return redirect()->route('forum_rpt')->with([
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
                return redirect()->route('forum_rpt')->with([
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
        return redirect()->route('forum_rpt')->with([
            'status' => 200,
            'message' => "Forum Posted Successfully!"
        ]);
    }
    public function a_add_freply(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'reply' => 'required|string',
            'forum' => 'required|string',
        ]);
        if( $validator->fails() ){
            return redirect()->route('forum_rpt')->with([
                'status' => 201,
                'message' => "Error creating forum reply. missing required fields"
            ]);
        }
        $input = $req->all();
        $input['uploadedby'] = Auth::user()->id;
        ForumReply::create($input);
        return redirect()->route('forum_rpt')->with([
            'status' => 200,
            'message' => "Replied Successfully!"
        ]);
    }
    protected function all_assignment()
    {
        $c_ = Assignment::where('is_deleted', false)->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function all_exam()
    {
        $c_ = Exam::where('is_deleted', false)->whereIn('type', ['666666', '444444'])->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function all_survey()
    {
        $c_ = Exam::where('type', '555555')->where('is_deleted', false)->get();
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function all_forum()
    {
        $c_ = Forum::where('is_deleted', false)->get();
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
}
