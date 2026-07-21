import React from 'react'
import { ClipLoader } from 'react-spinners'

const DangerBtn = ({
    children,
    loading = false,
    disabled= false,
    className = "",
    type="button",
    onClick
}) => {
    const defaultClass = `rounded-md bg-red-600 dark:bg-red-800 px-5 py-2 text-sm font-semibold text-white shadow-sm 
                            focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 
                            dark:focus-visible:outline-red-900 flex items-center gap-1 `
    const defaultCond = `hover:bg-red-500 dark:hover:bg-red-900 `
    const loadingCond = `disabled:bg-red-400 disabled:cursor-wait disabled:text-gray-200 `
    const disabledCond = `disabled:bg-red-400 disabled:cursor-not-allowed disabled:text-gray-200 `
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

export default DangerBtn