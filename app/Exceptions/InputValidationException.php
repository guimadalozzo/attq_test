<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class InputValidationException extends Exception
{
    /**
     * @var int
     */
    protected $code = 400;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var array
     */
    protected $errors;

    /**
     * InputValidationException constructor.
     *
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
        $this->render();

        return parent::__construct();
    }

    /**
     * @return JsonResponse
     */
    public function render()
    {
        $errors = array_map(function ($attribute, $violation) {
            if (request()->server('HTTP_ACCEPT_LANGUAGE') === 'pt-br') {
                $this->title = 'O dado informado está incorreto.';
            } else {
                $this->title = 'Invalid input data.';
            }

            return [
                'code' => $this->code,
                'title' => $this->title,
                'source' => [
                    'pointer' => '/' . str_replace('.', '/', $attribute)
                ],
                'detail' => $violation
            ];
        }, array_keys($this->errors), $this->errors);

        return response()->json(['error' => $errors], Response::HTTP_BAD_REQUEST);
    }
}
