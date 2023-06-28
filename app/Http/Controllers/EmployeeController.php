<?php

namespace App\Http\Controllers;

use App\Http\Requests\checkEmployeeLoginRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Interfaces\EmployeeInterface;

/**
 * Class EmployeeController
 * @author Thura Win
 * @create 22/06/2023
 * @return array
 */
class EmployeeController extends Controller
{

    private $employeeInterface;

    public function __construct(EmployeeInterface $employeeInterface)
    {
        $this->employeeInterface = $employeeInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @author Thura Win
     * @create 22/06/2023
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('pages.login');
    }

    /**
     * Display a listing of the resource.
     *
     * @author Thura Win
     * @create 22/06/2023
     * @return \Illuminate\Http\Response
     */
    public function login(checkEmployeeLoginRequest $request)
    {
        //
        try {
            // $isExists = $this->employeeInterface->checkEmployeeLogin($request->txtId, $request->txtPassword);

            // if ($isExists) {
            //     return redirect()->route('items.list')->with('success', 'Login Successfully');
            // }
            // else{
            //     return redirect()->route('employees.login-form')->withErrors(['message' => 'Email or Password is incorrect']);
            // }

            $result = $this->employeeInterface->checkEmployeeLogin($request->txtId, $request->txtPassword);

            if ($result === true) {
                return redirect()->route('items.list')->with('success', 'Login Successfully');
            } elseif ($result === 'password') {
                return redirect()->route('employees.login-form')->withErrors(['message' => 'Incorrect password']);
            } elseif ($result === 'emp_id') {
                return redirect()->route('employees.login-form')->withErrors(['message' => 'Employee ID not found']);
            }
        } catch (\Exception $e) {
            return redirect()->route('employees.login-form')->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @author Thura Win
     * @create 22/06/2023
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
