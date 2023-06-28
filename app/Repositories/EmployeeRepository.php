<?php

namespace App\Repositories;

use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\EmployeeInterface;


/**
     * Manage Database for Employee.
     * @author Thura Win
     * @create 21/06/2023
     */
class EmployeeRepository implements EmployeeInterface
{
    /**
     * Get all Employees from DB.
     * @author Thura Win
     * @create 21/06/2023
     * @param  --
     * @return array
     */
    public function getAllEmployees()
    {
        return Employee::get()->toArray();
    }

    /**
     * Get Employee from specific ID from DB.
     * @author Thura Win
     * @create 21/06/2023
     * @param  int $id
     * @return array
     */
    public function getEmployeeById($id)
    {
        return Employee::find($id)->toArray();
    }


    /**
     * Get Employee from specific ID and Password from DB.
     * @author Thura Win
     * @create 21/06/2023
     * @param  int $empId, string $password
     * @return array
     */
    public function checkEmployeeLogin($empId, $password)
    {
        $employee = Employee::where('emp_id', $empId)->first();

        if ($employee) {
            if (Hash::check($password, $employee->password)) {
                return true; // Successful login
            } else {
                return 'password'; // Password incorrect
            }
        }

        return 'emp_id'; // Employee ID not found
    }
}
