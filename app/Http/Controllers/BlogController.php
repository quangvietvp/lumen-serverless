<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Blog;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Concerns\InteractsWithInput;
use Kreait\Firebase\Contract\Firestore as FirebaseStore;

class BlogController extends Controller
{
    use InteractsWithInput;
    protected $database;

    public function __construct(FirebaseStore $firebaseStore)
    {
        $this->middleware('fireauth');
        $this->database = $firebaseStore;
    }

    /**
     * @return JsonResponse
     */
    public function all()
    {
        return response()->json(Blog::all());
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function detail($id)
    {
        return response()->json(Blog::find($id));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        // Insert to firestore
        $rs = $this->database->database()->collection('blogs')->add([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ]);

        // Insert to database
        $name = $rs->name();
        $request->merge(['document_id' => substr($name, strrpos($name, '/') + 1)]);
        $blog = Blog::create($request->all());
        return response()->json($blog, Response::HTTP_CREATED);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request)
    {
        // Finding in database
        $blog = Blog::findOrFail($id);

        // Update firestore
        $this->database->database()->collection('blogs')->document($blog->document_id)->update(
            [
                ['path' => 'content', 'value' => $request->input('content')],
                ['path' => 'title', 'value' => $request->input('title')],
            ]
        );

        // Update on database
        $blog->update($request->all());
        return response()->json($blog, Response::HTTP_OK);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function delete($id)
    {
        $blog = Blog::findOrFail($id);
        // Delete firestore
        $this->database->database()->collection('blogs')->document($blog->document_id)->delete();

        // Delete on database
        Blog::findOrFail($id)->delete();
        return response('Deleted Successfully', Response::HTTP_OK);
    }
}
