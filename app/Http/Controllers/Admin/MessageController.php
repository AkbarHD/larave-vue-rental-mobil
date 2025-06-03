<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MessageTemplate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageController extends Controller
{

    public function index()
    {
        $templates = [
            'data' => MessageTemplate::latest()->get()
        ];

        return Inertia::render('Admin/Messages/Index', [
            'templates' => $templates,
        ]);
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'type' => 'required|string|unique:message_templates',
            'content' => 'required|string',
        ]);
        MessageTemplate::create($validatedData);

        return redirect()->route('admin.messages.index');
    }


    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'content' => 'required|string',
        ]);

        $template = MessageTemplate::findOrFail($id);
        $template->update($validatedData);

        return redirect()->route('admin.messages.index');
    }


    public function destroy($id)
    {
        $template = MessageTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('admin.messages.index');
    }
}
