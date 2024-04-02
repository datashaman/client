<?php

declare(strict_types=1);

namespace OpenAI\Responses\Threads\Messages\Delta;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Responses\Threads\Runs\ThreadRunResponseLastError;
use OpenAI\Responses\Threads\Runs\Steps\ThreadRunStepResponseToolCallsStepDetails;
use OpenAI\Responses\Threads\Runs\Steps\ThreadRunStepResponseMessageCreationStepDetails;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @implements ResponseContract<array{role: string, content: array<int, array{type: 'image_file', image_file: array{file_id: string}}|array{type: 'text', text: array{value: string, annotations: array<int, array{type: 'file_citation', text: string, file_citation: array{file_id: string, quote: string}, start_index: int, end_index: int}|array{type: 'file_path', text: string, file_path: array{file_id: string}, start_index: int, end_index: int}>}}>, file_ids: array<int, string>}>
 */
final class ThreadMessageDeltaObject implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{role: string, content: array<int, array{type: 'image_file', image_file: array{file_id: string}}|array{type: 'text', text: array{value: string, annotations: array<int, array{type: 'file_citation', text: string, file_citation: array{file_id: string, quote: string}, start_index: int, end_index: int}|array{type: 'file_path', text: string, file_path: array{file_id: string}, start_index: int, end_index: int}>}}>, file_ids: array<int, string>}>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  array<int, ThreadMessageResponseContentImageFileObject|ThreadMessageDeltaResponseContentTextObject>  $content
     * @param  array<int, string>  $file_ids
     */
    private function __construct(
        public string $role,
        public array $content,
        public array $fileIds,
    ) {
    }

    /**
     * Acts as static factory, and returns a new Response instance.
     *
     * @param  array{role: string, content: array<int, array{type: 'image_file', image_file: array{file_id: string}}|array{type: 'text', text: array{value: string, annotations: array<int, array{type: 'file_citation', text: string, file_citation: array{file_id: string, quote: string}, start_index: int, end_index: int}|array{type: 'file_path', text: string, file_path: array{file_id: string}, start_index: int, end_index: int}>}}>, file_ids: array<int, string>}  $attributes
     */
    public static function from(array $attributes): self
    {
        $content = array_map(
            fn (array $content): \OpenAI\Responses\Threads\Messages\Delta\ThreadMessageDeltaResponseContentTextObject|\OpenAI\Responses\Threads\Messages\Delta\ThreadMessageDeltaResponseContentImageFileObject => match ($content['type']) {
                'text' => ThreadMessageDeltaResponseContentTextObject::from($content),
                'image_file' => ThreadMessageDeltaResponseContentImageFileObject::from($content),
            },
            $attributes['content'],
        );

        return new self(
            $attributes['role'],
            $content,
            $attributes['file_ids'],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'role' => $this->role,
            'content' => $this->content,
            'file_ids' => $this->fileIds,
        ];
    }
}