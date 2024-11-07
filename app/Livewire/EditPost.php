<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use App\Lib\HtmlToMarkdown;
use Illuminate\Support\Str;
use App\Lib\PostGenerator;

class EditPost extends Component
{
    public Post $post;

    public string $idea = '';
    public string $tone = '';
    public string $output = '';

    public function mount()
    {
        $this->post = request()->post;
        $this->tone = $this->post->tone;
        $this->idea = Str::markdown($this->post->content, ['renderer' => [ 'soft_break' => "<br />\n"]]);
        $this->output = Str::markdown($this->post->output, ['renderer' => [ 'soft_break' => "<br />\n"]]);
    }

    public function updatePost()
    {
        if ($this->post->output === $this->output) {
            return;
        }

        $converter = new HtmlToMarkdown();
        $this->post->content = $converter->convert($this->idea);
        $this->post->output = $converter->convert($this->output);
        $this->post->tone = $this->tone;
        $this->post->save();
    }

    public function regeneratePost()
    {
        $converter = new HtmlToMarkdown();
        $output = '';
        $response = (new PostGenerator())->generate(
            $converter->convert($this->idea), $this->tone
        );

        foreach ($response as $block) {
            foreach ($block->choices as $choice) {
                if ($choice->delta) {
                    $content = $choice->delta->content;
                    $output .= $content;
                    $this->stream(to: 'output', content: $content, replace: $output === '');
                }
            }
        }

        $this->post->content = $converter->convert($this->idea);
        $this->post->output = $output;
        $this->post->tone = $this->tone;
        $this->post->save();
        $this->output = Str::markdown($output, ['renderer' => [ 'soft_break' => "<br />\n"]]);
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
