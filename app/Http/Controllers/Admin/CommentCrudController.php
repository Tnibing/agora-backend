<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RoleEnum;
use App\Http\Requests\CommentRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CommentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CommentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Comment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/comment');
        CRUD::setEntityNameStrings('comentario', 'comentarios');
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

        $this->crud->with(['parent', 'author', 'article']);

        CRUD::column('author.name')
            ->type('text')
            ->label('Usuario')
        ;

        CRUD::column('role')
            ->type('custom_html')
            ->label('Permisos')
            ->escaped(false)
            ->value(function ($entry) {
                $labels = RoleEnum::labels();
                $roleLabel = $labels[$entry->author->role] ?? $entry->author->role;

                $colors = [
                    'admin'  => 'bg-warning',
                    'editor' => 'bg-info',
                    'user'   => 'bg-primary',
                ];

                $colorClass = $colors[$entry->author->role] ?? 'bg-secondary';

                return '<span class="badge '.$colorClass.'">'.$roleLabel.'</span>';
            })
        ;

        CRUD::column('author.email')
            ->type('email')
            ->label('Email')
        ;

        CRUD::column('article.title')
            ->type('text')
            ->label('Título artículo')
        ;

        CRUD::column('content')
            ->type('text')
            ->label('Contenido')
        ;

        CRUD::filter('author_name')
            ->type('text')
            ->label('Nombre')
            ->whenActive(function ($input) {
                CRUD::addClause('whereHas', 'author', function($query) use ($input) {
                    $query->where('name', 'LIKE', '%' . $input . '%');
                } );
            })
        ;

        CRUD::filter('author_email')
            ->type('text')
            ->label('Email')
            ->whenActive(function ($input) {
                CRUD::addClause('whereHas', 'author', function($query) use ($input) {
                    $query->where('email', 'LIKE', '%' . $input . '%');
                });
            })
        ;

        CRUD::filter('article_title')
            ->type('text')
            ->label('Título artículo')
            ->whenActive(function ($input) {
                CRUD::addClause('whereHas', 'article', function($query) use ($input) {
                    $query->where('title', 'LIKE', '%' . $input . '%');
                });
            })
        ;

        CRUD::filter('article_content')
            ->type('text')
            ->label('Contenido artículo')
            ->whenActive(function ($input) {
                CRUD::addClause('whereHas', 'article', function($query) use ($input) {
                    $query->where('content', 'LIKE', '%' . $input . '%');
                });
            })
        ;
    }

    public function setupShowOperation()
    {
        $this->setupListOperation();

        CRUD::column('created_at')
            ->type('datetime')
            ->label('Fecha creación')
        ;

        CRUD::column('updated_at')
            ->type('datetime')
            ->label('Última modificación')
        ;
    }
}
