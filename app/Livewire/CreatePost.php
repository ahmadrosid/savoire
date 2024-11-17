<?php

namespace App\Livewire;

use Livewire\Component;
use App\Lib\PostGenerator;
use App\Lib\HtmlToMarkdown;
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
            'content' => (new HtmlToMarkdown())->convert($this->post),
            'tone' => $this->tone,
            'output' => $this->output,
        ]);
    }

    public function resetAll()
    {
        $this->post = '';
        $this->tone = '';
        $this->output = '';
    }

    public function render()
    {
        return <<<HTML
        <div>
            <div data-svelte="CreatePost.svelte"></div>
        </div>
        HTML;
    }
}
