<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 現在ログインしているユーザーのuseridと同じtaskのみ取得する
        $tasks = Task::where('user_id', Auth::id())->get();
        // タスク一覧ビューでそれを表示
        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        // タスク作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
        ]);
        // タスクを作成
        $task = new Task;
        $task->user_id = Auth::id();
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();
        // タスク一覧画面へリダイレクトさせる
        return redirect()->route('tasks.index');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // ログインしているユーザーidとtaskのuseridが一致しない場合、トップページへリダイレクト
        if (!(Auth::id() === $task->user_id)) {
            return redirect()->route('tasks.index');
        }
        // タスク詳細ビュー表示
        return view('tasks.show', [
            'task' => $task,
        ]);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        // ログインしているユーザーidとtaskのuseridが一致しない場合、トップページへリダイレクト
        if (!(Auth::id() === $task->user_id)) {
            return redirect()->route('tasks.index');
        }
        // メッセージ編集ビューを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
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
         $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
        ]);
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // ログインしているユーザーidとtaskのuseridが一致しない場合、トップページへリダイレクト
        if (!(Auth::id() === $task->user_id)) {
            return redirect()->route('tasks.index');
        }
        // タスクを更新
        $task->user_id = Auth::id();
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();
        // タスク一覧画面へリダイレクト
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // ログインしているユーザーidとtaskのuseridが一致しない場合、トップページへリダイレクト
        if (!(Auth::id() === $task->user_id)) {
            return redirect()->route('tasks.index');
        }
        // タスクを削除
        $task->delete();
        // タスク一覧画面へリダイレクト
        // return redirect('/');
        // return redirect('tasks');
        return redirect()->route('tasks.index');
    }
}
