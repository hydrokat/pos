<?php

class SupplierSeeder extends Seeder {

    public function run() {
        $sups = [
            array('code'   => 'UNI',
                  'name'   => 'United Laboratories',
                  'address'   => 'Manila'
            ),
        ];

        DB::table('suppliers')->insert($sups);

        $this->command->info('Suppliers table seeded!');
    }

}
