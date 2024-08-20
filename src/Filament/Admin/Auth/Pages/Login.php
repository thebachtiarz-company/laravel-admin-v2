<?php

namespace TheBachtiarz\Admin\Filament\Admin\Auth\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as FilamentLogin;
use Filament\Support\Enums\IconPosition;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use TheBachtiarz\OAuth\Helpers\AuthUserHelper;
use TheBachtiarz\OAuth\Http\Requests\Rules\AuthIdentifierRule;
use TheBachtiarz\OAuth\Http\Requests\Rules\AuthPasswordRule;

class Login extends FilamentLogin
{
    protected string $authMethod;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->authMethod = AuthUserHelper::authMethod();
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            sprintf('data.%s', $this->authMethod) => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }

    /**
     * @return array<int|string,string|Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()->schema([
                    $this->getIdentifierFormComponent(),
                    $this->getPasswordFormComponent(),
                    $this->getRememberFormComponent(),
                ])->statePath('data'),
            ),
        ];
    }

    protected function getIdentifierFormComponent(): Component
    {
        return TextInput::make($this->authMethod)
            ->label(Str::ucfirst($this->authMethod))
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1])
            ->rules(AuthIdentifierRule::rules()[AuthIdentifierRule::IDENTIFIER]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->rules(AuthPasswordRule::rules()[AuthPasswordRule::PASSWORD]);
    }

    protected function getAuthenticateFormAction(): Action
    {
        return Action::make('authenticate')
            ->label(__('filament-panels::pages/auth/login.form.actions.authenticate.label'))
            ->icon('heroicon-c-arrow-right-end-on-rectangle')->iconPosition(IconPosition::Before)
            ->submit('authenticate');
    }

    /**
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            $this->authMethod => $data[$this->authMethod],
            'password' => $data['password'],
        ];
    }
}
