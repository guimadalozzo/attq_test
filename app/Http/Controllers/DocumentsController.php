<?php

namespace App\Http\Controllers;

use App\Exceptions\InputValidationException;
use App\Http\Responses\DocumensCollectionResponse;
use App\Models\Document;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Throwable;

class DocumentsController extends Controller
{
    /**
     * @var Document
     */
    private $model;


    /**
     * DocumentsController constructor.
     * @param Document $model
     */
    public function __construct(Document $model)
    {
        $this->model = $model;
    }


    /**
     * @param Request $request
     * @return DocumensCollectionResponse
     */
    public function index(Request $request)
    {
        /** @var LengthAwarePaginator $paginator */
        $paginator = $this->model->query()
            ->where('user_id',  Auth::id())
            ->orderBy('id')
            ->paginate($request->input('limit'))
            ->appends($request->query());

        return new DocumensCollectionResponse($paginator->getCollection(), $paginator);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws InputValidationException|Exception
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['file.*' => 'required|max:8192|mimes:pdf', 'file' => 'nullable']);
        if ($validator->fails()) {
            throw new InputValidationException($validator->errors()->getMessages());
        }

        if (!$request->file('file')) {
            throw new InputValidationException(['no file send to endpoint']);
        }

        // standardize content for insertion
        $content = $request->file('file');
        is_array($content) ? $files = $content : $files = [$content];

        $filesWithError = [];
        foreach ($files as $file) {
            try {
                $fileName = md5(mt_rand()) . '.' . $file->clientExtension();
                if (Storage::disk('PDF_DOC')->put($fileName, $file->getContent())) {
                    $this->model->query()->create([
                        'user_id'   => Auth::id(),
                        'title'     => $file->getClientOriginalName(),
                        'file_name' => $fileName
                    ]);
                } else {
                    throw new Exception('Faill to save file in database');
                }
            } catch (Throwable $t) {
                $filesWithError[] = $file->getClientOriginalName();
            }
        }

        if ($filesWithError) {
            $names = implode(',', array_values($filesWithError));
            throw new InputValidationException(["fails to save images {$names}"]);
        }

        return response()->json(['data' => 'success'], Response::HTTP_OK);
    }


    /**
     *
     */
    public function show()
    {
        //TODO: implement method
    }


    /**
     *
     */
    public function update()
    {
        //TODO: implement method
    }


    /**
     *
     */
    public function destroy()
    {
        //TODO: implement method
    }
}
