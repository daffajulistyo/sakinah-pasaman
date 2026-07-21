import React from 'react'

const MyInput = ({ 
    label = "label",
    id="myinput",
    name="myinput",
    type="text",
    placeholder="input your text...",
    className="",
    value="",
    onChange,
    onBlur=null,
    disabled=false,
    error=""
}) => {
    const defaultClass = `bg-gray-50 disabled:bg-gray-100 disabled:text-gray-500 
                            disabled:dark:bg-gray-600 disabled:dark:text-gray-400 border text-sm rounded-lg focus:ring-blue-500 
                            focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 
                            dark:focus:ring-blue-500 dark:focus:border-blue-500 `
    const normalCase = "border-gray-300 text-gray-900 dark:border-gray-600 dark:text-white "
    const invalidCase = "border-red-500 text-red-500 dark:border-red-600 text-red-500 "
    return (
        <div className="sm:mb-4 mb-2">
            <label htmlFor={id} 
                className={"block text-sm font-medium leading-6 " + (error !== "" ? "text-red-500" : "text-gray-900 dark:text-white")}>
                {label}
            </label>
            <div className="mt-2">
                <input
                    id={id}
                    name={name}
                    type={type}
                    placeholder={placeholder}
                    className={defaultClass + (error !== "" ? invalidCase : normalCase ) + className}
                    value={value}
                    onChange={onChange}
                    onBlur={onBlur}
                    disabled={disabled}
                />
                <p className="text-xs text-red-500 mt-1 pl-2">{error}</p>
            </div>
        </div>
    )
}

export default MyInput