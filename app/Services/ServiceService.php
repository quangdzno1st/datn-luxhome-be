<?php

namespace App\Services;

interface ServiceService
{
    public function getAll();
    public function getById($id);
    public function createNew($data);
    public function update($data, $id);
    public function delete($id);
    public function restore($id);
    public function forceDelete($id);
}