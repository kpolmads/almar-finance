<x-app-layout>
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        @if (session()->has('success'))
            <div class="alert alert-success">
                <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800"
                    role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                        <span class="font-medium">{{ session()->get('success') }}</span>
                    </div>
                </div>
            </div>
        @endif
        <div class="relative">
            <h1 class="text-xl sm:text-2xl text-fonts-200 dark:text-slate-100 font-bold mb-4">Grant Loan Entry List</h1>
        </div>

        <!-- Cards -->
        <section class="container">
            <div class="p-4 sm:p-6 w-full max-w-[1500px] mx-auto bg-white rounded-lg border border-bgbody-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                    <!-- Search Forms -->
                    <div class="w-full sm:w-auto flex flex-col sm:flex-row gap-4">
                        <form method="GET" action="{{ route('loan.index') }}" class="flex-1">
                            <div class="relative">
                                <input type="text" id="search1" name="search"
                                    class="bg-bgbody-100 border border-bgbody-200 text-fonts-100 text-sm rounded-full focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 pr-16 p-4 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Transaction No..." required />
                                <div class="absolute inset-y-0 left-3 flex items-center text-fonts-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                    </svg>
                                </div>
                                <button type="submit" class="absolute inset-y-1 right-1 flex items-center bg-indigo-500 hover:bg-indigo-600 text-white rounded-full px-4 py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24" width="20px" fill="#FFFFFF">
                                        <path d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                                    </svg>
                                </button>
                            </div>
                        </form>

                        <form method="GET" action="{{ route('loan.index') }}" class="flex-1">
                            <div class="relative">
                                <input type="text" id="search_name" name="search_name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-full focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 pr-16 p-4 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Customer Name" required />
                                <div class="absolute inset-y-0 left-3 flex items-center text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                    </svg>
                                </div>
                                <button type="submit" class="absolute inset-y-1 right-1 flex items-center bg-indigo-500 hover:bg-indigo-600 text-white rounded-full px-4 py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24" width="20px" fill="#FFFFFF">
                                        <path d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Filters and Actions -->
                    <div class="flex flex-wrap gap-2 justify-start sm:justify-end">
                        @props([
                            'align' => 'right',
                        ])
                        
                        <!-- Customer Type Filter -->
                        <div class="relative inline-flex" x-data="{ open: false }">
                            <button class="bg-white-100 border border-gray-300 gap-2 text-fonts-100 text-sm rounded-full focus:ring-blue-500 focus:border-blue-500 px-4 py-2 flex items-center hover:bg-indigo-500 hover:text-white dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600"
                                aria-haspopup="true" @click.prevent="open = !open" :aria-expanded="open">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-filter"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                                <span class="hidden sm:inline">Customer type</span>
                            </button>

                            <!-- Filter Dropdown -->
                            <form method="GET" action="{{ route('loan.index') }}">
                                <div class="origin-top-right z-10 absolute top-full left-0 right-auto min-w-56 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 pt-1.5 rounded shadow-lg overflow-hidden mt-1 {{ $align === 'right' ? 'md:left-auto md:right-0' : 'md:left-0 md:right-auto' }}"
                                    @click.outside="open = false" @keydown.escape.window="open = false" x-show="open"
                                    x-transition:enter="transition ease-out duration-200 transform"
                                    x-transition:enter-start="opacity-0 -translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-out duration-200"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    x-cloak>
                                    <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase pt-1.5 pb-2 px-3">Filters</div>
                                    <ul class="max-h-40 overflow-y-auto mb-4">
                                        @foreach ($types as $type)
                                            <li class="py-1 px-3">
                                                <label class="flex items-center">
                                                    <input type="checkbox" name="filter[]" value="{{ $type->code }}" class="form-checkbox"/>
                                                    <span class="text-sm font-medium ml-2">{{ $type->description }}</span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="py-2 px-3 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/20">
                                        <ul class="flex items-center justify-between">
                                            <li>
                                                <a href="{{ route('loan.index') }}" class="btn-xs bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 text-slate-500 dark:text-slate-300 hover:text-slate-600 dark:hover:text-slate-200">Clear</a>
                                            </li>
                                            <li>
                                                <button type="submit" class="btn-xs bg-blue-400 hover:bg-blue-700 text-white" @click="open = false" @focusout="open = false">Apply</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Transaction Type Filter -->
                        <div class="relative inline-flex" x-data="{ open: false }">
                            <button class="bg-white-100 border border-gray-300 gap-2 text-fonts-100 text-sm rounded-full focus:ring-blue-500 focus:border-blue-500 px-4 py-2 flex items-center hover:bg-indigo-500 hover:text-white dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600"
                                aria-haspopup="true" @click.prevent="open = !open" :aria-expanded="open">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-filter"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                                <span class="hidden sm:inline">Transaction type</span>
                            </button>

                            <!-- Transaction Type Dropdown -->
                            <form method="GET" action="{{ route('loan.index') }}">
                                <div class="origin-top-right z-10 absolute top-full left-0 right-auto min-w-56 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 pt-1.5 rounded shadow-lg overflow-hidden mt-1 {{ $align === 'right' ? 'md:left-auto md:right-0' : 'md:left-0 md:right-auto' }}"
                                    @click.outside="open = false" @keydown.escape.window="open = false" x-show="open"
                                    x-transition:enter="transition ease-out duration-200 transform"
                                    x-transition:enter-start="opacity-0 -translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-out duration-200"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    x-cloak>
                                    <!-- Transaction type filter options -->
                                    <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase pt-1.5 pb-2 px-3">Filters</div>
                                    <ul class="max-h-40 overflow-y-auto mb-4">
                                        <li class="py-1 px-3">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="transactionType" value="NEW" class="form-checkbox"/>
                                                <span class="text-sm font-medium ml-2">NEW</span>
                                            </label>
                                        </li>
                                        <li class="py-1 px-3">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="transactionType" value="RENEW" class="form-checkbox"/>
                                                <span class="text-sm font-medium ml-2">RENEW</span>
                                            </label>
                                        </li>
                                        <li class="py-1 px-3">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="transactionType" value="RECONS" class="form-checkbox"/>
                                                <span class="text-sm font-medium ml-2">RECONS</span>
                                            </label>
                                        </li>
                                        <li class="py-1 px-3">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="transactionType" value="W/COLLAT" class="form-checkbox"/>
                                                <span class="text-sm font-medium ml-2">W/COLLAT</span>
                                            </label>
                                        </li>
                                        <li class="py-1 px-3">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="transactionType" value="CA" class="form-checkbox"/>
                                                <span class="text-sm font-medium ml-2">CA</span>
                                            </label>
                                        </li>
                                        <li class="py-1 px-3">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="transactionType" value="W/CERT" class="form-checkbox"/>
                                                <span class="text-sm font-medium ml-2">W/CERT</span>
                                            </label>
                                        </li>
                                        <li class="py-1 px-3">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="transactionType" value="CBA" class="form-checkbox"/>
                                                <span class="text-sm font-medium ml-2">CBA</span>
                                            </label>
                                        </li>
                                    </ul>
                                    <div class="py-2 px-3 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/20">
                                        <ul class="flex items-center justify-between">
                                            <li>
                                                <button type="reset" class="btn-xs bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 text-slate-500 dark:text-slate-300 hover:text-slate-600 dark:hover:text-slate-200">Clear</button>
                                            </li>
                                            <li>
                                                <button type="submit" class="btn-xs bg-blue-400 hover:bg-blue-700 text-white" @click="open = false" @focusout="open = false">Apply</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- New Entry Button -->
                        @cannot('auditor_access')
                        <a href="{{ route('loan.create') }}" class="btn bg-indigo-500 hover:bg-indigo-600 text-white rounded-full px-4 sm:px-6 py-2 sm:py-3 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-plus"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                            <span class="hidden sm:block ml-2">New</span>
                        </a>
                        @endcannot
                    </div>
                </div>

                <!-- Table Section -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left text-sm rounded-md overflow-hidden border border-black">
                        <thead class="bg-bgbody-100 rounded-2xl">
                            <tr class="text-fonts-100 font-extrabold">
                                <th class="p-4 text-fonts-100 font-normal">ID</th>
                                <th class="p-4 text-fonts-100 font-normal">Customer ID</th>
                                <th class="p-4 text-fonts-100 font-normal">Customer Name</th>
                                <th class="p-4 text-fonts-100 font-normal hidden sm:table-cell">Customer Type</th>
                                <th class="p-4 text-fonts-100 font-normal hidden md:table-cell">Loan Type</th>
                                <th class="p-4 text-fonts-100 font-normal hidden lg:table-cell">Transaction Type</th>
                                <th class="p-4 text-fonts-100 font-normal">Status</th>
                                <th class="p-4 text-fonts-100 font-normal">Action</th>
                            </tr>
                        </thead>

                        <tbody class="text-gray-600 font-semibold text-sm">
                            @foreach ($lists as $list)
                                @if ($list->customer != null)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-4 text-sm font-medium text-gray-500 dark:text-gray-200 whitespace-nowrap">
                                            {{ $list->id }}
                                        </td>
                                        <td class="px-4 py-4 text-sm font-medium text-gray-500 dark:text-gray-200 whitespace-nowrap">
                                            {{ $list->customer->id }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300">
                                            {{ $list->customer->first_name }} {{ $list->customer->last_name }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 hidden sm:table-cell">
                                            {{ $list->customer->customerType->description }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 hidden md:table-cell">
                                            {{ ucfirst($list->loan_type) }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 hidden lg:table-cell">
                                            {{ $list->transaction_type }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300">
                                            @php
                                                $status = $list->status;
                                                $statusClass = 'inline-flex items-center justify-center w-24 h-8 text-sm font-medium rounded-full border';
                                                
                                                switch ($status) {
                                                    case 'CLOSE':
                                                        $statusClass .= ' bg-gray-100 text-gray-700 border-gray-600';
                                                        break;
                                                    case 'CNCLD':
                                                        $statusClass .= ' bg-rose-100 text-rose-700 border-rose-500';
                                                        break;
                                                    case 'FULPD':
                                                        $statusClass .= ' bg-emerald-100 text-emerald-700 border-emerald-500';
                                                        break;
                                                    case 'UNPD':
                                                        $statusClass .= ' bg-amber-100 text-amber-700 border-amber-500';
                                                        break;
                                                    default:
                                                        $statusClass .= ' bg-gray-100 text-gray-700 border-gray-400';
                                                        break;
                                                }
                                            @endphp
                                            <span class="px-2 py-1 {{ $statusClass }}">
                                                {{ $statusMapping[$status] ?? ($status ?? 'PENDING') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-sm">
                                            <div class="flex items-center gap-x-2 sm:gap-x-4">
                                                <div class="relative inline-flex" x-data="{ open: false }"  x-init="Popper.createPopper($el, $refs.popover, { placement: 'bottom'});">
                                                    <button class="text-gray-500 transition-colors duration-200 dark:hover:text-indigo-500 dark:text-gray-300 hover:text-indigo-500 focus:outline-none"
                                                            aria-haspopup="true" 
                                                            @click.prevent="open = !open" 
                                                            :aria-expanded="open">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-printer-check"><path d="M13.5 22H7a1 1 0 0 1-1-1v-6a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v.5"/><path d="m16 19 2 2 4-4"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/></svg>
                                                    </button>

                                                    <!-- Print options dropdown -->
                                                    <div class="absolute right-8 bottom-0 z-10 mt-2 w-56 h-[100px] overflow-y-auto origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                                        @click.outside="open = false"
                                                        @keydown.escape.window="open = false"
                                                        x-show="open"
                                                        x-transition:enter="transition ease-out duration-200 transform"
                                                        x-transition:enter-start="opacity-0 -translate-y-2"
                                                        x-transition:enter-end="opacity-100 translate-y-0"
                                                        x-transition:leave="transition ease-out duration-200"
                                                        x-transition:leave-start="opacity-100"
                                                        x-transition:leave-end="opacity-0"
                                                        x-cloak>
                                                        <ul class="dropdown-menu  block text-gray-700  z-50">
                                                            <li>
                                                                <a href="{{ route('printGrantLoan.index', $list->id) }}" class="text-sm font-medium text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-200 flex items-center py-1 px-3">Print Grant Loan</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('printApplicationForm.index', $list->id) }}" class="text-sm font-medium text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-200 flex items-center py-1 px-3">Application Form</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('printVoucher.index', $list->id) }}" class="text-sm font-medium text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-200 flex items-center py-1 px-3">Voucher Form</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('printAffidavit.index', $list->id) }}" class="text-sm font-medium text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-200 flex items-center py-1 px-3">Affidavit Form</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('printSPA.index', $list->id) }}" class="text-sm font-medium text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-200 flex items-center py-1 px-3">SPA Form</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('printAgreement.index', $list->id) }}" class="text-sm font-medium text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-200 flex items-center py-1 px-3">Agreement Form</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('printCashBreakdown.index', $list->id) }}" class="text-sm font-medium text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-200 flex items-center py-1 px-3">Cash Breakdown Form</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('printDisclosureStatement.index', $list->id) }}" class="text-sm font-medium text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-200 flex items-center py-1 px-3">Disclosure Statement Form</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('printPromissoryATM.index', $list->id) }}" class="text-sm font-medium text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-200 flex items-center py-1 px-3">Promissory ATM Form</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('printSPA.index', $list->id) }}" class="text-sm font-medium text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-200 flex items-center py-1 px-3">SPA Form</a>
                                                            </li>
                                                           
                                                        </ul>
                                                    </div>
                                                </div>

                                                <a href="{{ route('loan.show', $list->id) }}"
                                                    class="text-gray-500 transition-colors duration-200 dark:hover:text-indigo-500 dark:text-gray-300 hover:text-indigo-500 focus:outline-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $lists->links() }}
                </div>
            </div>
        </section>
    </div>
</x-app-layout>

<script src="https://unpkg.com/@popperjs/core@2">   </script>