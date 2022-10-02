<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    protected $name;
    protected $email;
    protected $phone;

    /**
     * UserExport constructor
     */
    public function __construct($name, $email, $phone)
    {
        $this->name  = $name;
        $this->email = $email;
        $this->phone = $phone;
    }

    /**
     * @param mixed $user
     *
     * @return array
     */
    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->phone
        ];
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Phone'
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $users = User::orderBy('id', 'desc');

        if ($this->name != '') {
            $users = $users->where('name', 'like', '%' . $this->name . '%');
        }

        if ($this->email != '') {
            $users = $users->where('email', 'like', '%' . $this->email . '%');
        }

        if ($this->phone != '') {
            $users = $users->where('phone', 'like', '%' . $this->phone . '%');
        }

        return $users->get();

    }
}
