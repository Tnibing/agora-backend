<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RoleEnum;
use App\Http\Requests\Admin\UserCreateRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
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
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('usuario', 'usuarios');
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

        CRUD::column('user_image')
            ->type('image')
            ->label('Imagen de perfil')
            ->value(function($entry) {
                return $entry->user_image;
            })
            ->height('40px')
            ->width('40px')
        ;

        CRUD::column('name')
            ->type('string')
            ->label('Nombre')
        ;

        CRUD::column('email')
            ->type('email')
            ->label('Email')
        ;
        
        CRUD::column('role')
            ->type('custom_html')
            ->label('Permisos')
            ->escaped(false)
            ->value(function ($entry) {
                $labels = RoleEnum::labels();
                $roleLabel = $labels[$entry->role] ?? $entry->role;

                $colors = [
                    'admin'  => 'bg-warning',
                    'editor' => 'bg-info',
                    'user'   => 'bg-primary',
                ];

                $colorClass = $colors[$entry->role] ?? 'bg-secondary';

                return '<span class="badge '.$colorClass.'">'.$roleLabel.'</span>';
            })
        ;

        CRUD::filter('name')
            ->type('text')
            ->label('Nombre')
            ->whenActive(function ($input) {
                CRUD::addClause('where', 'name', 'LIKE', '%' . $input . '%');
            })
        ;

        CRUD::filter('email')
            ->type('text')
            ->label('Email')
            ->whenActive(function ($input) {
                CRUD::addClause('where', 'email', 'LIKE', '%' . $input . '%');
            })
        ;

        CRUD::filter('role')
            ->type('select2')
            ->label('Permiso')
            ->values(function () {
                return RoleEnum::labels();
            })
            ->whenActive(function($input) {
                CRUD::addClause('where', 'role', $input);
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
        CRUD::setValidation(UserCreateRequest::class);

        CRUD::field('name')
            ->type('text')
            ->label('Nombre')
            ->wrapper(['class' => 'form-group col-md-6 offset-md-3'])
            ->attributes(['aria-label' => 'Nombre'])
        ;

        CRUD::field('email')
            ->type('email')
            ->label('Email')
            ->wrapper(['class' => 'form-group col-md-6 offset-md-3'])
            ->attributes(['aria-label' => 'Email'])
        ;

        CRUD::field('password')
            ->type('password')
            ->label('Contraseña')
            ->wrapper(['class' => 'form-group col-md-6 offset-md-3'])
            ->attributes(['aria-label' => 'Contraseña'])
        ;

        CRUD::field('password_confirmation')
            ->type('password')
            ->label('Confirmación de contraseña')
            ->wrapper(['class' => 'form-group col-md-6 offset-md-3'])
            ->attributes(['aria-label' => 'Confirmación de contraseña'])
        ;

        CRUD::field([
            'name' => 'role',
            'label' => 'Tipo de usuario',
            'type' => 'radio',
            'options' => RoleEnum::labels(),
            'inline' => false,
            'default' => RoleEnum::USER->value,
            'wrapper' => [
                'class' => 'form-group col-md-3 offset-md-3',
            ],
            'attributes' => [
                'aria-label' => 'Tipo de usuario',
            ]
        ]);

        CRUD::field('user_image_url')
            ->type('image')
            ->label('Imagen de perfil')
            ->withFiles([
                'disk' => 'public',
                'path' => 'images/user',
            ])
            ->wrapper(['class' => 'form-group col-md-3'])
            ->attributes(['aria-label' => 'Imagen de perfil'])
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
        CRUD::setValidation(UserUpdateRequest::class);

        CRUD::field('name')
            ->type('text')
            ->label('Nombre')
            ->wrapper(['class' => 'form-group col-md-6 offset-md-3'])
            ->attributes(['aria-label' => 'Nombre'])
        ;

        CRUD::field('email')
            ->type('email')
            ->label('Email')
            ->wrapper(['class' => 'form-group col-md-6 offset-md-3'])
            ->attributes(['aria-label' => 'Email'])
        ;

        CRUD::field('password')
            ->type('password')
            ->label('Contraseña')
            ->wrapper(['class' => 'form-group col-md-6 offset-md-3'])
            ->hint('Déjelo vacío si no se requiere cambiar la contraseña')
            ->attributes(['aria-label' => 'Contraseña (vacío si no se requiere cambio de contraseña)'])
        ;

        CRUD::field('password_confirmation')
            ->type('password')
            ->label('Confirmación de contraseña')
            ->wrapper(['class' => 'form-group col-md-6 offset-md-3'])
            ->hint('Déjelo vacío si no se requiere cambiar la contraseña')
            ->attributes(['aria-label' => 'Confirmación de contraseña (vacío si no se requiere cambio de contraseña)'])
        ;

        CRUD::field([
            'name' => 'role',
            'label' => 'Tipo de usuario',
            'type' => 'radio',
            'options' => RoleEnum::labels(),
            'inline' => false,
            'default' => RoleEnum::USER->value,
            'wrapper' => [
                'class' => 'form-group col-md-2 offset-md-3',
            ],
            'attributes' => [
                'aria-label' => 'Tipo de usuario',
            ]
        ]);

        CRUD::field('user_image_url')
            ->type('image')
            ->label('Imagen de perfil')
            ->withFiles([
                'disk' => 'public',
                'path' => 'images/user',
            ])
            ->wrapper(['class' => 'form-group col-md-4'])
            ->attributes(['aria-label' => 'Imagen de perfil'])
        ;

        CRUD::setOperationSetting('update', function ($request, $model) {
            if (empty($request->password) && empty($request->password_confirmation)) {
                unset($request['password']);
                unset($request['password_confirmation']);
            }
        });
    }

    public function update()
    {
        $request = $this->crud->validateRequest();

        $data = $request->except(['password', 'password_confirmation']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $this->crud->update($request->get($this->crud->model->getKeyName()), $data);

        \Alert::success(trans('backpack::crud.update_success'))->flash();

        return $this->crud->performSaveAction($request->get($this->crud->model->getKeyName()));
    }

    public function setupDeleteOperation()
    {
        CRUD::field('user_image_url')
            ->type('image')
            ->withFiles()
        ;

        $this->setupCreateOperation();
    }

    public function setupShowOperation()
    {
        $this->setupListOperation();

        CRUD::column('created_at')->label('Fecha de creación')->type('datetime');

        CRUD::column('updated_at')->label('Última modificación')->type('datetime');
    }

}
