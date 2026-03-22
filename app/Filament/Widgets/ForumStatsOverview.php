<?php

namespace App\Filament\Widgets;

use App\Models\Comment;
use App\Models\CommentDislike;
use App\Models\CommentLike;
use App\Models\Subject;
use App\Models\Thread;
use App\Models\ThreadDislike;
use App\Models\ThreadLike;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ForumStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Users', User::count())
                ->description('Registered accounts')
                ->icon('heroicon-o-users'),

            Stat::make('Threads', Thread::count())
                ->description('Total discussions')
                ->icon('heroicon-o-chat-bubble-left-right'),

            Stat::make('Comments', Comment::count())
                ->description('All comments and replies')
                ->icon('heroicon-o-chat-bubble-oval-left-ellipsis'),

            Stat::make('Subjects', Subject::count())
                ->description('Available subjects')
                ->icon('heroicon-o-book-open'),

            Stat::make('Thread Likes', ThreadLike::count())
                ->description('Likes on threads')
                ->icon('heroicon-o-hand-thumb-up'),

            Stat::make('Comment Likes', CommentLike::count())
                ->description('Likes on comments')
                ->icon('heroicon-o-hand-thumb-up'),

            Stat::make('Thread Dislikes', ThreadDislike::count())
                ->description('Dislikes on threads')
                ->icon('heroicon-o-hand-thumb-down'),

            Stat::make('Comment Dislikes', CommentDislike::count())
                ->description('Dislikes on comments')
                ->icon('heroicon-o-hand-thumb-down'),
        ];
    }
}
