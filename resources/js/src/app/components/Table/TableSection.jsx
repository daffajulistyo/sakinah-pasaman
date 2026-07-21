import React from 'react'
import { RecordPerPage, SearchBar, Pagination } from '@components/Table'

const TableSection = ({ children, pagination = {}, getDataAction }) => {
    const getDataOnPagination = (page) => {
        getDataAction(page, pagination.per_page, pagination.search)
    }

    const getDataOnSearch = (words) => {
        getDataAction("1", pagination.per_page, words)
    }

    const getDataOnRecordPerPage = (records) => {
        getDataAction("1", records, pagination.search)
    }

    return (
        <section className="bg-gray-50 dark:bg-gray-900">
            <div className="mx-auto">
                <div className="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden min-h-96">
                    <div className="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        
                        <RecordPerPage records={pagination.per_page} onchange={getDataOnRecordPerPage} />
                        <SearchBar value={pagination.search} onchange={getDataOnSearch} />
                    </div>
                    <div className="overflow-x-auto">
                        { children }
                    </div>
                    <Pagination getDataAction={getDataOnPagination} search={pagination.search} currentPage={pagination.page} totalPage={pagination.total_page} />
                </div>
            </div>
        </section>
    )
}

export default TableSection