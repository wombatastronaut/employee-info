<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Employee;

class EmployeesController extends Controller
{

    /**
     * Gets all the users for table
     *
     * @param Request $request
     * @return object
     */
    public function index(Request $request)
    {
        $table_options = $this->getTableOptions($request);

        $result = Employee::orderBy($table_options['sort_field'], $table_options['sort_dir'])
            ->paginate($table_options['per_page']);

        return $result;
    }

    /**
     * Gets table options for data table
     *
     * @param Request $request
     * @return array
     */
    private function getTableOptions($request)
    {
        $result = [];
        $sort = explode('|', $request->input('sort'));
        $sort_field = array_first($sort);
        $sort_dir = array_last($sort);
        $per_page = (int) $request->input('per_page');

        return [
            'filter' => $request->input('filter'),
            'sort_field' => $sort_field,
            'sort_dir' => $sort_dir,
            'per_page' => $per_page
        ];
    }
}
