<?php

namespace TheBachtiarz\Admin\Filament\Clusters\AuthToken\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use TheBachtiarz\Admin\Filament\Clusters\AuthToken\Resources\AuthTokenResource\Pages;
use TheBachtiarz\Admin\Traits\Filament\Resources\HasAuthorizedResource;
use TheBachtiarz\OAuth\Helpers\OauthModelHelper;
use TheBachtiarz\OAuth\Models\AuthToken;

class AuthTokenResource extends Resource
{
    use HasAuthorizedResource;

    protected static ?string $modelLabel = 'Access Tokens';

    protected static ?int $navigationSort = 20;

    protected static ?string $slug = 'access-tokens';

    protected static ?string $model = AuthToken::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Token Name')
                    ->fontFamily(FontFamily::Mono)->weight(FontWeight::SemiBold),
                TextColumn::make('tokenable_id')->label('Owner Access')
                    ->fontFamily(FontFamily::Mono)->weight(FontWeight::SemiBold)
                    ->formatStateUsing(fn(int $state): string => OauthModelHelper::instance()::find($state)->getIdentifier())
                    ->url(fn(int $state): string => sprintf('credentials/%s/edit', $state), true)
                    ->limit(15)
                    ->searchable()->sortable(),
                TextColumn::make('created_at')->label('Created')
                    ->fontFamily(FontFamily::Mono)
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_used_at')->label('Last Used')
                    ->fontFamily(FontFamily::Mono)
                    ->dateTime(),
                TextColumn::make('expires_at')->label('Expired At')
                    ->fontFamily(FontFamily::Mono)
                    ->dateTime(),
            ])
            ->poll()
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAuthTokens::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
