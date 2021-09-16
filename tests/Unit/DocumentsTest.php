<?php

namespace Tests\Unit;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Throwable;

class DocumentsTest extends TestCase
{
    /**
     * @test
     * @throws Throwable
     */
    public function valid_user_post_file()
    {
        $uploadFile = new UploadedFile(
            base_path() . '/storage/app/public/tests/teste.pdf',
            'teste.pdf',
            'pdf',
            null,
            true
        );

        $this->assertArrayHasKey('data',
            $this->call(
                'POST',
                '/api/v1.0/documents',
                [],
                [],
                ['file' => $uploadFile],
                ['PHP_AUTH_USER' => 'renato.dambros@gmail.com', 'PHP_AUTH_PW' => 123],
                ['Accept' => 'application/json']
            )->decodeResponseJson()
        );
    }


    /**
     * @test
     * @throws Throwable
     */
    public function valid_user_retun_data()
    {
        $this->assertArrayHasKey('data',
            $this->call(
                'GET',
                '/api/v1.0/documents',
                [],
                [],
                [],
                ['PHP_AUTH_USER' => 'renato.dambros@gmail.com', 'PHP_AUTH_PW' => 123],
                []
            )->decodeResponseJson()
        );
    }
}
