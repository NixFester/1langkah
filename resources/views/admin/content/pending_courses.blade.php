@extends('layouts.app')

@section('title', 'Pending Courses - Content Review')

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pending Courses</h1>
            <p class="text-sm text-gray-500 mt-1">Review and approve courses submitted by mentors</p>
        </div>
        <a href="{{ route('admin.courses') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <x-icon name="arrow-left" class="w-4 h-4" />
            Back to All Courses
        </a>
    </div>

    <x-flash-messages />

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
            <div class="text-sm text-gray-500">Pending Review</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-2xl font-bold text-green-600">{{ $stats['approved_today'] }}</div>
            <div class="text-sm text-gray-500">Approved Today</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-2xl font-bold text-red-600">{{ $stats['rejected_today'] }}</div>
            <div class="text-sm text-gray-500">Rejected Today</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
            <div class="text-sm text-gray-500">Total Mentor Courses</div>
        </div>
    </div>

    <!-- DATA TABLE -->
    @if($courses->count() > 0)
    <x-data-table :paginator="$courses">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-bold">Course</th>
                <th class="px-6 py-4 font-bold">Mentor</th>
                <th class="px-6 py-4 font-bold">Category</th>
                <th class="px-6 py-4 font-bold">Status</th>
                <th class="px-6 py-4 font-bold text-right">Action</th>
            </tr>
        </thead>

        @forelse($courses as $course)
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0 text-red-600">
                        <x-icon name="book" class="w-6 h-6" />
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-bold text-gray-900 truncate">{{ $course->title }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">
                            {{ Str::limit($course->short_description, 50) }}
                        </div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">{{ $course->mentor_name }}</div>
                <div class="text-xs text-gray-500">{{ $course->mentor_company ?? '-' }}</div>
            </td>
            <td class="px-6 py-4">
                <span class="bg-gray-100 text-gray-700 text-[11px] font-bold px-2.5 py-1 rounded-md">{{ $course->category }}</span>
            </td>
            <td class="px-6 py-4">
                @if($course->isPending())
                    <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 text-xs font-bold px-2.5 py-1 rounded-md">
                        <x-icon name="clock" class="w-3 h-3" />
                        Pending
                    </span>
                @elseif($course->isApproved())
                    <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-md">
                        <x-icon name="check" class="w-3 h-3" />
                        Approved
                    </span>
                @elseif($course->isRejected())
                    <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-md">
                        <x-icon name="x" class="w-3 h-3" />
                        Rejected
                    </span>
                @endif
            </td>
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('admin.courses.manage', $course) }}" class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                        <x-icon name="eye" class="w-3 h-3 mr-1" />
                        Review
                    </a>

                    @if($course->isPending())
                    <form method="POST" action="{{ route('admin.content.courses.approve', $course) }}" class="m-0">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center bg-green-50 text-green-600 hover:bg-green-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                            <x-icon name="check" class="w-3 h-3 mr-1" />
                            Approve
                        </button>
                    </form>
                    <button type="button" onclick="showRejectModal({{ $course->id }}, '{{ $course->title }}')" class="inline-flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                        <x-icon name="x" class="w-3 h-3 mr-1" />
                        Reject
                    </button>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="px-6 py-8">
                <x-empty-state message="No courses pending review" icon="check-circle" />
            </td>
        </tr>
        @endforelse
    </x-data-table>
    @else
    <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
        <x-icon name="check-circle" class="w-12 h-12 mx-auto text-green-500 mb-4" />
        <h3 class="text-lg font-bold text-gray-900 mb-2">All caught up!</h3>
        <p class="text-gray-500">No courses pending review at the moment.</p>
    </div>
    @endif

</div>

<!-- REJECT MODAL -->
<div id="rejectModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Reject Course</h3>
        <form method="POST" id="rejectForm">
            @csrf
            <input type="hidden" name="action" value="reject">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for rejection</label>
                <textarea name="reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Explain why this course is being rejected..." required></textarea>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="hideRejectModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                    Reject Course
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showRejectModal(courseId, courseTitle) {
    document.getElementById('rejectForm').action = '/admin/content/courses/' + courseId + '/reject';
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('flex');
}
</script>
@endpush
@endsection
