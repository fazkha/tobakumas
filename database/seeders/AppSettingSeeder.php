<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppSetting::create([
            'parm' => 'prefix_purchase_order',
            'value' => 'PO',
        ]);
        AppSetting::create([
            'parm' => 'prefix_sale_order',
            'value' => 'SO',
        ]);
    }
}
