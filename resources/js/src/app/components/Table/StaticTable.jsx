import React from 'react'

const StaticTable = ({
    header=null,
    children
}) => {
    return (
        <section className="bg-gray-50 dark:bg-gray-900">
            <div className="mx-auto">
                <div className="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden min-h-96">
                    
                    <div className="overflow-x-auto">
                        
                        <table className="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                {header}
                            </thead>
                            
                            <tbody>
                                { children }
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    )
}

export default StaticTable