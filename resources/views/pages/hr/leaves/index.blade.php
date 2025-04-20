<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page header -->
        <div class="mb-8">
            <div class="mb-4">
                <h1 class="text-2xl md:text-2xl text-fonts-200 dark:text-slate-100 font-bold mb-4">Leave Requests</h1>
            </div>
            <div class="mb-6">
                <a href="{{ route('leaves.create') }}" 
                   class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
                    <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="hidden xs:block ml-2">Create Leave Request</span>
                </a>
            </div>
        </div>

        <!-- Cards -->
        <div class="bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700">
            <header class="px-5 py-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="font-semibold text-slate-800 dark:text-slate-100">Leave Requests</h2>
            </header>
            <div class="p-3">
                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <!-- Table head -->
                        <thead class="text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-700">
                            <tr>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-left">Employee</div>
                                </th>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-left">Leave Type</div>
                                </th>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-left">Dates</div>
                                </th>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-left">Days Requested</div>
                                </th>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-left">Reason</div>
                                </th>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-left">Status</div>
                                </th>
                               @canany(['hr_access', 'super_access'])
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-left">Actions</div>
                                </th>
                                @endcan
                            </tr>
                        </thead>
                        <!-- Table body -->
                        <tbody class="text-sm divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($leaves as $leave)
                            <tr>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ $leave->employee->name }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ ucfirst($leave->leave_type) }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ $leave->start_date->format('M d, Y') }} - {{ $leave->end_date->format('M d, Y') }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ $leave->days_requested }} days</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ Str::limit($leave->reason, 50) }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $leave->getStatusColorAttribute() }}-100 text-{{ $leave->getStatusColorAttribute() }}-800">
                                            {{ ucfirst($leave->status) }}
                                        </span>
                                    </div>
                                </td>
                                @canany(['hr_access', 'super_access'])
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">
                                        <div class="flex items-center space-x-2">
                                            @if($leave->status === 'pending')
                                            <form action="{{ route('leaves.approve', $leave) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-500 hover:text-green-600">
                                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16">
                                                        <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.733.733 0 0 1 1.05-.01z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('leaves.reject', $leave) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-500 hover:text-red-600">
                                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16">
                                                        <path d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z"/>
                                                        <path d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                @endcan
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
