<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Auth\Events\Registered;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Register extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $user_type = 'client';
    public $phone = '';
    public $address = '';
    public $city = '';
    public $country = '';
    public $latitude = '';
    public $longitude = '';
    
    // Driver specific fields
    public $vehicle_type = '';
    public $vehicle_model = '';
    public $vehicle_plate = '';
    public $license_number = '';
    public $bio = '';

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'user_type' => 'required|in:client,driver',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ];

        // Add driver-specific validation rules
        if ($this->user_type === 'driver') {
            $rules['vehicle_type'] = 'required|string|max:100';
            $rules['vehicle_model'] = 'required|string|max:100';
            $rules['vehicle_plate'] = 'required|string|max:20';
            $rules['license_number'] = 'required|string|max:50';
            $rules['bio'] = 'nullable|string|max:500';
        }

        return $rules;
    }

    public function register()
    {
        $this->validate();

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'user_type' => $this->user_type,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];

        // Add driver-specific data
        if ($this->user_type === 'driver') {
            $userData['vehicle_type'] = $this->vehicle_type;
            $userData['vehicle_model'] = $this->vehicle_model;
            $userData['vehicle_plate'] = $this->vehicle_plate;
            $userData['license_number'] = $this->license_number;
            $userData['bio'] = $this->bio;
            $userData['is_available'] = true;
        }

        $user = User::create($userData);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}