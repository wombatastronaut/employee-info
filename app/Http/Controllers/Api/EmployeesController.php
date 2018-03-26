<?php

namespace App\Http\Controllers\Api;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Employee;

class EmployeesController extends Controller
{
    /**
     * Add/Edit an employee
     * 
     * @param Request $request
     * @return Response
     */
    public function post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:employees',
            'birthdate' => 'required',
            'blood_type' => 'required',
        ]);
        
        if($validator->fails()){
            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

        if ($request->has('id')) {
            $employee = Employee::find($request->input('id'));
        } else {
            $employee = new Employee;
        }

        $employee->name = $request->input('name');
        $employee->email = $request->input('email');
        $employee->birthdate = $request->input('birthdate');
        $employee->blood_type = $request->input('blood_type');

        $result = $employee->save();

        return [
            'success' => $result
        ];
    }

    /**
     * Get all the employees for table
     *
     * @param Request $request
     * @return Response
     */
    public function getAllForTable(Request $request)
    {
        $table_options = $this->getTableOptions($request);

        $result = Employee::where('name', 'LIKE', "%{$table_options['filter']}%")
            ->orWhere('email', 'LIKE', "%{$table_options['filter']}%")
            ->orderBy($table_options['sort_field'], $table_options['sort_dir'])
            ->paginate($table_options['per_page']);

        return $result;
    }

    /**
     * Get options for data table
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

    /**
     * Delete an employee
     *
     * @param string $id
     * @return Response
     */
    public function delete($id)
    {
        $employee = Employee::findOrFail($id);
        $result = $employee->delete();

        return [
            'success' => $result
        ];
    }
}
