<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Interfaces\EmployeeInterface;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\checkEmployeeLoginRequest;

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
            $result = $this->employeeInterface->checkEmployeeLogin($request->txtId, $request->txtPassword);

            if ($result === true) {
                // Store the employee session
                Session::put('employee', $request->txtId);

                return redirect()->route('items.list')->with('success', __('public.loginSuccessfully'));
            } elseif ($result === 'password') {
                return redirect()->route('employees.login-form')->withErrors(['message' => __('public.incorrectPassword')]);
            } elseif ($result === 'emp_id') {
                return redirect()->route('employees.login-form')->withErrors(['message' => __('public.employeeIdNotFound')]);
            }
        } catch (\Exception $e) {
            return redirect()->route('employees.login-form')->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Employee Logout.
     *
     * @author Thura Win
     * @create 06/07/2023
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        //
        try {
            // Clear the employee session
            Session::forget('employee');

            return redirect()->route('employees.login-form')->with('success', __('public.logoutSuccessfully'));
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
