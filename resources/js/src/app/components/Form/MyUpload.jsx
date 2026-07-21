import React from 'react'

const MyUpload = ({
    label="Upload File",
    id="input_file",
    error="",
    notes="",
    onChange=null
}) => {
    return (
        <div className="sm:mb-4 mb-2">
            <label htmlFor={id} 
                className={"block text-sm font-medium leading-6 " + (error !== "" ? "text-red-500" : "text-gray-900 dark:text-white")}>
                {label}
            </label>
            <input 
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg 
                        cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none 
                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" 
                aria-describedby="file_input_help" 
                id={id} 
                type="file" 
                onChange={onChange}
            />
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">{notes}</p>
        </div>
    )
}

export default MyUpload