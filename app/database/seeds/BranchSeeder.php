<?php

class BranchSeeder extends Seeder {

    public function run() {
        $branches = [
            array('name'   => 'Main',
                  'address'   => 'Tuguegarao',
            ),
            array('name'   => 'Branch 1',
                  'address'   => 'Carig',
            ),
        ];

        DB::table('branches')->insert($branches);

        $this->command->info('Branches table seeded!');
    }

}
