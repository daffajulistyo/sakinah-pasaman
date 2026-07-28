<section x-init="datatable.getData()">
    <div class="rounded-sm border border-stroke bg-white shadow-sm dark:border-strokedark dark:bg-boxdark">
        <div class="flex flex-wrap items-center gap-2 border-b border-stroke px-4 py-3 dark:border-strokedark">
            <div class="flex-1" style="max-width:300px">
                <input type="text" x-model="datatable.search" @input.debounce.500ms="datatable.refreshTable()"
                    class="w-full rounded-lg border border-stroke bg-transparent px-3 py-1.5 text-sm outline-none focus:border-primary dark:border-strokedark dark:bg-form-input"
                    placeholder="Search...">
            </div>
            <div class="ms-auto relative" x-data="{ open: false }">
                <button @click="open = !open" class="inline-flex items-center gap-1 rounded border border-stroke px-3 py-1.5 text-sm hover:bg-gray-2 dark:border-strokedark dark:hover:bg-graydark">
                    <span x-text="datatable.pagination.per_page"></span> per page
                    <svg class="fill-current" width="10" height="6" viewBox="0 0 10 6"><path d="M0 0l5 6 5-6z"/></svg>
                </button>
                <div x-show="open" @click.outside="open = false"
                    class="absolute right-0 mt-1 w-36 rounded-sm border border-stroke bg-white shadow-sm dark:border-strokedark dark:bg-boxdark z-[50]">
                    <button @click="datatable.pagination.per_page = 10; datatable.refreshTable(); open = false" class="block w-full px-4 py-2 text-left text-sm hover:bg-gray-2 dark:hover:bg-graydark">10 Entries</button>
                    <button @click="datatable.pagination.per_page = 25; datatable.refreshTable(); open = false" class="block w-full px-4 py-2 text-left text-sm hover:bg-gray-2 dark:hover:bg-graydark">25 Entries</button>
                    <button @click="datatable.pagination.per_page = 50; datatable.refreshTable(); open = false" class="block w-full px-4 py-2 text-left text-sm hover:bg-gray-2 dark:hover:bg-graydark">50 Entries</button>
                    <button @click="datatable.pagination.per_page = 100; datatable.refreshTable(); open = false" class="block w-full px-4 py-2 text-left text-sm hover:bg-gray-2 dark:hover:bg-graydark">100 Entries</button>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="border-b border-stroke bg-gray-2 dark:border-strokedark dark:bg-meta-4">
                    {{ $thead }}
                </thead>
                <tbody>
                    {{ $tbody }}
                    <tr x-show="datatable.isEmpty() && !datatable.loading">
                        <td class="px-4 py-6 text-center text-bodydark2" colspan="100%">No matching records found.</td>
                    </tr>
                    <tr x-show="datatable.loading">
                        <td class="px-4 py-6 text-center" colspan="100%">
                            <div class="inline-block h-5 w-5 animate-spin rounded-full border-2 border-solid border-primary border-t-transparent"></div>
                            <span class="ml-2 text-bodydark2">Loading...</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-2 border-t border-stroke px-4 py-3 dark:border-strokedark">
            <span class="text-sm text-bodydark2">
                Showing <span class="font-medium text-black dark:text-white" x-text="datatable.showingLabel()"></span>
                of <span class="font-medium text-black dark:text-white" x-text="datatable.pagination.total_records"></span>
            </span>
            <div class="flex gap-1">
                <button @click="datatable.previousPage" :disabled="datatable.pagination.page == 1"
                    class="rounded border border-stroke px-3 py-1 text-sm hover:bg-gray-2 dark:border-strokedark disabled:opacity-50 disabled:cursor-not-allowed">&laquo;</button>
                <template x-for="page in datatable.pages">
                    <button @click="datatable.goToPage(page)" x-text="page"
                        class="rounded border px-3 py-1 text-sm"
                        :class="datatable.isCurrentPage(page) ? 'bg-primary text-white border-primary' : 'border-stroke hover:bg-gray-2 dark:border-strokedark'"
                        :disabled="page === '...'"></button>
                </template>
                <button @click="datatable.nextPage" :disabled="datatable.pagination.page == datatable.pagination.total_page"
                    class="rounded border border-stroke px-3 py-1 text-sm hover:bg-gray-2 dark:border-strokedark disabled:opacity-50 disabled:cursor-not-allowed">&raquo;</button>
            </div>
        </div>
    </div>
</section>

