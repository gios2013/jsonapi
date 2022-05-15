<?php

namespace Tests\Feature\Articles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Article;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_articles()
    {

        $this->withoutExceptionHandling();

        $response = $this->postJson(route('api.v1.articles.store'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nuevo Articulo',
                    'slug' => 'nuevo-articulo',
                    'content' => 'Descripcion del Nuevo Articulo',
                ]
            ]
        ]);
        $response->assertCreated();

        $article = Article::first();

        $response->assertHeader(
            'Location',
            route('api.v1.articles.show', $article)
        );

        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => 'Nuevo Articulo',
                    'slug' => 'nuevo-articulo',
                    'content' => 'Descripcion del Nuevo Articulo',
                ],
                'links' => [
                    'self' => route('api.v1.articles.show', $article)
                ]
            ]
        ]);
    }

    /** @test */
    public function title_is_required()
    {

        $response = $this->postJson(route('api.v1.articles.store'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'edd',
                    'slug' => 'nuevo-articulo',
                    'content' => 'Descripcion del Nuevo Articulo',
                ]
            ]
        ]);

        $response->assertJsonApiValidationErrors('title');

    }

    /** @test */
    public function title_must_be_at_least_4_characters()
    {

        $response = $this->postJson(route('api.v1.articles.store'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'dff',
                    'slug' => 'nuevo-articulo',
                    'content' => 'Descripcion del Nuevo Articulo',
                ]
            ]
        ]);

        $response->assertJsonApiValidationErrors('title');

        // $response->assertJsonValidationErrors('data.attributes.title');

    }

    /** @test */
    public function slug_is_required()
    {

        $response = $this->postJson(route('api.v1.articles.store'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nuevo Articulo',
                    'content' => 'Descripcion del Nuevo Articulo',
                ]
            ]
        ]);
        $response->assertJsonApiValidationErrors('slug');

    }

    /** @test */
    public function content_is_required()
    {

        $response = $this->postJson(route('api.v1.articles.store'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nuevo Articulo',
                    'slug' => 'nuevo-articulo',
                ]
            ]
        ]);
        $response->assertJsonApiValidationErrors('content');

    }
}
