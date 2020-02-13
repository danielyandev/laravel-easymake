### Event

#### Make new event with defined channel and name
```
php artisan easymake:event TestEvent --channel="presence" --channelName="easy-channel"
```

Event's broadcastOn method
```php
public function broadcastOn()
{
    return new PresenceChannel('easy-channel');
}
```

#### Make new event with ShouldBroadcast interface implementation
```
php artisan easymake:event TestEvent --shb
```

Generated event
```php
class TestEvent implements ShouldBroadcast
{
    ...
}
```

#### Available values for --channel option
- private
- presence
- channel

###### Note: Values are not case sensitive
