<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorsController extends Controller {
    /**
     * Get authors or a specific author by ID.
     *
     * @param int|null $authorId
     * @return JsonResponse
     */
    public function getAuthors($authorId = null): JsonResponse {
        if ($authorId !== null && !Author::where('id', $authorId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Author not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => Author::getAuthors($authorId)
        ]);
    }

    /**
     * Get books by author ID.
     *
     * @param int $authorId
     * @return JsonResponse
     */
    public function getAuthorBooks($authorId): JsonResponse {
        $author = Author::find($authorId);
        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Author not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $author->books->toArray()
        ]);
    }

    /**
     * Store a new author.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'active' => 'required|boolean'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()->first()
            ], 400);
        }
        $author = Author::post($validator->validated());
        return $author ? response()->json([
            'success' => true,
            'message' => 'Author created successfully',
            'data' => $author
        ]) : response()->json([
            'success' => false,
            'message' => 'Failed to create author'
        ], 500);
    }

    public function update(Request $request, $authorId): JsonResponse {
        if (!Author::where('id', $authorId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Author not found'
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'active' => 'sometimes|boolean'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()->first()
            ], 400);
        }
        $author = Author::post(array_merge($validator->validated(), ['id' => $authorId]));
        return $author ? response()->json([
            'success' => true,
            'message' => 'Author updated successfully',
            'data' => $author
        ]) : response()->json([
            'success' => false,
            'message' => 'Failed to update author'
        ], 500);
        
    }

    public function destroy($authorId): JsonResponse {
        $author = Author::find($authorId);
        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Author not found'
            ], 404);
        }

        if ($author->books()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete author with associated books'
            ], 409);
        }

        if ($author->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Author deleted successfully'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete author'
        ], 500);
        
    }
}
