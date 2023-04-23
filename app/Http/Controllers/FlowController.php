<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\Interfaces\FlowRepositoryInterface;

class FlowController extends Controller
{
    private FlowRepositoryInterface $flowRepo;

    public function __construct(FlowRepositoryInterface $flowRepo)
    {
        $this->flowRepo = $flowRepo;
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

    public function index(): \Illuminate\Http\JsonResponse
    {
        return $this->success($this->flowRepo->getAll());
    }

    public function store(Request $request)
    {
        if (!$this->validate(
            [
                'item' => 'required|string',
                'amount' => 'required|numeric',
                'is_income' => 'required|numeric'
            ]
        )) {
            return $this->error();
        }

        $income = $this->flowRepo->create($request->all());

        return $this->success(['id' => $income->id]);
    }
}
