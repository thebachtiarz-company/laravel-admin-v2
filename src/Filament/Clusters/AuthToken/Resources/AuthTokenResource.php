<?php

namespace TheBachtiarz\Admin\Filament\Clusters\AuthToken\Resources;

use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
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

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->poll('30s')
            ->defaultGroup(
                Tables\Grouping\Group::make('tokenable_id')->label('User')
                    ->getTitleFromRecordUsing(fn(Model $model) => OauthModelHelper::instance()::find($model->tokenable_id)->getIdentifier())
                    ->collapsible(),
            )
            ->columns([
                TextColumn::make('name')->label('Token Name')
                    ->fontFamily(FontFamily::Mono)->weight(FontWeight::SemiBold)
                    ->copyable()->copyMessage('Token copied')->copyMessageDuration(1500),
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
