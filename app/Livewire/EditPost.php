<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class EditPost extends Component
{
    public Post $post;
    public $idea;
    public $output;

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function updatePost(array $data)
    {
        $this->post->content = $data['idea'];
        $this->post->output = $data['output'];
        $this->post->save();
    }

    public function delete()
    {
        $this->post->delete();
        return redirect()->to('/');
    }

    public function render()
    {
        $post = htmlspecialchars(json_encode($this->post), ENT_QUOTES, 'UTF-8');
        return <<<TEXT
        <div>
            <div 
                data-svelte="EditPost.svelte" 
                data-csrf="{{ csrf_token() }}"
                data-post="$post"
            ></div>
        </div>
        TEXT;
    }
}
