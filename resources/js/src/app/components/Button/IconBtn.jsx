import React from 'react'
import { ClipLoader } from 'react-spinners'

const IconBtn = ({
    children,
    loading = false,
    disabled= false,
    className = "",
    type="button",
    onClick
}) => {
    const defaultClass = `text-white bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full 
            text-sm p-1.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:focus:ring-blue-800 `
    const defaultCond = `hover:bg-blue-800 dark:hover:bg-blue-700 `
    const loadingCond = `disabled:bg-indigo-400 disabled:cursor-wait disabled:text-gray-200 `
    const disabledCond = `disabled:bg-indigo-400 disabled:cursor-not-allowed disabled:text-gray-200 `
    let cond = disabled ? disabledCond : ( loading ? loadingCond : defaultCond )
    return (
        <button 
            type={type}
            disabled={loading || disabled ? true : false}
            className={ defaultClass + cond + className }
            onClick={onClick}
        >
            { loading ? 
            <span><ClipLoader size={14} color='#e0b3b3' /> Loading...</span>
            :  children }
        </button>
    )
}

export default IconBtn