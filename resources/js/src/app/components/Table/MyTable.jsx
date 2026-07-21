import React from 'react'

const MyTable = ({ children }) => {
    return (
        <table className="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            { children }
        </table>
    )
}

export default MyTable