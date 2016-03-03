<?php

class UserSeeder extends Seeder {

    public function run() {
      //NULL -> not going to expire! awesomesauce!
        $accts = [
            array('username'   => 'dev.god',
                  'password'   => Hash::make('()M6!bbq'),
                  'name'       => 'G Bacani',
                  'role'       => 1,
                  'expiry'     => NULL,
                  'created_at' => date('Y-m-d H:i:s'),
                  'updated_at' => date('Y-m-d H:i:s'),
            ),
            array('username'   => 'pharmacyy',
                  'password'   => Hash::make('ownerpw'),
                  'name'       => 'Store Owner',
                  'role'       => 2,
                  'expiry'     => date('Y-m-d', strtotime('03/15/2018')),
                  'created_at' => date('Y-m-d H:i:s'),
                  'updated_at' => date('Y-m-d H:i:s'),

            ),
            array('username'   => 'ruby',
                  'password'   => Hash::make('ruby123123'),
                  'name'       => 'Ruby',
                  'role'       => 3,
                  'expiry'     => date('Y-m-d', strtotime('03/15/2018')),
                  'created_at' => date('Y-m-d H:i:s'),
                  'updated_at' => date('Y-m-d H:i:s'),
            ),
            array('username'   => 'expired',
                  'password'   => Hash::make('expired'),
                  'name'       => 'Expired Account',
                  'role'       => 3,
                  'expiry'     => date('Y-m-d', strtotime('01/01/1970')),
                  'created_at' => date('Y-m-d H:i:s'),
                  'updated_at' => date('Y-m-d H:i:s'),
            )
        ];

        DB::table('users')->insert($accts);

        $this->command->info('Users table seeded!');
    }

}
