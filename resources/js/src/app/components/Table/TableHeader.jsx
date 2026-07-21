import React from 'react'

const TableHeader = ({ children }) => {
    return (
        <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            {children}
        </thead>
    )
}

export default TableHeader