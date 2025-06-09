<?php

return [
    'old_reservations' => [
        'description' => 'Eliminar reservas con más de un año desde el check-out',
        'starting' => 'Buscando reservas antiguas...',
        'found' => 'Reservas encontradas: :count',
        'completed' => 'Reservas eliminadas correctamente.',
    ],
    'create_admin' => [
        'description' => 'Crear un nuevo usuario administrador con el nombre, correo y contraseña especificados.',
        'user_exists' => 'El usuario con email :email ya existe.',
        'validation' => [
            'name_required' => 'El nombre es obligatorio.',
            'name_string' => 'El nombre debe ser una cadena de texto.',
            'name_max' => 'El nombre no puede tener más de 255 caracteres.',
            'email_required' => 'El correo electrónico es obligatorio.',
            'email_email' => 'El correo electrónico debe ser válido.',
            'email_max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'email_unique' => 'El correo electrónico ya está en uso.',
            'password_required' => 'La contraseña es obligatoria.',
            'password_string' => 'La contraseña debe ser una cadena de texto.',
            'password_min' => 'La contraseña debe tener al menos 8 caracteres.',
        ],
        'created' => "Usuario admin ':name' creado correctamente.",
        'ask' => [
            'name' => '¿Cuál es el nombre del administrador?',
            'email' => '¿Cuál es el correo del administrador?',
            'password' => '¿Cuál es la contraseña del administrador?',
        ],
        'dev_clear_cache' => 'Se ha limpiado el caché correctamente.'
    ],
];
