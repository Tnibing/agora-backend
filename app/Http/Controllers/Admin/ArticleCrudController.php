<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ArticleCreateRequest;
use App\Http\Requests\Admin\ArticleUpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ArticleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ArticleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Article::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/article');
        CRUD::setEntityNameStrings('noticia', 'noticias');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->orderBy('created_at', 'asc');

        $this->crud->with(['user', 'tags']);

        CRUD::column('title')
            ->type('string')
            ->label('Título')
        ;

        CRUD::column('description_image_url')
            ->type('image')
            ->label('Imagen descripción')
            ->value(function ($entry){
                return $entry->article_description_image;
            })
        ;

        CRUD::column('main_image_url')
            ->type('image')
            ->label('Imagen principal')
            ->value(function ($entry){
                return $entry->article_main_image;
            })
        ;

        CRUD::column('user_id')
            ->type('select')
            ->label('Autor')
            ->entity('user')
            ->attribute('name')
            ->model('App\Models\User')
        ;

        CRUD::column('tags')
            ->label('Categorías')
            ->type('closure')
            ->function(function($entry) {
                return $entry->tags->map(function($tag) {
                    $color = $tag->color ?? '#6c757d';
                    return "<span class='badge' style='background-color: {$color};'>{$tag->name}</span>";
                })->implode(' ');
            })
            ->escaped(false)
        ;

        CRUD::filter('author')
            ->type('select2')
            ->label('Autor')
            ->values(function () {
                return \App\Models\User::where('role', 'editor')->orWhere('role', 'admin')->get()->keyBy('id')->pluck('name', 'id')->toArray();
            })
            ->whenActive(function($input) {
                CRUD::addClause('where', 'user_id', $input);
            })
        ;

        CRUD::filter('title')
            ->type('text')
            ->label('Título')
            ->whenActive(function ($input) {
                CRUD::addClause('where', 'title', 'LIKE', '%' . $input . '%');
            })
        ;

        CRUD::filter('tags')
            ->label('Categorías')
            ->type('select2_multiple')
            ->values(function () {
                return \App\Models\Tag::all()->keyBy('id')->pluck('name', 'id')->toArray();
            })
            ->whenActive(function($values) {
                $values = json_decode($values);
                CRUD::addClause('whereHas', 'tags', function($query) use ($values) {
                    $query->whereIn('tags.id', $values);
                });
            })
        ;
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ArticleCreateRequest::class);

        CRUD::field('title')
            ->type('text')
            ->label('Título')
            ->wrapper(['class' => 'form-group col-md-6'])
            ->attributes(['aria-label' => 'Título'])
        ;

        CRUD::field('description_image_url')
            ->type('image')
            ->label('Imagen de descripción')
            ->wrapper(['class' => 'form-group col-md-12'])
            ->attributes(['aria-label' => 'Imagen de descripción'])
            ->withFiles([
                'disk' => 'public',
                'path' => 'images/article',
            ])
            ->hint('Opcional')
        ;

        CRUD::field([
            'name'  => 'description',
            'label' => 'Descripción',
            'type'  => 'wysiwyg',
            'elfinderOptions' => false,
            ])
        ;

        CRUD::field('main_image_url')
            ->type('image')
            ->label('Imagen principal')
            ->wrapper(['class' => 'form-group col-md-12'])
            ->attributes(['aria-label' => 'Imagen principal'])
            ->withFiles([
                'disk' => 'public',
                'path' => 'images/article',
            ])
            ->hint('Opcional')
        ;

        CRUD::field([
            'name'  => 'content',
            'label' => 'Contenido',
            'type'  => 'wysiwyg',
            'elfinderOptions' => false,
            ])
        ;

        CRUD::field([
            'name'      => 'tags',
            'label'     => 'Categorías',
            'type'      => 'checklist',
            'entity'    => 'tags',
            'attribute' => 'name',
            'model'     => "App\Models\Tag",
            'pivot'     => true,
            'wrapper'   => ['class' => 'form-group col-md-3'],
            'attributes' => ['aria-label' => 'Categorías'],
            'hint' => 'Seleccione una o varias categorías',
        ]);

        CRUD::field('user_id')
            ->type('hidden')
            ->value(backpack_user()->id)
        ;
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(ArticleUpdateRequest::class);

        CRUD::field('title')
            ->type('text')
            ->label('Título')
            ->wrapper(['class' => 'form-group col-md-6'])
            ->attributes(['aria-label' => 'Título'])
        ;

        CRUD::field('description_image_url')
            ->type('image')
            ->label('Imagen de descripción')
            ->wrapper(['class' => 'form-group col-md-12'])
            ->attributes(['aria-label' => 'Imagen de descripción'])
            ->withFiles([
                'disk' => 'public',
                'path' => 'images/article',
            ])
            ->hint('Opcional')
        ;

        CRUD::field([
            'name'  => 'description',
            'label' => 'Descripción',
            'type'  => 'wysiwyg',
            'elfinderOptions' => false,
            ])
        ;

        CRUD::field('main_image_url')
            ->type('image')
            ->label('Imagen principal')
            ->wrapper(['class' => 'form-group col-md-12'])
            ->attributes(['aria-label' => 'Imagen principal'])
            ->withFiles([
                'disk' => 'public',
                'path' => 'images/article',
            ])
            ->hint('Opcional')
        ;

        CRUD::field([
            'name'  => 'content',
            'label' => 'Contenido',
            'type'  => 'wysiwyg',
            'elfinderOptions' => false,
            ])
        ;

        CRUD::field([
            'name'      => 'tags',
            'label'     => 'Categorías',
            'type'      => 'checklist',
            'entity'    => 'tags',
            'attribute' => 'name',
            'model'     => "App\Models\Tag",
            'pivot'     => true,
            'wrapper'   => ['class' => 'form-group col-md-3'],
            'attributes' => ['aria-label' => 'Categorías'],
            'hint' => 'Seleccione una o varias categorías',
        ]);
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();

        CRUD::column('description')
            ->type('wysiwyg')
            ->label('Descripción')
        ;

        CRUD::column('content')
            ->type('wysiwyg')
            ->label('Contenido')
        ;

        CRUD::column('created_at')
            ->type('datetime')
            ->label('Fecha de creación')
        ;

        CRUD::column('updated_at')
            ->type('datetime')
            ->label('Última modificación')
        ;
    }

    public function setupDeleteOperation()
    {
        CRUD::field('description_image_url')
            ->type('image')
            ->withFiles()
        ;

        CRUD::field('main_image_url')
            ->type('image')
            ->withFiles()
        ;

        $this->setupCreateOperation();
    }
}
