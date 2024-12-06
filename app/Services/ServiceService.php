<?php

namespace App\Services;

use Illuminate\Http\Request;

interface ServiceService
{
    public function getAll(Request $request, $org_id);
    public function getById($id);
    public function createNew($data);
    public function update($data, $id);
    public function delete($id);
    public function restore($id);
    public function forceDelete($id);
}