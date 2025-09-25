<?php

namespace App\Livewire\Public;

use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;

class NewsIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $items = News::active()
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('livewire.public.news-index', compact('items'));
    }
}
