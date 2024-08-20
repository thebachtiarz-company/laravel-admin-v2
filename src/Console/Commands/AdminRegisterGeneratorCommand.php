<?php

namespace TheBachtiarz\Admin\Console\Commands;

use Illuminate\Support\Carbon;
use TheBachtiarz\Base\Http\Console\Commands\AbstractCommand;
use TheBachtiarz\OAuth\Helpers\AuthUserHelper;
use TheBachtiarz\OAuth\Interfaces\Repositories\AuthUserRepositoryInterface;
use TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface;

class AdminRegisterGeneratorCommand extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thebachtiarz:admin:generate-default';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate default admin credential(s)';

    /**
     * Constructor
     */
    public function __construct(
        protected AuthUserRepositoryInterface $authUserRepository,
    ) {
        $this->commandTitle = 'Generate Default Admin';

        parent::__construct();
    }

    protected function commandProcess(): bool
    {
        /** @var array<string,string> $authorizedUsers */
        $authorizedUsers = config(key: 'tbadmin.filament_admin_identifiers', default: []);

        /** @var string $adminDefaultPassword */
        $adminDefaultPassword = config(key: 'tbadmin.filament_admin_password', default: '&Secret67890');

        foreach ($authorizedUsers as $email => $username) {
            $user = $this->authUserRepository->throwIfNullEntity(false)->getByIdentifier(identifier: ${AuthUserHelper::authMethod()});

            if (!$user) {
                $user = app(AuthUserInterface::class);
                $user->setEmailVerifiedAt(Carbon::now());
            }

            $user->setIdentifier(identifier: ${AuthUserHelper::authMethod()})->setPassword(password: $adminDefaultPassword);

            $this->authUserRepository->createOrUpdate(model: $user);
        }

        return true;
    }
}
