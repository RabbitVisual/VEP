<?php

declare(strict_types=1);

namespace VertexSolutions\Community\Http\Controllers;

use App\Http\Controllers\Controller;
use VertexSolutions\Community\Services\FeedAggregatorService;

class FeedController extends Controller
{
    public function __construct(
        private FeedAggregatorService $feedAggregator
    ) {}

    public function index()
    {
        $page = (int) request('page', 1);
        $feed = $this->feedAggregator->getPaginatedFeed($page);

        return view('community::feed', [
            'feed' => $feed,
        ]);
    }
}
