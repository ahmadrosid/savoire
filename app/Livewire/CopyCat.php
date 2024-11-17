<?php

namespace App\Livewire;

use Livewire\Component;

class CopyCat extends Component
{
    public string $post = '';
    public string $output = '';
    public string $template = '';

    public function performCopyStyle($data)
    {
        $this->post = $data['post'];
        $this->output = $data['output'];
    }

    public function resetAll()
    {
        $this->post = '';
        $this->output = '';
        $this->template = '';
    }

    public function render()
    {
        return <<<TEXT
        <div>
            <div 
                data-svelte="CopyCat.svelte" 
                data-csrf="{{ csrf_token() }}"
            ></div>
        </div>
        TEXT;
    }
}
