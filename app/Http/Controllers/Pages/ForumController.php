<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\ForumPost;
use App\Models\ForumReply;
use App\Models\ForumVote;
use App\Models\User;
use App\Services\AchievementService;
use App\Services\XpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ForumController extends Controller
{
    private XpService $xpService;

    public function __construct(XpService $xpService)
    {
        $this->xpService = $xpService;
    }

    /**
     * Display the forum listing with pagination and search
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'newest');

        $query = ForumPost::with(['user'])
            ->search($search);

        // Sorting options
        $query = match ($sort) {
            'oldest' => $query->oldest(),
            'popular' => $query->orderByRaw('(upvotes - downvotes) DESC'),
            'most_commented' => $query->orderByDesc('reply_count'),
            default => $query->latest(), // 'newest'
        };

        $posts = $query->paginate(15)->withQueryString();

        // Get user's votes for all posts
        $userVotes = [];
        if (Auth::check()) {
            $postIds = $posts->pluck('id')->toArray();
            $userVotes = ForumVote::where('user_id', Auth::id())
                ->whereIn('votable_id', $postIds)
                ->where('votable_type', ForumPost::class)
                ->pluck('is_upvote', 'votable_id')
                ->toArray();
        }

        return view('pages.komunitas', [
            'posts' => $posts,
            'search' => $search,
            'sort' => $sort,
            'userVotes' => $userVotes,
        ]);
    }

    /**
     * Show a single post with replies
     */
    public function show(int $id): View
    {
        $post = ForumPost::with(['user', 'topLevelReplies.user', 'topLevelReplies.children.user'])
            ->findOrFail($id);

        // Get user's votes
        $userVotes = [];
        if (Auth::check()) {
            // Get votes for the post
            $postVote = ForumVote::where('user_id', Auth::id())
                ->where('votable_id', $post->id)
                ->where('votable_type', ForumPost::class)
                ->first();
            $userVotes['post'] = $postVote?->is_upvote;

            // Get votes for all replies
            $replyIds = $post->topLevelReplies->pluck('id')->toArray();
            $allReplies = $post->topLevelReplies->flatMap(function ($reply) {
                return $reply->children->push($reply);
            })->pluck('id')->toArray();

            $replyVotes = ForumVote::where('user_id', Auth::id())
                ->whereIn('votable_id', $allReplies)
                ->where('votable_type', ForumReply::class)
                ->pluck('is_upvote', 'votable_id')
                ->toArray();

            $userVotes['replies'] = $replyVotes;
        }

        return view('pages.komunitas-post', [
            'post' => $post,
            'userVotes' => $userVotes,
        ]);
    }

    /**
     * Show the create post form
     */
    public function create(): View
    {
        return view('pages.komunitas-create');
    }

    /**
     * Store a new post
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:10000'],
            'image_urls' => ['nullable', 'string', 'max:5000'],
        ]);

        // Parse image URLs from comma-separated string
        $imageUrls = null;
        if (! empty($validated['image_urls'])) {
            $urls = array_filter(array_map('trim', explode(',', $validated['image_urls'])));
            $urls = array_filter($urls, function ($url) {
                return filter_var($url, FILTER_VALIDATE_URL);
            });
            if (! empty($urls)) {
                $imageUrls = array_values($urls);
            }
        }

        $post = ForumPost::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image_urls' => $imageUrls,
            'upvotes' => 0,
            'downvotes' => 0,
            'reply_count' => 0,
        ]);

        // Award XP for creating a forum post
        $this->xpService->awardXp(
            Auth::user(),
            'forum_post_created',
            ForumPost::class,
            $post->id
        );
        app(AchievementService::class)->checkAndAward(Auth::user(), AchievementService::TRIGGER_FORUM_POST);

        return redirect()->route('komunitas.show', $post->id)
            ->with('success', __('app.msg_success_post_berhasil_dibuat'));
    }

    /**
     * Store a reply to a post
     */
    public function reply(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'post_id' => ['required', 'exists:forum_posts,id'],
            'content' => ['required', 'string', 'max:5000'],
            'parent_id' => ['nullable', 'exists:forum_replies,id'],
            'image_urls' => ['nullable', 'string', 'max:5000'],
        ]);

        // Parse image URLs
        $imageUrls = null;
        if (! empty($validated['image_urls'])) {
            $urls = array_filter(array_map('trim', explode(',', $validated['image_urls'])));
            $urls = array_filter($urls, function ($url) {
                return filter_var($url, FILTER_VALIDATE_URL);
            });
            if (! empty($urls)) {
                $imageUrls = array_values($urls);
            }
        }

        $reply = ForumReply::create([
            'forum_post_id' => $validated['post_id'],
            'user_id' => Auth::id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'content' => $validated['content'],
            'image_urls' => $imageUrls,
            'upvotes' => 0,
            'downvotes' => 0,
        ]);

        // Award XP for creating a forum reply
        $this->xpService->awardXp(
            Auth::user(),
            'forum_reply_created',
            ForumReply::class,
            $reply->id
        );

        // Check for forum reply achievements
        app(AchievementService::class)->checkAndAward(Auth::user(), AchievementService::TRIGGER_FORUM_REPLY);

        // Increment post reply count
        ForumPost::find($validated['post_id'])->incrementReplyCount();

        return redirect()->back()
            ->with('success', __('app.msg_success_balasan_berhasil_dikirim'));
    }

    /**
     * Vote on a post or reply
     */
    public function vote(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'votable_id' => ['required', 'integer'],
            'votable_type' => ['required', 'in:post,reply'],
            'vote_type' => ['required', 'in:up,down,remove'],
        ]);

        $userId = Auth::id();
        $votableType = $validated['votable_type'] === 'post' ? ForumPost::class : ForumReply::class;
        $votableId = $validated['votable_id'];
        $voteType = $validated['vote_type'];

        // Find existing vote
        $existingVote = ForumVote::where('user_id', $userId)
            ->where('votable_id', $votableId)
            ->where('votable_type', $votableType)
            ->first();

        $votable = $votableType::findOrFail($votableId);

        if ($voteType === 'remove') {
            // Remove vote
            if ($existingVote) {
                if ($existingVote->is_upvote) {
                    $votable->decrement('upvotes');
                } else {
                    $votable->decrement('downvotes');
                }
                $existingVote->delete();
            }
        } else {
            $isUpvote = $voteType === 'up';

            if ($existingVote) {
                // Update existing vote
                if ($existingVote->is_upvote !== $isUpvote) {
                    // Switch vote direction
                    if ($isUpvote) {
                        $votable->decrement('downvotes');
                        $votable->increment('upvotes');
                    } else {
                        $votable->decrement('upvotes');
                        $votable->increment('downvotes');
                    }
                    $existingVote->update(['is_upvote' => $isUpvote]);
                }
            } else {
                // Create new vote
                ForumVote::create([
                    'user_id' => $userId,
                    'votable_id' => $votableId,
                    'votable_type' => $votableType,
                    'is_upvote' => $isUpvote,
                ]);

                if ($isUpvote) {
                    $votable->increment('upvotes');

                    // Award XP to the post/reply author for receiving an upvote
                    if ($votable->user_id !== $userId) {
                        $action = $votableType === ForumPost::class ? 'forum_post_upvoted' : 'forum_reply_upvoted';
                        $this->xpService->awardXpToUserId(
                            $votable->user_id,
                            $action,
                            $votableType,
                            $votableId
                        );

                        // Check for vote received achievements
                        $votableUser = User::find($votable->user_id);
                        if ($votableUser) {
                            app(AchievementService::class)->checkAndAward($votableUser, AchievementService::TRIGGER_FORUM_VOTE_RECEIVED);
                        }
                    }
                } else {
                    $votable->increment('downvotes');
                }
            }
        }

        // Refresh to get updated counts
        $votable->refresh();

        return response()->json([
            'success' => true,
            'upvotes' => $votable->upvotes,
            'downvotes' => $votable->downvotes,
            'score' => $votable->score,
        ]);
    }
}
