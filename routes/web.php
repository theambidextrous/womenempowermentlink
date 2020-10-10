<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'WelcomeController@welcome')->name('welcome');

Auth::routes();

/** admin */
Route::get('/wel/admin/home', 'AdminController@index')->name('a_home');
/** courses */
Route::get('/wel/admin/home/course/home/{id}', 'AdminController@a_coursehome')->name('a_coursehome');
Route::post('/wel/admin/home/courses/new', 'AdminController@a_addcourse')->name('a_addcourse');
Route::put('/wel/admin/home/courses/edit/{id}', 'AdminController@a_editcourse')->name('a_editcourse');
Route::put('/wel/admin/home/courses/delete/{id}', 'AdminController@a_delcourse')->name('a_delcourse');
/** course units */
Route::post('/wel/admin/home/courses/unit', 'AdminController@a_addunit')->name('a_addunit');
Route::get('/wel/admin/home/courses/unit/{id}', 'AdminController@a_unithome')->name('a_unithome');
Route::put('/wel/admin/home/courses/unit/edit/{id}', 'AdminController@a_editunit')->name('a_editunit');
Route::put('/wel/admin/home/courses/unit/delete/{id}', 'AdminController@a_delunit')->name('a_delunit');
/** unit assignments, exams, quizes, survey */
Route::get('/wel/admin/home/courses/unit/assign/{id}', 'AdminController@a_uassign')->name('a_uassign');
Route::post('/wel/admin/home/courses/unit/add/assign', 'AdminController@a_addassign')->name('a_addassign');
Route::get('/wel/admin/home/courses/unit/del/assign/{id}', 'AdminController@a_delassign')->name('a_delassign');
Route::get('/wel/admin/home/courses/unit/exam/{id}', 'AdminController@a_uexams')->name('a_uexams');
Route::post('/wel/admin/home/courses/unit/add/ex', 'AdminController@a_addexam')->name('a_addexam');
Route::get('/wel/admin/home/courses/unit/del/exam/{id}', 'AdminController@a_delexam')->name('a_delexam');
Route::get('/wel/admin/home/courses/unit/{unit}/exam/{exam}','AdminController@a_uexam_qhome')->name('a_uexam_qhome');
Route::get('/wel/admin/home/courses/unit/exam/q/del/{id}', 'AdminController@a_delq')->name('a_delq');
Route::post('/wel/admin/home/courses/unit/ex/add/q', 'AdminController@a_addexam_q')->name('a_addexam_q');
Route::post('/wel/admin/home/courses/unit/ex/activate', 'AdminController@a_act_exam')->name('a_act_exam');
/** reports */
Route::get('/wel/admin/home/reports/assignment', 'ReportController@assign_rpt')->name('assign_rpt');
Route::get('/wel/admin/home/reports/exam', 'ReportController@exam_rpt')->name('exam_rpt');
Route::get('/wel/admin/home/reports/survey', 'ReportController@survey_rpt')->name('survey_rpt');
/** forums */
Route::get('/wel/admin/home/reports/forums', 'ReportController@forum_rpt')->name('forum_rpt');
Route::post('/wel/admin/home/reports/forums', 'ReportController@a_add_forum')->name('a_add_forum');
Route::post('/wel/admin/home/reports/forum/reply', 'ReportController@a_add_freply')->name('a_add_freply');
/** unit lessons */
Route::post('/wel/admin/home/courses/unit/lesson', 'AdminController@a_addlesson')->name('a_addlesson');
Route::post('/wel/admin/home/courses/unit/lesson/live','AdminController@a_addlesson_live')->name('a_addlesson_live');
Route::get('/wel/admin/home/courses/unit/lesson/{id}', 'AdminController@a_lessonhome')->name('a_lessonhome');
Route::put('/wel/admin/home/courses/unit/lesson/edit/{id}', 'AdminController@a_editlesson')->name('a_editlesson');
Route::put('/wel/admin/home/courses/unit/lesson/delete/{id}', 'AdminController@a_dellesson')->name('a_dellesson');
/** Admins */
Route::get('/wel/admin/home/admins', 'AdminController@a_admins')->name('a_admins');
Route::post('/wel/admin/home/admins', 'AdminController@a_add_admin')->name('a_add_admin');
/** tutors */
Route::get('/wel/admin/home/tutors', 'AdminController@a_tutors')->name('a_tutors');
Route::get('/wel/admin/home/tutors/{id}', 'AdminController@a_tutorhome')->name('a_tutorhome');
Route::post('/wel/admin/home/tutors', 'AdminController@a_add_tutor')->name('a_add_tutor');
Route::put('/wel/admin/home/tutors/{id}', 'AdminController@a_edittutor')->name('a_edittutor');
Route::put('/wel/admin/home/tutors/delete/{id}', 'AdminController@a_deltutor')->name('a_deltutor');
Route::get('/wel/admin/home/tutors/drop/unit/{id}', 'AdminController@a_drop_unit')->name('a_drop_unit');
Route::post('/wel/admin/home/tutors/assign/unit', 'AdminController@a_assign_unit')->name('a_assign_unit');
/** learners */
Route::get('/wel/admin/home/learners', 'AdminController@a_learners')->name('a_learners');
Route::get('/wel/admin/home/learners/{id}', 'AdminController@a_learnerhome')->name('a_learnerhome');
Route::post('/wel/admin/home/learners', 'AdminController@a_add_learner')->name('a_add_learner');
Route::put('/wel/admin/home/learners/{id}', 'AdminController@a_editlearner')->name('a_editlearner');
Route::put('/wel/admin/home/learners/delete/{id}', 'AdminController@a_dellearner')->name('a_dellearner');
Route::get('/wel/admin/home/learners/drop/course/{id}', 'AdminController@a_drop_course')->name('a_drop_course');
Route::post('/wel/admin/home/learners/enroll','AdminController@a_enroll_course')->name('a_enroll_course');
/** learner grades */
Route::get('/wel/admin/home/learner/grade/{id}', 'AdminController@a_learnergrade')->name('a_learnergrade');
Route::put('/wel/admin/home/learner/grade/unit', 'AdminController@a_gradeunit')->name('a_gradeunit');

