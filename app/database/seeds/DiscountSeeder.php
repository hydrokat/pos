<?php

class DiscountSeeder extends Seeder {

    public function run() {
        $dc = [
            array('p_code'   => 'ALAXAN FR CAP',
                  'percent_discount'   => 5,
                  'qty_discount' => 50
            ),
            array('p_code'   => 'ALAXAN FR CAP',
                  'percent_discount'   => 10,
                  'qty_discount' => 100
            ),
        ];

        DB::table('discounts')->insert($dc);

        $this->command->info('Discounts table seeded!');
    }

}
