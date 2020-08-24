<?php 
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class Repository implements RepositoryInterface
{
    // model property on class instances
    protected $model;

    // Constructor to bind model to repo
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    // Get all instances of model
    public function all()
    {
        return $this->model->all();
    }

    // create a new record in the database
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    // Get the associated model
    public function getModel()
    {
        return $this->model;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    // Plucked associated model attribute
    public function pluckedRelatedModelAttr($id, $model, $attr)
    {
        $data = $this->model->whereHas($model, function($query) use ($id) {
                    $query->whereId($id);
                })->pluck($attr, 'id');

        return $data;
    }
}
