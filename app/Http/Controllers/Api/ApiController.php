<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
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
use App\Pcode;
use App\Message;
use Validator;
use Storage;
use Config;
/** mail */
use Illuminate\Support\Facades\Mail;
use App\Mail\NewSignUp;
use App\Mail\NewReqReset;
/** notification */
use App\Notifications\ActivateNotification;

class ApiController extends Controller
{
    public function test_push()
    {
        try{
            $notifiable_res = $this->push_notify('Login success. Welcome to WEL', Auth::user()->id, 'womensempowermentlink');
            return $notifiable_res;
            // return response([
            //     'status' => 200,
            //     'message' => "push test",
            // ], 200);
        }catch( Exception $e )
        {
            return response([
                'status' => 211,
                'message' => $e->getMessage(),
            ], 403);
        }
    }
    public function push_notify($msg, $userid, $channel)
    {
        try{
            // $notifiable_res = null;
            $user = User::find($userid);
            if(!is_null($user) && !is_null($user->device_token))
            {
                $payload = [
                    'expo_token' => $user->device_token,
                    'title' => 'Women Empowerment Link - WEL',
                    'message' => $msg,
                    'channel' => $channel
                ];
                Message::create($payload);
                $payload = json_decode(json_encode($payload));
                return $user->notify(new ActivateNotification($payload));
            }
            throw new \Exception('Notification error. User information not set.');
        }catch( \Exception $e ){
            throw new \Exception($e->getMessage());
        }
    }
    public function stream($file)
    {
        $filename = ('app/cls/trt/content/'.$file);
        return response()->download(storage_path($filename), null, [], null);
    }
    public function signin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        if( $validator->fails() ){
            return response([
                'status' => 201,
                'message' => "Invalid data Error",
                'errors' => $validator->errors()->all(),
            ], 403);
        }
        $login = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        if( !Auth::attempt( $login ) )
        {
            return response([
                'status' => 201,
                'message' => "Invalid username or password. Try again",
                'errors' => [],
            ], 403);
        }
        $accessToken = Auth::user()->createToken('authToken')->accessToken;
        $user = Auth::user();
        $notifiable_res = $this->test_push();
        if( !Auth::user()->is_student )
        {
            return response([
                'status' => 201,
                'message' => "Invalid Account. Access allowed for learners only",
                'errors' => [],
            ], 403);
        }
        $user['token'] = $accessToken;
        return response([
            'status' => 200,
            'message' => 'Success. logged in',
            'payload' => $user,
            'notifiable' => $notifiable_res,
        ], 200);
    }
    public function signup(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required|string',
                'password' => 'required|string',
                'c_password' => 'required|same:password',
                'county' => 'required|string',
                'position' => 'required|string',
                'gender' => 'required|string'
            ]);
            if( $validator->fails() ){
                return response([
                    'status' => 201,
                    'message' => "Invalid data Error. All fields are required",
                    'errors' => $validator->errors()->all(),
                ], 403);
            }
            $input = $request->all();
            if( User::where('email', $input['email'])->count() )
            {
                return response([
                    'status' => 201,
                    'message' => "Email address already used",
                    'errors' => $validator->errors()->all(),
                ], 403);
            }
            if( User::where('phone', $input['phone'])->count() )
            {
                return response([
                    'status' => 201,
                    'message' => "phone number already used",
                    'errors' => $validator->errors()->all(),
                ], 403);
            }
            // $input['password'] = bcrypt($input['password']);
            $input['is_admin'] = false;
            $input['is_teacher'] = false;
            $input['is_student'] = true;
            $input['is_active'] = true;
            // $input['gender'] = 'FEMALE';
            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);
            $access_token = $user->createToken('authToken')->accessToken;
            $user['token'] = $access_token;
            Mail::to($input['email'])->send(new NewSignUp($input));
            return response([
                'status' => 200,
                'message' => 'Success. Account created',
                'payload' => $user,
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response([
                'status' => 201,
                'message' => "Server error. Invalid data",
                'errors' => [],
            ], 403);
        } catch (PDOException $e) {
            return response([
                'status' => 201,
                'message' => "Db error. Invalid data",
                'errors' => [],
            ], 403);
        }
    }
    public function d_token($pushToken)
    {
        if(!strlen(Auth::user()->device_token))
        {
            User::find(Auth::user()->id)->update(['device_token' => $pushToken]);
            return response([
                'status' => 200,
                'message' => "device token updated",
                'payload' => $pushToken,
            ], 200);
        }
        return response([
            'status' => 200,
            'message' => "device token exist",
            'payload' => Auth::user()->device_token,
        ], 403);
        
    }
    public function update_p_pic(Request $request)
    {
        try{
            $input = [];
            if( $request->hasFile('photo') )
            {
                $content = $request->file('photo');
                $content_name = time() . $content->getClientOriginalName();
                Storage::disk('welapp')->putFileAs('images', $content, $content_name);
                $input['profile'] = $content_name;
            }
            $user = User::find(Auth::user()->id)->update($input);
            return response([
                'status' => 200,
                'message' => 'Success. Account updated',
                'payload' => $user,
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response([
                'status' => 201,
                'message' => "Server error. Invalid data",
                'errors' => [],
            ], 403);
        } catch (PDOException $e) {
            return response([
                'status' => 201,
                'message' => "Storage error. Invalid data",
                'errors' => [],
            ], 403);
        }
    }
    public function update_profile(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required|string',
                'address' => 'string',
                'gender' => 'required|string',
                'county' => 'required|string',
                'position' => 'required|string',
            ]);
            if( $validator->fails() ){
                return response([
                    'status' => 201,
                    'message' => "Invalid data Error. All fields are required",
                    'errors' => $validator->errors(),
                ], 403);
            }
            $input = $request->all();
            $user = User::find(Auth::user()->id)->update($input);
            return response([
                'status' => 200,
                'message' => 'Success. Account updated',
                'payload' => $user,
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response([
                'status' => 201,
                'message' => "Server error. Invalid data",
                'errors' => [],
            ], 403);
        } catch (PDOException $e) {
            return response([
                'status' => 201,
                'message' => "Storage error. Invalid data",
                'errors' => [],
            ], 403);
        }
    }
    public function reqreset($email)
    {
        try{
            $user = User::where('email', $email)->count();
            if(!$user){
                return response([
                    'status' => 201,
                    'message' => "There is no user with that email. Try again or create account",
                    'errors' => [],
                ], 403); 
            }
            Pcode::where('email', $email)->update(['used' => true]);
            $code = $this->createCode(6,1);
            $data = ['email' => $email, 'code' => $code ];
            if( Pcode::create($data) )
            {
                $msg = "Hi, use OTP code " . $code . " to validate your account.";
                $data['msg'] = $msg;
                Mail::to($data['email'])->send(new NewReqReset($data));
                return response([
                    'status' => 200,
                    'message' => "A one time password has been sent to your email address",
                    'errors' => [],
                ], 200); 
            }
            return response([
                'status' => 201,
                'message' => "Error sending email",
                'errors' => [],
            ], 403); 
            
        }catch( Exception $e){
            return response([
                'status' => 201,
                'message' => $e->getMessage(),
                'errors' => [],
            ], 403); 
        }
    }
    public function verifyreset($code, $email)
    {
        try{
            $data = ['email' => $email, 'code' => $code ];
            $isValid = Pcode::where('email', $email)
                ->where('code', $code)
                ->where('used', false)
                ->orderBy('created_at', 'desc')
                ->first();
            if( !is_null($isValid) )
            {
                $isValid->used = true;
                $isValid->save();
                return response([
                    'status' => 200,
                    'message' => "Code verified!",
                    'payload' => $data,
                    'errors' => [],
                ], 200); 
            }
            return response([
                'status' => 201,
                'message' => "You entered Invalid Verification Code" . $code.' - '.$email,
                'errors' => [],
            ], 403); 
        }catch( Exception $e){
            return response([
                'status' => 201,
                'message' => "Invalid Access. No data",
                'errors' => [],
            ], 403); 
        }
    }
    public function finishreset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
            'c_password' => 'required|same:password'
        ]);
        if( $validator->fails() ){
            return response([
                'status' => 201,
                'message' => 'Passwords do no match',
                'errors' => $validator->errors()
            ], 403);
        }
        $email = $request->get('email');
        $user = User::where('email', $email)->first();
        if(!is_null($user)){
            $user->password = Hash::make($request->get('password'));
            $user->save();
            return response([
                'status' => 200,
                'message' => 'Password was reset, Login now',
                'payload' => $email
            ], 200);
        }
        return response([
            'status' => 201,
            'message' => 'Data error. We could not updte password',
            'errors' => []
        ], 403);
    }
    public function get_courses()
    {
        return response([
            'status' => 200,
            'message' => 'Courses',
            'payload' => [
                'myc' => $this->my_active_courses(),
                'all' => $this->all_courses(),
            ]
        ], 200);
    }
    public function drop_course($course)
    {
        $me = Auth::user()->id;
        $c_ = LearnerUnitPerformance::where('learner', $me)->where('course', $course)->count();
        if( $c_ > 0 )
        {
            return response([
                'status' => 201,
                'message' => 'You cannot drop this course because you have active units',
                'payload' => $course
            ], 200);
        }
        LearnerCourse::where('learner', $me)->where('course', $course)->delete();
        return response([
            'status' => 201,
            'message' => 'Course Dropped',
            'payload' => $course
        ], 200);
        
    }
    public function enroll_course($course)
    {
        if( strlen($course) == 0 )
        {
            return response([
                'status' => 201,
                'message' => 'You have not selected a valid course',
                'payload' => $course
            ], 403);
        }
        $me = Auth::user()->id;
        $input = [];
        $input['learner'] = Auth::user()->id;
        $input['course'] = $course;
        LearnerCourse::create($input);
        return response([
            'status' => 200,
            'message' => 'You have been Enrolled',
            'payload' => $course
        ], 200);
    }
    public function get_units()
    {
        return response([
            'status' => 200,
            'message' => 'Units',
            'payload' => [
                'all' => $this->all_my_units(),
            ]
        ], 200);
    }
    public function get_lessons($unit)
    {
        return response([
            'status' => 200,
            'message' => 'Lessons',
            'payload' => [
                'all' => $this->all_my_lessons($unit),
            ]
        ], 200);
    }
    public function get_exam($unit)
    {
        return response([
            'status' => 200,
            'message' => 'exam',
            'payload' => [
                'all' => $this->all_my_exams($unit),
            ]
        ], 200);
    }
    public function get_survey($unit)
    {
        return response([
            'status' => 200,
            'message' => 'surveys',
            'payload' => [
                'all' => $this->all_my_surveys($unit),
            ]
        ], 200);
    }
    public function get_exam_q($exam)
    {
        /** create exam attempt */
        $questions = $this->pull_exam_questions($exam);
        $_the_input = [];
        $_the_input['exam'] = $exam;
        $_the_input['student'] = Auth::user()->id;
        $_payload = [];
        $_payload['exam'] = $exam;
        $_payload['student'] = Auth::user()->id;
        try{
            if( is_array($questions) )
            {
                $index = 0;
                $loop = 0;
                foreach( $questions as $_question ):
                    $this->has_attempted($exam, $_question['id']);
                    $_the_input['question_id'] = $_question['id'];
                    $_the_input['q_index'] = $index;
                    $_the_input['correct'] = $this->extract_correct($_question['options']);
                    $_the_input['selected'] = null;
                    $_question['options'] = json_decode($_question['options'], true);
                    $_question['q_index'] = $index;
                    $_payload['questions'][$loop] = $_question;
                    $_payload['indices'][$loop] = [ "index" => $index, "value" => null];
                    ExamProgress::create($_the_input);
                    $index++;
                    $loop++;
                endforeach;
                return response([
                    'status' => 200,
                    'message' => 'questions',
                    'payload' => [
                        'all' => $_payload,
                    ]
                ], 200);
            }
            return response([
                'status' => 201,
                'message' => 'no questions found.',
                'payload' => []
            ], 403);
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            return response([
                'status' => 201,
                'message' => 'You have already attempted this exam',
                'payload' => []
            ], 403);
        }
        catch( \Exception $ex){ 
            return response([
                'status' => 201,
                'message' => $ex->getMessage(),
                'payload' => []
            ], 403);
        }
    }
    public function mark_exam(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'choice' => 'required|string',
            'index' => 'required',
            'exam' => 'required|string',
            'question' => 'required',
        ]);
        if( $validator->fails() ){
            return response([
                'status' => 201,
                'message' => "no selection made",
                'errors' => $validator->errors()
            ], 403);
        }
        $input = $req->all();
        $progress = ExamProgress::where('exam', $input['exam'])
            ->where('q_index', $input['index'])
            ->where('question_id', $input['question'])
            ->where('student', Auth::user()->id)
            ->first();
        if( !is_null($progress) ){
            $progress->selected = $input['choice'];
            if($progress->correct == $input['choice'])
            {
                $progress->maxscore = Question::find($input['question'])->maxscore;
            }
            else
            {
                $progress->maxscore = 0;
            }
            $progress->save();
            return response([
                'status' => 200,
                'message' => "no selection made",
                'payload' => $progress
            ], 200);
        }
        return response([
            'status' => 201,
            'message' => "progress not found for the payload",
            'payload' => $input
        ], 403);
    }
    public function mark_survey(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'choice' => 'required|string',
            'index' => 'required',
            'exam' => 'required|string',
            'question' => 'required',
        ]);
        if( $validator->fails() ){
            return response([
                'status' => 201,
                'message' => "no selection made",
                'errors' => $validator->errors()
            ], 403);
        }
        $input = $req->all();
        $progress = ExamProgress::where('exam', $input['exam'])
            ->where('q_index', $input['index'])
            ->where('question_id', $input['question'])
            ->where('student', Auth::user()->id)
            ->first();
        if( !is_null($progress) ){
            $progress->selected = $input['choice'];
            $progress->maxscore = 1;
            $progress->save();
            return response([
                'status' => 200,
                'message' => "done",
                'payload' => $progress
            ], 200);
        }
        return response([
            'status' => 201,
            'message' => "progress not found for the payload",
            'payload' => $input
        ], 403);
    }
    public function end_exam_finish(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'exam' => 'required',
        ]);
        if( $validator->fails() ){
            return response([
                'status' => 201,
                'message' => "invalid data",
                'errors' => $validator->errors()
            ], 403);
        }
        $exam = $req->get('exam');
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
        return response([
            'status' => 200,
            'message' => "submitted",
            'payload' => $score
        ], 200);
    }
    public function get_forums($unit)
    {
        return response([
            'status' => 200,
            'message' => 'forums',
            'payload' => [
                'all' => $this->all_my_forums($unit),
            ]
        ], 200);
    }
    public function get_forum_reply($id)
    {
        return response([
            'status' => 200,
            'message' => 'forum reply',
            'payload' => [
                'all' => $this->this_forum_reply($id),
            ]
        ], 200);
    }
    public function forum_post(Request $req )
    {
        $validator = Validator::make($req->all(), [
            'unit' => 'required',
            'forum' => 'required|string',
        ]);
        if( $validator->fails() ){
            return response([
                'status' => 201,
                'message' => "invalid when creating new post",
                'errors' => $validator->errors(),
            ], 403);
        }
        $input = $req->all();
        $input['uploadedby'] = Auth::user()->id;
        $input['course'] = Unit::find($input['unit'])->course;
        Forum::create($input);
        return response([
            'status' => 200,
            'message' => "Post created successfully!",
            'payload' => [],
        ], 200);
    }
    public function forum_reply(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'reply' => 'required|string',
            'forum' => 'required',
        ]);
        if( $validator->fails() ){
            return response([
                'status' => 201,
                'message' => "invalid data",
                'errors' => $validator->errors(),
            ], 403);
        }
        $input = $req->all();
        $input['uploadedby'] = Auth::user()->id;
        ForumReply::create($input);
        return response([
            'status' => 200,
            'message' => "reply sent!",
            'payload' => [],
        ], 200);
    }
    public function get_assign($unit)
    {
        return response([
            'status' => 200,
            'message' => 'assignments',
            'payload' => [
                'all' => $this->all_my_assign($unit),
            ]
        ], 200);
    }
    public function submit_assign(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'assignment' => 'required',
        ]);
        if( $validator->fails() ){
            return response([
                'status' => 201,
                'message' => "invalid data",
                'errors' => $validator->errors(),
            ], 403);
        }
        $input = $req->all();
        $file_uuid = (string) Str::uuid();
        if( !$req->hasFile('subfile') )
        {
            return response([
                'status' => 201,
                'message' => "File error. No file uploaded",
                'errors' => []
            ], 403);
        }
        if( !in_array($req->file('subfile')->getClientOriginalExtension(), ['doc', 'docx', 'pdf']))
        {
            return response([
                'status' => 201,
                'message' => "File error. Invalid file uploaded use MS office word or pdf",
                'errors' => []
            ], 403);
        }
        $content = $req->file('subfile');
        $content_name = $file_uuid . $content->getClientOriginalName();
        Storage::disk('local')->putFileAs('cls/trt/content', $content, $content_name);
        $input['submission_file'] = $content_name;
        $input['learner'] = Auth::user()->id;
        $input['markedby'] = 0;
        $input['score'] = 0.0;
        LearnerAssignment::create($input);
        return response([
            'status' => 200,
            'message' => "Success! Assignement Submitted. You can monitor scores under performance",
            'payload' => []
        ], 200);
    }
    public function get_perform($unit)
    {
        $unit_title = 'None Selected';
        $unit_t = Unit::find($unit);
        if(!is_null($unit_t))
        {
            $unit_title = $unit_t->name;
        }
        return response([
            'status' => 200,
            'message' => 'assignments',
            'payload' => [
                'a' => $this->my_assign_perf($unit),
                'b' => $this->my_exam_perf($unit),
                'c' => $unit_title
            ]
        ], 200);
    }
    protected function my_assign_perf($unit)
    {
        $idf = Assignment::select('id')
            ->where('unit', $unit)
            ->where('is_deleted', false)
            ->get();
        if(is_null($idf))
        {
            return [];
        }
        $idf = $idf->toArray();
        $c_ = LearnerAssignment::whereIn('assignment', $idf)
            ->where('learner', Auth::user()->id)
            ->get();
        if(is_null($c_))
        {
            return [];
        }
        $c_ = $c_->toArray();
        $rtn = [];
        foreach ( $c_ as $item ):
            $obj = Assignment::find($item['assignment']);
            $item['title'] = $obj->title;
            $item['maxscore'] = $obj->maxscore;
            array_push($rtn, $item);
        endforeach;

        return $rtn;
    }
    protected function my_exam_perf($unit)
    {
        $idf = Exam::select('id')
            ->whereIn('type', ['666666', '444444'])
            ->where('unit', $unit)
            ->where('is_deleted', false)
            ->get();
        if(is_null($idf))
        {
            return [];
        }
        $idf = $idf->toArray();
        $c_ = LearnerExam::whereIn('exam', $idf)
            ->where('learner', Auth::user()->id)
            ->get();
        if(is_null($c_))
        {
            return [];
        }
        $c_ = $c_->toArray();
        $rtn = [];
        foreach ( $c_ as $item ):
            $obj = Exam::find($item['exam']);
            $item['title'] = $obj->title;
            $item['maxscore'] = $obj->maxscore;
            array_push($rtn, $item);
        endforeach;

        return $rtn;
    }
    protected function this_forum_reply($id)
    {
        $me = Auth::user()->id;
        $c_ = ForumReply::where('forum', $id)
            ->where('is_deleted', false)
            ->orderBy('id', 'desc')
            ->get();
        if(is_null($c_))
        {
            return [];
        }
        $_data = $c_->toArray();
        $rtn = [];
        foreach( $_data as $item ):
            $item['user'] = User::find($item['uploadedby'])->name;
            array_push($rtn, $item);
        endforeach;

        return $rtn;
    }
    protected function all_my_forums($unit)
    {
        $me = Auth::user()->id;
        $c_ = null;
        if( $unit == 'all' )
        {
            $c_ = Forum::where('is_deleted', false)
                ->whereIn('course', $this->my_course_ids())
                ->orderBy('id', 'desc')
                ->get();
        }
        else 
        {
            $c_ = Forum::where('unit', $unit)
                ->where('is_deleted', false)
                ->whereIn('course', $this->my_course_ids())
                ->orderBy('id', 'desc')
                ->get();
        }
        if(is_null($c_))
        {
            return [];
        }
        $_data = $c_->toArray();
        $rtn = [];
        foreach( $_data as $item ):
            $item['reply'] = ForumReply::where('forum', $item['id'])
                ->count();
            $item['user'] = User::find($item['uploadedby'])->name;
            array_push($rtn, $item);
        endforeach;

        return $rtn;
    }
    protected function all_my_assign($unit)
    {
        $me = Auth::user()->id;
        $c_ = null;
        if( $unit == 'all' )
        {
            $c_ = Assignment::where('is_deleted', false)
                ->whereIn('course', $this->my_course_ids())
                ->whereNotIn('id', $this->my_done_assign())
                ->orderBy('id', 'desc')
                ->get();
        }
        else 
        {
            $c_ = Assignment::where('unit', $unit)
                ->where('is_deleted', false)
                ->whereIn('course', $this->my_course_ids())
                ->whereNotIn('id', $this->my_done_assign())
                ->orderBy('id', 'desc')
                ->get();
        }
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function has_attempted($exam, $q)
    {
        $count = ExamProgress::where('exam', $exam)->where('student', Auth::user()->id)->where('question_id', $q)->count();
        if( $count > 0 )
        {
            throw new \Exception("Invalid Access. You have already attempted this exam");
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
            throw new \Exception("You tried to access an exam that is not fully set. Please reach out to the administrator for further assistance");
        }
        return $q->toArray();
    }
    protected function all_my_exams($unit)
    {
        $me = Auth::user()->id;
        $c_ = null;
        if( $unit == 'all' )
        {
            $c_ = Exam::where('is_deleted', false)
                ->where('is_active', true)
                ->whereIn('course', $this->my_course_ids())
                ->whereIn('type', ['666666', '444444'])
                ->whereNotIn('id', $this->my_done_exams())
                ->orderBy('id', 'desc')
                ->get();
        }
        else 
        {
            $c_ = Exam::where('unit', $unit)
                ->where('is_deleted', false)
                    ->where('is_active', true)
                ->whereIn('type', ['666666', '444444'])
                ->whereNotIn('id', $this->my_done_exams())
                ->orderBy('id', 'desc')
                ->get();
        }
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function all_my_surveys($unit)
    {
        $me = Auth::user()->id;
        $c_ = null;
        if( $unit == 'all' )
        {
            $c_ = Exam::where('is_deleted', false)
                ->where('is_active', true)
                ->whereIn('course', $this->my_course_ids())
                ->where('type', '555555')
                ->whereNotIn('id', $this->my_done_exams())
                ->orderBy('id', 'desc')
                ->get();
        }
        else 
        {
            $c_ = Exam::where('unit', $unit)
                ->where('is_deleted', false)
                ->where('is_active', true)
                ->where('type', '555555')
                ->whereNotIn('id', $this->my_done_exams())
                ->orderBy('id', 'desc')
                ->get();
        }
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function my_course_ids()
    {
        $me = Auth::user()->id;
        $rtn = LearnerCourse::select('course')->where('learner', $me)->get();
        if(is_null($rtn))
        {
            return [];
        }
        return $rtn->toArray();
    }
    protected function my_done_assign()
    {
        $me = Auth::user()->id;
        $rtn = LearnerAssignment::select(['assignment'])->where('learner', $me)->get();
        if(is_null($rtn))
        {
            return [];
        }
        return $rtn->toArray();
    }
    protected function my_done_exams()
    {
        $me = Auth::user()->id;
        $rtn = LearnerExam::select(['exam'])->where('learner', $me)->get();
        if(is_null($rtn))
        {
            return [];
        }
        return $rtn->toArray();
    }
    protected function all_my_lessons($unit)
    {
        $me = Auth::user()->id;
        $c_ = null;
        if( $unit == 'all' )
        {
            $c_ = Lesson::where('is_deleted', false)
                ->orderBy('id', 'desc')
                ->get();
        }
        else 
        {
            $c_ = Lesson::where('unit', $unit)
                ->where('is_deleted', false)
                ->orderBy('id', 'desc')
                ->get();
        }
        if(is_null($c_))
        {
            return [];
        }
        return $c_->toArray();
    }
    protected function all_my_units()
    {
        $me = Auth::user()->id;
        $c_ = LearnerCourse::select('course')->where('learner', $me)->where('is_deleted', false)->get();
        if(is_null($c_))
        {
            return [];
        }
        $rtn = Unit::whereIn('course', $c_->toArray())->orderBy('id', 'desc')->get();
        if(is_null($rtn))
        {
            return [];
        }
        return $rtn->toArray();
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
    protected function createCode($length = 20, $t = 0) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if( $t > 0 ){
            $characters = '0123456789';
        }
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

