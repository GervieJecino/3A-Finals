<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // Implement filtering, sorting, searching, limit, offset, and fields selection
        $query = Student::query();

        // Example: Filter by year
        if ($request->has('year')) {
            $query->where('year', $request->year);
        }

        // Apply other filters as needed

        // Handle sorting
        if ($request->has('sort_by')) {
            $sortDirection = $request->has('sort_desc') ? 'desc' : 'asc';
            $query->orderBy($request->sort_by, $sortDirection);
        }

        // Handle pagination
        $limit = $request->input('limit', 10);
        $students = $query->paginate($limit);

        return response()->json([
            'metadata' => [
                'count' => $students->total(),
                'search' => $request->input('search', null),
                'limit' => $limit,
                'offset' => $students->firstItem() - 1,
                'fields' => $request->input('fields', []),
            ],
            'students' => $students,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'birthdate' => 'required|date_format:Y-m-d',
            'sex' => 'required|in:MALE,FEMALE',
            'address' => 'required|string',
            'year' => 'required|integer',
            'course' => 'required|string',
            'section' => 'required|string',
        ]);

        $student = Student::create($validatedData);

        return response()->json($student, 201);
    }

    public function show($id)
    {
        $student = Student::findOrFail($id);

        return response()->json($student);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validatedData = $request->validate([
            'firstname' => 'string',
            'lastname' => 'string',
            'birthdate' => 'date_format:Y-m-d',
            'sex' => 'in:MALE,FEMALE',
            'address' => 'string',
            'year' => 'integer',
            'course' => 'string',
            'section' => 'string',
        ]);

        $student->update($validatedData);

        return response()->json($student);
    }
}
