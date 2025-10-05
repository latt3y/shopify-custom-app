<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessWebhookJob implements ShouldQueue
{
    use Queueable;

    protected $_payload;
    protected $_topics;
    protected $_shop;

    public function __construct($payload, $topics, $shop)
    {
      $this->_payload = $payload;
      $this->_shop = $shop;
      $this->_topics = $topics;
    }

    public function handle(): void
    {
      \Log::info("Processing webhook", [
        'topic' => $this->topic,
        'shop' => $this->shopDomain,
      ]);

      match ($this->topic) {
        'products/create' => $this->handleProductCreate(),
        'products/update' => $this->handleProductUpdate(),
        'products/delete' => $this->handleProductDelete(),
        default => \Log::warning("Unknown topic: {$this->topic}"),
      };
    }

    protected function handleProductCreate()
    {
      \Log::info("Creating product", $this->payload);
    }

    protected function handleProductUpdate()
    {
      \Log::info("Updating product", $this->payload);
    }

    protected function handleProductDelete()
    {
      \Log::info("Deleting product", $this->payload);
    }
}
