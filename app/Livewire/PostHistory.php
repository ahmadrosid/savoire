<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use Livewire\WithPagination;

class PostHistory extends Component
{
    use WithPagination;

    public function delete($id)
    {
        Post::destroy($id);
    }

    private function getPageLinks($paginator)
    {
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        
        // Always show first and last page
        $links = [
            ['label' => 'Previous', 'url' => $paginator->previousPageUrl()],
        ];

        // Show first page
        if ($currentPage > 3) {
            $links[] = ['label' => '1', 'url' => $paginator->url(1)];
            $links[] = ['label' => '...', 'url' => null];
        }

        // Show pages around current page
        for ($i = max(1, $currentPage - 1); $i <= min($lastPage, $currentPage + 1); $i++) {
            $links[] = [
                'label' => (string)$i,
                'url' => $paginator->url($i),
                'active' => $i === $currentPage
            ];
        }

        // Show last page
        if ($currentPage < $lastPage - 2) {
            $links[] = ['label' => '...', 'url' => null];
            $links[] = ['label' => (string)$lastPage, 'url' => $paginator->url($lastPage)];
        }

        $links[] = ['label' => 'Next', 'url' => $paginator->nextPageUrl()];

        return $links;
    }

    public function getPage($page)
    {
        $posts = Post::orderByDesc('created_at')
            ->paginate(10, ['*'], 'page', $page)
            ->through(function ($post) {
                $post->created_at_human = $post->created_at->diffForHumans();
                return $post;
            });

        return [
            'success' => true,
            'data' => [
                'data' => $posts->items(),
                'current_page' => $posts->currentPage(),
                'from' => $posts->firstItem(),
                'to' => $posts->lastItem(),
                'total' => $posts->total(),
                'links' => $this->getPageLinks($posts)
            ]
        ];
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function render()
    {
        $posts = Post::orderByDesc('created_at')
            ->paginate(10)
            ->through(function ($post) {
                $post->created_at_human = $post->created_at->diffForHumans();
                return $post;
            });
            
        $postsData = [
            'data' => $posts->items(),
            'current_page' => $posts->currentPage(),
            'from' => $posts->firstItem(),
            'to' => $posts->lastItem(),
            'total' => $posts->total(),
            'links' => $this->getPageLinks($posts)
        ];

        $postsJson = htmlspecialchars(json_encode($postsData), ENT_QUOTES, 'UTF-8');
        
        return <<<TEXT
        <div>
            <div 
                data-svelte="PostHistory.svelte" 
                data-csrf="{{ csrf_token() }}"
                data-posts="$postsJson"
            ></div>
        </div>
        TEXT;
    }
}
