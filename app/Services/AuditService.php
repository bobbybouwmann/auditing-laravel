<?php

namespace App\Services;

use App\Models\Audit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class AuditService implements Auditor
{
    private ?float $requestTime = null;
    private ?User $user = null;
    private string $url = '';
    private ?string $route = 'unknown';
    private Collection $abilities;
    private Collection $emails;
    private Collection $models;
    private Collection $notifications;
    private Collection $properties;

    public function __construct()
    {
        $this->abilities = collect();
        $this->emails = collect();
        $this->models = collect();
        $this->notifications = collect();
        $this->properties = collect();
    }

    public function addUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function onRoute(?string $route = null): self
    {
        if ($route) {
            $this->route = $route;
        }

        return $this;
    }

    public function onUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function addAbility(string $ability, ?bool $result = null): self
    {
        $this->abilities->put($ability, $result);

        return $this;
    }

    public function addEmail(string $email): self
    {
        $this->abilities->push($email);

        return $this;
    }

    public function addModel(Model $model): self
    {
        if (! $this->models->has($model::class)) {
            $this->models->put($model::class, collect());
        }

        /** @var Collection $models */
        $models = $this->models->get($model::class);
        $models->add($model);

        $this->models[$model::class][] = $models->unique('id');

        return $this;
    }

    public function addModels(Collection $models): self
    {
        $models->each(function (Model $model) {
            $this->addModel($model);
        });

        return $this;
    }

    public function addMail(string $email): self
    {
        $this->emails->push($email);

        return $this;
    }

    public function addNotification(Notification $notification, string $channel): self
    {
        $this->notifications->put($notification->id, [
            'notification' => $notification::class,
            'channel' => $channel,
        ]);

        return $this;
    }

    public function addProperty(string $key, mixed $value): self
    {
        $this->properties->put($key, $value);

        return $this;
    }

    public function finish(): void
    {
        $this->requestTime = microtime(true) - LARAVEL_START;

        $this->toDatabase();
    }

    private function toDatabase(): void
    {
        $audit = new Audit();
        $audit->url = $this->url;
        $audit->datetime = Carbon::now();
        $audit->request_time = $this->requestTime;
        $audit->route = $this->route;
        $audit->abilities = $this->abilities;
        $audit->emails = $this->emails;
        $audit->models = $this->models;
        $audit->notifications = $this->notifications;
        $audit->properties = $this->properties;

        if ($this->user) {
            $audit->user()->associate($this->user);
        }

        $audit->save();
    }
}
