<?php

namespace App\Http\Controllers;

use App\Models\GlobalMessage;
use Illuminate\Http\Request;

class GlobalMessageController extends Controller
{
    public function index()
    {
        $messages = GlobalMessage::all();
        return view('global_messages.index', compact('messages'));
    }

    public function create()
    {
        return view('global_messages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
        ]);

        GlobalMessage::create($request->all());

        return redirect()->route('global_messages.index')->with('success', 'Mensagem criada com sucesso!');
    }

    public function show($id)
    {
        $message = GlobalMessage::findOrFail($id);
        return view('global_messages.show', compact('message'));
    }

    public function edit($id)
    {
        $message = GlobalMessage::findOrFail($id);
        return view('global_messages.edit', compact('message'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
        ]);

        $message = GlobalMessage::findOrFail($id);
        $message->update($request->all());

        return redirect()->route('global_messages.index')->with('success', 'Mensagem atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $message = GlobalMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('global_messages.index')->with('success', 'Mensagem excluída com sucesso!');
    }
}

