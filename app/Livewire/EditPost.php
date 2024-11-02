<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use App\Lib\HtmlToMarkdown;
use Illuminate\Support\Str;

class EditPost extends Component
{
    public Post $post;

    public string $output = '';

    public function mount()
    {
        $this->post = request()->post;
        $this->output = Str::markdown($this->post->output, ['renderer' => [ 'soft_break' => "<br />\n"]]);
    }

    public function updatePost()
    {
        if ($this->post->output === $this->output) {
            return;
        }

        $converter = new HtmlToMarkdown();
        $this->post->output = $converter->convert($this->output);
        $this->post->save();
    }

    public function delete()
    {
        $this->post->delete();
        return redirect()->to('/');
    }

    public function render()
    {
        return view('livewire.edit-post');
    }
}
