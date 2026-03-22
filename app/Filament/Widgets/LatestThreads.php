<?php

namespace App\Filament\Widgets;

use App\Models\Thread;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestThreads extends BaseWidget
{
    protected static ?string $heading = 'Latest Threads';

    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Thread::query()->with(['user', 'subject'])->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->limit(40)
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author'),

                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Subject'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ]);
    }
}
