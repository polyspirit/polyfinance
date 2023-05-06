<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\FlowRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\UserRepository;

class FlowController extends Controller
{
    public function __construct(
        private FlowRepository $flowRepo,
        private CurrencyRepository $currencyRepo,
        private UserRepository $userRepo,
    )
    {
    }

    public function main(): \Illuminate\Contracts\View\View
    {
        return view(
            'pages.incomes',
            [
                'title' => __('main.list_of') . ' ' . __('entities.incomes')
            ]
        );
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $isIncome = $request->is_income ?? true;

        return $this->success([
            'users' => $this->userRepo->all(),
            'flows' => $this->flowRepo->getWhere('is_income', $isIncome),
            'currencies' => $this->currencyRepo->all()
        ]);
    }

    public function getIncomes(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!isset($request->is_income) || !$request->is_income) {
            $request->merge(['is_income' => true]);
        }

        return $this->index($request);
    }

    public function getExpenses(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!isset($request->is_income) || $request->is_income) {
            $request->merge(['is_income' => false]);
        }

        return $this->index($request);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!$this->validate(
            [
                'currency_id' => 'required|numeric',
                'item' => 'required|string',
                'amount' => 'required|numeric',
                'is_income' => 'required|numeric',
            ]
        )) {
            return $this->error();
        }

        $this->addUserIdToRequest($request);

        $income = $this->flowRepo->create($request->all());

        return $this->success($income);
    }
}
