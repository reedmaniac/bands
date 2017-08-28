<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Important
        $band = App\Models\Band::create([
            'name' => 'AC/DC',
            'website' => 'http://acdc.com',
            'start_date' => '1973-11-01'
        ]);

        App\Models\Album::create([
            'name' => 'Back in Black',
            'band_id' => $band->id,
            'recorded_date' => '1980-05-01',
            'release_date' => '1980-07-25',
            'genre' => 'Rock',
            'number_of_tracks' => 10,
            'producer' => 'Robert John "Mutt" Lange'
        ]);

        // And then random seed data
    	factory(App\Models\Band::class, 50)->create()->each(function ($u) {

    		$album_count = rand(0,5);

    		for($i = 0; $i <= $album_count; $i++) {
    			$u->albums()->save(factory(App\Models\Album::class)->make());
    		}
		});

    }
}
