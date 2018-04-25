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

class Email implements SerializablePersonalDataValue {
    
    /** @var PersonalKey */
    private $personalKey;        
    /** @var string */
    private $address;

    private function __construct(PersonalKey $personalKey, string $address) {
        $this->personalKey = $personalKey;
        $this->address = $address;
    }
    
    public static function fromString(PersonalKey $personalKey, string $address) {
        return new static($personalKey, $address);
    }

    public function serialize(): string {
        return json_encode([
            'personalKey' => $this->personalKey->toString(),
            'address' => $this->address,
        ]);
    }

    public static function deserialize(string $string) {
        $values = json_decode($string);
        return new static(PersonalKey::fromString($values->personalKey), $values->address);
    }
    
    public static function erasedState(PersonalKey $personalKey) {
        $email = static($personalKey, "awareness-account@mycompany.com");
        $email->erased = true;
        return $email;
    }
    
    public function wasErased() {
        return $this->erased;
    }
    
    private $erased = false;
}

class Member extends Aggregate {
    
    public static function register(MemberId $id, Name $name, Email $email) {
        $member = new Member;
        $member->raise(new MemberWasRegistered($id, $name, $email));
        return $member;
    }
    
    // ...
}

class MemberWasRegistered implements DomainEvent {
    
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