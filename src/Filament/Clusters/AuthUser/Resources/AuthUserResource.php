<?php

namespace TheBachtiarz\Admin\Filament\Clusters\AuthUser\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use TheBachtiarz\Admin\Filament\Clusters\AuthUser\Resources\AuthUserResource\Pages;
use TheBachtiarz\Admin\Traits\Filament\Resources\HasAuthorizedResource;
use TheBachtiarz\OAuth\Http\Requests\Rules\AuthEmailRule;
use TheBachtiarz\OAuth\Http\Requests\Rules\AuthPasswordRule;
use TheBachtiarz\OAuth\Http\Requests\Rules\AuthUsernameRule;
use TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface;
use TheBachtiarz\OAuth\Models\AuthUser;

class AuthUserResource extends Resource
{
    use HasAuthorizedResource;

    protected static ?string $modelLabel = 'Credentials';

    protected static ?int $navigationSort = 10;

    protected static ?string $slug = 'credentials';

    protected static ?string $model = AuthUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Group::make()->schema([
                        Forms\Components\TextInput::make(AuthUserInterface::ATTRIBUTE_EMAIL)->label('Email')->inlineLabel()
                            ->string()
                            ->rules(AuthEmailRule::rules()[AuthEmailRule::EMAIL])
                            ->unique(ignoreRecord: true)
                            ->prefixIcon('heroicon-o-at-symbol')
                            ->live()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make(AuthUserInterface::ATTRIBUTE_USERNAME)->label('Username')->inlineLabel()
                            ->string()
                            ->rules(AuthUsernameRule::rules()[AuthUsernameRule::USERNAME])
                            ->prefixIcon('heroicon-o-credit-card')
                            ->live()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make(AuthUserInterface::ATTRIBUTE_PASSWORD)->label('Password')->inlineLabel()
                            ->password()
                            ->rules(AuthPasswordRule::rules()[AuthPasswordRule::PASSWORD])
                            ->revealable(true)
                            ->prefixIcon('heroicon-o-key')
                            ->live()
                            ->columnSpanFull(),
                    ])->columns(12)->columnStart(['sm' => 'full', 'md' => 2])->columnSpan(['sm' => 'full', 'md' => 10]),
                ])->columns(12)->columnSpanFull(),
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(AuthUserInterface::ATTRIBUTE_EMAIL)->label('User Identifier')
                    ->fontFamily(FontFamily::Mono)->weight(FontWeight::SemiBold)
                    ->searchable()
                    ->sortable()
                    ->copyable()->copyMessage('Identifier copied')->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make(AuthUserInterface::ATTRIBUTE_USERNAME)->label('Username')
                    ->fontFamily(FontFamily::Mono)->weight(FontWeight::SemiBold)
                    ->limit(7),
                Tables\Columns\TextColumn::make(AuthUserInterface::ATTRIBUTE_CODE)->label('User Unique Code')
                    ->fontFamily(FontFamily::Mono)->weight(FontWeight::SemiBold)
                    ->limit(15),
                Tables\Columns\TextColumn::make(AuthUserInterface::ATTRIBUTE_CREATED_AT)->label('Registered At')
                    ->fontFamily(FontFamily::Mono)
                    ->since(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make(AuthUserInterface::ATTRIBUTE_USERNAME)->label('Have Username')
                    ->placeholder('All')
                    ->trueLabel('With username')->falseLabel('Without username')
                    ->queries(
                        true: fn(Builder $query) => $query->whereNotNull(AuthUserInterface::ATTRIBUTE_USERNAME),
                        false: fn(Builder $query) => $query->whereNull(AuthUserInterface::ATTRIBUTE_USERNAME),
                        blank: fn(Builder $query) => $query->withoutTrashed(),
                    )
                    ->native(false),
                Tables\Filters\TrashedFilter::make()->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuthUsers::route('/'),
            'create' => Pages\CreateAuthUser::route('/create'),
            'edit' => Pages\EditAuthUser::route('/{record}/edit'),
        ];
    }


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
