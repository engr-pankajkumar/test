<?php

namespace App\Repositories\Interfaces;

interface RepositoryInterface {
 
    public function all($columns = array('*'));
 
    public function paginate($perPage = 15, $columns = array('*'));
 
    public function create(array $data);
 
    public function update(array $data, $id);
 
    public function delete($id);
 
    public function find($id, $columns = array('*'));
 
    public function findBy(array $attributes, $type, $columns = array('*'));

    public function findActive($columns = array('*'));

    public function updateBy(array $data, array $where);

    public function updateOrCreate(array $data, array $where);

    public function findOrCreate(array $where);

    public function deleteBy(array $where);

    public function truncate();

}