<?php

namespace App\Livewire;

use Livewire\Component;
use App\Lib\PostGenerator;
use Illuminate\Support\Str;
use App\Models\Post;

class CreatePost extends Component
{
    public string $post = '';
    public string $tone = '';
    public string $output = '';

    public function generatePost()
    {
        $this->output = '';
        $response = (new PostGenerator())->generate(
            $this->post, $this->tone
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

    public function rendered_output()
    {

        $config = [
            'renderer' => [
                'soft_break' => "<br />\n",
            ],
        ];
        return Str::markdown($this->output, $config);
    }

    public function resetAll()
    {
        $this->post = '';
        $this->tone = '';
        $this->output = '';
    }

    public function render()
    {
        return view('livewire.create-post');
    }
}
