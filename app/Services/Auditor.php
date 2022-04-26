<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

interface Auditor
{
    public function addUser(User $user): self;

    public function onRoute(?string $route = null): self;

    public function onUrl(string $url): self;

    public function addAbility(string $ability, ?bool $result = null): self;

    public function addEmail(string $email): self;

    public function addModel(Model $model): self;

    public function addModels(Collection $models): self;

    public function addMail(string $email): self;

    public function addNotification(Notification $notification, string $channel): self;

    public function addProperty(string $key, mixed $value): self;

    public function finish(): void;
}
