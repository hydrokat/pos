<?php

class ItemTypeSeeder extends Seeder {

    public function run() {
      $types = [
            array('code' => 'med',
                  'name' => 'Medicine',
                  'desc' => 'Medicines'
            ),
            array('code' => 'msp',
                  'name' => 'Medicine Supplies',
                  'desc' => 'Medicine Supplies'
            ),
            array('code' => 'lab',
                  'name' => 'Lab Equipments',
                  'desc' => 'Lab Equipments'
            ),            
            array('code' => 'meq',
                  'name' => 'Medical Equipment',
                  'desc' => 'Medical Equipment'
            ),
            array('code' => 'labsup',
                  'name' => 'Lab Supplies',
                  'desc' => 'Laboratory Supplies'
            ),
            array('code' => 'csm',
                  'name' => 'Cosmetics',
                  'desc' => 'Cosmetics'
            ),
      ];

        DB::table('itemTypes')->insert($types);

        $this->command->info('Item types table seeded!');
    }

}
