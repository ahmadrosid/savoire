<?php

namespace App\Livewire;

use Livewire\Component;
use App\Lib\PostGenerator;
use Illuminate\Support\Str;
use App\Models\Post;

class CopyCat extends Component
{
    public string $post = '';
    public string $tone = '-';
    public string $output = '';
    public string $template = '';

    public function generatePost()
    {
        $this->output = '';
        $response = (new PostGenerator())->copyStyle(
            $this->template, $this->post,
        );

        foreach ($response as $block) {
            foreach ($block->choices as $choice) {
                if ($choice->delta) {
                    $content = $choice->delta->content;
                    $this->output .= $content;
                    $this->stream(to: 'output', content: $content, replace: $this->output === '');
                }
            }
        }

        Post::create([
            'content' => $this->post,
            'tone' => $this->tone,
            'output' => $this->output,
        ]);
    }

    public function resetAll()
    {
        $this->post = '';
        $this->output = '';
    }

    public function render()
    {
        return view('livewire.copy-cat');
    }
}
