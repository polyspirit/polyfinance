<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\FlowRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\UserRepository;
use App\Repositories\TagRepository;
use App\Classes\Dates;

class FlowController extends Controller
{
    public function __construct(
        private FlowRepository $flowRepo,
        private CurrencyRepository $currencyRepo,
        private UserRepository $userRepo,
        private TagRepository $tagRepo,
        private Dates $datesClass,
    ) {
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
            'dates' => $this->datesClass->getDatedFlows($request->type),
            'currencies' => $this->currencyRepo->all(),
            'tags' => $this->tagRepo->all(),
            'defaultCurrencyId' => $this->currencyRepo->getDefaultCurrency()->id
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

    public function destroy(Request $request, int|string $id): \Illuminate\Http\JsonResponse
    {
        $result = $this->flowRepo->delete((int) $id);

        if ($result) {
            return $this->success(['message' => 'Flow ' . $id . ' deleted']);
        } else {
            return $this->error('Flow ' . $id . ' didn\'t deleted');
        }
    }
}
