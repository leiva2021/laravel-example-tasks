<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Models\Task;
use Illuminate\Http\Request;

/**
    * Show Task Dashboard
    */
Route::get('/', function () {
    Log::info("Get /");
    $startTime = microtime(true);
    // Simple cache-aside logic
    if (Cache::has('tasks')) {
        $data = Cache::get('tasks');
        return view('tasks', ['tasks' => $data, 'elapsed' => microtime(true) - $startTime]);
    } else {
        $data = Task::orderBy('created_at', 'asc')->get();
        Cache::add('tasks', $data);
        return view('tasks', ['tasks' => $data, 'elapsed' => microtime(true) - $startTime]);
    }
});

/**
    * Add New Task
    */
Route::post('/task', function (Request $request) {
    Log::info("Post /task");
    $validator = Validator::make($request->all(), [
        'name' => 'required|max:255',
    ]);

    if ($validator->fails()) {
        Log::error("Add task failed.");
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }

    $task = new Task;
    $task->name = $request->name;
    $task->save();
    // Clear the cache
    Cache::flush();

    return redirect('/');
});

Route::get('/editTask/{id}', function ($id) {
    Log::info('Get /editTask');
    $startTime = microtime(true);
    $task = Task::findOrFail($id);
    if(Cache::has('tasks')){
       $data = Cache::get('tasks');
    }
    return view('tasks', ['taskEdit' => $task, 'tasks' => $data, 'isEdit' => true, 'elapsed' => microtime(true) - $startTime]);
})->name('editTask');

Route::put('/updateTask/{task}', function(Request $request, Task $task) {
    Log::info('Update /updateTask/'.$task->id);
    $validator = Validator::make($request->all(), [
        'name' => 'required|max:255',
    ]);
    if ($validator->fails()) {
        Log::error("Update task failed.");
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }
    $task->update($request->all());
    // Clear the cache
    Cache::flush();
    return redirect('/');
})->name('updateTask');

/**
    * Delete Task
    */
Route::delete('/task/{id}', function ($id) {
    Log::info('Delete /task/'.$id);
    Task::findOrFail($id)->delete();
    // Clear the cache
    Cache::flush();

    return redirect('/');
});
