import React from 'react'

const Pagination = ({
    currentPage = 1,
    totalPage = 1,
    search = "",
    getDataAction
}) => {
    const generatePagination = () => {
        const maxVisiblePages = 3; // Maximum number of visible page links
        const ellipsis = "..."; // Ellipsis symbol

        let pages = [];

        // Calculate the range of visible page links
        const startPage = Math.max(
            1,
            currentPage - Math.floor(maxVisiblePages / 2)
        );
        const endPage = Math.min(totalPage, startPage + maxVisiblePages - 1);

        // Add page links
        for (let i = startPage; i <= endPage; i++) {
            pages.push(i);
        }

        // Add ellipsis if necessary
        if (startPage > 1) {
            pages.unshift(ellipsis);
            pages.unshift(1);
        }
        if (endPage < totalPage) {
            pages.push(ellipsis);
            pages.push(totalPage);
        }

        return pages;
    }
    const onPage = (page) => {
        getDataAction(page)
    }
    const nextPageAction = () => {
        const next = parseInt(currentPage) + 1
        getDataAction(next)
    }

    const prevPageAction = () => {
        const prev = parseInt(currentPage) - 1
        getDataAction(prev)
    }
    return (
        
        <nav className="flex flex-col md:flex-row justify-end items-start md:items-center space-y-3 md:space-y-0 p-4"
            aria-label="Table navigation">
            <ul className="inline-flex items-stretch -space-x-px">
                <li>
                    <button disabled={currentPage.toString() === "1"} onClick={() => prevPageAction()}
                        className="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border 
                            disabled:bg-gray-100 disabled:text-gray-700 disabled:dark:bg-gray-700 disabled:dark:text-white disabled:cursor-not-allowed
                            border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 
                            dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        <span className="sr-only">Previous</span>
                        <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fillRule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clipRule="evenodd" />
                        </svg>
                    </button>
                </li>
                {
                    generatePagination().map((page, key) => (
                        currentPage.toString() === page.toString() ?
                        <li key={key}>
                            <button disabled
                                className="flex items-center justify-center text-sm py-2 px-3 leading-tight font-bold
                                border border-gray-300 bg-gray-100 text-gray-700 cursor-not-allowed
                                dark:border-gray-700 dark:bg-gray-700 dark:text-white">
                                {page}
                            </button>
                        </li>
                        :
                        <li key={key}>
                            <button onClick={() => { onPage(page) }}
                                className="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 
                                bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 
                                dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                {page}
                            </button>
                        </li>
                    ))
                }
                <li>
                    <button disabled={currentPage.toString() === totalPage.toString()} onClick={() => nextPageAction()}
                        className="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border 
                            disabled:bg-gray-100 disabled:text-gray-700 disabled:dark:bg-gray-700 disabled:dark:text-white disabled:cursor-not-allowed
                            border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 
                            dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        <span className="sr-only">Next</span>
                        <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fillRule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clipRule="evenodd" />
                        </svg>
                    </button>
                </li>
            </ul>
        </nav>
    )
}

export default Pagination