<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TagRepository;

class TagController extends Controller
{
    public function __construct(
        private TagRepository $tagRepo
    )
    {
    }

    public function main(): \Illuminate\Contracts\View\View
    {
        return view(
            'pages.tags',
            [
                'title' => __('main.list_of') . ' ' . __('entities.tags')
            ]
        );
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->success([
            'tags' => $this->tagRepo->all()
        ]);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!$this->validate(
            [
                'name' => 'required|string',
                'color' => 'string',
                'comment' => 'string|nullable'
            ]
        )) {
            return $this->error();
        }

        $tag = $this->tagRepo->create($request->all());

        return $this->success($tag);
    }
}
