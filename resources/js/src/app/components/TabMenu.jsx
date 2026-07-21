import React from 'react'
import { Link } from 'react-router-dom'

const TabMenu = ({menuitem = [], active = ""}) => {
    return (
        <div className="flex flex-col w-full px-3 py-2 overflow-x-scroll absolute">
            <div className="sm:grid sm:grid-cols-6 flex gap-3 ">
                {
                    menuitem.map((item, index) => (
                        item.is_active ? 
                        <button to={item.url} key={index} disabled
                            className="py-2 px-4 bg-teal-300 dark:bg-teal-800 rounded-lg flex justify-center border-2 border-teal-400 dark:border-teal-900 sm:w-full w-32">
                            <span className="text-teal-500 dark:text-white font-semibold text-center text-xs">{ item.name.toUpperCase() }</span>
                        </button>
                        : <Link to={item.url} key={index}
                            className="py-2 px-4 bg-teal-200 dark:bg-teal-700 hover:bg-teal-300 dark:hover:bg-teal-800 rounded-lg flex justify-center drop-shadow-md 
                                hover:drop-shadow-none border-2 border-teal-200 hover:border-teal-400 dark:border-teal-700 dark:hover:border-teal-900 sm:w-full w-32">
                            <span className="text-teal-500 dark:text-white font-semibold text-center text-xs">{ item.name.toUpperCase() }</span>
                        </Link>
                    ))
                }
            </div>
        </div>
    )
}

export default TabMenu