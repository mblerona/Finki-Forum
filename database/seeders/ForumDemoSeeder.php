<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\CommentDislike;
use App\Models\CommentLike;
use App\Models\Subject;
use App\Models\Tag;
use App\Models\Thread;
use App\Models\ThreadDislike;
use App\Models\ThreadLike;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ForumDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        CommentDislike::truncate();
        CommentLike::truncate();
        ThreadDislike::truncate();
        ThreadLike::truncate();
        Comment::truncate();
        Thread::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $demoUsers = [
            ['name' => 'Test Student', 'email' => 'student@finki.edu.mk'],
            ['name' => 'Student2', 'email' => 'student2@finki.edu.mk'],
            ['name' => 'Elena Trajkovska', 'email' => 'elena@finki.edu.mk'],
            ['name' => 'Marko Stojanovski', 'email' => 'marko@finki.edu.mk'],
            ['name' => 'Ana Petrovska', 'email' => 'ana@finki.edu.mk'],
            ['name' => 'Filip Nikolov', 'email' => 'filip@finki.edu.mk'],
            ['name' => 'Sara Jovanovska', 'email' => 'sara@finki.edu.mk'],
        ];

        foreach ($demoUsers as $demoUser) {
            User::firstOrCreate(
                ['email' => $demoUser['email']],
                [
                    'name' => $demoUser['name'],
                    'password' => Hash::make('password'),
                    'role' => 'student',
                ]
            );
        }

        $users = User::where('role', 'student')->get();
        $subjects = Subject::all();
        $tags = Tag::all();

        if ($users->isEmpty() || $subjects->isEmpty()) {
            return;
        }

        $threadTitles = [
            'How should I prepare for the final exam?',
            'Best resources for this subject?',
            'Can someone explain this assignment?',
            'Is attendance important here?',
            'What should I focus on for the midterm?',
            'Any tips for passing this course?',
            'Which topics are the hardest?',
            'How do you solve the lab exercises?',
            'Does anyone have good notes for this week?',
            'Project team members needed',
            'Which professor explanations helped you most?',
            'How much theory is asked on the exam?',
            'What is the best way to study these materials?',
            'Can someone share practice examples?',
            'What should I revise first?',
            'Anyone interested in a study group?',
            'How difficult is the second partial exam?',
            'What was your experience with this course?',
            'How do I approach the project requirements?',
            'Can someone recommend useful videos or slides?',
        ];

        $threadBodies = [
            'I am trying to organize my studying better and would appreciate some guidance from people who already passed this subject.',
            'I missed a few classes and now I am not sure which materials are the most important. Any help would be appreciated.',
            'The assignments are a bit confusing for me, especially the practical part. If someone can explain the logic, that would help a lot.',
            'I want to understand which topics matter most before I spend too much time on less important details.',
            'If anyone has useful examples, solved exercises, or advice from previous exams, please share them here.',
            'This subject seems manageable, but I want to be smarter with how I prepare and practice.',
        ];

        $createdThreads = collect();

        for ($i = 0; $i < 20; $i++) {
            $thread = Thread::create([
                'title' => $threadTitles[$i % count($threadTitles)],
                'content' => $threadBodies[array_rand($threadBodies)],
                'user_id' => $users->random()->id,
                'subject_id' => $subjects->random()->id,
                'is_anonymous' => fake()->boolean(20),
                'created_at' => now()->subDays(rand(0, 40))->subHours(rand(0, 23)),
                'updated_at' => now(),
            ]);

            if ($tags->isNotEmpty()) {
                $tagIds = $tags->random(rand(1, min(3, $tags->count())))->pluck('id')->toArray();
                $thread->tags()->sync($tagIds);
            }

            $createdThreads->push($thread);
        }

        foreach ($createdThreads as $thread) {
            $likers = $users->random(rand(0, min(6, $users->count())));
            foreach ($likers as $user) {
                ThreadLike::firstOrCreate([
                    'thread_id' => $thread->id,
                    'user_id' => $user->id,
                ]);
            }

            $availableForDislikes = $users->whereNotIn('id', ThreadLike::where('thread_id', $thread->id)->pluck('user_id'));
            if ($availableForDislikes->isNotEmpty()) {
                $dislikers = $availableForDislikes->random(rand(0, min(2, $availableForDislikes->count())));
                foreach ($dislikers as $user) {
                    ThreadDislike::firstOrCreate([
                        'thread_id' => $thread->id,
                        'user_id' => $user->id,
                    ]);
                }
            }

            $topLevelCommentsCount = rand(0, 4);

            for ($j = 0; $j < $topLevelCommentsCount; $j++) {
                $comment = Comment::create([
                    'content' => fake()->sentence(rand(10, 20)),
                    'user_id' => $users->random()->id,
                    'thread_id' => $thread->id,
                    'parent_id' => null,
                    'is_anonymous' => fake()->boolean(15),
                    'created_at' => now()->subDays(rand(0, 30)),
                    'updated_at' => now(),
                ]);

                $commentLikers = $users->random(rand(0, min(4, $users->count())));
                foreach ($commentLikers as $user) {
                    CommentLike::firstOrCreate([
                        'comment_id' => $comment->id,
                        'user_id' => $user->id,
                    ]);
                }

                $availableCommentDislikers = $users->whereNotIn('id', CommentLike::where('comment_id', $comment->id)->pluck('user_id'));
                if ($availableCommentDislikers->isNotEmpty()) {
                    $commentDislikers = $availableCommentDislikers->random(rand(0, min(1, $availableCommentDislikers->count())));
                    foreach ($commentDislikers as $user) {
                        CommentDislike::firstOrCreate([
                            'comment_id' => $comment->id,
                            'user_id' => $user->id,
                        ]);
                    }
                }

                $replyCount = rand(0, 2);

                for ($k = 0; $k < $replyCount; $k++) {
                    $reply = Comment::create([
                        'content' => fake()->sentence(rand(6, 14)),
                        'user_id' => $users->random()->id,
                        'thread_id' => $thread->id,
                        'parent_id' => $comment->id,
                        'is_anonymous' => fake()->boolean(10),
                        'created_at' => now()->subDays(rand(0, 20)),
                        'updated_at' => now(),
                    ]);

                    $replyLikers = $users->random(rand(0, min(3, $users->count())));
                    foreach ($replyLikers as $user) {
                        CommentLike::firstOrCreate([
                            'comment_id' => $reply->id,
                            'user_id' => $user->id,
                        ]);
                    }

                    $availableReplyDislikers = $users->whereNotIn('id', CommentLike::where('comment_id', $reply->id)->pluck('user_id'));
                    if ($availableReplyDislikers->isNotEmpty()) {
                        $replyDislikers = $availableReplyDislikers->random(rand(0, min(1, $availableReplyDislikers->count())));
                        foreach ($replyDislikers as $user) {
                            CommentDislike::firstOrCreate([
                                'comment_id' => $reply->id,
                                'user_id' => $user->id,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
