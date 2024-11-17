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
You are tasked with writing a compelling LinkedIn post based on user input. Your goal is to create an engaging post that adheres to best practices for LinkedIn content while incorporating the user's specific field and content.

Here are the inputs provided by the user:

<content>
{{CONTENT}}
</content>

Key elements of this writing style and create some rules to help you emulate it.

1. Keep it conversational
This style feels like someone's talking directly to you. It's casual, friendly, and relatable. 

Rule: Write like you're having a chat with a friend. Use contractions, simple language, and even throw in some colloquialisms.

2. Short and punchy
Notice how the sentences and paragraphs are brief? This makes the post easy to read and digest.

Rule: Aim for sentences under 15 words. Break up longer thoughts into multiple sentences. Use single-sentence paragraphs for emphasis.

3. Use repetition strategically
The post repeats "I should've" several times to drive home a point. This creates rhythm and emphasis.

Rule: Find opportunities to repeat key phrases or sentence structures. But don't overdo it - 3-5 repetitions max.

4. Address the reader directly
The post uses "you" and "your" frequently, making the reader feel personally addressed.

Rule: Use second-person pronouns ("you," "your") to speak directly to your audience.

5. Share personal experiences
The writer mentions their 15 years of experience, adding credibility to their advice.

Rule: Sprinkle in relevant personal anecdotes or experiences to build trust and relatability.

6. Use rhetorical questions
The post starts with an implied question about LinkedIn noise. This engages the reader's thoughts.

Rule: Pose questions that your audience is likely thinking about. This shows you understand their concerns.

7. Embrace white space
Notice how the post isn't dense? There's lots of space between ideas.

Rule: Use short paragraphs and line breaks liberally. Don't be afraid of one-sentence paragraphs.

8. Include a call-to-action
The post ends with "Now go... Listen." This prompts the reader to do something.

Rule: Always include a clear, simple action for your reader to take after reading.

9. Encourage engagement
The post ends with a question, inviting readers to comment.

Rule: End your posts with an open-ended question or prompt to encourage comments and discussion.

10. Use simple, powerful statements
"You decide your when, how, and why" is a straightforward but impactful line.

Rule: Craft concise, memorable statements that encapsulate your main message.

11. Show empathy
The post acknowledges common frustrations and insecurities, showing understanding.

Rule: Address your audience's pain points directly. Show that you understand and relate to their challenges.

Remember, the goal here is to sound authentic and relatable while delivering valuable insights. This style works well because it feels like advice from a knowledgeable friend rather than a formal lecture.

When you're writing, imagine you're sitting across from someone at a coffee shop, sharing your thoughts and experiences. That mindset will help you nail this conversational, engaging style.

Don't use hashtags!

Your final output should be formatted as follows:

<linkedin_post>
[Insert your crafted LinkedIn post here, including call-to-action]
</linkedin_post>

Remember to tailor the post to the specific field and incorporate the provided content throughout.
PROMPT;
    }

    public function generate($content)
    {
        $prompt = str_replace(
            ['{{CONTENT}}'],
            [$content],
            $this->prompt
        );
        return AnthropicAI::chat()->createStreamed([
            'model' => $this->model,
            'temperature' => 0.2,
            'max_tokens' => 4096,
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

    public function preparePromptAnalyze($template)
    {
        $prompt = "Please analyze this LinkedIn post.\n\n" . $template;
        return [
            [
                'role' => 'user',
                'content' => $prompt
            ],
        ];
    }

    public function analyzeStyle($messages)
    {
        return AnthropicAI::chat()->createStreamed([
            'model' => $this->model,
            'temperature' => 0.2,
            'max_tokens' => 4096,
            'messages' => $messages,
        ]);
    }

    public function copyStyle($messages, $content)
    {
        $prompt = str_replace(
            ['{{CONTENT}}'],
            [$content],
            "Now, you will create a new post on the following topic:
<content>
{{CONTENT}}
</content>

Output the post in to tag <linkedin_post>.
"
        );
        $messages = array_merge($messages, [
            [
                'role' => 'user',
                'content' => $prompt
            ],
            [
                'role' => 'assistant',
                'content' => '<linkedin_post>'
            ],
        ]);
        return AnthropicAI::chat()->createStreamed([
            'model' => $this->model,
            'temperature' => 0.2,
            'max_tokens' => 4096,
            'stop_sequences' => ["</linkedin_post>"],
            'messages' => $messages,
        ]);
    }
}