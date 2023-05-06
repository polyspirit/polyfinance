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
        return $this->success([
            'users' => $this->userRepo->all(),
            'flows' => $this->flowRepo->getWhere('type', $request->type),
            'currencies' => $this->currencyRepo->all()
        ]);
    }

    public function getIncomes(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->merge(['type' => 'income']);

        return $this->index($request);
    }

    public function getExpenses(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->merge(['type' => 'expense']);

        return $this->index($request);
    }

    public function getSpendings(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->merge(['type' => 'spending']);

        return $this->index($request);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!$this->validate(
            [
                'currency_id' => 'required|numeric',
                'item' => 'required|string',
                'amount' => 'required|numeric',
                'type' => 'string',
            ]
        )) {
            return $this->error();
        }

        $this->addUserIdToRequest($request);

        $income = $this->flowRepo->create($request->all());

        return $this->success($income);
    }
}
