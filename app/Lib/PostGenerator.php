<?php

namespace App\Lib;

use Ahmadrosid\Laravel\Anthropic\AnthropicAI;

class PostGenerator
{
    public string $prompt;
    public string $prompt_copy_cat;
    public string $model = 'claude-3-5-sonnet-20241022';

    public function __construct()
    {
        $this->prompt = <<<'PROMPT'
You are tasked with writing a compelling LinkedIn post based on user input. Your goal is to create an engaging post that adheres to best practices for LinkedIn content while incorporating the user's specific field, content, and desired tone.

Here are the inputs provided by the user:

<content>
{{CONTENT}}
</content>

<tone>
{{TONE}}
</tone>
4 important components that must be in your writing on LinkedIn:

1. Title (Hook)
2. Headline (Re-Hook)
3. Material
4. CTA

## Title (Hook)
This section is the most important section, here you are tasked with making people who are scrolling on LinkedIn stop. Make sure the title is short, no more than 5 words.

## Headline (Re-Hook)
After people stop because of the title, the next step is to make sure people can't resist reading your writing.

## Material
This is the core of your writing. Fill it with useful content. Structure your writing with a 1-2-1-2-1 format so it's easy to read.

## CTA (Call to Action)
End strongly! Invite readers to do something. You can ask for comments, share, or follow.

When crafting the post, keep these tips in mind:
- Use simple, direct language
- Address the reader directly when appropriate
- Use emojis sparingly, if at all
- Avoid jargon unless it's common in the specified field
- Don't put any hashtags
- Don't use any emojis

Your final output should be formatted as follows:

<linkedin_post>
[Insert your crafted LinkedIn post here, including call-to-action]
</linkedin_post>

Remember to tailor the post to the specific field, incorporate the provided content, and maintain the desired tone throughout.
PROMPT;

        $this->prompt_copy_cat = <<<'PROMPT'
You are tasked with analyzing the writing style and tone of a LinkedIn post and then creating a similar post on a different topic. Follow these steps carefully:

1. First, read and analyze the following LinkedIn post:

<template>
{{TEMPLATE_POST}}
</templte>

2. Analyze the writing style and tone of the post. Consider elements such as:
   - Length of sentences and paragraphs
   - Use of punctuation
   - Formal or informal language
   - Use of personal anecdotes or examples
   - Inclusion of questions or calls to action
   - Overall structure (e.g., introduction, body, conclusion)
   - Any unique stylistic elements

3. Now, you will create a new post on the following topic:

<content>
{{CONTENT}}
</content>

4. Write a new LinkedIn post that follows the writing style and tone you analyzed from the original post. Apply the stylistic elements you observed to this new topic.

5. Important: Do not copy any specific content or examples from the original post. Your goal is to emulate the style and tone, not the content. The new post should be entirely original and focused on the new topic.

6. Present your new LinkedIn post within <linkedin_post> tags.

Please keep this in mind as you work on your new post:
- Don't put any hashtags

Remember, the key is to capture the essence of the writing style and tone, not to replicate the content. Your new post should feel similar in style to the original, but be completely unique in its content and tailored to the new topic.
PROMPT;
    }

    public function generate($content, $tone)
    {
        $prompt = str_replace(
            ['{{CONTENT}}', '{{TONE}}'],
            [$content, $tone],
            $this->prompt
        );
        return AnthropicAI::chat()->createStreamed([
            'model' => $this->model,
            'temperature' => 0.2,
            'max_tokens' => 1024,
            'stop_sequences' => ["</linkedin_post>"],
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ],
                [
                    'role' => 'assistant',
                    'content' => '<linkedin_post>'
                ],
            ],
        ]);
    }

    public function copyStyle($template, $content)
    {
        $prompt = str_replace(
            ['{{CONTENT}}', '{{TEMPLATE}}'],
            [$content, $template],
            $this->prompt_copy_cat
        );
        return AnthropicAI::chat()->createStreamed([
            'model' => $this->model,
            'temperature' => 0.2,
            'max_tokens' => 1024,
            'stop_sequences' => ["</linkedin_post>"],
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ],
                [
                    'role' => 'assistant',
                    'content' => '<linkedin_post>'
                ],
            ],
        ]);
    }
}