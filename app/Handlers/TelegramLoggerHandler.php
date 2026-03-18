<?php

namespace App\Handlers;

use App\Jobs\LogMessageJob;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

class TelegramLoggerHandler extends AbstractProcessingHandler {
    protected function write(LogRecord $record): void {
        $levelMarkers = [
            'DEBUG'     => '🐛 DEBUG',
            'INFO'      => 'ℹ️ INFO',
            'NOTICE'    => '📝 NOTICE',
            'WARNING'   => '⚠️ WARNING',
            'ERROR'     => '❌ ERROR',
            'CRITICAL'  => '🔥 CRITICAL',
            'ALERT'     => '🚨 ALERT',
            'EMERGENCY' => '🚩 EMERGENCY',
        ];

        $levelMarker = $levelMarkers[strtoupper($record['level_name'])] ?? 'LOG';

        $message = "<b>🕒 Time:</b> " . $record['datetime']->format('Y-m-d H:i:s') . "\n" .
            "<b>🔖 Level:</b> " . $levelMarker . "\n" .
            "<b>📝 Message:</b> " . $record['message'];

        // Если передан Exception — выводим точную инфу
        if (isset($record['context']['exception']) && $record['context']['exception'] instanceof \Throwable) {
            $e = $record['context']['exception'];
            $message .= "\n<b>📍 Exception:</b> " . get_class($e) .
                "\n<b>📄 File:</b> " . $e->getFile() .
                "\n<b>📌 Line:</b> " . $e->getLine();
        } else {
            // Если нет exception — ищем вручную через стек
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 15);
            foreach ($trace as $frame) {
                if (
                    isset($frame['file'], $frame['line'], $frame['class']) &&
                    !str_contains($frame['class'], 'Monolog')
                ) {
                    $message .= "\n<b>📄 File:</b> {$frame['file']}" .
                        "\n<b>📌 Line:</b> {$frame['line']}" .
                        "\n<b>👤 Class:</b> {$frame['class']}::{$frame['function']}()";
                    break;
                }
            }
        }

        // Добавляем context, если есть
        if (!empty($record['context'])) {
            $context = $record['context'];
            // Убираем exception, если есть, чтобы не дублировался
            if (isset($context['exception'])) {
                unset($context['exception']);
            }

            if (!empty($context)) {
                $contextDetails = json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                $message .= "\n<b>🧩 Context:</b>\n<pre>" . htmlspecialchars($contextDetails) . "</pre>";
            }
        }

        LogMessageJob::dispatch($message);
    }
}
