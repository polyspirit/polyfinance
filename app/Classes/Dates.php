<?php

namespace App\Classes;

use App\Repositories\FlowRepository;
use App\Models\User;

class Dates
{
    public function __construct(private FlowRepository $flowRepository)
    {
    }

    public function getDatedFlows(string $type): \Illuminate\Support\Collection
    {
        $users = User::all();
        $years = $this->flowRepository->getDatesByType($type);

        foreach ($years as $year => $months) {
            foreach($months as $month => $dates) {
                foreach($dates as $key => $date) {
                    $dates->forget($key);
                }

                $userInfo = [];
                foreach ($users as $user) {
                    $userInfo[$user->id] = [
                        'name' => $user->name,
                        'email' => $user->email,
                        'flows' => $this->flowRepository->getByUserIdAndYearMonth($user->id, $year, $month)
                    ];
                }

                $dates->put('users', $userInfo);
            }
        }

        return $years;
    }
}
