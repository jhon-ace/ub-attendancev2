<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin\Employee;
use Spatie\Permission\Models\Role;

class AssignDefaultRolesToEmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the 'employee' role exists for the 'employee' guard
        Role::firstOrCreate(['name' => 'employee']);

        // Get all employees from the 'employees' table
        $employees = Employee::all();

        foreach ($employees as $employee) {
            // Assign the 'employee' role to each employee
            if (!$employee->hasRole('employee')) {
                $employee->assignRole('employee');
            }
        }
    }
}
