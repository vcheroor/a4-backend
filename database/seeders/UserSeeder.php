DB::table('users')->insert([
    [
        'name' => 'Admin',
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
    ],
    [
        'name' => 'User',
        'email' => 'user@example.com',
        'password' => Hash::make('password'),
    ]
]);
