<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\TrainingTypeController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\SubtopicController;
use App\Http\Controllers\Admin\TrainingBatchController;
use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\TimeTableController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "admin" middleware group. Make something great!
|
*/

Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    // Master Management
    Route::prefix('master')->name('master.')->group(function () {
        // Companies
        Route::get('/companies', [CompanyController::class, 'index'])->name('companies');
        Route::get('/companies/add', [CompanyController::class, 'create'])->name('companies.create');
        Route::get('/companies/{id}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
        Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
        Route::put('/companies/{id}', [CompanyController::class, 'update'])->name('companies.update');
        Route::get('/companies/{id}', [CompanyController::class, 'show'])->name('companies.show');
        Route::put('/companies/{id}', [CompanyController::class, 'update'])->name('companies.update');
        Route::delete('/companies/{id}', [CompanyController::class, 'destroy'])->name('companies.destroy');
        Route::get('/api/companies', [CompanyController::class, 'getCompanies'])->name('companies.api');
        
        // Designations
        Route::get('/designations', [DesignationController::class, 'index'])->name('designations');
        Route::get('/designations/add', [DesignationController::class, 'create'])->name('designations.create');
        Route::get('/designations/{id}/edit', [DesignationController::class, 'edit'])->name('designations.edit');
        Route::post('/designations', [DesignationController::class, 'store'])->name('designations.store');
        Route::put('/designations/{id}', [DesignationController::class, 'update'])->name('designations.update');
        Route::get('/designations/{id}', [DesignationController::class, 'show'])->name('designations.show');
        Route::delete('/designations/{id}', [DesignationController::class, 'destroy'])->name('designations.destroy');
        Route::get('/api/designations', [DesignationController::class, 'getDesignations'])->name('designations.api');
        
        // Training Types
        Route::get('/training-types', [TrainingTypeController::class, 'index'])->name('training_types');
        Route::get('/training-types/add', [TrainingTypeController::class, 'create'])->name('training_types.create');
        Route::get('/training-types/{id}/edit', [TrainingTypeController::class, 'edit'])->name('training_types.edit');
        Route::post('/training-types', [TrainingTypeController::class, 'store'])->name('training_types.store');
        Route::put('/training-types/{id}', [TrainingTypeController::class, 'update'])->name('training_types.update');
        Route::get('/training-types/{id}', [TrainingTypeController::class, 'show'])->name('training_types.show');
        Route::delete('/training-types/{id}', [TrainingTypeController::class, 'destroy'])->name('training_types.destroy');
        Route::get('/api/training-types', [TrainingTypeController::class, 'getTrainingTypes'])->name('training_types.api');
        
        // Holidays
        Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays');
        Route::get('/holidays/add', [HolidayController::class, 'create'])->name('holidays.create');
        Route::get('/holidays/{id}/edit', [HolidayController::class, 'edit'])->name('holidays.edit');
        Route::post('/holidays', [HolidayController::class, 'store'])->name('holidays.store');
        Route::put('/holidays/{id}', [HolidayController::class, 'update'])->name('holidays.update');
        Route::get('/holidays/{id}', [HolidayController::class, 'show'])->name('holidays.show');
        Route::delete('/holidays/{id}', [HolidayController::class, 'destroy'])->name('holidays.destroy');
        Route::get('/api/holidays', [HolidayController::class, 'getHolidays'])->name('holidays.api');

        Route::get('venues',[VenueController::class, 'venues'])->name('venues');
        Route::post('venues/add',[VenueController::class, 'addVenue'])->name('venues.add');
        Route::post('venues/update',[VenueController::class, 'updateVenue'])->name('venues.update');
        Route::delete('venues/{id}',[VenueController::class, 'destroy'])->name('venues.destroy');

        

        
    
    });
    
    // Training Batch Management
    Route::prefix('training-batch')->name('training_batch.')->group(function () {
        Route::get('/', [TrainingBatchController::class, 'index'])->name('index');
        Route::get('/add', [TrainingBatchController::class, 'create'])->name('create');
        Route::get('/{id}/edit', [TrainingBatchController::class, 'edit'])->name('edit');
        Route::post('/', [TrainingBatchController::class, 'store'])->name('store');
        Route::put('/{id}', [TrainingBatchController::class, 'update'])->name('update');
        Route::get('/{id}', [TrainingBatchController::class, 'show'])->name('show');
        Route::delete('/{id}', [TrainingBatchController::class, 'destroy'])->name('destroy');
        Route::get('/api/batches', [TrainingBatchController::class, 'getTrainingBatches'])->name('api');
        Route::post('/api/batch-count', [TrainingBatchController::class, 'getBatchCount'])->name('batch.count');
    });

      Route::prefix('module')->name('module.')->group(function () {

        // Module Master
        Route::get('/module-master', [ModuleController::class, 'index'])->name('master');
        Route::get('/module-master/add', [ModuleController::class, 'create'])->name('master.create');
        Route::get('/module-master/{module_id}/edit', [ModuleController::class, 'edit'])->name('master.edit');

        Route::post('/module-master', [ModuleController::class, 'store'])->name('master.store');
        Route::put('/module-master/{module_id}', [ModuleController::class, 'update'])->name('master.update');
        Route::delete('/module-master/{module_id}', [ModuleController::class, 'destroy'])->name('master.destroy');
        Route::get('/module-master/api', [ModuleController::class, 'getModules'])->name('module.master.api');

        // API route for subtopics
        Route::get('/api/subtopics/{topicId}', function($topicId) {
            try {
                // Get subtopics from subtopics table
                $subtopics = DB::table('subtopics')
                    ->where('topic_id', $topicId)
                    ->where('status', 1) // Only active subtopics
                    ->orderBy('sort_order', 'asc')
                    ->get();
                
                return response()->json($subtopics);
            } catch (\Exception $e) {
                // Log error for debugging
                \Log::error('Subtopics API Error: ' . $e->getMessage());
                return response()->json(['error' => 'Failed to load subtopics'], 500);
            }
        });

        Route::get('/topics', [TopicController::class, 'index'])->name('topics');
        Route::get('/topics/add', [TopicController::class, 'create'])->name('topics.create');
        Route::get('/topics/{id}/edit', [TopicController::class, 'edit'])->name('topics.edit');
        Route::post('/topics', [TopicController::class, 'store'])->name('topics.store');
        Route::put('/topics/{id}', [TopicController::class, 'update'])->name('topics.update');
        Route::get('/topics/{id}', [TopicController::class, 'show'])->name('topics.show');
        Route::delete('/topics/{id}', [TopicController::class, 'destroy'])->name('topics.destroy');
        Route::get('/api/topics', [TopicController::class, 'getTopics'])->name('topics.api');
        
        // Subtopics
        Route::get('/subtopics', [SubtopicController::class, 'index'])->name('subtopics');
        Route::get('/subtopics/add', [SubtopicController::class, 'create'])->name('subtopics.create');
        Route::get('/subtopics/{id}/edit', [SubtopicController::class, 'edit'])->name('subtopics.edit');
        Route::post('/subtopics', [SubtopicController::class, 'store'])->name('subtopics.store');
        Route::put('/subtopics/{id}', [SubtopicController::class, 'update'])->name('subtopics.update');
        Route::get('/subtopics/{id}', [SubtopicController::class, 'show'])->name('subtopics.show');
        Route::delete('/subtopics/{id}', [SubtopicController::class, 'destroy'])->name('subtopics.destroy');
        Route::get('/api/subtopics', [SubtopicController::class, 'getSubtopics'])->name('subtopics.api');
         });
             Route::prefix('timetable-management')->name('timetable-management.')->group(function () {
               Route::get('/',[TimeTableController::class, 'index'])->name('index');
               Route::get('calender/batch',[TimeTableController::class, 'addNewBatch'])->name('calender.batch');
               Route::post('batch/store',[TimeTableController::class, 'storeBatch'])->name('batch.store');
               Route::get('weekly/training',[TimeTableController::class,'weeklyTraining'])->name('weekly.training');
    });



     
});
