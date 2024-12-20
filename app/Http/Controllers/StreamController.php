<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\PostGenerator;
use App\Models\Post;
use App\Http\Sse\StreamEvent;

class StreamController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->stream(function () use ($request) {
            try {
                $input = $request->validate([
                    'text' => 'required|string',
                    'documents' => 'nullable|array',
                    'mode' => 'required|string|in:generate,copy,format,analyze',
                    'analysis' => 'nullable|string',
                ]);
                logger()->info('Start streaming: ', [$input]);
                $this->stream($input);
            } catch (\Exception $e) {
                report($e);
                logger()->error($e);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    public function stream($input)
    {
        $generator = new PostGenerator();
        $response = null;
        $output = '';

        switch ($input['mode']) {
            case 'analyze':
                $messages = $generator->preparePromptAnalyze($input['text']);
                $response = $generator->analyzeStyle($messages);
                break;
            case 'copy':
                $response = $generator->copyStyle([
                    ['role' => 'assistant', 'content' => $input['analysis']],
                ], $input['text']);
                break;
            default:
                $response = $generator->generate($input['text']);
                break;
        }

        foreach ($response as $block) {
            foreach ($block->choices as $choice) {
                if ($choice->delta) {
                    $content = $choice->delta->content;
                    $output .= $content;
                    StreamEvent::token($content)->emit();
                }
            }
        }

        if ($input['mode'] !== 'analyze') {
            Post::create([
                'content' => $input['text'],
                'output' => $output,
            ]);
        }
    }
}
