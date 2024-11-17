<?php

namespace App\Livewire;

use App\Models\Template;
use App\Models\Category;
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

    public function getTemplates()
    {
        return [
            'templates' => Template::with('category')->get(),
            'categories' => Category::all()
        ];
    }

    public function render()
    {
        $templates = htmlspecialchars(json_encode($this->getTemplates()), ENT_QUOTES, 'UTF-8');
        return <<<TEXT
        <div>
            <div 
                data-svelte="CopyCat.svelte" 
                data-csrf="{{ csrf_token() }}"
                data-templates="$templates"
            ></div>
        </div>
        TEXT;
    }
}
