<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

class PostHistory extends Component
{
    public function delete($id)
    {
        Post::destroy($id);
    }

    public function render()
    {
        return view('livewire.post-history', [
            'posts' => Post::orderByDesc('created_at')->paginate(10),
        ]);
    }
}
