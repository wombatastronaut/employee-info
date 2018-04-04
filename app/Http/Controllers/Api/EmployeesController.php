<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Employee;
use Validator;
use Image;
use PDF;


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
            'email' => 'required|email|unique:employees,email,' . $request->input('id'),
            'birthdate' => 'required',
            'blood_type' => 'required',
        ]);
        

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

        // Check if for edit or new employee
        if ($request->has('id') && !is_null($request->input('id'))) {
            $employee = Employee::find($request->input('id'));
        } else {
            $employee = new Employee;
        }

        $employee->name = $request->input('name');
        $employee->email = $request->input('email');
        $employee->birthdate = $request->input('birthdate');
        $employee->blood_type = $request->input('blood_type');

        $result = $employee->save();

        if($result) {
            if ($request->has('image') && !is_null($request->input('image'))) {
                $employee->image = $this->storeImage($request->input('image'), 'jpg', $employee->id, config('constants.employees_image_path'));
            }

            if ($request->has('signature') && !is_null($request->input('signature'))) {
                $employee->signature = $this->storeImage($request->input('signature'), 'png', $employee->id, config('constants.employees_signature_path'));
            }

            $employee->save();
        }

        return [
            'success' => $result
        ];
    }

    /**
     * Store file
     * 
     * @param object $image
     * $param string $path
     * @return string
     */
    private function storeImage($image, $format, $id, $path) {
        if (!file_exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    
        $result = Image::make($image)->save($path . '\\' . $id . '.' . $format);

        return $result->basename;
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

    public function details(Employee $employee)
    {
        view()->share('employee', $employee);
        return view('details', ['employee' => $employee]);
        // $pdf = PDF::loadView('htmltopdfview');
        // return $pdf->download();
    }

    /**
     * Delete an employee
     *
     * @param string|int $id
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
