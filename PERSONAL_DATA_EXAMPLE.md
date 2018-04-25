# Personal Data Example #

This is an example of the lifecycle of protected personal data.

```php
<?php
class UiController {
    public function runCommand($firstName, $lastName, $email) {
        $this->commandBus->execute(
            new RegisterMember(
                Uuid::uuid4()->toString(),
                $firstName,
                $lastName,
                $email
            )
        );
    }
}

class RegisterMember implements Command {
    
    /** @var MemberId */
    private $memberId;
    /** @var Name */
    private $name;
    /** @var Email */
    private $email;
    
    public function __construct($memberId, $firstName, $lastName, $email) {
        $this->memberId = MemberId::fromString($memberId);
        $personalKey = PersonalKey::fromId($this->memberId);        
        $this->name = new Name($personalKey, $firstName, $lastName);
        $this->email = new Email($personalKey, $email);
    }
    
    public function execute(EventStore $events) {
        $member = Member::register($this->memberId, $this->name, $this->email);
        $events->storeStream($member->flushEvents());
    }
}
```