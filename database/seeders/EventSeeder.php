<?php

namespace Database\Seeders;

use App\Domains\Event\Model\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::factory()
            ->count(15000)
            ->create()
            ->each(function (Event $event) {
                $event->code = 'Event_#'.$event->id;
                $event->save();
            });
    }
}
