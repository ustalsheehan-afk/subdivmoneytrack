<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class SmsTemplateService
{
    private const FILE_PATH = 'sms_templates.json';

    private const DEFAULTS = [
        'dues_reminder' => 'Hello {resident_name}, this is a reminder that your dues for {due_title} amounting to PHP {amount} is due on {due_date}. Please settle on time. {payment_link}',
        'penalty_notice' => 'Hello {resident_name}, you have a recorded penalty amounting to PHP {amount} for {penalty_reason}. Please settle this as soon as possible. {payment_link}',
    ];

    public function all(): array
    {
        if (!Storage::exists(self::FILE_PATH)) {
            return self::DEFAULTS;
        }

        $raw = Storage::get(self::FILE_PATH);
        $decoded = json_decode($raw, true);

        if (!is_array($decoded)) {
            return self::DEFAULTS;
        }

        return array_merge(self::DEFAULTS, $decoded);
    }

    public function save(array $templates): void
    {
        $current = $this->all();

        $next = [
            'dues_reminder' => trim((string) ($templates['dues_reminder'] ?? $current['dues_reminder'])),
            'penalty_notice' => trim((string) ($templates['penalty_notice'] ?? $current['penalty_notice'])),
        ];

        Storage::put(self::FILE_PATH, json_encode($next, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function render(string $key, array $data): string
    {
        $templates = $this->all();
        $template = (string) ($templates[$key] ?? '');

        foreach ($data as $k => $v) {
            $template = str_replace('{' . $k . '}', (string) $v, $template);
        }

        return preg_replace('/\{[a-zA-Z0-9_]+\}/', '', $template) ?? $template;
    }
}
