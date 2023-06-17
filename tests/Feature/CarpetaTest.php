<?php

namespace Tests\Feature;

use App\Helpers\Email;
use App\Models\Post;
use App\Helpers\functions;
use Illuminate\Auth\Events\Validated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use PHPUnit\Framework\TestCase;


//clases propias de laravel, permiten la autocarga

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CarpetaTest extends TestCase
{
    // public function test_Carpeta()
    // {

    //     $var = true;
    //     $this->assertTrue($var);

    //     $this->assertTrue(true);
    //     $this->assertTrue(true);
    // }

    // //Test de Email
    // public function test_Email()
    // {

    //     $result = Email::validate('i@admin.com');
    //     $this->assertTrue($result);

    //     $result = Email::validate('i@@admin.com');
    //     $this->assertFalse($result);

    //     $result = validate_email('i@admin.com');
    //     $this->assertTrue($result);

    // }

    // //Test de Cambio de mayuscula a minuscula
    // public function test_set_name_in_lowercase()
    // {

    //     $post = new Post;
    //     $post->name = "Proyecto en PHP";

    //     $this->assertEquals("proyecto en php", $post->name);

    // }

    // //Test de intercambio de espacio por guion
    // public function test_get_slug()
    // {

    //     $post = new Post;
    //     $post->name = "Proyecto en PHP";

    //     $this->assertEquals("proyecto-en-php", $post->slug);

    // }

    // //Test verificar enlace, comparar
    // public function test_get_href()
    // {
    //     $post = new Post; 
    //     $post->name = "Proyecto en PHP";

    //     $this->assertEquals("blog/proyecto-en-php",$post->href());
    // }

    //carga de archivo
    public function test_upload()
    {
        Storage::fake('local');

        $response = $this->post("profile", [
            "photo" => $photo = UploadedFile::fake()->image("photo.png")
        ]);

        Storage::disk('local')->assertExists("profiles/{$photo->hashName()}");

        $response->assertRedirect('profile');
    }
}
