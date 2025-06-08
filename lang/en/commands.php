<?php

return [
    'old_reservations' => [
        'description' => 'Delete reservations older than one year since check-out',
        'starting' => 'Searching for old reservations...',
        'found' => 'Reservations found: :count',
        'completed' => 'Old reservations deleted successfully.',
    ],
    'create_admin' => [
        'description' => 'Create a new admin user with the specified name, email, and password.',
        'user_exists' => 'The user with email :email already exists.',
        'validation' => [
            'name_required' => 'The name is required.',
            'name_string' => 'The name must be a string.',
            'name_max' => 'The name may not be greater than 255 characters.',
            'email_required' => 'The email is required.',
            'email_email' => 'The email must be a valid email address.',
            'email_max' => 'The email may not be greater than 255 characters.',
            'email_unique' => 'The email has already been taken.',
            'password_required' => 'The password is required.',
            'password_string' => 'The password must be a string.',
            'password_min' => 'The password must be at least 8 characters.',
        ],
        'created' => "Admin user ':name' created successfully.",
        'ask' => [
            'name' => 'What is the admin name?',
            'email' => 'What is the admin email?',
            'password' => 'What is the admin password?',
        ],
        'dev_clear_cache' => 'Cache cleared successfully.'
    ],
];
