<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;

class ModuleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_hours' => 'required|numeric',
        ]);

        Module::create($request->all());

        return redirect()->back()->with('success', 'Module created successfully.');
    }

    public function edit($id)
    {
        $module = Module::findOrFail($id);
        return view('admin.modules.edit', compact('module'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_hours' => 'required|numeric',
        ]);

        $module = Module::findOrFail($id);
        $module->update($request->all());

        return redirect()->back()->with('success', 'Module updated successfully.');
    }

    public function destroy($id)
    {
        $module = Module::findOrFail($id);
        $module->delete();

        return redirect()->route('admin.modules.index')->with('success', 'Module deleted successfully.');
    }
}
