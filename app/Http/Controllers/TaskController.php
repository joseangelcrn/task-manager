<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Input\Input;

class TaskController extends Controller
{


    public function __construct()
    {
        $this->middleware('permission:task-index',['only'=>'index']);
        $this->middleware('permission:task-create',['only'=>'create']);
        $this->middleware('permission:task-show',['only'=>'show']);
        $this->middleware('permission:task-update',['only'=>'update']);
        $this->middleware('permission:task-delete',['only'=>'destroy']);
        $this->middleware('permission:task-assign',['only'=>'assignTask']);
        $this->middleware('permission:task-enable',['only'=>'enabledList']);
        $this->middleware('permission:task-disable',['only'=>'disableddList']);
        $this->middleware('permission:task-restore',['only'=>'restoreTask']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tasks = Task::all();
        return view('task.list-all',compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $users = User::all();
        return view('task.create',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $task = Task::create($request->all());
        $userIds = $request->user_id;

        $task->assignUser($userIds,true);

        if ($task != null) {
            return back()->with('success','Tarea creada correctamente');
        }
        else{
            return back()->with('error','Error al crear la tarea');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user = Auth::user();
        if ($user->hasRole('admin') or $task->users()->find($user->id) != null) {
            $task = Task::findOrFail($id);
            return view('task.show',compact('task'));
        }
       else{
           return view('forbidden');
       }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $task = Task::findOrFail($id);
        $users = User::all();
        return view('task.edit',compact('task','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $task = Task::findOrFail($id);
        $userIds = $request->user_id;

        $updated = $task->update($request->all());
        $task->assignUser($userIds,true);

       if ($updated) {
            return back()->with('success','Tarea actualizada correctamente');
        }
        else{
            return back()->with('error','Error al actualizar la tarea');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $task = Task::findOrFail($id);
        $deactivated = $task->deactivate();

        if ($deactivated) {
            return back()->with('success','Tarjeta borrada correctamente');
        }
        else{
            return back()->with('error','Error al borrar la tarjeta');
        }
    }

    /**
     * CUSTOM
     */

    public function enabledList()
    {
        $tasks = Task::where('active',true)->get();
        return view('task.list-enabled',compact('tasks'));
    }

    public function disabledList()
    {
        $tasks = Task::where('active',false)->get();
        return view('task.list-disabled',compact('tasks'));
    }

    public function assignTask(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $task = Task::findOrFail($request->task_id);
        $wasAssigned = $user->assignTask($task->id);

        if ($wasAssigned) {
            return redirect()->back()->with('success','Se le ha asignado correctamente la tarea al usuario');
        } else {
            return redirect()->back()->with('error','Ocurrio un error al asignar la tarea al usuario');
        }
    }

    public function restoreTask($id)
    {
        $task = Task::findOrFail($id);
        $activated  = $task->activate();

        if ($activated) {
            return back()->with('success','La tarea se ha vuelto a activar correctamente.');
        } else {
            return back()->with('error','Error al volver ha activar la tarea.');
        }

    }
}