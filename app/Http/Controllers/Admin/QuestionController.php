<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request, Course $course)
    {
        $questions = $course->questions()
            ->when($request->search, function ($query) use ($request) {
                $query->where('title', 'like', "%{$request->search}%")
                    ->orWhere('answer', 'like', "%{$request->search}%");
            })
            ->paginate(10);

        return view('admin.questions.index', compact('questions', 'course'));
    }

    public function create(Course $course)
    {
        return view('admin.questions.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'answer' => 'nullable|string',
        ]);

        $course->questions()->create([
            'title' => $request->title,
            'answer' => $request->answer,
        ]);

        return redirect()->route('admin.courses.questions', $course)
            ->with('success', 'Question created successfully.');
    }

    public function edit(Course $course, Question $question)
    {
        return view('admin.questions.edit', compact('question', 'course'));
    }

    public function update(Request $request, Course $course, Question $question)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'answer' => 'nullable|string',
        ]);

        $question->update($request->all());

        return redirect()->route('admin.courses.questions', $course)
            ->with('success', 'Question updated successfully.');
    }

    public function destroy(Course $course, Question $question)
    {
        $question->delete();

        return redirect()->route('admin.courses.questions', $course)
            ->with('success', 'Question deleted successfully.');
    }
}
