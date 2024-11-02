<?php

namespace App\Livewire;

use Livewire\Component;
use App\Lib\PostGenerator;
use Illuminate\Support\Str;
use App\Models\Post;
use Livewire\Attributes\On;

class CopyCat extends Component
{
    public string $post = '';
    public string $tone = '-';
    public string $output = '';
    public string $template = '';

    public array $messages = [];

    public function generatePost()
    {
        $this->output = '';
        $messages = (new PostGenerator())->preparePromptAnalyze(
            $this->template
        );
        $response = (new PostGenerator())->analyzeStyle($messages);
    
        $output = '';
        foreach ($response as $block) {
            foreach ($block->choices as $choice) {
                if ($choice->delta) {
                    $content = $choice->delta->content;
                    $output .= $content;
                    $this->stream(to: 'output', content: $content, replace: $output === '');
                }
            }
        }

        $this->messages[] = [
            'role' => 'assistant',
            'content' => $output
        ];

        $this->dispatch('performCopyStyle');
    }

    #[On('performCopyStyle')]
    public function performCopyStyle()
    {
        $this->output = '';
        $response = (new PostGenerator())->copyStyle(
            $this->messages, $this->post,
        );

        logger()->info($this->messages);
    
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
