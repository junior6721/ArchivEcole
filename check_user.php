<?php
$u = App\Models\User::where('email','zoglobossoujude@gmail.com')->first();
echo 'role (enum): ' . $u->role->value . PHP_EOL;
echo 'roles spatie: ';
print_r($u->getRoleNames()->toArray());
echo 'permissions: ';
print_r($u->getAllPermissions()->pluck('name')->toArray());
