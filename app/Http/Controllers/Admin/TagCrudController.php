<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TagCreateRequest;
use App\Http\Requests\Admin\TagUpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TagCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TagCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Tag::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/tag');
        CRUD::setEntityNameStrings('categoría', 'categorías');
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

        CRUD::column('name')
            ->type('string')
            ->label('Nombre')
        ;

        CRUD::column('color')
            ->type('color')
            ->label('Color')
        ;

        CRUD::filter('name')
            ->type('text')
            ->label('Nombre')
            ->whenActive(function ($input) {
                CRUD::addClause('where', 'name', 'LIKE', '%' . $input . '%');
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
        CRUD::setValidation(TagCreateRequest::class);

        CRUD::field('name')
            ->type('text')
            ->label('Nombre')
            ->wrapper(['class' => 'form-group col-md-6 offset-md-3'])
            ->attributes(['aria-label' => 'Nombre'])
        ;

        CRUD::field('color')
            ->type('color')
            ->label('Color')
            ->default('#FFFFFF')
            ->wrapper(['class' => 'form-group col-md-6 offset-md-3'])
            ->attributes(['aria-label' => 'Color'])
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
        CRUD::setValidation(TagUpdateRequest::class);

        CRUD::field('name')
            ->type('text')
            ->label('Nombre')
            ->wrapper(['class' => 'form-group col-md-6 offset-md-3'])

        ;

        CRUD::field('color')
            ->type('color')
            ->label('Color')
            ->default('#FFFFFF')
            ->wrapper(['class' => 'form-group col-md-6 offset-md-3'])

        ;
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();

        CRUD::column('created_at')->type('datetime')->label('Fecha de creación');

        CRUD::column('updated_at')->type('datetime')->label('Última modificación');
    }
}
