<?php

namespace App\Lib;

use Ahmadrosid\Laravel\Anthropic\AnthropicAI;

class PostGenerator
{
    public string $prompt;
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
}