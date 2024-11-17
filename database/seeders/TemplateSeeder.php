<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Template;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Create Categories
        $categories = [
            [
                'name' => 'Technical Writing',
                'slug' => 'technical-writing',
                'description' => 'Templates for technical blog posts and documentation'
            ],
            [
                'name' => 'Personal Stories',
                'slug' => 'personal-stories',
                'description' => 'Templates for sharing personal experiences and journeys'
            ]
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        // Create Templates
        $templates = [
            [
                'name' => 'Technical Tutorial',
                'avatar' => 'https://api.dicebear.com/7.x/shapes/svg?seed=tutorial',
                'content' => "# How I Built X with Y: A Step-by-Step Guide

Recently, I had the opportunity to build [Project Name] using [Technology]. In this post, I'll walk you through my journey, the challenges I faced, and the solutions I discovered.

## The Problem

[Describe the problem you were trying to solve]

## The Solution

Here's how I approached it:

1. First, I...
2. Then, I...
3. Finally, I...

## Key Learnings

- Learning 1
- Learning 2
- Learning 3

## Conclusion

[Your concluding thoughts]",
                'total_reactions' => 150,
                'total_comments' => 25,
                'total_reposts' => 45,
                'category_id' => 1
            ],
            [
                'name' => 'Career Journey',
                'avatar' => 'https://api.dicebear.com/7.x/shapes/svg?seed=journey',
                'content' => "# My Journey from X to Y: Lessons Learned

After [X] years of working as a [Previous Role], I made the transition to [New Role]. Here's my story and what I learned along the way.

## The Beginning

[Your starting point]

## The Transition

Key milestones:
- Milestone 1
- Milestone 2
- Milestone 3

## Challenges Faced

1. Challenge 1
2. Challenge 2
3. Challenge 3

## Advice for Others

[Your advice]

## Looking Forward

[Your future plans]",
                'total_reactions' => 200,
                'total_comments' => 35,
                'total_reposts' => 60,
                'category_id' => 2
            ],
            [
                'name' => 'Project Retrospective',
                'avatar' => 'https://api.dicebear.com/7.x/shapes/svg?seed=retrospective',
                'content' => "# Project Retrospective: [Project Name]

A comprehensive look back at our recent project, analyzing what went well, what could be improved, and key takeaways for future initiatives.

## Project Overview
- Duration: [Time Period]
- Team Size: [Number]
- Key Objectives: [List main goals]

## What Went Well
1. Success Point 1
2. Success Point 2
3. Success Point 3

## Challenges Encountered
- Challenge 1: [Description and how it was addressed]
- Challenge 2: [Description and how it was addressed]
- Challenge 3: [Description and how it was addressed]

## Lessons Learned
1. Key Learning 1
2. Key Learning 2
3. Key Learning 3

## Future Recommendations
- Recommendation 1
- Recommendation 2
- Recommendation 3

## Conclusion
[Summary of main points and final thoughts]",
                'total_reactions' => 175,
                'total_comments' => 30,
                'total_reposts' => 50,
                'category_id' => 1
            ]
        ];

        foreach ($templates as $template) {
            Template::create($template);
        }
    }
}
