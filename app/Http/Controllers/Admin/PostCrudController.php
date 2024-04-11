<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Auth;

/**
 * Class PostCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PostCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Post::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/post');
        CRUD::setEntityNameStrings('post', 'posts');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'name' => 'id',
            'label' => 'ID',
            'type' => 'int'
        ]);

        $this->crud->addColumn([
            'name' => 'image',
            'label' => 'Thumbnail',
            'type' => 'image',
            'wrapper' => [
                'style' => 'display: flex; align-items: center; justify-content: center; width: 20px; height: 20px;'
            ],
            'url' => function($entity) {
                return asset("storage/uploads/{$entity->image}");
            },
        ]);

        $this->crud->addColumn([
            'name' => 'title',
            'label' => 'Title',
            'type' => 'string'
        ]);

        $this->crud->addColumn([
            'name' => 'status',
            'label' => 'Active',
            'type' => 'boolean',
            'options' => [0 => 'Enabled', 1 => 'Disabled']
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PostRequest::class);

        CRUD::addField([
            'name' => 'title',
            'label' => 'Title',
            'type' => 'text',
            'search' => function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            }
        ]);

        CRUD::addField([
            'name' => 'description',
            'label' => 'Description',
            'type' => 'textarea'
        ]);

        CRUD::addField([
            'name' => 'image',
            'label' => 'Image',
            'type' => 'upload',
            'upload' => true,
            'crop' => true,
            'aspect_ratio' => 1,
            'disk' => 'public',
            'withFiles' => true
        ]);

        CRUD::addField([
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select_from_array',
            'options' => [
                1 => 'Enabled',
                0 => 'Disabled',
            ],
            'allows_null' => false,
        ]);

        CRUD::addField([
            'name' => 'flex_column',
            'type' => 'custom_html',
            'value' => '<style>.flex-column { display: flex; flex-direction: column; }</style>',
        ]);
    }

    protected function setupUpdateOperation()
    {

        CRUD::field('title')
            ->label('Title')
            ->type('text');

        CRUD::field('description')
            ->label('Description')
            ->type('textarea');

        CRUD::field('image')
            ->label('Image')
            ->type('upload')
            ->upload(true)
            ->disk('public')
            ->storeDir('uploads');

        CRUD::field('status')
            ->label('Status')
            ->type('select_from_array')
            ->options([
                1 => 'Enabled',
                0 => 'Disabled',
            ])
            ->allows_null(false);

        $this->setupCreateOperation();
        $this->crud->setOperationSetting('showDeleteButton', true);
    }



    public function destroy($id)
    {
        CRUD::hasAccessOrFail('delete');

        return CRUD::delete($id);
    }

    protected function setupShowOperation()
    {
        $this->crud->setValidation(PostRequest::class);
        $this->autoSetupShowOperation();

        CRUD::column('image')
            ->label('Image')
            ->type('image')
            ->prefix('');

        CRUD::column('status')
            ->label('Status')
            ->type('select_from_array')
            ->options([
                1 => 'Enabled',
                0 => 'Disabled',
            ])
            ->allows(false);

    }

}
