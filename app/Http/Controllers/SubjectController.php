<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function index($studentId, Request $request)
    {
        // Initialize query builder
        $query = Subject::where('student_id', $studentId);

        // Apply filters
        if ($request->has('remarks')) {
            $query->where('remarks', $request->remarks);
        }

        // Apply sorting
        if ($request->has('sort')) {
            // Example sorting by average grade
            $sortField = $request->sort;
            $query->orderBy($sortField);
        }

        // Paginate results
        $subjects = $query->paginate(10); // Adjust as per your pagination needs

        return response()->json([
            'metadata' => [
                'count' => $subjects->total(),
                'search' => null,
                'limit' => $subjects->perPage(),
                'offset' => $subjects->currentPage(),
                'fields' => [],
            ],
            'subjects' => $subjects->items(),
        ]);
    }

    public function store(Request $request, $studentId)
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'subject_code' => 'required|string',
            'name' => 'required|string',
            'description' => 'required|string',
            'instructor' => 'required|string',
            'schedule' => 'required|string',
            'grades' => 'required|array',
            'grades.prelims' => 'required|numeric',
            'grades.midterms' => 'required|numeric',
            'grades.pre_finals' => 'required|numeric',
            'grades.finals' => 'required|numeric',
            'date_taken' => 'required|date_format:Y-m-d',
        ]);

        // Calculate average grade
        $averageGrade = ($validatedData['grades']['prelims'] + $validatedData['grades']['midterms'] + $validatedData['grades']['pre_finals'] + $validatedData['grades']['finals']) / 4;

        // Determine remarks based on average grade
        $remarks = $averageGrade >= 3.0 ? 'PASSED' : 'FAILED';

        // Create new subject
        $subject = Subject::create([
            'student_id' => $studentId,
            'subject_code' => $validatedData['subject_code'],
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'instructor' => $validatedData['instructor'],
            'schedule' => $validatedData['schedule'],
            'grades' => $validatedData['grades'],
            'average_grade' => $averageGrade,
            'remarks' => $remarks,
            'date_taken' => $validatedData['date_taken'],
        ]);

        return response()->json($subject, 201);
    }

    public function show($studentId, $subjectId)
    {
        // Retrieve a specific subject for a student
        $subject = Subject::where('student_id', $studentId)->findOrFail($subjectId);

        return response()->json($subject);
    }

    public function update(Request $request, $studentId, $subjectId)
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'subject_code' => 'string',
            'name' => 'string',
            'description' => 'string',
            'instructor' => 'string',
            'schedule' => 'string',
            'grades' => 'array',
            'grades.prelims' => 'numeric',
            'grades.midterms' => 'numeric',
            'grades.pre_finals' => 'numeric',
            'grades.finals' => 'numeric',
            'date_taken' => 'date_format:Y-m-d',
        ]);

        // Find the subject to update
        $subject = Subject::where('student_id', $studentId)->findOrFail($subjectId);

        // Update subject attributes
        $subject->fill($validatedData);

        // Recalculate average grade if grades are updated
        if (isset($validatedData['grades'])) {
            $averageGrade = ($validatedData['grades']['prelims'] + $validatedData['grades']['midterms'] + $validatedData['grades']['pre_finals'] + $validatedData['grades']['finals']) / 4;
            $subject->average_grade = $averageGrade;
            $subject->remarks = $averageGrade >= 3.0 ? 'PASSED' : 'FAILED';
        }

        // Save changes
        $subject->save();

        return response()->json($subject);
    }
}
