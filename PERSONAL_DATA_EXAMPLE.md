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

class Member extends Aggregate {
    
    public static function register(MemberId $id, Name $name, Email $email) {
        $member = new Member;
        $member->raise(new MemberWasRegistered($id, $name, $email));
        return $member;
    }
    
    // ...
}

class MemberWasRegistered implements \EventSourcery\EventSourcing\DomainEvent {
    
    /** @var MemberId */
    public $memberId;
    /** @var Name */
    public $name;
    /** @var Email */
    public $email;
    
    public function __construct(MemberId $memberId, Name $name, Email $email) {
        $this->memberId = $memberId;
        $this->name = $name;
        $this->email = $email;
    }
}

class SendMemberWelcomeEmail implements Listener {
    public function handle(DomainEvent $event): void {
        if ($event instanceof MemberWasRegistered) {
            $registration = (MemberWasRegistered) $event;
            if ($registration->email->wasErased()) {
                return;
            }
            // send email
        }
    }
}
```