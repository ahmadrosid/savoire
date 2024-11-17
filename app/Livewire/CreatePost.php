<?php

namespace App\Livewire;

use Livewire\Component;

class CreatePost extends Component
{
    public function render()
    {
        return <<<HTML
        <div>
            <div data-svelte="CreatePost.svelte" data-csrf="{{ csrf_token() }}"></div>
        </div>
        HTML;
    }
}
