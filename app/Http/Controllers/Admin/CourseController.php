<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::latest()
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            })
            ->paginate(10);

        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'video_url' => 'nullable|string|max:255',
        ]);

        $imagePath = $request->file('image')->store('public/courses');

        Course::create([
            ...$request->except('image'),
            'cover' => $imagePath,
        ]);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'video_url' => 'nullable|string|max:255',
        ]);

        $imagePath = $course->image;

        if ($request->hasFile('image')) {
            if (Storage::exists($course->image)) {
                Storage::delete($course->image);
            }

            $imagePath = $request->file('image')->store('public/courses');
        }

        $course->update([
            ...$request->except('image'),
            'cover' => $imagePath,
        ]);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        if (Storage::exists($course->image)) {
            Storage::delete($course->image);
        }

        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    public function modules(Request $request, Course $course)
    {
        $modules = Module::query()
            ->when($request->last_id, function ($query) use ($request) {
                $query->where('id', '>', $request->last_id);
            })
            ->limit(10)
            ->get();

        $course->load('modules');
        $modulesOfCourse = $course->modules->pluck('id');

        return view('admin.courses.manage', compact('course', 'modules', 'modulesOfCourse'));
    }

    public function createAndAttach(Request $request, Course $course)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_hours' => 'required|numeric',
        ]);

        $course->load('modules');
        $module = Module::create($request->all());
        $course->modules()->attach($module->id, ['order' => $course->modules->count() + 1]);

        return redirect()->route('admin.courses.modules', $course)
            ->with('success', 'Module created and attached successfully.');
    }

    public function attachModule(Request $request, Course $course)
    {
        $course->load('modules');
        $modules = collect(explode(',', $request->modules));

        $lastOrder = $course->modules->max('pivot.order') ?? 0;
        $course->modules()->attach($modules->mapWithKeys(function ($module, $index) use ($lastOrder) {
            return [$module => ['order' => $lastOrder + $index + 1]];
        })->toArray());

        return redirect()->route('admin.courses.modules', $course)
            ->with('success', 'Module attached successfully.');
    }

    public function detachModule(Course $course, Module $module)
    {
        $course->load('modules');
        $course->modules()->detach($module->id);

        return redirect()->route('admin.courses.modules', $course)
            ->with('success', 'Module detached successfully.');
    }
}
