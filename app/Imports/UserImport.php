<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class UserImport implements ToCollection, WithHeadingRow
{
    private $imported = 0;
    private $updated = 0;
    private $errors = [];
    private $skipErrors;

    public function __construct($skipErrors = true)
    {
        $this->skipErrors = $skipErrors;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                $rowNumber = $index + 2;

                // Validasi email wajib
                if (empty($row['email'])) {
                    $this->addError($rowNumber, 'N/A', 'Email tidak boleh kosong');
                    continue;
                }

                // Ambil data
                $email = strtolower(trim($row['email']));
                $name = $row['name'] ?? ($row['nama'] ?? 'User');
                $password = !empty($row['password']) ? $row['password'] : 'password123';
                $role = in_array($row['role'] ?? 'user', ['admin', 'user']) ? $row['role'] : 'user';
                
                // Data Employee
                $employeeNumber = $row['employee_number'] ?? ($row['nip'] ?? null);
                $department = $row['department'] ?? null;
                $position = $row['position'] ?? null;

                // Cek user existing
                $existingUser = User::where('email', $email)->first();

                if ($existingUser) {
                    // Update user
                    $existingUser->update([
                        'name' => $name,
                        'role' => $role,
                    ]);
                    
                    // Update atau create employee
                    if ($existingUser->employee) {
                        $existingUser->employee->update([
                            'employee_number' => $employeeNumber,
                            'department' => $department,
                            'position' => $position
                        ]);
                    } else {
                        Employee::create([
                            'user_id' => $existingUser->id,
                            'employee_number' => $employeeNumber,
                            'department' => $department,
                            'position' => $position
                        ]);
                    }
                    
                    $this->updated++;
                } else {
                    // Buat user baru
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => Hash::make($password),
                        'role' => $role,
                        'status' => 'active',
                        'email_verified_at' => now(),
                    ]);

                    // Buat employee
                    Employee::create([
                        'user_id' => $user->id,
                        'employee_number' => $employeeNumber,
                        'department' => $department,
                        'position' => $position
                    ]);
                    
                    $this->imported++;
                }

            } catch (\Exception $e) {
                $this->addError($rowNumber, $row['email'] ?? 'N/A', $e->getMessage());
            }
        }
    }

    private function addError($row, $email, $error)
    {
        if ($this->skipErrors) {
            $this->errors[] = [
                'row' => $row,
                'email' => $email,
                'error' => $error
            ];
        } else {
            throw new \Exception("Error pada baris {$row}: {$error}");
        }
    }

    public function getImportedCount()
    {
        return $this->imported;
    }

    public function getUpdatedCount()
    {
        return $this->updated;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}