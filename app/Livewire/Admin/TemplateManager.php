<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Template;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TemplateManager extends Component
{
    use WithFileUploads;

    public $name;
    public $content;
    public $avatar;
    public $category_id;
    public $newCategoryName;
    public $newCategoryDescription;
    public $total_reactions = 0;
    public $total_comments = 0;
    public $total_reposts = 0;
    public $jsonContent;

    protected $rules = [
        'name' => 'required|min:3',
        'content' => 'required',
        'avatar' => 'nullable|image|max:1024',
        'category_id' => 'required|exists:categories,id',
        'total_reactions' => 'required|integer|min:0',
        'total_comments' => 'required|integer|min:0',
        'total_reposts' => 'required|integer|min:0',
        'jsonContent' => 'required',
    ];

    public function createCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|min:3|unique:categories,name',
            'newCategoryDescription' => 'required',
        ]);

        $category = Category::create([
            'name' => $this->newCategoryName,
            'slug' => Str::slug($this->newCategoryName),
            'description' => $this->newCategoryDescription,
        ]);

        $this->category_id = $category->id;
        $this->reset(['newCategoryName', 'newCategoryDescription']);
        $this->dispatch('category-created');
    }

    public function createTemplate()
    {
        $this->parseJson();
        
        $template = Template::create([
            'name' => $this->name,
            'content' => $this->content,
            'avatar' => $this->avatar,
            'category_id' => $this->category_id,
            'total_reactions' => $this->total_reactions,
            'total_comments' => $this->total_comments,
            'total_reposts' => $this->total_reposts,
        ]);

        $this->reset(['name', 'content', 'avatar', 'jsonContent', 'total_reactions', 'total_comments', 'total_reposts']);
        $this->dispatch('template-created');
    }

    public function parseJson()
    {
        $this->validate([
            'jsonContent' => 'required'
        ]);

        try {
            $data = json_decode($this->jsonContent, true, 512, JSON_THROW_ON_ERROR);
            
            // Validate required fields
            $requiredFields = ['name', 'avatar', 'content', 'total_reaction', 'total_comment', 'total_repost'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    $this->addError('jsonContent', "Missing required field: {$field}");
                    return;
                }
            }
            
            // Map the values to component properties
            $this->name = $data['name'];
            $this->avatar = $data['avatar'];
            $this->content = $data['content'];
            $this->total_reactions = (int) str_replace(',', '', $data['total_reaction']);
            $this->total_comments = (int) str_replace(',', '', $data['total_comment']);
            $this->total_reposts = (int) str_replace(',', '', $data['total_repost']);
            
            $this->dispatch('json-parsed');
        } catch (\JsonException $e) {
            $this->addError('jsonContent', 'Invalid JSON format: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.template-manager', [
            'categories' => Category::all(),
            'templates' => Template::with('category')->latest()->get(),
        ]);
    }

    public function deleteTemplate(Template $template)
    {
        if ($template->avatar && !Str::startsWith($template->avatar, 'http')) {
            Storage::disk('public')->delete($template->avatar);
        }
        
        $template->delete();
        $this->dispatch('template-deleted');
    }
}
