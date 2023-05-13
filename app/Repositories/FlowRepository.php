<?php

namespace App\Repositories;

use App\Repositories\MainRepository;
use App\Models\Flow;
use Illuminate\Support\Carbon;

class FlowRepository extends MainRepository
{
    protected string $model = Flow::class;

    public function getDatesByType(string $type): \Illuminate\Support\Collection
    {
        return $this->getWhere('type', $type, ['created_at'])->groupBy(['year', 'month']);
    }

    public function getByUserIdAndYearMonth(int $userId, string $year, string $month): \Illuminate\Database\Eloquent\Collection
    {
        $firstDateOfMonth = $year . '-' . $month . '-01';
        $lastDateOfMonth = Carbon::createFromDate($firstDateOfMonth)->endOfMonth()->toDateString();

        return Flow::where('user_id', $userId)
            ->where('created_at', '>=', $firstDateOfMonth)
            ->where('created_at', '<=',  $lastDateOfMonth)
            ->get();
    }
}
