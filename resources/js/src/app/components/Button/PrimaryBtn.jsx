import React from 'react'
import { ClipLoader } from 'react-spinners'

const PrimaryBtn = ({
    children,
    loading = false,
    disabled= false,
    className = "",
    type="button",
    onClick
}) => {
    const defaultClass = `rounded-md bg-indigo-600 dark:bg-indigo-800 px-5 py-2 text-sm font-semibold text-white shadow-sm 
                            focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 
                            dark:focus-visible:outline-indigo-900 flex items-center gap-1 `
    const defaultCond = `hover:bg-indigo-500 dark:hover:bg-indigo-900 `
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

export default PrimaryBtn