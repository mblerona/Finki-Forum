<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\RelationManagers\CommentsRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\ThreadsRelationManager;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [
            ThreadsRelationManager::class,
            CommentsRelationManager::class,
        ];
    }
}