/** learner perf */
Route::get('/wel/admin/home/learner/perf/{id}', 'AdminController@a_learnerperf')->name('a_learnerperf');
/** learner courses */
Route::get('/wel/admin/home/learner/coz/{id}', 'AdminController@a_learnercoz')->name('a_learnercoz');
/** stream */
Route::get('/wel/admin/home/courses/unit/lesson/files/{file}', 'AdminController@stream')->name('file_stream');


/** Teachers */
Route::get('/wel/tutor/home', 'TutorController@index')->name('t_home');
Route::get('/wel/tutor/home/profile', 'TutorController@t_profile')->name('t_profile');
Route::post('/wel/tutor/home/profile/update', 'TutorController@t_profile_update')->name('t_profile_update');
Route::post('/wel/tutor/home/pwd/change', 'TutorController@t_pwd_change')->name('t_pwd_change');
//lesson
Route::get('/wel/tutor/home/lessons/{unit}', 'TutorController@t_lessonhome')->name('t_lessonhome');
Route::post('/wel/tutor/home/lesson/new', 'TutorController@t_addlesson')->name('t_addlesson');
Route::post('/wel/tutor/home/llesson/new','TutorController@t_addlesson_live')->name('t_addlesson_live');
Route::get('/wel/tutor/home/lesson/drop/{id}','TutorController@t_droplesson')->name('t_droplesson');
//assign
Route::get('/wel/tutor/home/assign/{unit}', 'TutorController@t_assignhome')->name('t_assignhome');
Route::post('/wel/tutor/home/assign/new', 'TutorController@t_addassign')->name('t_addassign');
Route::get('/wel/tutor/home/assign/drop/{id}','TutorController@t_dropassign')->name('t_dropassign');
Route::get('/wel/tutor/home/assign/sub/{hash}','TutorController@t_assignsub')->name('t_assignsub');
Route::post('/wel/tutor/home/assign/grade','TutorController@t_assigngrade')->name('t_assigngrade');
//exam
Route::get('/wel/tutor/home/exam/{unit}', 'TutorController@t_examhome')->name('t_examhome');
Route::post('/wel/tutor/home/exam/new', 'TutorController@t_addexam')->name('t_addexam');
Route::get('/wel/tutor/home/exam/preview/{hash}', 'TutorController@t_expreview')->name('t_expreview');
Route::post('/wel/tutor/home/exam/preview/activate', 'TutorController@t_act_exam')->name('t_act_exam');
Route::get('/wel/tutor/home/exam/question/del/{id}', 'TutorController@t_delq')->name('t_delq');
Route::get('/wel/tutor/home/exam/del/{id}', 'TutorController@t_delexam')->name('t_delexam');
//grading
Route::get('/wel/tutor/home/grade/{unit}', 'TutorController@t_gradehome')->name('t_gradehome');
Route::put('/wel/tutor/home/grade/unit', 'TutorController@t_gradeunit')->name('t_gradeunit');
/** Learners */
Route::get('/wel/learner/home', 'LearnerController@index')->name('s_home');
Route::get('/wel/learner/home/profile', 'LearnerController@s_profile')->name('s_profile');
Route::post('/wel/learner/home/profile/update', 'LearnerController@s_profile_update')->name('s_profile_update');
Route::post('/wel/learner/home/pwd/change', 'LearnerController@s_pwd_change')->name('s_pwd_change');
Route::post('/wel/learner/home/enroll/course', 'LearnerController@s_enrollcourse')->name('s_enrollcourse');
// lessons
Route::get('/wel/learner/home/lessons/{unit}', 'LearnerController@s_lessonhome')->name('s_lessonhome');
//assign
Route::get('/wel/learner/home/assign/{unit}', 'LearnerController@s_assignhome')->name('s_assignhome');
Route::put('/wel/learner/home/assign/upload', 'LearnerController@s_assignsubmit')->name('s_assignsubmit');
//exams
Route::get('/wel/learner/home/examinables/{unit}', 'LearnerController@s_examhome')->name('s_examhome');
Route::get('/wel/learner/home/exam/{exam}/unit/{unit}', 'LearnerController@s_examattempt')->name('s_examattempt');
