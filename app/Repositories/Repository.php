<?php


namespace App\Repositories;


use App\Http\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class Repository implements RepositoryInterface
{

    protected $model;

    public function __construct(Model $model){
        $this ->model = $model;
    }



    public function all()
    {
        return $this ->model->paginate(PAGINATION_COUNT)->get();
    }

    public function create(array $data)
    {
        return $this ->model->create($data);
    }

    public function update(array $data, $id)
    {
        $record =$this->find($id);
        return $record->update($data);
    }

    public function delete($id)
    {
        return $this->model->destory($id);
    }

    public function show($id)
    {
        return $this ->model->findOrFail($id);
    }

    //get the associated model
    public function getModel(){
        return $this->model;
    }

    //set the associated model
    public function setModel($model){
         $this->model = $model;
         return $this;
    }

    // Eager load database relations
    public function with($relations){
        return $this->model->with($relations);
    }
}
