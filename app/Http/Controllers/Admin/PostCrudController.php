<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Prologue\Alerts\Facades\Alert;

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
            'name' => 'image',
            'label' => 'Thumbnail',
            'type' => 'image',
            'wrapper' => [
                'style' => 'display: flex; align-items: center; justify-content: center; width: 30px; height: 30px;'
            ]
        ]);

        $this->crud->addColumn([
            'name' => 'title',
            'label' => 'Title',
            'type' => 'text',
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    if (!$entry) {
                        return '';
                    }
                    $entry->id = 17;
                    return backpack_url('post/'.$entry->id.'/show');
                },
                'style' => 'text-decoration: none !important'
            ],
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

    public function getClickableName()
    {
        return '<a href="'.url('admin/'.$this->getTable().'/'.$this->getKey()).'">'.$this->name.'</a>';
    }

}
