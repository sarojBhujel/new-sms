<?php

use App\Http\Controllers\Classrooms\ClassroomController;
use App\Http\Controllers\FeeNamesController;
use App\Http\Controllers\Grades\GradeController;
use App\Http\Controllers\Students\StudentController;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', 'App\Http\Controllers\HomeController@index')->name('selection');


Route::group(['namespace' => 'App\Http\Controllers\Auth'], function () {

    Route::get('/login', function () {
        return redirect()->route('login.show', ['type' => 'student']);
    })->middleware('guest')->name('login');

    Route::get('/login/{type}', 'LoginController@loginForm')->middleware('guest')->name('login.show');

    Route::post('/login', 'LoginController@login')->name('login.submit');

    Route::get('/logout/{type}', 'LoginController@logout')->name('logout');
});
 
//==============================Translate all pages============================
Route::group(
    [
        'middleware' => ['auth']
    ],
    function () {
        //==============================dashboard============================
        //Route::get('/', 'App\Http\Controllers\HomeController@index')->name('dashboard');
        Route::get('/dashboard', 'App\Http\Controllers\HomeController@dashboard')->name('dashboard');

        //==============================Grades============================
        Route::group(['namespace' => 'App\Http\Controllers\Grades'], function () {
            Route::resource('Grades', GradeController::class);
            Route::get('grades/edit/{id}', 'GradeController@edit')->name('grades.edit.ajax');
            Route::patch('grades/{id}', 'GradeController@update')->name('grades.update.ajax');
            Route::delete('grades/{id}', 'GradeController@destroy')->name('grades.destroy.ajax');
        });

        //==============================Fiscal Years============================
        Route::group(['namespace' => 'App\Http\Controllers\FiscalYears'], function () {
            Route::resource('fiscal-years', 'FiscalYearController');
            Route::post('fiscal-years/{id}/activate', 'FiscalYearController@activate')->name('fiscal-years.activate');
        });

        //==============================Classrooms============================
        Route::group(['namespace' => 'App\Http\Controllers\Classrooms'], function () {
            Route::resource('Classrooms', 'ClassroomController');

            Route::post('delete_all', 'ClassroomController@delete_all')->name('delete_all');

            Route::post('Filter_Classes', 'ClassroomController@Filter_Classes')->name('Filter_Classes');
        });

        //==============================Sections============================
        Route::group(['namespace' => 'App\Http\Controllers\Sections'], function () {

            Route::resource('Sections', 'SectionController');

            Route::get('/classes/{id}', 'SectionController@getclasses');
        });


        //==============================Parents============================
        Route::view('add_parent', 'livewire.show_Form')->name('add_parent');

        Route::group(['namespace' => 'App\Http\Controllers\Parents'], function () {
            Route::get('parents/import', 'ParentController@importForm')->name('parents.import.form');
            Route::post('parents/import', 'ParentController@import')->name('parents.import');
            Route::get('parents/import-template', 'ParentController@downloadTemplate')->name('parents.import.template');
            Route::post('parents/quick-create', 'ParentController@storeQuick')->name('parents.quick-create');
        });

        //==============================Teachers============================
        Route::group(['namespace' => 'App\Http\Controllers\Teachers'], function () {

            Route::resource('Teachers', 'TeacherController');
        });

        //==============================Specializations============================
        Route::group(['namespace' => 'App\Http\Controllers'], function () {
            Route::resource('specializations', 'SpecializationController');
            Route::get('specializations/{id}/edit', 'SpecializationController@edit')->name('specializations.edit.ajax');
            Route::patch('specializations/{id}', 'SpecializationController@update')->name('specializations.update.ajax');
            Route::delete('specializations/{id}', 'SpecializationController@destroy')->name('specializations.destroy.ajax');
        });

        Route::get('fee-names/classes', [FeeNamesController::class, 'classes'])->name('fee-names.classes');
        Route::get('nepali-months', function () { return \App\Models\NepaliMonth::orderBy('sequence')->get(); })->name('nepali-months.index');
        Route::resource('fee-names',FeeNamesController::class);
        //==============================Students============================
        Route::group(['namespace' => 'App\Http\Controllers\Students'], function () {

            Route::resource('Students', StudentController::class);
            Route::get('Students/{id}/id-card', 'StudentController@idCard')->name('Students.id-card');
            Route::get('students/{id}/id-card', 'StudentController@idCard');
            Route::get('students-import-template', 'StudentController@downloadImportTemplate')->name('Students.import.template');
            Route::post('Students/import', 'StudentController@import')->name('Students.import');
            Route::get('indirect_admin', 'OnlineClasseController@indirectCreate')->name('indirect.create');
            Route::post('indirect_admin', 'OnlineClasseController@storeIndirect')->name('indirect.store');
            Route::resource('online_classes', 'OnlineClasseController');
            Route::resource('Promotion', 'PromotionController');
            Route::resource('Graduated', 'GraduatedController');
            Route::resource('Fees', 'FeesController');
            Route::resource('Fees_Invoices', 'FeesInvoicesController');
            Route::resource('receipt_students', 'ReceiptStudentsController');
            Route::resource('ProcessingFee', 'ProcessingFeeController');
            Route::resource('Payment_students', 'PaymentController');
            Route::resource('Attendance', 'AttendanceController');

            Route::get('download_file/{filename}', 'LibraryController@downloadAttachment')->name('downloadAttachment');
            Route::resource('library', 'LibraryController');



            Route::post('Upload_attachment', 'StudentController@Upload_attachment')->name('Upload_attachment');
            Route::get('Download_attachment/{studentsname}/{filename}', 'StudentController@Download_attachment')->name('Download_attachment');
            Route::post('Delete_attachment', 'StudentController@Delete_attachment')->name('Delete_attachment');
        });

        //==============================Subjects============================
        Route::group(['namespace' => 'App\Http\Controllers\Subjects'], function () {
            Route::resource('subjects', 'SubjectController');
        });

        //==============================Faculties============================
        Route::group(['namespace' => 'App\Http\Controllers\Faculties'], function () {
            Route::resource('Faculties', 'FacultyController');
            Route::get('Faculties/edit/{id}', 'FacultyController@edit')->name('faculties.edit.ajax');
            Route::patch('Faculties/{id}', 'FacultyController@update')->name('faculties.update.ajax');
            Route::delete('Faculties/{id}', 'FacultyController@destroy')->name('faculties.destroy.ajax');
            Route::get('grades/{id}/faculties', 'FacultyController@byGrade')->name('grades.faculties');
        });

        //==============================Quizzes============================
        Route::group(['namespace' => 'App\Http\Controllers\Quizzes'], function () {
            Route::resource('Quizzes', 'QuizzController');
        });

        //==============================questions============================
        Route::group(['namespace' => 'App\Http\Controllers\Questions'], function () {
            Route::resource('Questions', 'QuestionController');
        });

        //==============================Setting============================
        Route::resource('settings', 'App\Http\Controllers\SettingController');
    }
);